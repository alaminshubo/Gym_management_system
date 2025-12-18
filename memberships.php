<?php
include 'config.php';


$table_name = "membership"; 

$id = $name = $duration = $price = "";
$edit_mode = false;

if (isset($_POST['save'])) {
    $name     = $_POST['name'];
    $duration = $_POST['duration'];
    $price    = $_POST['price'];

    if (!empty($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE $table_name SET Name=?, Duration=?, Price=? WHERE Membership_id=?");
        $stmt->bind_param("sidi", $name, $duration, $price, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO $table_name (Name, Duration, Price) VALUES (?, ?, ?)");
        $stmt->bind_param("sid", $name, $duration, $price);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: memberships.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM $table_name WHERE Membership_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: memberships.php");
    exit;
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_mode = true;
    $stmt = $conn->prepare("SELECT * FROM $table_name WHERE Membership_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $name     = $row['Name'];
        $duration = $row['Duration'];
        $price    = $row['Price'];
    }
    $stmt->close();
}

$result = $conn->query("SELECT * FROM $table_name ORDER BY Membership_id ASC");

include 'includes/header.php';
?>

<header class="main-header">
    <h1>Manage Membership Plans</h1>
</header>

<section class="card">
    <h2><?= $edit_mode ? "Edit Plan" : "Add New Plan" ?></h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="form-group">
            <label for="name">Plan Name</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="e.g., Gold, Silver" value="<?= $name ?>" required>
        </div>
        <div class="form-group">
            <label for="duration">Duration (Months)</label>
            <input type="number" id="duration" name="duration" class="form-control" placeholder="e.g., 1, 6, 12" value="<?= $duration ?>" required>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" step="0.01" id="price" name="price" class="form-control" placeholder="Price" value="<?= $price ?>" required>
        </div>
        <button type="submit" name="save" class="btn btn-primary"><?= $edit_mode ? "Update Plan" : "Add Plan" ?></button>
    </form>
</section>

<section class="card">
    <h2>All Plans</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Duration (Months)</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['Membership_id'] ?></td>
                <td><?= $row['Name'] ?></td>
                <td><?= $row['Duration'] ?></td>
                <td><?= $row['Price'] ?></td>
                <td>
                    <a href="?edit=<?= $row['Membership_id'] ?>" class="btn btn-success">Edit</a>
                    <a href="?delete=<?= $row['Membership_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

<?php include 'includes/footer.php'; ?>
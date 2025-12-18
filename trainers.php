<?php
include 'config.php';

$id = $name = $phone = $specialization = $email = "";
$edit_mode = false;

if (isset($_POST['save'])) {
    $name   = $_POST['name'];
    $phone  = $_POST['phone'];
    $specialization = $_POST['specialization'];
    $email  = $_POST['email'];

    if (!empty($_POST['id'])) { 
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE trainers SET Name=?, Phone=?, Specialization=?, Email=? WHERE Trainer_id=?");
        $stmt->bind_param("ssssi", $name, $phone, $specialization, $email, $id);
        $stmt->execute();
        $stmt->close();
    } else { 
        $stmt = $conn->prepare("INSERT INTO trainers (Name, Phone, Specialization, Email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $phone, $specialization, $email);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: trainers.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM trainers WHERE Trainer_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: trainers.php");
    exit;
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_mode = true;
    $stmt = $conn->prepare("SELECT * FROM trainers WHERE Trainer_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $name   = $row['Name'];
        $phone  = $row['Phone'];
        $specialization = $row['Specialization'];
        $email  = $row['Email'];
    }
    $stmt->close();
}

$result = $conn->query("SELECT * FROM trainers ORDER BY Trainer_id ASC");

include 'includes/header.php';
?>

<header class="main-header">
    <h1>Manage Trainers</h1>
</header>

<section class="card">
    <h2><?= $edit_mode ? "Edit Trainer" : "Add New Trainer" ?></h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Full Name" value="<?= $name ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" class="form-control" placeholder="Phone Number" value="<?= $phone ?>" required>
        </div>
        <div class="form-group">
            <label for="specialization">Specialization</label>
            <input type="text" id="specialization" name="specialization" class="form-control" placeholder="e.g., CrossFit, Yoga" value="<?= $specialization ?>">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Email Address" value="<?= $email ?>" required>
        </div>
        <button type="submit" name="save" class="btn btn-primary"><?= $edit_mode ? "Update Trainer" : "Add Trainer" ?></button>
    </form>
</section>

<section class="card">
    <h2>All Trainers</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Specialization</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['Trainer_id'] ?></td>
                <td><?= $row['Name'] ?></td>
                <td><?= $row['Phone'] ?></td>
                <td><?= $row['Specialization'] ?></td>
                <td><?= $row['Email'] ?></td>
                <td>
                    <a href="?edit=<?= $row['Trainer_id'] ?>" class="btn btn-success">Edit</a>
                    <a href="?delete=<?= $row['Trainer_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

<?php include 'includes/footer.php'; ?>
<?php
include 'config.php';

$id = $name = $age = $gender = $email = $phone = "";
$edit_mode = false;

if (isset($_POST['save'])) {
    $name   = $_POST['name'];
    $age    = $_POST['age'];
    $gender = $_POST['gender'];
    $email  = $_POST['email'];
    $phone  = $_POST['phone'];

    if (!empty($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE members SET name=?, age=?, gender=?, email=?, phone=? WHERE member_id=?");
        $stmt->bind_param("sisssi", $name, $age, $gender, $email, $phone, $id);
        $stmt->execute();
        $stmt->close();
    } else { 
        $stmt = $conn->prepare("INSERT INTO members (name, age, gender, email, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $name, $age, $gender, $email, $phone);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: members.php");
    exit;
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM members WHERE member_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: members.php");
    exit;
}


if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_mode = true;
    $stmt = $conn->prepare("SELECT * FROM members WHERE member_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $name   = $row['Name'];
        $age    = $row['Age'];
        $gender = $row['Gender'];
        $email  = $row['Email'];
        $phone  = $row['Phone'];
    }
    $stmt->close();
}

$result = $conn->query("SELECT * FROM members ORDER BY member_id ASC");

include 'includes/header.php';
?>

<header class="main-header">
    <h1>Manage Members</h1>
</header>

<section class="card">
    <h2><?= $edit_mode ? "Edit Member" : "Add New Member" ?></h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Full Name" value="<?= $name ?>" required>
        </div>
        <div class="form-group">
            <label for="age">Age</label>
            <input type="number" id="age" name="age" class="form-control" placeholder="Age" value="<?= $age ?>">
        </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" name="gender" class="form-control">
                <option value="">Select Gender</option>
                <option value="Male"   <?= ($gender=='Male')?'selected':'' ?>>Male</option>
                <option value="Female" <?= ($gender=='Female')?'selected':'' ?>>Female</option>
                <option value="Other"  <?= ($gender=='Other')?'selected':'' ?>>Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Email Address" value="<?= $email ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" class="form-control" placeholder="Phone Number" value="<?= $phone ?>" required>
        </div>
        <button type="submit" name="save" class="btn btn-primary"><?= $edit_mode ? "Update Member" : "Add Member" ?></button>
    </form>
</section>

<section class="card">
    <h2>All Members</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['Member_id'] ?></td>
                <td><?= $row['Name'] ?></td>
                <td><?= $row['Age'] ?></td>
                <td><?= $row['Gender'] ?></td>
                <td><?= $row['Email'] ?></td>
                <td><?= $row['Phone'] ?></td>
                <td>
                    <a href="?edit=<?= $row['Member_id'] ?>" class="btn btn-success">Edit</a>
                    <a href="?delete=<?= $row['Member_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

<?php include 'includes/footer.php'; ?>
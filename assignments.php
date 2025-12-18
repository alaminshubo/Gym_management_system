<?php
include 'config.php';

$table_name = "trainer_assignment";

$id = $mid = $tid = "";
$edit_mode = false;

if (isset($_POST['save'])) {
    $mid = $_POST['member_id'];
    $tid = $_POST['trainer_id'];

    if (!empty($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE $table_name SET Member_id=?, Trainer_id=? WHERE Assignment_id=?");
        $stmt->bind_param("iii", $mid, $tid, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO $table_name (Member_id, Trainer_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $mid, $tid);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: assignments.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM $table_name WHERE Assignment_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: assignments.php");
    exit;
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_mode = true;
    $stmt = $conn->prepare("SELECT * FROM $table_name WHERE Assignment_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $mid = $row['Member_id'];
        $tid = $row['Trainer_id'];
    }
    $stmt->close();
}

$assignments_result = $conn->query("
    SELECT ta.*, m.Name as MemberName, t.Name as TrainerName
    FROM $table_name ta
    JOIN members m ON ta.Member_id = m.Member_id
    JOIN trainers t ON ta.Trainer_id = t.Trainer_id
    ORDER BY ta.Assignment_id ASC
");
$members_result = $conn->query("SELECT Member_id, Name FROM members ORDER BY Name ASC");
$trainers_result = $conn->query("SELECT Trainer_id, Name FROM trainers ORDER BY Name ASC");

include 'includes/header.php';
?>

<header class="main-header">
    <h1>Trainer Assignments</h1>
</header>

<section class="card">
    <h2><?= $edit_mode ? "Edit Assignment" : "Assign Trainer to Member" ?></h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="form-group">
            <label for="member_id">Member</label>
            <select id="member_id" name="member_id" class="form-control" required>
                <option value="">Select Member</option>
                <?php mysqli_data_seek($members_result, 0); // Reset pointer ?>
                <?php while($member = $members_result->fetch_assoc()): ?>
                    <option value="<?= $member['Member_id'] ?>" <?= ($mid == $member['Member_id']) ? 'selected' : '' ?>>
                        <?= $member['Name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="trainer_id">Trainer</label>
            <select id="trainer_id" name="trainer_id" class="form-control" required>
                <option value="">Select Trainer</option>
                <?php while($trainer = $trainers_result->fetch_assoc()): ?>
                    <option value="<?= $trainer['Trainer_id'] ?>" <?= ($tid == $trainer['Trainer_id']) ? 'selected' : '' ?>>
                        <?= $trainer['Name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" name="save" class="btn btn-primary"><?= $edit_mode ? "Update Assignment" : "Assign Trainer" ?></button>
    </form>
</section>

<section class="card">
    <h2>All Assignments</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Member Name</th>
                <th>Assigned Trainer</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $assignments_result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['Assignment_id'] ?></td>
                <td><?= $row['MemberName'] ?></td>
                <td><?= $row['TrainerName'] ?></td>
                <td>
                    <a href="?edit=<?= $row['Assignment_id'] ?>" class="btn btn-success">Edit</a>
                    <a href="?delete=<?= $row['Assignment_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

<?php include 'includes/footer.php'; ?>
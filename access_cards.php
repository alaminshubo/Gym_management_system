<?php
include 'config.php';

$table_name = "access_card";

$id = $member_id = $status = "";
$edit_mode = false;

if (isset($_POST['save'])) {
    $member_id = $_POST['member_id'];
    $status    = $_POST['status'];

    if (!empty($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE $table_name SET Member_id=?, Status=? WHERE Access_card_id=?");
        $stmt->bind_param("isi", $member_id, $status, $id);
        $stmt->execute();
        $stmt->close();
    } else { 
        $stmt = $conn->psertrepare("INSERT INTO $table_name (Member_id, Status) VALUES (?, ?)");
        $stmt->bind_param("is", $member_id, $status);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: access_cards.php");
    exit;
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM $table_name WHERE Access_card_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: access_cards.php");
    exit;
}


if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_mode = true;
    $stmt = $conn->prepare("SELECT * FROM $table_name WHERE Access_card_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $member_id = $row['Member_id'];
        $status    = $row['Status'];
    }
    $stmt->close();
}

$cards_result = $conn->query("
    SELECT ac.*, m.Name as MemberName 
    FROM $table_name ac 
    LEFT JOIN members m ON ac.Member_id = m.Member_id 
    ORDER BY ac.Access_card_id ASC
");
$members_result = $conn->query("SELECT Member_id, Name FROM members ORDER BY Name ASC");

include 'includes/header.php';
?>

<header class="main-header">
    <h1>Manage Access Cards</h1>
</header>

<section class="card">
    <h2><?= $edit_mode ? "Edit Access Card" : "Issue New Card" ?></h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="form-group">
            <label for="member_id">Member</label>
            <select id="member_id" name="member_id" class="form-control" required>
                <option value="">Select Member</option>
                <?php while($member = $members_result->fetch_assoc()): ?>
                    <option value="<?= $member['Member_id'] ?>" <?= ($member_id == $member['Member_id']) ? 'selected' : '' ?>>
                        <?= $member['Name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control" required>
                <option value="">Select Status</option>
                <option value="Active"   <?= ($status=='Active')?'selected':'' ?>>Active</option>
                <option value="Inactive" <?= ($status=='Inactive')?'selected':'' ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" name="save" class="btn btn-primary"><?= $edit_mode ? "Update Card" : "Issue Card" ?></button>
    </form>
</section>

<section class="card">
    <h2>All Access Cards</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>Card ID</th>
                <th>Member Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $cards_result->fetch_assoc()){ ?>
            <tr>
                <td><?= $row['Access_card_id'] ?></td>
                <td><?= $row['MemberName'] ?> (ID: <?= $row['Member_id'] ?>)</td>
                <td><?= $row['Status'] ?></td>
                <td>
                    <a href="?edit=<?= $row['Access_card_id'] ?>" class="btn btn-success">Edit</a>
                    <a href="?delete=<?= $row['Access_card_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

<?php include 'includes/footer.php'; ?>
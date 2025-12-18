<?php
include 'config.php';

$id = $member_id = $card_id = $date = $check_in = $check_out = "";
$edit_mode = false;

if (isset($_POST['save'])) {
    $member_id = $_POST['member_id'];
    $card_id   = $_POST['card_id'];
    $date      = $_POST['date'] ?: date('Y-m-d');
    $check_in  = $_POST['check_in'] ?: date('H:i:s');
    $check_out = $_POST['check_out'] ?: null;

    if (!empty($_POST['id'])) { 
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE attendance 
                                SET Member_id=?, Access_card_id=?, Date=?, Check_in_time=?, Check_out_time=? 
                                WHERE Attendance_id=?");
        $stmt->bind_param("iisssi", $member_id, $card_id, $date, $check_in, $check_out, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO attendance (Member_id, Access_card_id, Date, Check_in_time, Check_out_time)
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $member_id, $card_id, $date, $check_in, $check_out);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: attendance.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM attendance WHERE Attendance_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: attendance.php");
    exit;
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_mode = true;
    $stmt = $conn->prepare("SELECT * FROM attendance WHERE Attendance_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    if ($row = $result_edit->fetch_assoc()) {
        $member_id = $row['Member_id'];
        $card_id   = $row['Access_card_id'];
        $date      = $row['Date'];
        $check_in  = $row['Check_in_time'];
        $check_out = $row['Check_out_time'];
    }
    $stmt->close();
}

$attendance_result = $conn->query("
    SELECT a.*, m.Name as MemberName 
    FROM attendance a 
    JOIN members m ON a.Member_id = m.Member_id 
    ORDER BY a.Date DESC, a.Check_in_time DESC
");
$members_result = $conn->query("SELECT Member_id, Name FROM members ORDER BY Name ASC");
$cards_result = $conn->query("SELECT Access_card_id, Member_id FROM access_card WHERE Status = 'Active'");

include 'includes/header.php';
?>

<header class="main-header">
    <h1>Manage Attendance</h1>
</header>

<section class="card">
    <h2><?= $edit_mode ? "Edit Attendance" : "Mark Attendance" ?></h2>
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
            <label for="card_id">Access Card ID</label>
             <select id="card_id" name="card_id" class="form-control" required>
                <option value="">Select Card</option>
                <?php while($card = $cards_result->fetch_assoc()): ?>
                    <option value="<?= $card['Access_card_id'] ?>" <?= ($card_id == $card['Access_card_id']) ? 'selected' : '' ?>>
                        Card ID: <?= $card['Access_card_id'] ?> (Member ID: <?= $card['Member_id'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" id="date" name="date" class="form-control" value="<?= $date ?: date('Y-m-d') ?>">
        </div>
        <div class="form-group">
            <label for="check_in">Check In Time</label>
            <input type="time" id="check_in" name="check_in" class="form-control" value="<?= $check_in ?: date('H:i:s') ?>">
        </div>
        <div class="form-group">
            <label for="check_out">Check Out Time</label>
            <input type="time" id="check_out" name="check_out" class="form-control" value="<?= $check_out ?>">
        </div>
        <button type="submit" name="save" class="btn btn-primary"><?= $edit_mode ? "Update Attendance" : "Mark Attendance" ?></button>
    </form>
</section>

<section class="card">
    <h2>Attendance Log</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Member Name</th>
                <th>Card ID</th>
                <th>Date</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $attendance_result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['Attendance_id'] ?></td>
                <td><?= $row['MemberName'] ?> (ID: <?= $row['Member_id'] ?>)</td>
                <td><?= $row['Access_card_id'] ?></td>
                <td><?= $row['Date'] ?></td>
                <td><?= $row['Check_in_time'] ?></td>
                <td><?= $row['Check_out_time'] ?></td>
                <td>
                    <a href="?edit=<?= $row['Attendance_id'] ?>" class="btn btn-success">Edit</a>
                    <a href="?delete=<?= $row['Attendance_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

<?php include 'includes/footer.php'; ?>
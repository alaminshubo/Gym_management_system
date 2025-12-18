<?php
include 'config.php';

$id = $mid = $amount = $date = "";
$edit_mode = false;

if (isset($_POST['save'])) {
    $mid    = $_POST['member_id'];
    $amount = $_POST['amount'];
    $date   = $_POST['date'] ?: date('Y-m-d');

    if (!empty($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE payments SET Member_id=?, Amount=?, Payment_date=? WHERE Payment_id=?");
        $stmt->bind_param("idsi", $mid, $amount, $date, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO payments (Member_id, Amount, Payment_date) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $mid, $amount, $date);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: payments.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM payments WHERE Payment_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: payments.php");
    exit;
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_mode = true;
    $stmt = $conn->prepare("SELECT * FROM payments WHERE Payment_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $mid    = $row['Member_id'];
        $amount = $row['Amount'];
        $date   = $row['Payment_date'];
    }
    $stmt->close();
}

$payments_result = $conn->query("SELECT p.*, m.Name as MemberName FROM payments p JOIN members m ON p.Member_id = m.Member_id ORDER BY p.Payment_date DESC");
$members_result = $conn->query("SELECT Member_id, Name FROM members ORDER BY Name ASC");

include 'includes/header.php';
?>

<header class="main-header">
    <h1>Manage Payments</h1>
</header>

<section class="card">
    <h2><?= $edit_mode ? "Edit Payment" : "Add New Payment" ?></h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="form-group">
            <label for="member_id">Member</label>
            <select id="member_id" name="member_id" class="form-control" required>
                <option value="">Select Member</option>
                <?php while($member = $members_result->fetch_assoc()): ?>
                    <option value="<?= $member['Member_id'] ?>" <?= ($mid == $member['Member_id']) ? 'selected' : '' ?>>
                        <?= $member['Name'] ?> (ID: <?= $member['Member_id'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" step="0.01" id="amount" name="amount" class="form-control" placeholder="Amount" value="<?= $amount ?>" required>
        </div>
        <div class="form-group">
            <label for="date">Payment Date</label>
            <input type="date" id="date" name="date" class="form-control" value="<?= $date ?: date('Y-m-d') ?>">
        </div>
        <button type="submit" name="save" class="btn btn-primary"><?= $edit_mode ? "Update Payment" : "Add Payment" ?></button>
    </form>
</section>

<section class="card">
    <h2>Payment History</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Member Name</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $payments_result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['Payment_id'] ?></td>
                <td><?= $row['MemberName'] ?> (ID: <?= $row['Member_id'] ?>)</td>
                <td><?= $row['Amount'] ?></td>
                <td><?= $row['Payment_date'] ?></td>
                <td>
                    <a href="?edit=<?= $row['Payment_id'] ?>" class="btn btn-success">Edit</a>
                    <a href="?delete=<?= $row['Payment_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

<?php include 'includes/footer.php'; ?>
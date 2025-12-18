<?php
include 'config.php';
include 'includes/header.php';

$total_members = $conn->query("SELECT COUNT(*) as count FROM members")->fetch_assoc()['count'];
$total_trainers = $conn->query("SELECT COUNT(*) as count FROM trainers")->fetch_assoc()['count'];
$total_revenue_result = $conn->query("SELECT SUM(Amount) as total FROM payments")->fetch_assoc();
$total_revenue = $total_revenue_result['total'] ?? 0;
?>

<div class="container"> <header class="main-header">
        <h1>Dashboard</h1>
    </header>

    <section class="stat-cards">
        <div class="stat-card">
            <h3>Total Members</h3>
            <p class="number"><?= $total_members ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Trainers</h3>
            <p class="number"><?= $total_trainers ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Revenue</h3>
            <p class="number">BDT <?= number_format($total_revenue, 2) ?></p>
        </div>
    </section>

    <section class="card">
        <h2>Quick Links</h2>
        <div style="display: flex; gap: 10px; margin-top: 15px;">
            <a href="members.php" class="btn btn-primary">Manage Members</a>
            <a href="trainers.php" class="btn btn-success">Manage Trainers</a>
            <a href="payments.php" class="btn btn-primary" style="background-color: #6c757d;">Log Payment</a>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
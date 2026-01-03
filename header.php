<?php
$current_page = basename($_SERVER['PHP_SELF']);

function is_active($page_name, $current_page) {
    return ($page_name == $current_page) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Management System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background-color: #007bff;">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php" style="font-weight: bold; font-size: 24px;">GYMnesia</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto"> <li class="nav-item">
          <a class="nav-link <?= is_active('index.php', $current_page) ?>" href="index.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= is_active('members.php', $current_page) ?>" href="members.php">Members</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= is_active('trainers.php', $current_page) ?>" href="trainers.php">Trainers</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= is_active('memberships.php', $current_page) ?>" href="memberships.php">Memberships</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= is_active('payments.php', $current_page) ?>" href="payments.php">Payments</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= is_active('assignments.php', $current_page) ?>" href="assignments.php">Assignments</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= is_active('access_cards.php', $current_page) ?>" href="access_cards.php">Access Cards</a>
        </li>
         <li class="nav-item">
          <a class="nav-link <?= is_active('attendance.php', $current_page) ?>" href="attendance.php">Attendance</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<main class="container mt-4">

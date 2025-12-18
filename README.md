# Gym Management System (PHP & MySQL)

A comprehensive web-based application designed to manage gym operations, including member registration, trainer assignments, membership plans, payments, and attendance tracking.

## 🚀 Features
* **Dashboard Overview**: Real-time statistics showing total members, trainers, and total revenue.
* **Member Management**: Full CRUD (Create, Read, Update, Delete) functionality for managing gym members.
* **Trainer Assignments**: Assign specific trainers to members to track personal training sessions.
* **Membership Plans**: Manage various membership tiers (e.g., Gold, Silver) with custom durations and pricing.
* **Payment Tracking**: Log and monitor member payments to maintain financial records.
* **Attendance & Access Cards**: Track member check-in/check-out times using unique access card IDs.

## 🛠️ Tech Stack
* **Frontend**: HTML5, CSS3 (Custom styles and Bootstrap 5)
* **Backend**: PHP
* **Database**: MySQL
* **Styling**: Custom modern UI with responsive cards and data tables

## 📋 Database Structure
The system uses a relational database named `gym_management_system` with the following tables:
* `members`: Stores personal details of gym members.
* `trainers`: Stores trainer profiles and specializations.
* `membership`: Defines different subscription plans.
* `payments`: Records all financial transactions.
* `attendance`: Logs daily entry and exit times.
* `access_card`: Manages the status of physical/digital access cards.
* `trainer_assignment`: Links members to their respective trainers.

## ⚙️ Installation
1.  **Clone the repository**:
    ```bash
    git clone [https://github.com/alaminshubo/your-repo-name.git](https://github.com/your-username/your-repo-name.git)
    ```
2.  **Setup Database**:
    * Open PHPMyAdmin.
    * Create a database named `gym_management_system`.
    * Import the provided `gym_management_system.sql` file.
3.  **Configure Connection**:
    * Open `config.php`.
    * Update the `$host`, `$user`, `$pass`, and `$db` variables to match your local server environment.
4.  **Run Application**:
    * Move the project folder to your server directory (e.g., `htdocs` for XAMPP).
    * Access via `http://localhost/your-folder-name/index.php`.

## 🎨 UI Design
The interface features a clean, professional look with:
* **Responsive Navigation**: Easy access to all modules.
* **Stat Cards**: Visual representation of key business metrics on the dashboard.
* **Data Tables**: Organized list views for all records with action buttons for editing and deleting.

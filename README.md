### Library Management System

A streamlined, digital platform designed to automate and simplify daily library operations. This system provides an intuitive interface for managing a library's book inventory, tracking member registrations, and handling book borrowing and return transactions seamlessly. 

### Key Features

* **Book Inventory Management:** Easily add, update, search, and remove books from the system catalog.
* **Member & Staff Management:** Track member or staff account statuses.
* **Borrowing & Return Tracking:** Automate the checkout and return process with real-time availability updates.
* **Dashboard Analytics:** Quick view of total books and active rentals.

### Tech Stack

* **Frontend & Backend:** PHP, HTML5, CSS3, JavaScript
* **Local Server Environment:** XAMPP
* **Database Management:** MySQL via phpMyAdmin

### Getting Started & Local Setup

To run this project locally, you will need to have **XAMPP** installed on your machine. 

### 1. Project Installation

1. Download and install [XAMPP](https://www.apachefriends.org/).
2. Clone or download this repository into your XAMPP htdocs directory: 

  * **Windows:** C:\xampp\htdocs\
  * **macOS:** /Applications/XAMPP/xamppfiles/htdocs/

bash

cd C:\xampp\htdocs
git clone https://github.com/YOUR_USERNAME/YOUR_REPOSITORY_NAME.git

Use code with caution.

### 2. Database Setup (phpMyAdmin)

1. Open the **XAMPP Control Panel** and start both the **Apache** and **MySQL** modules.
2. Open your web browser and navigate to http://localhost/phpmyadmin/.
3. Click on **New** in the left sidebar to create a new database. Name it (e.g., library_db).
4. Click on the **Import** tab at the top.
5. Choose the SQL file provided in this repository (e.g., database.sql or look inside the database folder) and click **Go** to import the tables.

### 3. Database Connection Configuration

Make sure your project's database configuration file (usually config.php, db.php, or connection.php) matches your local XAMPP credentials: 

php

$servername = "localhost";
$username = "root";
$password = ""; // Default XAMPP password is empty
$dbname = "library_db"; // Your database name

Use code with caution.

### 4. Running the Application

1. Ensure Apache and MySQL are still running in your XAMPP Control Panel.
2. Open your browser and go to: http://localhost/YOUR_REPOSITORY_NAME/

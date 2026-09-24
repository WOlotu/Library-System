<?php
session_start();
include 'header.php';

//checking if user is admin
if ($_SESSION['username'] !== 'admin') {
    echo "<p style='color:red;'>Access denied. Admin only.</p>";
    include 'footer.php';
    exit();
}

//creating a connection
$conn = new mysqli('localhost', 'root', '', 'webassignment');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

//form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isbn = $_POST['isbn'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    
    //adding a book
    $stmt = $conn->prepare("INSERT INTO book (ISBN, BookTitle, Author, Reserved) VALUES (?, ?, ?, 'N')");
    $stmt->bind_param("sss", $isbn, $title, $author);
    
    //validation
    if ($stmt->execute()) {
        echo "<p style='color:green;'>Book added successfully</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
    $stmt->close();
}
?>

<h2>Add New Book</h2>
<!--Formatting-->

<style>
.menu-link {
    display: inline-block;
    padding: 15px 30px;
    margin: 10px;
    background-color: #bb8369ff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 18px;
    transition: background-color 0.3s;
}
.menu-link:hover {
    background-color: #bb8369ff;
}
</style>

<form method="post">
    <p>ISBN: <input type="text" name="isbn" required></p>
    <p>Title: <input type="text" name="title" required></p>
    <p>Author: <input type="text" name="author" required></p>
    <p><input type="submit" value="Add Book"></p>
</form>

<!--Menu tab-->
<div style="margin: 40px 0; text-align: center;">
    <a href="webassignmanagebooks.php" class="menu-link"> Back to manage books</a>
</div>

<?php
$conn->close();
include 'footer.php';
?>
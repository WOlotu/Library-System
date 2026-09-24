<?php
session_start();
include 'header.php';

//checking if user is admin
if ($_SESSION['username'] !== 'admin') {
    echo "<p style='color:red;'>Access denied. Admin only.</p>";
    include 'footer.php';
    exit();
}

if (!isset($_GET['isbn'])) {
    header('Location: webassignmanagebooks.php');
    exit();
}
//getting ibsn from database
$isbn = $_GET['isbn'];
$conn = new mysqli('localhost', 'root', '', 'webassignment');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

//book info
$stmt = $conn->prepare("SELECT BookTitle, Author, Reserved FROM book WHERE ISBN = ?");
$stmt->bind_param("s", $isbn);
$stmt->execute();
$stmt->bind_result($title, $author, $reserved);
$stmt->fetch();
$stmt->close();

//form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_isbn = $_POST['isbn'];
    $new_title = $_POST['title'];
    $new_author = $_POST['author'];
    $new_reserved = $_POST['reserved'];
    
    $stmt = $conn->prepare("UPDATE book SET ISBN=?, BookTitle=?, Author=?, Reserved=? WHERE ISBN=?");
    $stmt->bind_param("sssss", $new_isbn, $new_title, $new_author, $new_reserved, $isbn);
    
    if ($stmt->execute()) {
        echo "<p style='color:green;'>Book updated successfully</p>";
        //updating attributes for display
        $isbn = $new_isbn;
        $title = $new_title;
        $author = $new_author;
        $reserved = $new_reserved;
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
    $stmt->close();
}
?>

<h2>Edit Book</h2>
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
    <p>ISBN: <input type="text" name="isbn" value="<?php echo htmlspecialchars($isbn); ?>" required></p>
    <p>Title: <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required></p>
    <p>Author: <input type="text" name="author" value="<?php echo htmlspecialchars($author); ?>" required></p>
    <p>Reserved: 
        <select name="reserved">
            <option value="Y" <?php echo ($reserved == 'Y') ? 'selected' : ''; ?>>Yes</option>
            <option value="N" <?php echo ($reserved == 'N') ? 'selected' : ''; ?>>No</option>
        </select>
    </p>
    <p>
        <input type="submit" value="Update Book">
        <!--Menu tab-->
        <div style="margin: 40px 0; text-align: center;">
            <a href="webassignmanagebooks.php" class="menu-link"> Back to manage books</a>
        </div>
    </p>
</form>

<?php
$conn->close();
include 'footer.php';
?>
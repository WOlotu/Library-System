<?php
session_start();
include 'header.php';

//checking if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

//verifying user is admin
if ($_SESSION['username'] !== 'admin') {
    echo "<p style='color:red;'>Access denied. Admin only.</p>";
    include 'footer.php';
    exit();
}

//database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webassignment";
    
//creating connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//book delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_book'])) {
    $isbn_to_delete = $_POST['isbn'];
    
    $stmt = $conn->prepare("DELETE FROM book WHERE ISBN = ?");
    $stmt->bind_param("s", $isbn_to_delete);
    
    if ($stmt->execute()) {
        echo "<p style='color:green;'>Book deleted successfully</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
    $stmt->close();
}

//list of all books
$books_result = $conn->query("SELECT ISBN, BookTitle, Author, Reserved FROM book ORDER BY BookTitle");
?>

<h2>Manage Books</h2>
<!--Formatting of books-->

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

<?php if ($books_result->num_rows > 0): ?>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ISBN</th>
            <th>Title</th>
            <th>Author</th>
            <th>Reserved</th>
            <th>Action</th>
        </tr>
        
        <?php while ($book = $books_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($book['ISBN']); ?></td>
            <td><?php echo htmlspecialchars($book['BookTitle']); ?></td>
            <td><?php echo htmlspecialchars($book['Author']); ?></td>
            <td><?php echo htmlspecialchars($book['Reserved']); ?></td>
            <td>
                <form method="post" style="display:inline;" 
                      onsubmit="return confirm('Delete book <?php echo addslashes($book['BookTitle']); ?>?')">
                    <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($book['ISBN']); ?>">
                    <input type="submit" name="delete_book" value="Delete">
                </form>
                <a href="webassigneditbook.php?isbn=<?php echo urlencode($book['ISBN']); ?>" 
                   style="margin-left:10px;">Edit</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No books found.</p>
<?php endif; ?>

<div style="margin: 40px 0; text-align: center;">
    <a href="webassignaddbook.php" class="menu-link"> Add a new book</a>
    <a href="webassignindex.php" class="menu-link"> Back to main menu</a>
</div>

<?php
$books_result->free();
$conn->close();
include 'footer.php';
?>
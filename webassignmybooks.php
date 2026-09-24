<?php
session_start();
include 'header.php';

//verifying that user has logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['error'] = "You must be logged in to view this page";
    header("Location: webassignindex.php");
    exit();
}

//database
$conn = new mysqli('localhost', 'root', '', 'webassignment');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];

//verifying that user exists
$stmt = $conn->prepare("SELECT UserID FROM user WHERE UserName = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    $_SESSION['error'] = "User not found in database";
    header('Location: webassignindex.php');
    exit();
}
$stmt->close();

//deleting a reservation
if (isset($_GET['remove'])) {
    $isbn = $_GET['remove'];
    $stmt = $conn->prepare("DELETE FROM reservations WHERE ISBN=? AND UserName=?");
    $stmt->bind_param("ss", $isbn, $username);
    $stmt->execute();
    
    //updating a reservation 
    $stmt2 = $conn->prepare("UPDATE book SET Reserved='N' WHERE ISBN=?");
    $stmt2->bind_param("s", $isbn);
    $stmt2->execute();
    
    //confirmation message
    echo "<p class='success'>Reservation removed!</p>";
    $stmt->close();
    $stmt2->close();
}

//displaying users books
$stmt = $conn->prepare("SELECT r.*, b.BookTitle, b.Author FROM reservations r JOIN book b ON r.ISBN=b.ISBN WHERE r.UserName=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$books = $stmt->get_result();
?>

<h2>My Reserved Books</h2>
<!--displaying a users reserved books-->
<?php if ($books->num_rows > 0): ?>
    <table border="1">
        <tr><th>ISBN</th><th>Title</th><th>Author</th><th>Reserved Date</th><th>Action</th></tr>
        <?php while ($book = $books->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($book['ISBN']); ?></td>
            <td><?php echo htmlspecialchars($book['BookTitle']); ?></td>
            <td><?php echo htmlspecialchars($book['Author']); ?></td>
            <td><?php echo htmlspecialchars($book['ReservedDate']); ?></td>
            <td><a href="?remove=<?php echo urlencode($book['ISBN']); ?>" 
                   onclick="return confirm('Are you sure you want to remove this reservation?');">Remove</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No reserved books</p>
<?php endif; ?>

<?php 
$stmt->close();
$conn->close();
include 'footer.php'; 
?>
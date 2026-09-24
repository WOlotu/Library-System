<?php
session_start();
include 'header.php';

if (!isset($_SESSION['username'])) {
    header("Location: webassignindex.php");
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'webassignment');

//reserving a book
if (isset($_GET['reserve'])) {
    $isbn = $_GET['reserve'];
    $username = $_SESSION['username'];
    
    $conn->query("INSERT INTO reservations (ISBN, UserName, ReservedDate) VALUES ('$isbn', '$username', CURDATE())");
    $conn->query("UPDATE book SET Reserved='Y' WHERE ISBN='$isbn'");
    echo "<p class='success'>Book reserved!</p>";
}

//searching for a book
$sql = "SELECT b.*, c.CategoryDescription FROM book b JOIN category c ON b.CategoryID=c.CategoryID WHERE b.Reserved='N'";

if ($_POST) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    
    if ($title) $sql .= " AND BookTitle LIKE '%$title%'";
    if ($author) $sql .= " AND Author LIKE '%$author%'";
    if ($category) $sql .= " AND b.CategoryID='$category'";
}

//pagination
$books_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
//total number of books
$count_sql = str_replace("SELECT b.*, c.CategoryDescription", "SELECT COUNT(*) as total", $sql);
$count_result = $conn->query($count_sql);
$total_books = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_books / $books_per_page);
//pages of books
$offset = ($current_page - 1) * $books_per_page;
$sql .= " LIMIT $offset, $books_per_page";

$books = $conn->query($sql);
$categories = $conn->query("SELECT * FROM category");
?>

<h2>Search Books</h2>
<!--Displaying books-->
<form method="post">
    <p>Title: <input type="text" name="title"></p>
    <p>Author: <input type="text" name="author"></p>
    <p>Category: 
        <select name="category">
            <option value="">All</option>
            <?php while ($cat = $categories->fetch_assoc()): ?>
                <option value="<?php echo $cat['CategoryID']; ?>" 
                    <?php if (isset($_POST['category']) && $_POST['category'] == $cat['CategoryID']) echo 'selected'; ?>>
                    <?php echo $cat['CategoryDescription']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </p>
    <input type="submit" value="Search">
</form>

<?php if ($_POST || isset($_GET['reserve']) || $current_page > 1): ?>
    <h3>Results (<?php echo $total_books; ?> books found)</h3>
    
    <?php if ($books->num_rows > 0): ?>
        <table>
            <tr><th>ISBN</th><th>Title</th><th>Author</th><th>Category</th><th>Action</th></tr>
            <?php while ($book = $books->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($book['ISBN']); ?></td>
                <td><?php echo htmlspecialchars($book['BookTitle']); ?></td>
                <td><?php echo htmlspecialchars($book['Author']); ?></td>
                <td><?php echo htmlspecialchars($book['CategoryDescription']); ?></td>
                <td><a href="?reserve=<?php echo $book['ISBN']; ?>&page=<?php echo $current_page; ?>">Reserve</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
        
        <!-- Pagination Nav -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="?page=<?php echo $current_page - 1; ?>">&laquo; Previous</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php if ($i == $current_page): ?>
                    <strong><?php echo $i; ?></strong>
                <?php else: ?>
                    <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?php echo $current_page + 1; ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
    <?php else: ?>
        <p>No books found</p>
    <?php endif; ?>
<?php endif; ?>

<?php include 'footer.php'; ?>
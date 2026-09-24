<?php
session_start();
include 'header.php';

//verifying that user has logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

//checking if user is an admin
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

//creating a connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//user deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $user_id_to_delete = intval($_POST['user_id']);
    $current_username = $_SESSION['username'];
    
    //preventing admin from deleting themselves
    $check_stmt = $conn->prepare("SELECT UserName FROM user WHERE UserID = ?");
    $check_stmt->bind_param("i", $user_id_to_delete);
    $check_stmt->execute();
    $check_stmt->bind_result($deleted_username);
    $check_stmt->fetch();
    $check_stmt->close();
    
    if ($deleted_username === 'admin') {
        echo "<p style='color:red;'>Cannot delete admin account</p>";
    } else {
        $stmt = $conn->prepare("DELETE FROM user WHERE UserID = ?");
        $stmt->bind_param("i", $user_id_to_delete);
        
        //deleting user that isnt admin
        if ($stmt->execute()) {
            echo "<p style='color:green;'>User deleted successfully</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
        }
        $stmt->close();
    }
}

//selecting list of users and displaying them to be deleted
$users_result = $conn->query("SELECT UserID, UserName FROM user ORDER BY UserName");
?>

<h2>Manage Users</h2>
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

<?php if ($users_result->num_rows > 0): ?>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Action</th>
        </tr>
        
        <?php while ($user = $users_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['UserID']); ?></td>
            <td><?php echo htmlspecialchars($user['UserName']); ?></td>
            <td>
                <?php if ($user['UserName'] === 'admin'): ?>
                    <span style="color:gray;">(Admin - cannot delete)</span>
                <?php else: ?>
                    <form method="post" style="display:inline;" 
                          onsubmit="return confirm('Delete user <?php echo addslashes($user['UserName']); ?>?')">
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['UserID']); ?>">
                        <input type="submit" name="delete_user" value="Delete">
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No users found.</p>
<?php endif; ?>

<!--Menu tab-->
<div style="margin: 40px 0; text-align: center;">
    <a href="webassignindex.php" class="menu-link"> Back to main menu</a>
</div>

<?php
$users_result->free();
$conn->close();
include 'footer.php';
?>
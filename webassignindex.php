<?php
session_start();
include 'header.php';

//verifying user has been logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['error'] = " You must be logged in to view this page";
    header('Location: login.php');
    exit();
}

//database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webassignment";
    
//creating connection
$conn = new mysqli($servername, $username, $password, $dbname);

//checking connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//getting users id from their username
$user_id = null;
if (isset($_SESSION['username'])) {
    $stmt = $conn->prepare("SELECT UserID FROM user WHERE UserName = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();
    
    //if user has no id
    if (!$user_id) {
        $_SESSION['error'] = "User not found in database";
        header('Location: login.php');
        exit();
    }
}
//closing connection
$conn->close();

//destroying the session id if user logs out
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<html>
    <head>
        <title>Web Assign - Main Menu</title>
        <style>
            .menu-link {
                display: inline-block;
                padding: 15px 30px;
                margin: 10px;
                background-color: #2c5875;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                font-size: 18px;
                transition: background-color 0.3s;
            }
            .menu-link:hover {
                background-color: #f4c95d;
                color: #17324d;
            }
        </style>
    </head>
    <body style="font-family: sans-serif;">
        <h1 style="color: #17324d;">Main Menu</h1>
        
        <?php
        if (isset($_SESSION["error"])) {
            echo('<p style="color:red">Error: '.htmlentities($_SESSION["error"])."</p>\n");
            unset($_SESSION["error"]);
        }
        if (isset($_SESSION["success"])) {
            echo('<p style="color:green">'.htmlentities($_SESSION["success"])."</p>\n");
            unset($_SESSION["success"]);
        }
        ?>
        <!--Menu tabs-->
        <div style="margin: 40px 0; text-align: center;">
            <a href="webassignsearch.php" class="menu-link"> Search for Books</a>
            <a href="webassignmybooks.php" class="menu-link"> View My Books</a>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <form method="get" style="display: inline;">
                <input type="hidden" name="logout" value="1">
                <input type="submit" value="Logout" 
                    onclick="return confirm('Are you sure you want to logout?');"
                    style="padding: 10px 20px; font-size: 16px; cursor: pointer;">
            </form>
        </div>
    </body>
</html>

<?php include 'footer.php'; ?>

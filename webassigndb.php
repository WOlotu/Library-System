<?php

//database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webassignment";

//create connection
$conn = new mysqli($servername, $username, $password, $dbname);

//checking connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//dropping existing tables
$conn->query("DROP TABLE IF EXISTS reservations");
$conn->query("DROP TABLE IF EXISTS book");
$conn->query("DROP TABLE IF EXISTS user");
$conn->query("DROP TABLE IF EXISTS category");

//sql for category table
$sql1 = "CREATE TABLE IF NOT EXISTS category (
    CategoryID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    CategoryDescription VARCHAR(50) NOT NULL )";

//inserting category data
$category_sql = "INSERT INTO category (CategoryID, CategoryDescription) VALUES 
    ('001', 'Health'),
    ('002', 'Business'),
    ('003', 'Biography'),
    ('004', 'Technology'),
    ('005', 'Travel'),
    ('006', 'Self-Help'),
    ('007', 'Cookery'),
    ('008', 'Fiction')";

if ($conn->query($sql1) === TRUE && $conn->query($category_sql) === TRUE) {
    echo "Table category created successfully<br>";
} else {
    echo "Error creating table category: " . $conn->error . "<br>";
}

//sql for user table
$sql2 = "CREATE TABLE IF NOT EXISTS user (
    UserID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    UserName VARCHAR(20) NOT NULL UNIQUE,
    Password VARCHAR(20) NOT NULL,
    FirstName VARCHAR(20) NOT NULL,
    LastName VARCHAR(20) NOT NULL,
    PhoneNum VARCHAR(15) NOT NULL,
    AddressLine1 VARCHAR(50) NOT NULL,
    AddressLine2 VARCHAR(50) NOT NULL )";

//inserting user data
$user_sql = "INSERT INTO user (UserName, Password, FirstName, LastName, PhoneNum, AddressLine1, AddressLine2) VALUES 
    ('alanjmckenna', 't12345', 'Alan', 'McKenna', '9998377', '38 Cranley Road', 'Fairview'),
    ('joecrotty', 'kj7899', 'Joseph', 'Crotty', '8887889', 'Apt 5 Clyde Ro', 'Donnybrook'),
    ('tommy100', '123456', 'tom', 'behan', '9983747', '14 hvde road', 'dalkey'),
    ('admin', 'pass123', 'admin', 'admin', '1234567', 'n/a', 'n/a')";

if ($conn->query($sql2) === TRUE && $conn->query($user_sql) === TRUE) {
    echo "Table user created successfully<br>";
} else {
    echo "Error creating table user: " . $conn->error . "<br>";
}

//sql for book table
$sql3 = "CREATE TABLE IF NOT EXISTS book (
    ISBN VARCHAR(20) PRIMARY KEY,
    BookTitle VARCHAR(50) NOT NULL,
    Author VARCHAR(50) NOT NULL,
    Edition INT NOT NULL,
    Year VARCHAR(4) NOT NULL,
    Reserved VARCHAR(1) NOT NULL,
    CategoryID INT(6) UNSIGNED NOT NULL,
    FOREIGN KEY (CategoryID) REFERENCES category(CategoryID) )";

//inserting book data
$book_sql = "INSERT INTO book (ISBN, BookTitle, Author, Edition, Year, Reserved, CategoryID) VALUES 
    ('093-403992', 'Computers in Business', 'Alicia Oneill', 3, 1997, 'N', '003'),
    ('23472-8729', 'Exploring Peru', 'Stephanie Birchi', 4, 2005, 'N', '005'),
    ('237-34823', 'Business Strategy', 'Joe Peppard', 2, 2002, 'N', '002'),
    ('2308-923849', 'A guide to nutrition', 'John Thorpe', 2, 1997, 'N', '001'),
    ('2983-3494', 'Cooking for children', 'Anabelle Sharpe', 1, 2003, 'N', '007'),
    ('8218-308', 'computers for idiots', 'Susan O''Neill', 5, 1998, 'N', '004'),
    ('9823-23984', 'My life in picture', 'Kevin Graham', 8, 2004, 'N', '001'),
    ('9823-2403-0', 'DaVinci Code', 'Dan Brown', 1, 2003, 'N', '008'),
    ('98234-029384', 'My ranch in Texas', 'George Bush', 1, 2005, 'Y', '001'),
    ('9823-98345', 'How to cook Italian food', 'Jamie Oliver', 2, 2005, 'Y', '007'),
    ('9823-98487', 'Optimising your business', 'Cleo Blair', 1, 2001, 'N', '002'),
    ('988745-234', 'Tara Road', 'Maeve Binchy', 4, 2002, 'N', '008'),
    ('993-004-00', 'My life in bits', 'John Smith', 1, 2001, 'N', '001'),
    ('9987-0039882', 'Shooting History', 'Jon Snow', 1, 2003, 'N', '001')";

if ($conn->query($sql3) === TRUE && $conn->query($book_sql) === TRUE) {
    echo "Table book created successfully<br>";
} else {
    echo "Error creating table book: " . $conn->error . "<br>";
}

//sql for reservations table
$sql4 = "CREATE TABLE IF NOT EXISTS reservations (
    ISBN VARCHAR(20),
    UserName VARCHAR(20),
    ReservedDate DATE NOT NULL,
    FOREIGN KEY (ISBN) REFERENCES book(ISBN) ON DELETE CASCADE,
    FOREIGN KEY (UserName) REFERENCES user(UserName) ON DELETE CASCADE )";

//inserting reservation data
    $reservation_sql = "INSERT INTO reservations (ISBN, UserName, ReservedDate) VALUES 
    ('98234-029384', 'joecrotty', '2008-10-11'),
    ('9823-98345', 'tommy100', '2008-10-11')";

if ($conn->query($sql4) === TRUE && $conn->query($reservation_sql) === TRUE) {
    echo "Table reservations created successfully<br>";
} else {
    echo "Error creating table reservations: " . $conn->error . "<br>";
}


//closing connection
$conn->close();

?>

<!--linking to index-->
<h4>Database creation successful, head to the <a href="login.php">login</a> page</h4>


<?php
include 'header.php';

//database
if ($_POST) {
    $conn = new mysqli('localhost', 'root', '', 'webassignment');
    
    //fields for user to input data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    
    //validation
    if (strlen($password) < 6 || strlen($password) > 6) {
        echo "<p class='error'>Password must be 6 characters</p>";
    } elseif (!is_numeric($phone) || strlen($phone) < 10 || strlen($phone) > 10) {
        echo "<p class='error'>Phone number must be 10 digits</p>";
    //inserting user input into database
    } else {
        $sql = "INSERT INTO user (UserName, Password, FirstName, LastName, PhoneNum, AddressLine1, AddressLine2) 
                VALUES ('$username', '$password', '$firstname', '$lastname', '$phone', '$address1', '$address2')";
        
        //confirmation message/validation
        if ($conn->query($sql)) {
            echo "<p class='success'>Registered! <a href='login.php'>Login here</a></p>";
        } else {
            echo "<p class='error'>Username exists</p>";
        }
    }
}
?>

<h2>Register</h2>
<!--Form-->
<form method="post">
    <p>Username: <input type="text" name="username" required></p>
    <p>Password: <input type="password" name="password" required></p>
    <p>First Name: <input type="text" name="firstname" required></p>
    <p>Last Name: <input type="text" name="lastname" required></p>
    <p>Mobile: <input type="text" name="phone" required></p>
    <p>Address 1: <input type="text" name="address1" required></p>
    <p>Address 2: <input type="text" name="address2" required></p>
    <input type="submit" value="Register">
</form>

<?php include 'footer.php'; ?>
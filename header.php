<!DOCTYPE html>
<html>
<head>
    <title>Library System</title>
    <style>
        body { 
            font-family: Arial;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        header { 
            background: #17324d;
            color: white; 
            padding: 10px 20px;
            margin: 0 0 20px 0;
            width: 100%;
            box-sizing: border-box;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }

        nav a { 
            color: #f4c95d;
            margin: 0 10px; 
        }

        nav a:hover {
            color: #ffe7a3;
        }

        .error { 
            color: red; 
        }

        .success { 
            color: green; 
        }

        table { 
            border: 1px solid #ccc; 
            width: 100%; 
            margin: 10px 0; 
        }

        td, th { 
            border: 1px solid #ccc; 
            padding: 8px; 
        }

        h1, h2, h3 { 
            margin: 0;
            padding: 10px; 
        }
    </style>
</head>
<body>
    <header>
        <h1>Library</h1>
        <nav>
            <?php if (isset($_SESSION['username'])): ?>
                Welcome, <?php echo $_SESSION['username']; ?> |
                <a href="webassignindex.php">Library Index</a> |
                <a href="webassignsearch.php">Search Books</a> |
                <a href="webassignmybooks.php">My Books</a> |
                <?php if ($_SESSION['username'] == 'admin'): ?>
                    <a href="webassignmanageusers.php">Manage Users</a> |
                    <a href="webassignmanagebooks.php">Manage Books</a> |
                <?php endif; ?>
                <a href="webassignlogout.php?logout=1">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a> |
                <a href="webassignregister.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>
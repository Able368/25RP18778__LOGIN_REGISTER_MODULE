<?php
require_once 'config.php';
$loggedIn = is_logged_in();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>My App - Home</title>
</head>
<body>
<h1>Welcome to My App</h1>


<?php if ($loggedIn): ?>
<p>Hello, <?=htmlspecialchars($_SESSION['username'])?> — <a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a></p>
<?php else: ?>
<p><a href="register.php">Register</a> | <a href="login.php">Login</a></p>
<?php endif; ?>


<p>Public content goes here.</p>
</body>
</html>
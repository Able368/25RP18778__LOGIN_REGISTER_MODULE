<?php
require_once 'config.php';
session_start();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lname = trim($_POST['lname'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($lname === '') {
        $errors[] = 'Last name is required.';
    }
    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (!$errors) {
        // Find all users with that last name (there may be duplicates)
        $stmt = $pdo->prepare("SELECT id, fname, lname, email, password FROM users WHERE lname = :lname");
        $stmt->execute([':lname' => $lname]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$users) {
            // No user with that last name found
            $errors[] = 'Invalid last name or password.';
        } else {
            $loggedIn = false;
            foreach ($users as $user) {
                if (password_verify($password, $user['password'])) {
                    // Password matches — log this user in
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['fname']   = $user['fname'];
                    $_SESSION['lname']   = $user['lname'];
                    $_SESSION['email']   = $user['email'];
                    // You can add other session data as needed

                    $loggedIn = true;
                    $success = true;
                    break;
                }
            }

            if (!$loggedIn) {
                $errors[] = 'Invalid last name or password.';
            } else {
                // Redirect to dashboard or wherever
                header('Location: dashboard.php');
                exit;
            }
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
</head>
<body>
<h1>Login</h1>

<?php if ($errors): ?>
    <ul style="color:red;">
        <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post" action="">
    <label>Last Name:
        <input type="text" name="lname" required value="<?= htmlspecialchars($lname ?? '') ?>">
    </label><br>

    <label>Password:
        <input type="password" name="password" required>
    </label><br>

    <button type="submit">Login</button>
</form>

<p><a href="register.php">Register</a> | <a href="index.php">Back</a></p>
</body>
</html>

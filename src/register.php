<?php
require_once 'config.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect form input
    $fname    = trim($_POST['fname'] ?? '');
    $lname    = trim($_POST['lname'] ?? '');
    $genda    = trim($_POST['genda'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    // Validation
    if ($fname === '' || strlen($fname) < 2) {
        $errors[] = 'First name must be at least 2 characters.';
    }
    if ($lname === '' || strlen($lname) < 2) {
        $errors[] = 'Last name must be at least 2 characters.';
    }
    if ($genda === '') {
        $errors[] = 'Gender is required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
    if ($password !== $password2) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);

        if ($stmt->fetch()) {
            $errors[] = "Email is already registered.";
        } else {
            // Insert new user
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $ins = $pdo->prepare("
                INSERT INTO users (fname, lname, genda, email, password)
                VALUES (:fname, :lname, :genda, :email, :password)
            ");

            $ins->execute([
                ':fname' => $fname,
                ':lname' => $lname,
                ':genda' => $genda,
                ':email' => $email,
                ':password' => $hash
            ]);

            $success = true;
        }
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Register</title>
</head>
<body>

<h1>Register</h1>

<?php if ($success): ?>
    <p style="color:green;">Registration successful. <a href="login.php">Login now</a>.</p>
<?php else: ?>

    <?php if ($errors): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="">
        <label>First Name:
            <input type="text" name="fname" required value="<?= htmlspecialchars($fname ?? '') ?>">
        </label><br>

        <label>Last Name:
            <input type="text" name="lname" required value="<?= htmlspecialchars($lname ?? '') ?>">
        </label><br>

        <label>Gender:
            <select name="genda" required>
                <option value="">--Select--</option>
                <option value="Male"   <?= (isset($genda) && $genda=="Male") ? "selected" : "" ?>>Male</option>
                <option value="Female" <?= (isset($genda) && $genda=="Female") ? "selected" : "" ?>>Female</option>
            </select>
        </label><br>

        <label>Email:
            <input type="email" name="email" required value="<?= htmlspecialchars($email ?? '') ?>">
        </label><br>

        <label>Password:
            <input type="password" name="password" required>
        </label><br>

        <label>Confirm Password:
            <input type="password" name="password2" required>
        </label><br>

        <button type="submit">Register</button>
    </form>

<?php endif; ?>

<p><a href="index.php">Back</a></p>

</body>
</html>

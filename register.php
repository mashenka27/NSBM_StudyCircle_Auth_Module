<?php
session_start();
require_once 'config/database.php'; 

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($fullName) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $error = "This email is already registered. Please log in.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insertStmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'student')");
            $insertStmt->execute([$fullName, $email, $hashed_password]);
            $success = "Registration successful! You can now login.";
        }
    }
}

require_once 'includes/header.php';
?>

<h2>Create your account</h2>

<?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
<?php if($success) echo "<p style='color:green;'>$success</p>"; ?>

<form method="POST" action="register.php">
    <label>Full Name:</label><br>
    <input type="text" name="fullname" required><br><br>

    <label>Student Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password (min 8 chars):</label><br>
    <input type="password" name="password" minlength="8" required><br><br>

    <label>Confirm Password:</label><br>
    <input type="password" name="confirm_password" minlength="8" required><br><br>

    <button type="submit">Register</button>
</form>
<p><a href="login.php">Back to Login</a></p>

<?php 
require_once 'includes/footer.php'; 
?>
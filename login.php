<?php
session_start();
require_once 'config/database.php'; 

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        $stmt = $pdo->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            header("Location: student/profile.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}

require_once 'includes/header.php'; 
?>

<h2>Welcome back</h2>
<?php if($error) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST" action="login.php">
    <label>Student Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>
<p><a href="register.php">Create your account</a></p>

<?php 
require_once 'includes/footer.php'; 
?>
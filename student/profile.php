<?php
require_once '../includes/auth.php';
require_once '../config/database.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newName = trim($_POST['fullname']);
    if (!empty($newName)) {
        $stmt = $pdo->prepare("UPDATE users SET name = ? WHERE id = ?");
        $stmt->execute([$newName, $_SESSION['user_id']]);
        $_SESSION['name'] = $newName;
    }
}

require_once '../includes/header.php';
?>

<h2>Hello, <?php echo htmlspecialchars($_SESSION['name']); ?></h2>
<p>Role: <?php echo htmlspecialchars($_SESSION['role']); ?></p>

<h3>Edit Profile</h3>
<form method="POST" action="profile.php">
    <label>Full Name:</label><br>
    <input type="text" name="fullname" value="<?php echo htmlspecialchars($_SESSION['name']); ?>" required><br><br>
    <button type="submit">Update Name</button>
</form>

<br>
<a href="../logout.php">Logout</a>

<?php 
require_once '../includes/footer.php'; 
?>
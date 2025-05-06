<?php
include 'includes/header.php';
include 'config/db.php';
$message = "";
$result = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name = $_POST['full_name'];

    $checkEmailStmt = $pdo->prepare("SELECT * FROM user WHERE email = ? OR username = ?");
    $checkEmailStmt->execute([$email, $username]);
    $result = $checkEmailStmt->fetchAll();

    if ($result) {
        $message = "Email or Username is already exists";
    }else{
        $stmt = $pdo->prepare("INSERT INTO User (username, email, password_hash, full_name, bio, photo_id) VALUES (?, ?, ?, ?, ?, 1)");
        $stmt->execute([$username, $email, $password, $full_name, $bio]);
        $_SESSION['user_id'] = $user['user_id'];
        header("Location: index.php");
        exit;
    }
}
?>

<h2>Register</h2>
<div class="card">
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <?php if($message) echo("<div style='color: red;'>".$message . "<div>"); ?>
        <input type="submit" value="Register">
    </form>
</div>

<?php include 'includes/footer.php'; ?>
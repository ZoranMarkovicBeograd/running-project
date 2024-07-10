<?php
session_start();
require_once '../config/Database.php';
require_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];

    if ($user->login()) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['role'] = $user->role;
        header("Location: index.php");
        exit();
    } else {
        $error = "Neispravno korisničko ime ili lozinka";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<?php if (isset($error)) { echo "<p>$error</p>"; } ?>
<form method="POST" action="">
    <label>Korisničko ime: <input type="text" name="username" required></label><br>
    <label>Lozinka: <input type="password" name="password" required></label><br>
    <button type="submit">Login</button>
</form>
</body>
</html>


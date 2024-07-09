<?php
require_once '../config/Database.php';
require_once '../classes/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new \classes\User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];
    $user->role = $_POST['role'];

    if ($user->register()) {
        header("Location: login.php");
        exit();
    } else {
        echo "Registracija nije uspela.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registracija</title>
</head>
<body>
<form method="POST" action="">
    <label>Korisničko ime: <input type="text" name="username" required></label><br>
    <label>Lozinka: <input type="password" name="password" required></label><br>
    <label>Uloga:
        <select name="role" required>
            <option value="organizer">Organizator</option>
            <option value="runner">Trkač</option>
        </select>
    </label><br>
    <button type="submit">Registruj se</button>
</form>
</body>
</html>


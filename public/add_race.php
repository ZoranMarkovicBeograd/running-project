<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Race.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header("Location: login.php");
    exit();
}

if (!$_SERVER['REQUEST_METHOD'] == 'POST') {
    die("Dodavanje trke nije uspelo.");
}

$race = new Race();
$race->organizer_id = $_SESSION['user_id'];
$race->location = $_POST['location'];
$race->distance = $_POST['distance'];
$race->start_time = $_POST['start_time'];
$race->max_participants = $_POST['max_participants'];

if ($race->create()) {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dodaj trku</title>
</head>
<body>
<form method="POST" action="">
    <label>Lokacija: <input type="text" name="location" required></label><br>
    <label>Dužina (u metrima): <input type="number" name="distance" required></label><br>
    <label>Datum i vreme početka: <input type="datetime-local" name="start_time" required></label><br>
    <label>Maksimalno korisnika: <input type="number" name="max_participants" required></label><br>
    <button type="submit">Dodaj trku</button>
</form>
</body>
</html>

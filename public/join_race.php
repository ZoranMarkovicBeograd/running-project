<?php
session_start();
require_once '../config/Database.php';
require_once '../classes/RaceParticipant.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'runner') {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$participant = new RaceParticipant($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $participant->race_id = $_POST['race_id'];
    $participant->user_id = $_SESSION['user_id'];

    if ($participant->join()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Pridruživanje trci nije uspelo.";
    }
}
?>

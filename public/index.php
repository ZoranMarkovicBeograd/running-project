<?php
session_start();
require_once '../config/Database.php';
require_once '../classes/Race.php';
require_once '../classes/RaceParticipant.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$race = new \classes\Race($db);
$races = $race->getAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Trke</title>
</head>
<body>
<?php if ($_SESSION['role'] == 'organizer') { ?>
    <a href="add_race.php">Dodaj trku</a><br><br>
<?php } ?>
<h2>Dostupne trke</h2>
<form method="GET" action="">
    <label>Pretraga po gradu: <input type="text" name="location"></label>
    <button type="submit">Pretraži</button>
</form>
<ul>
    <?php while ($row = $races->fetch(PDO::FETCH_ASSOC)) { ?>
        <li>
            <strong>Lokacija:</strong> <?= htmlspecialchars($row['location']) ?><br>
            <strong>Dužina:</strong> <?= htmlspecialchars($row['distance']) ?> metara<br>
            <strong>Datum i vreme početka:</strong> <?= htmlspecialchars($row['start_time']) ?><br>
            <strong>Učesnici:</strong> <?= $row['participant_count'] ?>/<?= $row['max_participants'] ?><br>
            <?php if ($row['participant_count'] < $row['max_participants'] && $_SESSION['role'] == 'runner') { ?>
                <form method="POST" action="join_race.php">
                    <input type="hidden" name="race_id" value="<?= $row['id'] ?>">
                    <button type="submit">Join</button>
                </form>
            <?php } ?>
        </li>
    <?php } ?>
</ul>
</body>
</html>

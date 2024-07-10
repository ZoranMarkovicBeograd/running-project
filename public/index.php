<?php
session_start();
require_once '../models/Race.php';
require_once '../models/RaceParticipant.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$race = new Race();
$races = $race->getAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Trke</title>
</head>
<body>
<?php if ($_SESSION['role'] == 'organizer') : ?>
    <a href="add_race.php">Dodaj trku</a><br><br>
<?php endif; ?>
<h2>Dostupne trke</h2>
<form method="GET" action="">
    <label>Pretraga po gradu: <input type="text" name="location"></label>
    <button type="submit">Pretraži</button>
</form>
<ul>
    <?php foreach($races as $race): ?>
        <li>
            <strong>Lokacija:</strong> <?= htmlspecialchars($race['location']) ?><br>
            <strong>Dužina:</strong> <?= htmlspecialchars($race['distance']) ?> metara<br>
            <strong>Datum i vreme početka:</strong> <?= htmlspecialchars($race['start_time']) ?><br>
            <strong>Učesnici:</strong> <?= $race['participant_count'] ?>/<?= $race['max_participants'] ?><br>

            <?php if ($race->canParticipate($race)): ?>
                <form method="POST" action="join_race.php">
                    <input type="hidden" name="race_id" value="<?= $race['id'] ?>">
                    <button type="submit">Join</button>
                </form>
            <?php endif; ?>

        </li>
    <?php endforeach; ?>
</ul>
</body>
</html>

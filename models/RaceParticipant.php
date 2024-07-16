<?php

require_once __DIR__ . '/../config/Database.php';
class RaceParticipant extends Database {
    const TABLE_NAME = "race_participants";

    public int $race_id;
    public int $user_id;


    public function canJoinRace(): int
    {
        $query = "SELECT max_participants, (SELECT COUNT(*) FROM race_participants WHERE race_id = :race_id) AS participant_count FROM races WHERE id = :race_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":race_id", $this->race_id);
        $stmt->execute();

        $race = $stmt->fetch(PDO::FETCH_ASSOC);

        return $race && $race['participant_count'] < $race['max_participants'];
    }

    public function join(): bool
    {

        if(!$this->canJoinRace()) {
            return false;
        }

        $query = "INSERT INTO " . self::TABLE_NAME . " (race_id, user_id) VALUES (:race_id, :user_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":race_id", $this->race_id);
        $stmt->bindParam(":user_id", $this->user_id);

        return $stmt->execute();
    }
}
?>

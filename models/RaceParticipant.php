<?php

class RaceParticipant {
    private $conn;
    private $table_name = "race_participants";

    public $id;
    public $race_id;
    public $user_id;

    public function __construct($db) {
        $this->conn = $db;
    }

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

        $query = "INSERT INTO " . $this->table_name . " (race_id, user_id) VALUES (:race_id, :user_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":race_id", $this->race_id);
        $stmt->bindParam(":user_id", $this->user_id);

        return $stmt->execute();
    }
}
?>

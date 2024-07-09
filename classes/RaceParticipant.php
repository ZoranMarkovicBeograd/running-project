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

    public function join() {
        // Provera da li je trka popunjena
        $query = "SELECT max_participants, (SELECT COUNT(*) FROM race_participants WHERE race_id = :race_id) AS participant_count FROM races WHERE id = :race_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":race_id", $this->race_id);
        $stmt->execute();
        $race = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($race && $race['participant_count'] < $race['max_participants']) {
            $query = "INSERT INTO " . $this->table_name . " (race_id, user_id) VALUES (:race_id, :user_id)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":race_id", $this->race_id);
            $stmt->bindParam(":user_id", $this->user_id);

            if ($stmt->execute()) {
                return true;
            }
        }

        return false;
    }
}
?>

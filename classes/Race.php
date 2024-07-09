<?php

class Race {
    private $conn;
    private $table_name = "races";

    public $id;
    public $organizer_id;
    public $location;
    public $distance;
    public $start_time;
    public $max_participants;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (organizer_id, location, distance, start_time, max_participants) VALUES (:organizer_id, :location, :distance, :start_time, :max_participants)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":organizer_id", $this->organizer_id);
        $stmt->bindParam(":location", htmlspecialchars(strip_tags($this->location)));
        $stmt->bindParam(":distance", $this->distance);
        $stmt->bindParam(":start_time", $this->start_time);
        $stmt->bindParam(":max_participants", $this->max_participants);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getAll() {
        $query = "SELECT races.*, COUNT(race_participants.id) AS participant_count 
                  FROM " . $this->table_name . " 
                  LEFT JOIN race_participants ON races.id = race_participants.race_id 
                  GROUP BY races.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
}
?>

<?php

class Race extends Database
{
    const TABLE_NAME = "races";

    public int $organizer_id;
    public int $location;
    public int $distance;
    public int $start_time;
    public int $max_participants;

    public function create(): bool
    {
        $query = "INSERT INTO " . self::TABLE_NAME . " (organizer_id, location, distance, start_time, max_participants) VALUES (:organizer_id, :location, :distance, :start_time, :max_participants)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":organizer_id", $this->organizer_id);
        $stmt->bindParam(":location", htmlspecialchars(strip_tags($this->location)));
        $stmt->bindParam(":distance", $this->distance);
        $stmt->bindParam(":start_time", $this->start_time);
        $stmt->bindParam(":max_participants", $this->max_participants);

        return $stmt->execute();
    }

    public function getAll(): array
    {
        $query = "SELECT races.*, COUNT(race_participants.id) AS participant_count 
                  FROM " . $this->table_name . " 
                  LEFT JOIN race_participants ON races.id = race_participants.race_id 
                  GROUP BY races.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAssoc();
    }

    public function canParticipate($race): bool
    {
        return $race['participant_count'] < $race['max_participants'] && $_SESSION['role'] == 'runner';
    }
}
?>

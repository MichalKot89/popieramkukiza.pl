<?php
class Database {
    private $connection = null;

    private $host = DATABASE_HOST;
    private $user = DATABASE_USER;
    private $password = DATABASE_PASSWORD;
    private $database = DATABASE_NAME;

    public function __construct() {
        try {
            $this->connection = new PDO('mysql:host='.$this->host.'; dbname='.$this->database, $this->user, $this->password);  
            $this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $this->connection->exec("SET CHARACTER SET utf8");
        }
        catch (PDOException $err) {  
            print 'Nie udało się połączyć z bazą danych!';

            die;
        };
    }

    public function addEmails($sender, $senderName, $emails) {
        $senderId = $this->getSender($sender, $senderName);

        foreach ($emails as $email) {
            $statement = $this->connection->prepare("SELECT id FROM emails WHERE receiver = :email AND sender = :sender LIMIT 1");

            $statement->execute(array(
                'email' => $email,
                'sender' => $senderId
            ));

            if ($statement->rowCount() === 0) {
                $newStatement = $this->connection->prepare("INSERT INTO emails (`sender`, `receiver`, `date`, `sent`)"
                        . "VALUES (:sender, :email, NOW(), 0)");

                $newStatement->execute(array(
                    'email' => $email,
                    'sender' => $senderId
                ));

                $newStatement->closeCursor();
            }
        }
    }

    public function getEmailsToSend() {
        $query = $this->connection->query("SELECT e.receiver, s.name FROM emails e LEFT JOIN senders s ON (s.id = e.sender) WHERE e.sent = 0 LIMIT 100");

        if ($query === false) {
            return array();
        } else {
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function markAsSent($emails) {
        $statement = $this->connection->prepare("UPDATE emails SET sent = 1 WHERE receiver = :email AND sent = 0");

        foreach ($emails as $email) {
            $statement->execute(array(
                'email' => $email
            ));
        }
    }

    public function getCounts() {
        $results = array(
            'senders' => 0,
            'receivers' => 0
        );

        $query = $this->connection->query("SELECT COUNT(1) as count FROM emails");

        if ($query === false) {
            return $results;
        } else {
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $results['receivers'] = (int) $result['count'];
        }

        $query = $this->connection->query("SELECT COUNT(1) as count FROM senders");

        if ($query === false) {
            return $results;
        } else {
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $results['senders'] = (int) $result['count'];
        }

        return $results;
    }

    private function getSender($sender, $senderName) {
        $statement = $this->connection->prepare("SELECT id FROM senders WHERE email = :email LIMIT 1");

        $statement->execute(array(
            'email' => $sender
        ));

        if ($statement->rowCount() === 0) {
            $newStatement = $this->connection->prepare("INSERT INTO senders (`email`, `name`, `date`) VALUES (:email, :name, NOW())");

            $newStatement->execute(array(
                'email' => $sender,
                'name' => $senderName
            ));

            return $this->connection->lastInsertId();
        } else {
            $senderRow = $statement->fetch();

            return $senderRow['id'];
        }
    }
}
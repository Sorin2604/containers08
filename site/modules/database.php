<?php
namespace modules;

class Database {
    private $connection;
    

    public function __construct($path) {
        try {
            $this->connection = new \PDO("sqlite:$path");
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Database connection error: " . $e->getMessage());
        }
    }
    

    public function Execute($sql) {
        try {
            return $this->connection->exec($sql);
        } catch (\PDOException $e) {
            die("SQL execution error: " . $e->getMessage());
        }
    }
    

    public function Fetch($sql) {
        try {
            $statement = $this->connection->query($sql);
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die("SQL fetch error: " . $e->getMessage());
        }
    }

    public function Create($table, $data) {
        try {
            $columns = implode(", ", array_keys($data));
            $placeholders = implode(", ", array_fill(0, count($data), "?"));
            
            $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            $statement = $this->connection->prepare($sql);
            $statement->execute(array_values($data));
            
            return $this->connection->lastInsertId();
        } catch (\PDOException $e) {
            die("Create record error: " . $e->getMessage());
        }
    }
    

    public function Read($table, $id) {
        try {
            $sql = "SELECT * FROM $table WHERE id = ?";
            $statement = $this->connection->prepare($sql);
            $statement->execute([$id]);
            
            return $statement->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die("Read record error: " . $e->getMessage());
        }
    }
    

    public function Update($table, $id, $data) {
        try {
            $setParts = [];
            foreach (array_keys($data) as $column) {
                $setParts[] = "$column = ?";
            }
            $setClause = implode(", ", $setParts);
            
            $sql = "UPDATE $table SET $setClause WHERE id = ?";
            $statement = $this->connection->prepare($sql);
            
            $values = array_values($data);
            $values[] = $id;
            
            return $statement->execute($values);
        } catch (\PDOException $e) {
            die("Update record error: " . $e->getMessage());
        }
    }
    

    public function Delete($table, $id) {
        try {
            $sql = "DELETE FROM $table WHERE id = ?";
            $statement = $this->connection->prepare($sql);
            
            return $statement->execute([$id]);
        } catch (\PDOException $e) {
            die("Delete record error: " . $e->getMessage());
        }
    }
    

    public function Count($table) {
        try {
            $sql = "SELECT COUNT(*) as count FROM $table";
            $statement = $this->connection->query($sql);
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            
            return (int)$result['count'];
        } catch (\PDOException $e) {
            die("Count records error: " . $e->getMessage());
        }
    }
}
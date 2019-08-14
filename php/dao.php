<?php
define("DATABASE", "WebSite");
define("DSN", "mysql:host=localhost;dbname=" . DATABASE);
define("USER", "root");
define("PASSWORD", "123");

define("TABLE_USERS", "users");
define("COLUMN_USERS_USERNAME", "username");
define("COLUMN_USERS_PASSWORD", "password");
define("COLUMN_USERS_NAME", "name");
define("COLUMN_USERS_SURNAME", "surname");


class Dao
{
    private $conn;
    public $error;

    function __construct()
    {
        try {
            $this->conn = new PDO(DSN, USER, PASSWORD);

        } catch (PDOException $e) {
            $this->error = "Connection error: " . $e->getMessage();
        }
    }

    function isConnected()
    {
        return isset($this->conn);
    }

    function validateUser($user, $password)
    {
        $sql = "SELECT * FROM " . TABLE_USERS . " WHERE " . COLUMN_USERS_USERNAME . "='" . $user . "' AND " . COLUMN_USERS_PASSWORD . "=sha1('" . $password . "')";
        $statement = $this->conn->query($sql);
        return ($statement->rowCount() == 1) ? true : false;
    }

    function validateUserSignUp($user)
    {
        $sql = "SELECT * FROM " . TABLE_USERS . " WHERE " . COLUMN_USERS_USERNAME . "='" . $user . "'";
        $statement = $this->conn->query($sql);
        return ($statement->rowCount() == 1) ? true : false;
    }

    function showNameSurname($username)
    {
        $sql = "SELECT " . COLUMN_USERS_NAME . ", " . COLUMN_USERS_SURNAME . " FROM " . TABLE_USERS . " WHERE " . COLUMN_USERS_USERNAME . " = '" . $username . "'";
        return $this->conn->query($sql)->fetchAll();
    }

    function registerUser($username, $password, $name, $surname)
    {
        try {
            $sql = "INSERT INTO " . TABLE_USERS . " VALUES ('" . $username . "', sha1('" . $password . "'), '" . $name . "', '" . $surname . "')";
            $this->conn->exec($sql);
            return ($this->validateUser($username, $password)) ? true : false;
        } catch (PDOException $e) {
            $this->error = "Error: " . $this->$e->getMessage();
            echo $this->error;
            return false;
        }
    }
}
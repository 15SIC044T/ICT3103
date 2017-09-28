<?php

class Mysql_Driver {

    /**
     * Connection holds MySQLi resource
     */
    private $connection;

    /**
     * Create new connection to database
     */
    public function connect() {
        //connection parameters
        $host = 'localhost';
        $user = 'root';
        $password = '';
        $database = 'ICT3103';

        $this->connection = mysqli_connect($host, $user, $password, $database);
        if (mysqli_connect_errno($this->connection)) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    }

    public function close() {
        mysqli_close($this->connection);
    }

    public function query($qry) {
        $result = mysqli_query($this->connection, $qry);
        return $result;
    }

    public function num_rows($result) {
        return mysqli_num_rows($result);
    }

    public function fetch_array($result) {
        return mysqli_fetch_array($result);
    }

}

?>
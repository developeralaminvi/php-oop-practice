<?php

include_once "../config/config.php";

class Database
{
    public $host = HOST;
    public $user = USER;
    public $password = PASSWORD;
    public $database = DATABASE;

    public $link;
    public $error;

    public function __construct()
    {
        $this->bdConnect();
    }
    public function bdConnect()
    {
        $this->link = mysqli_connect($this->host, $this->user, 
        $this->password, $this->database);

        if (!$this->link) {
            $this->error = "Database Cunection Filed";
            return false;
        }
    }

    // Select Query
    public function select($query)
    {
        $result = mysqli_query($this->link, $query) or die($this->link->error . __LINE__);
        if (mysqli_num_rows( $result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    //Insert Query
    public function insert($query)
    {
        $result = mysqli_query(mysql: $this->link, query: $query) or die($this->link->error . __LINE__);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    //Update Query
    public function update($query)
    {
        $result = mysqli_query(mysql: $this->link, query: $query) or die($this->link->error . __LINE__);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    //Delete Query
    public function delete($query)
    {
        $result = mysqli_query(mysql: $this->link, query: $query) or die($this->link->error . __LINE__);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
}

?>
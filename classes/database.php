<?php 
namespace App;
use mysqli;

class Database {
public mysqli $connectdb;

private string $username = "root";
private string $password = "";
private string $database = "diary";
private string $host = "localhost";

public function __construct() {
    $this->connectdb = new \mysqli(
        $this -> host,
        $this -> username,
        $this -> password,
        $this -> database );

    }
}

<?php

namespace SkgSuraj\Quizapp\Config;

use mysqli;

class Database
{
  private $connection;

  public function __construct()
  {
    $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
    $dotenv->load();

    $host = $_ENV['DB_HOST'];
    $user = $_ENV['DB_USERNAME'];
    $password = $_ENV['DB_PASSWORD'];
    $database = $_ENV['DB_DATABASE'];

    $this->connection = new mysqli($host, $user, $password, $database);
    if ($this->connection->connect_error) {
      throw new \Exception(
        'Connection failed: ' . $this->connection->connect_error
      );
    }
  }

  public function getConnection(){
    return $this->connection;
  }
  public function closeConnection(){
    $this->connection->close();
  }
}

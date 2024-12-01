<?php

namespace SkgSuraj\Quizapp\Models;
use SkgSuraj\Quizapp\Config\Database;
use Exception;

class UserModel
{
  private $db;
  public function __construct()
  {
    $this->db = new Database();
  }

  public function saveUserOnRegister ($name, $email, $password) {
    try {
      $stmt = $this->db->getConnection()->prepare("SELECT * FROM user WHERE email = ?");
      $stmt->bind_param('s', $email);
      $stmt->execute();
      $stmt->store_result();
      if($stmt->num_rows > 0) {
        return ["status" => false, "message" => "Email already in use"];
      }
      $stmt->close();
      $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
      $stmt = $this->db->getConnection()->prepare("INSERT INTO user (name, email, password) VALUES (?, ?, ?)");
      $stmt->bind_param('sss', $name, $email, $hashedPassword);
      $stmt->execute();
      $uid = $stmt->insert_id;
      $stmt->close();
      $user = $this->db->getConnection()->query("SELECT id, email, name, is_admin FROM user WHERE id = $uid");
      $user = $user->fetch_assoc();
      return ["status" => true, "message" => "User successfully registered", "user" => $user];
    } catch (Exception $e) {
      echo "Connection Failed " . $e->getMessage();
      return ["status" => false, "message" => $e->getMessage()];
    }
  }

  public function checkLogin ($email, $password) {
    try {
      $stmt = $this->db->getConnection()->prepare("SELECT * FROM user WHERE email = ?");
      $stmt->bind_param('s', $email);
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if(password_verify($password, $row['password'])){
          unset($row['password']);
          return ["status" => true, "message" => "Login Successful", "user" => $row];
        }
        return ["status" => false, "message" => "Invalid Credentials"];
      }else{
        return ["status" => false, "message" => "Invalid Credentials"];
      }
    } catch (Exception $e) {
      return ["status" => false, "message" => $e->getMessage()];
    }
  }

  public function getAllUsers () {
    try {
      $result = $this->db->getConnection()->query("SELECT * FROM user");
      return $result->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
      echo "Connection Failde " . $e->getMessage();
      return [];
    }
  }
}

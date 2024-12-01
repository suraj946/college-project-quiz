<?php
namespace SkgSuraj\Quizapp\Controllers;
use SkgSuraj\Quizapp\Models\UserModel;

class UserController{
  private $model;
  public function __construct()
  {
    $this->model = new UserModel();
  }
  public function registerUser() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $name = trim($_POST['name']);
      $email = trim($_POST['email']);
      $password = trim($_POST['password']);

      if (empty($name) || empty($email) || empty($password)) {
        echo "All fields are required.";
        return;
      }
      $response = $this->model->saveUserOnRegister($name, $email, $password);
      if($response['status']) {
        session_start();
        $_SESSION['user'] = $response['user'];
        $_SESSION['message'] = $response['message'];
        header("Location: /");
        exit();
      }
      else{
        $error = $response['message'];
        include __DIR__ . "/../Views/register.php";
      }
    } else {
      include __DIR__ . "/../Views/register.php";
    }
  }

  public function loginUser() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $email = trim($_POST['email']);
      $password = trim($_POST['password']);
      $response = $this->model->checkLogin($email, $password);
      if($response['status']) {
        session_start();
        $_SESSION['user'] = $response['user'];
        $_SESSION['message'] = $response['message'];
        header("Location: /");
        exit();
      }
      else{
        $error = $response['message'];
        include __DIR__ . "/../Views/login.php";
      }
    } else {
      include __DIR__ . "/../Views/login.php";
    }
  }
  public function logoutUser() {
    session_start();
    session_destroy();
    header("Location: /login");
    exit();
  }
}


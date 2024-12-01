<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (file_exists('../vendor/autoload.php')) {
  require_once '../vendor/autoload.php';
} else {
  echo "Autoload file not found!";
  exit;
}

use SkgSuraj\Quizapp\Controllers\UserController;
use SkgSuraj\Quizapp\Controllers\PageController;
use SkgSuraj\Quizapp\Controllers\QuestionController;

$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$userController = new UserController();
$pageController = new PageController();
$questionController = new QuestionController();

switch ($requestUri) {
  case '/':
    $pageController->indexPage();
    break;
  case '/login':
    $userController->loginUser();
    break;
  case '/register':
    $userController->registerUser();
    break;
  case '/logout':
    $userController->logoutUser();
    break;
  case '/manage-questions':
    $pageController->manageQuestions();
    break;
  case '/api/questions':
    if($method === 'GET') {
      $questionController->getAllQuestions();
    }
    else if($method === 'POST') {
      $questionController->addQuestion();
    }
    else if($method === 'PUT') {
      $questionController->updateQuestion();
    }
    else if($method === 'DELETE') {
      $questionController->deleteQuestion();
    }
    break;
  case '/api/quiz-questions':
    if($method === 'POST') {
      $questionController->getQuestionsToPlay();
    }
    break;
  case '/api/questions/json-upload':
    if($method === 'POST') {
      $questionController->uploadJsonFile();
    }
    break;
  default:
    echo "Page not found!";
    break;
}

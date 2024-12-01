<?php
namespace SkgSuraj\Quizapp\Controllers;
use SkgSuraj\Quizapp\Models\QuestionModel;
require_once __DIR__."/../Middleware/Auth.php";

class QuestionController
{
  private $model;
  public function __construct()
  {
    $this->model = new QuestionModel();
  }

  public function getAllQuestions(){
    header('Content-Type: application/json');
    adminMiddleware();  
    $response = $this->model->fetchAll();
    if($response['status']) {
      http_response_code(200);
      echo json_encode(["questions" => $response['questions'], "success" => true, "message" => "Questions fetched successfully"]);
    }else{
      http_response_code(500);
      echo json_encode(["message" => $response['message'], "success" => false]);
    }
    exit();
  }
  public function addQuestion(){
    header('Content-Type: application/json');
    adminMiddleware();  
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['question'], $data['answer'], $data['options'], $data['difficulty'])) {
      echo json_encode(["message" => "All fields are required", "success" => false]);
      exit();
    }
    $question = $data['question'];
    $answer = $data['answer'];
    $options = $data['options'];
    $difficulty = $data['difficulty'];
    if(empty($question) || empty($answer) || empty($options) || empty($difficulty)) {
      http_response_code(400);
      echo json_encode(["message" => "All fields are required", "success" => false]);
      exit();
    }
    $response = $this->model->insertQuestion($question, $answer, $options, $difficulty);
    if($response['status']) {
      http_response_code(201);
      echo json_encode(["message" => "Question added successfully", "question" => $response['question'], "success" => true]);
    }else{
      http_response_code(500);
      echo json_encode(["message" => $response['message'], "success" => false]);
    }
    exit();
  }
  public function updateQuestion(){
    header('Content-Type: application/json');
    adminMiddleware();  
    $id = null; $question = null; $answer = null; $options = null; $difficulty = null;
    $data = json_decode(file_get_contents("php://input"), true);
    if(!isset($data['id']) || empty($data['id'])) {
      http_response_code(400);
      echo json_encode(["message" => "Question id is required", "success" => false]);
      exit();
    }else $id = $data['id'];
    if(isset($data['question'])) $question = $data['question'];
    if(isset($data['answer'])) $answer = $data['answer'];
    if(isset($data['options'])) $options = $data['options'];
    if(isset($data['difficulty'])) $difficulty = $data['difficulty'];
    
    if(!$question && !$answer && !$options && !$difficulty) {
      http_response_code(400);
      echo json_encode(["message" => "Nothing to update", "success" => false]);
      exit();
    }
    $response = $this->model->modifyQuestion($id, $question, $answer, $options, $difficulty);
    if($response['status']) {
      http_response_code(200);
      echo json_encode(["message" => "Question modified successfully", "success" => true, "question" => $response['question']]);  
    }else{
      http_response_code(500);
      echo json_encode(["message" => $response['message'], "success" => false]);
    }
    exit();
  }
  public function deleteQuestion(){
    header('Content-Type: application/json');
    adminMiddleware();  
    $data = json_decode(file_get_contents("php://input"), true);
    if(!isset($data['id']) || empty($data['id'])) {
      http_response_code(400);
      echo json_encode(["message" => "Question id is required", "success" => false]);
      exit();
    }
    $response = $this->model->removeQuestion($data['id']);
    if($response['status']) {
      http_response_code(200);
      echo json_encode(["message" => "Question deleted successfully", "success" => true]);  
    }else{
      http_response_code(500);
      echo json_encode(["message" => $response['message'], "success" => false]);
    }
    exit();
  }
  public function getQuestionsToPlay(){
    header('Content-Type: application/json');
    userMiddleware();  
    $data = json_decode(file_get_contents("php://input"), true);
    $difficulty = 'easy';
    if(isset($data['difficulty']) && !empty($data['difficulty'])) {
      $difficulty = $data['difficulty'];
    }
    $response = $this->model->getQuestionsOnDifficulty($difficulty);
    if($response['status']) {
      http_response_code(200);
      echo json_encode(["questions" => $response['questions'], "success" => true, "message" => "Lets play!"]);  
    }else{
      http_response_code(500);
      echo json_encode(["message" => $response['message'], "success" => false]);
    }
    exit();
  }

  public function uploadJsonFile(){
    header('Content-Type: application/json');
    adminMiddleware();
    $data = json_decode(file_get_contents("php://input"), true);
    if(!isset($data['jsonData']) || empty($data['jsonData'])) {
      http_response_code(400);
      echo json_encode(["message" => "jsonData is required", "success" => false]);
      exit();
    }
    $response = $this->model->storeMultipleQuestions($data['jsonData']);
    if($response['status']) {
      http_response_code(200);
      echo json_encode(["message" => "Questions added successfully", "success" => true]);  
    }else{
      http_response_code(500);
      echo json_encode(["message" => $response['message'], "success" => false]);
    }
    exit();
  }
}
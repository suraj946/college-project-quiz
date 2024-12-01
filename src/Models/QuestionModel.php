<?php
namespace SkgSuraj\Quizapp\Models;
use SkgSuraj\Quizapp\Config\Database;
use Exception;

class QuestionModel {
  private $db;
  public function __construct(){
    $this->db = new Database();
  }
  public function fetchAll () {
    try {
      $result = $this->db->getConnection()->query("SELECT * FROM question ORDER BY id ASC");
      $result = $result->fetch_all(MYSQLI_ASSOC);
      return ["status" => true, "questions" => $result];
    } catch (Exception $e) {
      echo "Connection Failed " . $e->getMessage();
      return ["status" => false, "message" => $e->getMessage()];
    }
  }
  public function insertQuestion($question, $answer, $options, $difficulty){
    try {
      $stmt = $this->db->getConnection()->prepare("INSERT INTO question (question, answer, options, difficulty) VALUES (?, ?, ?, ?)");
      $stmt->bind_param('ssss', $question, $answer, $options, $difficulty);
      $stmt->execute();
      $qid = $stmt->insert_id;
      $stmt->close();
      $insertedQuestion = $this->db->getConnection()->query("SELECT * FROM question WHERE id = $qid");
      $insertedQuestion = $insertedQuestion->fetch_assoc();
      return ["status" => true, "question" => $insertedQuestion];
    } catch (Exception $e) {
      echo "Connection Failed " . $e->getMessage();
      return ["status" => false, "message" => $e->getMessage()];
    }
  }
  public function modifyQuestion($id, $question = null, $answer = null, $options = null, $difficulty = null) {
    try {
      $fields = [];
      $params = [];
      $types = '';
      if (!empty($question)) {
        $fields[] = 'question = ?';
        $params[] = $question;
        $types .= 's';
      }
      if (!empty($answer)) {
        $fields[] = 'answer = ?';
        $params[] = $answer;
        $types .= 's';
      }
      if (!empty($options)) {
        $fields[] = 'options = ?';
        $params[] = $options;
        $types .= 's';
      }
      if (!empty($difficulty)) {
        $fields[] = 'difficulty = ?';
        $params[] = $difficulty;
        $types .= 's';
      }
      $query = "UPDATE question SET " . implode(', ', $fields) . " WHERE id = ?";
      $params[] = $id;
      $types .= 'i';
      $stmt = $this->db->getConnection()->prepare($query);
      $stmt->bind_param($types, ...$params);
      $stmt->execute();
      $stmt->close();
      $updatedQuestion = $this->db->getConnection()->query("SELECT * FROM question WHERE id = $id");
      $updatedQuestion = $updatedQuestion->fetch_assoc();
      return ["status" => true, "question" => $updatedQuestion];
    } catch (Exception $e) {
      echo "Connection Failed " . $e->getMessage();
      return ["status" => false, "message" => $e->getMessage()];
    }
  }
  public function removeQuestion($id) {
    try {
      $stmt = $this->db->getConnection()->prepare("DELETE FROM question WHERE id = ?");
      $stmt->bind_param('i', $id);
      $stmt->execute();
      $stmt->close();
      return ["status" => true];
    } catch (Exception $e) {
      echo "Connection Failed " . $e->getMessage();
      return ["status" => false, "message" => $e->getMessage()];
    }
  }
  public function getQuestionsOnDifficulty($difficulty) {
    try {
      $result = $this->db->getConnection()->query("SELECT * FROM question WHERE difficulty = '$difficulty' ORDER BY RAND() LIMIT 20");
      $result = $result->fetch_all(MYSQLI_ASSOC);
      return ["status" => true, "questions" => $result];
    } catch (Exception $e) {
      echo "Connection Failed: " . $e->getMessage();
      return ["status" => false, "message" => $e->getMessage()];
    }
  }

  public function storeMultipleQuestions($questions){
    try {
      $sql = "INSERT INTO question (question, answer, options, difficulty) VALUES ";
      $placeholders = [];
      $values = [];
      foreach ($questions as $question) {
        $placeholders[] = "(?, ?, ?, ?)";
        $values[] = $question['question'];
        $values[] = $question['answer'];
        $values[] = $question['options'];
        $values[] = $question['difficulty'];
      }
      $sql .= implode(', ', $placeholders);
      $stmt = $this->db->getConnection()->prepare($sql);
      $types = str_repeat('s', count($values));
      $stmt->bind_param($types, ...$values);
      $stmt->execute();
      $stmt->close();
      return ["status" => true];
    }
    catch (Exception $e) {
      echo "Connection Failed " . $e->getMessage();
      return ["status" => false, "message" => $e->getMessage()];
    }
  }
};
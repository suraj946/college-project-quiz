<?php
namespace SkgSuraj\Quizapp\Controllers;
class PageController{
  public function indexPage(){
    include __DIR__."/../Views/home.php";
  }

  public function manageQuestions(){
    include __DIR__."/../Views/question-mgmt.php";
  }
}

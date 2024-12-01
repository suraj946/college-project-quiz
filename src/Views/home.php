<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home Page</title>
  <link rel="stylesheet" href="/assests/css/popup.css">
  <link rel="stylesheet" href="/assests/css/navbar.css">
  <link rel="stylesheet" href="/assests/css/home.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>
  <?php
  session_start();
  if (!isset($_SESSION['user'])) {
    header("Location: login");
    exit();
  }
  ?>
  <?php include __DIR__ . "/partials/popup.php" ?>
  <?php include __DIR__ . "/partials/navbar.php" ?>

  <div class="difficulty-container" id="difficulty-container">
    <h1 class="text-center">Choose Difficulty Level</h1>
    <div class="btn-container">
      <button id="easy" class="btn btn-primary">Easy</button>
      <button id="medium" class="btn btn-success">Medium</button>
      <button id="hard" class="btn btn-danger">Hard</button>
    </div>
    <div class="spinner-border text-primary hide" role="status" id="spinner">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
  <div class="container hide" id="gameCanvas">
    <div class="common questionBox">
      <div class="question">
        <p id="questionTxt"></p>
      </div>
      <div class="options">
        <div class="inpBox">
          <input class="inputRadios" name="option" type="radio" id="option1">
          <label class="labels" for="option1"></label>
        </div>
        <div class="inpBox">
          <input class="inputRadios" name="option" type="radio" id="option2">
          <label class="labels" for="option2"></label>
        </div>
        <div class="inpBox">
          <input class="inputRadios" name="option" type="radio" id="option3">
          <label class="labels" for="option3"></label>
        </div>
        <div class="inpBox">
          <input class="inputRadios" name="option" type="radio" id="option4">
          <label class="labels" for="option4"></label>
        </div>
      </div>
      <button class="nextBtn" id="btn">Next Question</button>
    </div>
    <div class="common summaryBox">
      <p id="score"></p>
      <div class="restartBtns">
        <button class="btn btn-info" id="restartBtn">Restart</button>
        <button class="btn btn-success" id="replayBtn">Replay</button>
      </div>
      <div class="summary"></div>
    </div>
  </div>

  <script src="/assests/js/popup.js"></script>
  <script src="/assests/js/home.js"></script>
  <script>
    <?php
    if (isset($_SESSION['message'])): ?>
      showPopup("<?php echo $_SESSION['message']; ?>");
      <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
  </script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Question Management</title>
  <link rel="stylesheet" href="/assests/css/popup.css">
  <link rel="stylesheet" href="/assests/css/navbar.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="/assests/css/question.css">
</head>

<body>
  <?php
  session_start();
  if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] == 0) {
    header("Location: /login");
    exit();
  }
  ?>
  <?php include __DIR__ . "/partials/popup.php" ?>
  <?php include __DIR__ . "/partials/navbar.php" ?>

  <div class="container mt-5">
    <h1 class="text-center">Question Management</h1>
    <div class="btn-container">
      <button type="button" id="modalBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#questionFormModal">
        Add Question
      </button>
      <div class="fileBtnContainer">
        <div class="fileBtn" id="fileBtn">
          <input type="file" id="fileInput" accept="application/json" hidden>
          <label for="fileInput" id="uploadBtn" class="btn-style">Upload JSON File</label>
        </div>
        <div class="spinner-border text-light hide" role="status" id="spinner">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    </div>

    <table class="table mt-3 text-center" id="questionsTable">
      <thead class="text-center">
        <tr>
          <th>ID</th>
          <th>Question</th>
          <th>Answer</th>
          <th>Options</th>
          <th>Difficulty</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="questionsBody" class="text-center"></tbody>
    </table>

  </div>
  
  <div class="modal fade" id="questionFormModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-header">Add Question</h5>
      </div>
      <div class="modal-body myForm">
        <div class="input-container">
          <input type="text" id="question" placeholder="Enter question">
          <span class="error" id="questionError"></span>
        </div>
        <div class="input-container">
          <input type="text" id="answer" placeholder="Enter answer">
          <span class="error" id="answerError"></span>
        </div>
        
        <label class="form-label">Options</label>
        <div class="mb-3 option-container">
          <div class="option-group">
            <div class="input-container">
              <input type="text" id="option1" placeholder="Enter option 1">
              <span class="error" id="option1Error"></span>
            </div>
            <div class="input-container">
              <input type="text" id="option2" placeholder="Enter option 2">
              <span class="error" id="option2Error"></span>
            </div>
          </div>

          <div class="option-group">
            <div class="input-container">
              <input type="text" id="option3" placeholder="Enter option 3">
              <span class="error" id="option3Error"></span>
            </div>
            <div class="input-container">
              <input type="text" id="option4" placeholder="Enter option 4">
              <span class="error" id="option4Error"></span>
            </div>
          </div>
        </div>
        <div class="form-group difficulty-container">
          <label for="difficulty">Difficulty</label>
          <select class="form-control" id="difficulty">
            <option value="easy">Easy</option>
            <option value="medium">Medium</option>
            <option value="hard">Hard</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="cancelBtn" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="saveBtn" class="btn btn-primary">Add Question</button>
      </div>
    </div>
  </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="/assests/js/popup.js"></script>
  <script src="/assests/js/question.js"></script>
  <script>
    <?php
    if (isset($_SESSION['message'])): ?>
      showPopup("<?php echo $_SESSION['message']; ?>");
      <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
  </script>
</body>

</html>
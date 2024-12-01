<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="/assests/css/popup.css">
  <link rel="stylesheet" href="/assests/css/login-signup.css">
</head>
  <body>
  <?php include __DIR__ . "/partials/popup.php" ?>
  <?php
    session_start();
    if (isset($_SESSION['user'])) {
      header("Location: /");
      exit();
    }
   ?>
    <div class="container">
      <h2>Login</h2>
      <form id="loginForm" method="POST" action="/login" novalidate>
        <div class="input-container">
          <input type="email" id="email" name="email" placeholder="Email">
          <span class="error" id="emailError"></span>
        </div>

        <div class="input-container">
          <input type="password" id="password" name="password" placeholder="Password">
          <span class="error" id="passwordError"></span>
        </div>

        <button type="submit" id="submitBtn">Login</button>
      </form>
      <div class="link-container">
        <p>Don't have an account? <a href="/register">Sign Up</a></p>
      </div>
    </div>
  </body>
    <script src="/assests/js/popup.js"></script>
    <script type="text/javascript">
      document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const submitBtn = document.getElementById('submitBtn');
        
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');

        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,}$/;
        const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;

        function validateEmail() {
          if(emailInput.value.trim() === ""){
            emailError.textContent = 'Email is required';
            return false;
          }
          else if (!emailRegex.test(emailInput.value)) {
            emailError.textContent = 'Please enter a valid email address';
            return false;
          } else {
            emailError.textContent = '';
            return true;
          }
        }

        function validatePassword() {
          if(passwordInput.value.trim() === ""){
            passwordError.textContent = 'Password is required';
            return false;
          }
          else if (!passwordRegex.test(passwordInput.value)) {
            passwordError.textContent = 'Password must be at least 6 characters long and include a letter, a number, and a special character.';
            return false;
          } else {
            passwordError.textContent = '';
            return true;
          }
        }

        function validateForm() {
          const isEmailValid = validateEmail();
          const isPasswordValid = validatePassword();
          return (isEmailValid && isPasswordValid);
        }

        emailInput.addEventListener('input', validateEmail);
        passwordInput.addEventListener('input', validatePassword);

        form.addEventListener('submit', (event) => {
          event.preventDefault();
          if (validateForm()) {
            form.submit();
          }
        });
      });
      <?php if (isset($error)): ?>
        showPopup("<?php echo $error; ?>");
        <?php unset($error); ?>
      <?php endif; ?>
    </script>
  </body>
</html>

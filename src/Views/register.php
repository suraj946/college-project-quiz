<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
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
      <h2>Register</h2>
      <form id="registerForm" method="POST" action="/register" novalidate>
        <div class="input-container">
          <input type="text" id="name" name="name" placeholder="Name">
          <span class="error" id="nameError"></span>
        </div>

        <div class="input-container">
          <input type="email" id="email" name="email" placeholder="Email">
          <span class="error" id="emailError"></span>
        </div>

        <div class="input-container">
          <input type="password" id="password" name="password" placeholder="Password">
          <span class="error" id="passwordError"></span>
        </div>

        <div class="input-container">
          <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
          <span class="error" id="confirmPasswordError"></span>
        </div>    
        <button type="submit" id="submitBtn">Register</button>
      </form>
      <div class="link-container">
        <p>Already have an account? <a href="/login">Login</a></p>
      </div>
    </div>
  </body>
    <script src="/assests/js/popup.js"></script>
    <script type="text/javascript">
      document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('registerForm');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const submitBtn = document.getElementById('submitBtn');
        
        const nameError = document.getElementById('nameError');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');
        const confirmPasswordError = document.getElementById('confirmPasswordError');

        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,}$/;
        const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;
        const nameRegex = /^[A-Za-z ]{3,50}$/;

        function validateName() {
          if(nameInput.value.trim() === '') {
            nameError.textContent = 'Name is required';
            return false;
          }
          else if (!nameRegex.test(nameInput.value)) {
            nameError.textContent = 'Name must be between 3 and 50 characters and contain only letters';
            return false;
          } else {
            nameError.textContent = '';
            return true;
          }
        }

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

        function validateConfirmPassword() {
          if(confirmPasswordInput.value.trim() === "") {
            confirmPasswordError.textContent = 'Confirm Password is required';
            return false;
          }
          else if (confirmPasswordInput.value !== passwordInput.value) {
            confirmPasswordError.textContent = 'Passwords do not match';
            return false;
          } else {
            confirmPasswordError.textContent = '';
            return true;
          }
        }

        function validateForm() {
          const isNameValid = validateName();
          const isEmailValid = validateEmail();
          const isPasswordValid = validatePassword();
          const isConfirmPasswordValid = validateConfirmPassword();
          return (isNameValid && isEmailValid && isPasswordValid && isConfirmPasswordValid);
        }

        nameInput.addEventListener('input', validateName);
        emailInput.addEventListener('input', validateEmail);
        passwordInput.addEventListener('input', validatePassword);
        confirmPasswordInput.addEventListener('input', validateConfirmPassword);

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

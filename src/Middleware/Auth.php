<?php

function adminMiddleware() {
  session_start();
  if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] !== 1) {
    http_response_code(401);
    echo json_encode(["message" => "Unauthorized, not an admin", "success" => false]);
    exit();
  }
  return true;
}

function userMiddleware() {
  session_start();
  if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(["message" => "Unauthorized, not logged in", "success" => false]);
    exit();
  }
  return true;
}
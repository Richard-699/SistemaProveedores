<?php
if (session_status() === PHP_SESSION_NONE && !session_start()) {
    header("Location: ../Auth/login.php");
    exit();
}

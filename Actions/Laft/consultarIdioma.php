<?php

session_start();

$idioma = $_SESSION['lang'];

$response = [
    'success' => true,
    'idioma' => $idioma
];

echo json_encode($response);

?>
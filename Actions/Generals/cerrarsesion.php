<?php
session_start();
session_destroy();

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

header("location: ../../index.php");
?>

<?php
//log out and destroy session
session_start();
session_destroy();
header('Location: ../views/login.php');
exit();
?>
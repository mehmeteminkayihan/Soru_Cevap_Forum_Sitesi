<?php

session_start();

// Oturumu yok et (kapat)
session_destroy();

header("Location: login.php");
exit();
?>
<?php
session_start();
session_destroy();
header("Location: files_user/login.php");
?>
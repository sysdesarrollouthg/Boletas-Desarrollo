<?php
session_start();
unset($_SESSION['boleta']);
header('Location: index.php?views=establecimiento');
exit;

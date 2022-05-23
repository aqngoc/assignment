<?php
ob_start();
session_start();
if(isset($_SESSION)){
    session_destroy();
    header("Location: index.php");
}
else{
    header("Location: index.php");
}

<?php
   session_start();

   unset($_SESSION['username'],$_SESSION['userid'],$_SESSION['role']);

   session_destroy();

   header('Location: index.php');

   exit(); 

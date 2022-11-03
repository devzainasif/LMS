<?php 

//logout.php

//Simply Destroy the session, So that it will destroy User Login detail also.
session_start();
session_destroy();
header('location:index.php');

?>
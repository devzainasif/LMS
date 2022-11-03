<?php

//logout.php

//Simply Destroy the session, So that it will destroy Admin Login detail also.
session_start();
session_destroy();
header('location:../index.php');

?>
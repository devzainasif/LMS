<?php

include 'database_connection.php'; //Database Connectivity & Availabilty.
include 'function.php'; //Functions that we can use in our this file.

if (is_admin_login()){
	header('location:admin/index.php');
}
elseif (is_user_login()) { //If User is already Logged in then it will redirect to Issue_book_details
    header('location:issue_book_details.php');
}
else {
	include 'header.php';
?>

<div class="row align-items-md-stretch">

	<!-- Admin Login Panal button. -->
	<div class="col-md-12" style="margin: 25px 0px;"> 
		<div class="h-100 p-5 text-white bg-secondary adminLoginDesign" style="width: 50%; margin: auto;">
			<h2 class="d-flex justify-content-center">Admin Login</h2>
			<p></p>
			<a href="admin_login.php" class="btn btn-outline-secondary btn-light  d-flex justify-content-center loginBtn" style="width: 50%; margin: auto;">Admin Login</a>
		</div>
	</div>

	<!-- User Login Panal & NEW Resgisteration button. -->
	<div class="col-md-12" style="margin-bottom: 57px;">
		<div class="h-100 p-5 text-dark bg-light userLoginDesign" style="width: 50%; margin: auto;">
			<h2 class="d-flex justify-content-center">User Login</h2>
			<a href="user_login.php" class="btn btn-outline-light  btn-secondary d-flex justify-content-center loginBtn" style="width: 50%; margin: auto;">User Login</a>
			<a href="user_registration.php" class="btn btn-outline-danger btn-light d-flex justify-content-center mt-2 loginBtn" style="width: 50%; margin: auto;">Register Now</a>
		</div>
	</div>

</div>

<?php
}
include 'footer.php';

?>


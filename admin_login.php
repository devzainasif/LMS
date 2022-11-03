<?php

//admin_login.php


include 'database_connection.php'; //Database Connectivity & Availabilty.
include 'function.php'; //Functions that we can use in our this file.

$message = ''; //Use for multiple purpose to show errors & warning messages.

if (isset($_POST["login_button"])) //When we press login button from admin login panal.
{

	$formdata = array(); //This will get data from Front-end & transfer to back-end.
	if (empty($_POST["admin_email"])) //if user enter an empty email adress.
	{
		$message .= '<li>Email Address is required</li>'; //warning message.
	} else {
		if (!filter_var($_POST["admin_email"], FILTER_VALIDATE_EMAIL)) //Alogrithm check the validation of email address.
		{
			$message .= '<li>Invalid Email Address</li>'; //warning message.
		} else {
			$formdata['admin_email'] = $_POST['admin_email']; //if email address is all correct.
		}
	}

	if (empty($_POST['admin_password'])) //if user enter an empty password.
	{
		$message .= '<li>Password is required</li>'; //warning message.
	} else {
		$formdata['admin_password'] = $_POST['admin_password']; //if password is all correct.
	}

	if ($message == '') //This is use to check if there is any warning message. if yes then it will never run it.
	{
		$data = array(
			':admin_email'		=>	$formdata['admin_email'] //User entered email Address.
		);

		//Get user from database according to given email address.
		$query = "
		SELECT * FROM lms_admin 
        WHERE admin_email = :admin_email
		";

		$statement = $connect->prepare($query);
		$statement->execute($data);

		if ($statement->rowCount() > 0) //if statement is greater than 0, then it show there is a user present with this email address.
		{
			foreach ($statement->fetchAll() as $row) {
				if ($row['admin_status'] == 'Enable') {
					if ($row['admin_password'] == $formdata['admin_password']) //if password match with database password of user.
					{
						$_SESSION['admin_id'] = $row['admin_id'];
						header('location:admin/index.php');
					} else {
						$message = '<li>Wrong Password</li>'; //warning message.
					}
				} else {
					$message = '<li>Your Account has been disabled</li>';	 //warning message.
				}
			}
		} else {
			$message = '<li>Wrong Email Address</li>'; //warning message.
		}
	}
}

include 'header.php';

?>
<!-- This is Admin login Form -->
<div class="d-flex align-items-center justify-content-center" style="min-height:531px;">
	<div class="col-md-6">

		<?php
		if ($message != '') {
			//it will show any warning message.
			echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'.$message.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		?>

		<div class="card">
			<div class="card-header bg-secondary text-light">Admin Login</div>
			<div class="card-body">
				<form method="POST">
					<div class="mb-3">
						<label class="form-label">Email address</label>
						<input type="text" name="admin_email" id="admin_email" class="form-control" />
					</div>
					<div class="mb-3">
						<label class="form-label">Password</label>
						<input type="password" name="admin_password" id="admin_password" class="form-control" />
					</div>
					<div class="d-flex align-items-center justify-content-between mt-4 mb-0">
						<input type="submit" name="login_button" class="btn btn-secondary" value="Login" />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php
include 'footer.php';
?>
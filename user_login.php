<?php

//user_login.php

include 'database_connection.php'; //Database Connectivity & Availabilty.
include 'function.php'; //Functions that we can use in our this file.

if(is_user_login()) //If User is already logged in.
{
	header('location:issue_book_details.php');
}

$message = '';

if(isset($_POST["login_button"])) //When User press login button.
{
	$formdata = array();//This will get data from Front-end & transfer to back-end.
	if(empty($_POST["user_email_address"]))//if user enter an empty email adress.
	{
		$message .= '<li>Email Address is required</li>'; //warning message.
	}
	else
	{
		if(!filter_var($_POST["user_email_address"], FILTER_VALIDATE_EMAIL)) //Alogrithm check the validation of email address.
		{
			$message .= '<li>Invalid Email Address</li>'; //warning message.
		}
		else
		{
			$formdata['user_email_address'] = trim($_POST['user_email_address']); //if email address is all correct.
		}
	}

	if(empty($_POST['user_password'])) //if user enter an empty password.
	{
		$message .= '<li>Password is required</li>'; //warning message.
	}	
	else
	{
		$formdata['user_password'] = trim($_POST['user_password']); //if password is all correct.
	}

	if($message == '') //This is use to check if there is any warning message. if yes then it will never run it.
	{
		$data = array(
			':user_email_address'		=>	$formdata['user_email_address'] //User entered email Address.
		);

		//Get user from database according to given email address.
		$query = "
		SELECT * FROM lms_user 
        WHERE user_email_address = :user_email_address
		";

		$statement = $connect->prepare($query);

		$statement->execute($data);

		if($statement->rowCount() > 0) //if statement is greater than 0, then it show there is a user present with this email address.
		{
			foreach($statement->fetchAll() as $row)
			{
				if($row['user_status'] == 'Enable')
				{
					if($row['user_password'] == $formdata['user_password']) //if password match with database password of user.
					{
						$_SESSION['user_id'] = $row['user_unique_id'];
						header('location:issue_book_details.php');
					}
					else
					{
						$message = '<li>Wrong Password</li>'; //warning message.
					}
				}
				else 
				{
					
					$message = '<li>Your Account has been disabled</li>';	 //warning message.
				}
			}
		}
		else
		{
			$message = '<li>Wrong Email Address</li>'; //warning message.
		}
	}
}

include 'header.php';

?>
<!-- This is User login Form -->
<div class="d-flex align-items-center justify-content-center" style="height:531px;">
	<div class="col-md-6">
		<?php 

		if($message != '')
		{
			echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'.$message.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}

		?>
		<div class="card">
			<div class="card-header bg-secondary text-light">User Login</div>
			<div class="card-body">
				<form method="POST">
					<div class="mb-3">
						<label class="form-label">Email address</label>
						<input type="text" name="user_email_address" id="user_email_address" class="form-control" />
					</div>
					<div class="mb-3">
						<label class="form-label">Password</label>
						<input type="password" name="user_password" id="user_password" class="form-control" />
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
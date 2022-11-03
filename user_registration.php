<?php

//user_registration.php

include 'database_connection.php'; //Database Connectivity & Availabilty.
include 'function.php'; //Functions that we can use in our this file.

if(is_user_login())
{
	header('location:issue_book_details.php');
}

$message = ''; //Warnimg Message
$success = ''; //Success Message when user successfully registered.

if(isset($_POST["register_button"])) //When User press on Resgister Button.
{
	$formdata = array();//This will get data from Front-end & transfer to back-end.
	if(empty($_POST["user_email_address"])) //if user enter an empty email adress.
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

	if(empty($_POST["user_password"])) //if user enter an empty password.
	{
		$message .= '<li>Password is required</li>'; //warning message.
	}
	else
	{
		$formdata['user_password'] = trim($_POST['user_password']); //if password is all correct.
	}

	if(empty($_POST['user_name'])) //if user enter an empty name.
	{
		$message .= '<li>User Name is required</li>'; //warning message.
	}
	else
	{
		$formdata['user_name'] = trim($_POST['user_name']); //if name is all correct.
	}

	if(empty($_POST['user_address'])) //if user enter an empty address.
	{
		$message .= '<li>User Address Detail is required</li>'; //warning message.
	}
	else
	{
		$formdata['user_address'] = trim($_POST['user_address']); //if Address is all correct.
	}

	if(empty($_POST['user_contact_no'])) //if user enter an empty contact Number.
	{
		$message .= '<li>User Contact Number Detail is required</li>'; //warning message.
	}
	else
	{
		$formdata['user_contact_no'] = trim($_POST['user_contact_no']); //if Contact Number is all correct.
	}

	if(!empty($_FILES['user_profile']['name'])) 
	{
		//Uploading User Profile Image.
		$img_name = $_FILES['user_profile']['name'];
		$img_type = $_FILES['user_profile']['type'];
		$tmp_name = $_FILES['user_profile']['tmp_name'];
		$fileinfo = @getimagesize($tmp_name);
		$width = $fileinfo[0];
		$height = $fileinfo[1];

		$image_size = $_FILES['user_profile']['size'];
		$img_explode = explode(".", $img_name);
		$img_ext = strtolower(end($img_explode));
		$extensions = ["jpeg", "png", "jpg"];

		if(in_array($img_ext, $extensions))
		{
			if($image_size <= 2000000)  //Check image size
			{
				if($width == '225' && $height == '225') //If full fill the required size of image.
				{
					$new_img_name = time() . '-' . rand() . '.' . $img_ext;
					if(move_uploaded_file($tmp_name, "upload/".$new_img_name)) //Saved in upload Folder with new name.
					{
						$formdata['user_profile'] = $new_img_name;
					}
				}
				else
				{
					$message .= '<li>Image dimension should be within 225 X 225</li>'; //warning message.
				}
			}
			else
			{
				$message .= '<li>Image size exceeds 2MB</li>'; //warning message.
			}
		}
		else
		{
			$message .= '<li>Invalid Image File</li>'; //warning message.
		}
	}
	else
	{
		$message .= '<li>Please Select Profile Image</li>'; //warning message.
	}

	if($message == '') //If there is no warning message.
	{
		$data = array(
			':user_email_address'		=>	$formdata['user_email_address']
		);

		$query = "
		SELECT * FROM lms_user 
        WHERE user_email_address = :user_email_address
		";

		$statement = $connect->prepare($query);
		$statement->execute($data);

		if($statement->rowCount() > 0) //If user's Email is already Exists.
		{
			$message = '<li>Email Already Register</li>';
		}
		else //If user's Email is not already Exists.
		{
			$user_verificaton_code = md5(uniqid()); //Mdfiye the Unique ID.
			$user_unique_id = 'U' . rand(10000000,99999999); //Generate Unique ID.

			$data = array(
				':user_name'			=>	$formdata['user_name'],
				':user_address'			=>	$formdata['user_address'],
				':user_contact_no'		=>	$formdata['user_contact_no'],
				':user_profile'			=>	$formdata['user_profile'],
				':user_email_address'	=>	$formdata['user_email_address'],
				':user_password'		=>	$formdata['user_password'],
				':user_verificaton_code'=>	$user_verificaton_code,
				':user_unique_id'		=>	$user_unique_id,
				':user_status'			=>	'Enable',
				':user_created_on'		=>	get_date_time($connect)
			);

			//Insert into USER Table a new User.
			$query = "
			INSERT INTO lms_user 
            (user_name, user_address, user_contact_no, user_profile, user_email_address, user_password, user_verificaton_code, user_unique_id, user_status, user_created_on) 
            VALUES (:user_name, :user_address, :user_contact_no, :user_profile, :user_email_address, :user_password, :user_verificaton_code, :user_unique_id, :user_status, :user_created_on)
			";

			$statement = $connect->prepare($query);
			$statement->execute($data);

			$success = 'Thank you for registering for Library Management System & your Unique ID is <b>'.$user_unique_id.'</b> which will be used for issue book.';
		}

	}
}
include 'header.php';
?>


<div class="d-flex align-items-center justify-content-center mt-5 mb-5" style="min-height:700px;">
	<div class="col-md-6">
		<?php 
		if($message != '')
		{
			echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul>'.$message.'</ul><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		if($success != '')
		{
			echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'.$message.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';

			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'.$success.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
		}
		?>
		<!-- New User Registeration Form -->
		<div class="card">
			<div class="card-header bg-danger text-light">New User Registration</div>
			<div class="card-body">
				<form method="POST" enctype="multipart/form-data">
					<div class="mb-3">
						<label class="form-label">Email address</label>
						<input type="text" name="user_email_address" id="user_email_address" class="form-control" />
					</div>
					<div class="mb-3">
						<label class="form-label">Password</label>
						<input type="password" name="user_password" id="user_password" class="form-control" />
					</div>
					<div class="mb-3">
						<label class="form-label">User Name</label>
                        <input type="text" name="user_name" class="form-control" id="user_name" value="" />
                    </div>
					<div class="mb-3">
						<label class="form-label">User Contact No.</label>
						<input type="text" name="user_contact_no" id="user_contact_no" class="form-control" />
					</div>
					<div class="mb-3">
						<label class="form-label">User Address</label>
						<textarea name="user_address" id="user_address" class="form-control"></textarea>
					</div>
					<div class="mb-3">
						<label class="form-label">User Photo</label><br />
						<input type="file" name="user_profile" id="user_profile" />
						<br />
						<span class="text-muted">Only .jpg & .png image allowed. Image size must be 225 x 225</span>
					</div>
					<div class="text-center mt-4 mb-2">
						<input type="submit" name="register_button" class="btn btn-danger" value="Register" />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php 
include 'footer.php';
?>
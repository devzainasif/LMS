<?php

//profile.php

include 'database_connection.php'; //Database Connectivity & Availabilty.
include 'function.php'; //Functions that we can use in our this file.

if(!is_user_login()) //If user is logged in.
{
	header('location:user_login.php');
}

$message = ''; //Use for multiple purpose to show errors & warning messages.
$success = ''; //Success Messages.

if(isset($_POST['save_button'])) //When User press on save Button.
{
	$formdata = array();//This will get data from Front-end & transfer to back-end.

	if(empty($_POST['user_email_address'])) //if user enter an empty email adress.
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
		$message .= '<li>User Address Detail is required</li>'; //warning message.
	}
	else
	{
		$formdata['user_contact_no'] = $_POST['user_contact_no']; //if Contact Number is all correct.
	}

	$formdata['user_profile'] = $_POST['hidden_user_profile'];

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
			if($image_size <= 2000000) //Check image size
			{
				if($width == 225 && $height == 225)  //If full fill the required size of image.
				{
					$new_img_name = time() . '-' . rand() . '.'  . $img_ext;

					if(move_uploaded_file($tmp_name, "upload/" . $new_img_name)) //Saved in upload Folder with new name.
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

	if($message == '') //If there is no warning message.
	{
		$data = array(
			':user_name'			=>	$formdata['user_name'],
			':user_address'			=>	$formdata['user_address'],
			':user_contact_no'		=>	$formdata['user_contact_no'],
			':user_profile'			=>	$formdata['user_profile'],
			':user_email_address'	=>	$formdata['user_email_address'],
			':user_password'		=>	$formdata['user_password'],
			':user_updated_on'		=>	get_date_time($connect),
			':user_unique_id'		=>	$_SESSION['user_id']
		);

		$query = "
		UPDATE lms_user 
            SET user_name = :user_name, 
            user_address = :user_address, 
            user_contact_no = :user_contact_no, 
            user_profile = :user_profile, 
            user_email_address = :user_email_address, 
            user_password = :user_password, 
            user_updated_on = :user_updated_on 
            WHERE user_unique_id = :user_unique_id
		";

		$statement = $connect->prepare($query);
		$statement->execute($data);
		$success = 'Data Change Successfully'; 
	}
}


$query = "
	SELECT * FROM lms_user 
	WHERE user_unique_id = '".$_SESSION['user_id']."'
";

$result = $connect->query($query);
include 'header.php';
?>


<div class="d-flex align-items-center justify-content-center mt-5 mb-5" style="min-height:700px;">
	<div class="col-md-6">
		<?php 
		if($message != '')
		{
			echo '<div class="alert alert-danger"><ul>'.$message.'</ul></div>';
		}

		if($success != '')
		{
			echo '<div class="alert alert-success">'.$success.'</div>';
		}
		?>
		<div class="card">
			<div class="card-header">Profile</div>
			<div class="card-body">
			<?php 
			foreach($result as $row)
			{
			?>
			<!-- User Profile Form -->
				<form method="POST" enctype="multipart/form-data">
					<div class="mb-3">
						<label class="form-label">Email address</label>
						<input type="text" name="user_email_address" id="user_email_address" class="form-control" value="<?php echo $row['user_email_address']; ?>" />
					</div>
					<div class="mb-3">
						<label class="form-label">Password</label>
						<input type="password" name="user_password" id="user_password" class="form-control" value="<?php echo $row['user_password']; ?>" />
					</div>
					<div class="mb-3">
						<label class="form-label">User Name</label>
						<input type="text" name="user_name" id="user_name" class="form-control" value="<?php echo $row['user_name']; ?>" />
					</div>
					<div class="mb-3">
						<label class="form-label">User Contact No.</label>
						<input type="text" name="user_contact_no" id="user_contact_no" class="form-control" value="<?php echo $row['user_contact_no']; ?>" />
					</div>
					<div class="mb-3">
						<label class="form-label">User Address</label>
						<textarea name="user_address" id="user_address" class="form-control"><?php echo $row['user_address']; ?></textarea>
					</div>
					<div class="mb-3">
						<label class="form-label">User Photo</label><br />
						<input type="file" name="user_profile" id="user_profile" />
						<br />
						<span class="text-muted">Only .jpg & .png image allowed. Image size must be 225 x 225</span>
						<br />
						<input type="hidden" name="hidden_user_profile" value="<?php echo $row['user_profile']; ?>" />
						<img src="upload/<?php echo $row['user_profile']; ?>" width="100" class="img-thumbnail" />
					</div>
					<div class="text-center mt-4 mb-2">
						<input type="submit" name="save_button" class="btn btn-primary" value="Save" />
					</div>
				</form>

			<?php
			}
			?>
			</div>
		</div>
	</div>
</div>

<?php 

include 'footer.php';

?>
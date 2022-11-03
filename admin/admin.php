<?php

//admin.php

include '../database_connection.php'; //Database Connectivity & Availabilty.
include '../function.php'; //Functions that we can use in our this file.


if (!is_admin_login()) {
    header('location:../admin_login.php');
}

$message = ''; //Use for multiple purpose to show errors & warning messages.
$error = ''; //error Messages.

//When press on a add new Admin button.
if (isset($_POST["add_admin"])) {
    $formdata = array();

    if (empty($_POST["admin_email"])) {
        $error .= '<li>Email is required</li>';
    } else {
        $formdata['admin_email'] = trim($_POST["admin_email"]);
    }

    if (empty($_POST["admin_password"])) {
        $error .= '<li>Password is required</li>';
    } else {
        $formdata['admin_password'] = trim($_POST["admin_password"]);
    }

    $data = array(
        ':admin_email'        =>    $formdata['admin_email']
    );

    $query = "
		SELECT * FROM lms_admin 
        WHERE admin_email = :admin_email
		";

    $statement = $connect->prepare($query);
    $statement->execute($data);
    if ($error == '') {
        if ($statement->rowCount() > 0) //If user's Email is already Exists.
        {
            $error = '<li>Email Already Register</li>';
        } else {
            $data = array(
                ':admin_email'        =>    $formdata['admin_email'],
                ':admin_password'        =>    $formdata['admin_password'],
                ':admin_status'            =>    'Enable',
                ':admin_added_on'        =>    get_date_time($connect)
            );

            $query = "
		INSERT INTO lms_admin 
        ( admin_email, admin_password, admin_status, admin_added_on) 
        VALUES ( :admin_email, :admin_password, :admin_status, :admin_added_on)
		";

            $statement = $connect->prepare($query);
            $statement->execute($data);
            header('location:admin.php?msg=add');
        }
    }
}

//When press on edit Admin button.
if (isset($_POST["edit_admin"])) {
    $formdata = array();

    if (empty($_POST["admin_email"])) {
        $error .= '<li>Email is required</li>';
    } else {
        $formdata['admin_email'] = trim($_POST["admin_email"]);
    }

    if (empty($_POST["admin_password"])) {
        $error .= '<li>Password is required</li>';
    } else {
        $formdata['admin_password'] = trim($_POST["admin_password"]);
    }

    if ($error == '') {
        $data = array(
            ':admin_email'        =>    $formdata['admin_email'],
            ':admin_password'        =>    $formdata['admin_password'],
            ':admin_updated_on'        =>    get_date_time($connect),
            ':admin_id'                =>    $_POST["admin_id"]
        );

        $query = "
		UPDATE lms_admin 
        SET admin_email = :admin_email, 
        admin_password = :admin_password, 
        admin_updated_on = :admin_updated_on 
        WHERE admin_id = :admin_id
		";

        $statement = $connect->prepare($query);
        $statement->execute($data);

        header('location:admin.php?msg=edit');
    }
}

//when press on delete button
if (isset($_GET["action"], $_GET["code"], $_GET["status"]) && $_GET["action"] == 'delete') {
    $admin_id = $_GET["code"];
    $status = $_GET["status"];

    $data = array(
        ':admin_status'        =>    $status,
        ':admin_updated_on'    =>    get_date_time($connect),
        ':admin_id'            =>    $admin_id
    );

    $query = "
	UPDATE lms_admin 
    SET admin_status = :admin_status, 
    admin_updated_on = :admin_updated_on 
    WHERE admin_id = :admin_id
	";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    header('location:admin.php?msg=' . strtolower($status) . '');
}


$query = "
	SELECT * FROM lms_admin
";

$statement = $connect->prepare($query);
$statement->execute();

include '../header.php';

?>

<div class="container-fluid py-4" style="min-height: 700px;">
    <h1>Admin Management</h1>
    <?php
    if (isset($_GET["action"])) {
        //When admin press add button.
        if ($_GET["action"] == 'add') {
    ?>

            <ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="admin.php">Admin Management</a></li>
                <li class="breadcrumb-item active">Add Admin</li>
            </ol>

            <?php
            if ($error != '') {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul class="list-unstyled">' . $error . '</ul> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
            ?>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-plus"></i> Add New Admin
                    <!-- Add new Admin form. -->
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Admin Email</label>
                                    <input type="email" name="admin_email" id="admin_email" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="admin_password" id="admin_password" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 mb-3 text-center">
                            <input type="submit" name="add_admin" class="btn btn-success" value="Add" />
                        </div>
                    </form>
                </div>
            </div>

            <?php
            //When admin press on edit button.
        } else if ($_GET["action"] == 'edit') {
            $admin_id = convert_data($_GET["code"], 'decrypt');

            if ($admin_id > 0) {
                $query = "
				SELECT * FROM lms_admin 
                WHERE admin_id = '$admin_id'
				";

                $Admin_result = $connect->query($query);

                foreach ($Admin_result as $Admin_row) {
            ?>
                    <ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="admin.php">Admin Management</a></li>
                        <li class="breadcrumb-item active">Edit Admin</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-user-plus"></i> Edit Admin Details
                            <!-- Edit Admin details form -->
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Admin Email</label>
                                            <input type="email" name="admin_email" id="admin_email" class="form-control" value="<?php echo $Admin_row['admin_email']; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <input type="password" name="admin_password" id="admin_password" class="form-control" value="<?php echo $Admin_row['admin_password']; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 mb-3 text-center">
                                    <input type="hidden" name="admin_id" value="<?php echo $Admin_row['admin_id']; ?>" />
                                    <input type="submit" name="edit_admin" class="btn btn-primary" value="Edit" />
                                </div>
                            </form>
                        </div>
                    </div>
        <?php
                }
            }
        }
    } else {
        ?>

        <ol class="breadcrumb mt-4 mb-4 bg-light p-2 border">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Admin Management</li>
        </ol>
        <?php

        //When new Admin added or edited any Admin message will show here.
        if (isset($_GET["msg"])) {
            if ($_GET["msg"] == 'add') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">New Admin Added<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
            if ($_GET['msg'] == 'edit') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Admin Data Edited <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
            if ($_GET["msg"] == 'disable') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Admin Status Change to Disable <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
            if ($_GET['msg'] == 'enable') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Admin Status Change to Enable <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
        }
        //Show all Admin record.
        ?>
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col col-md-6">
                        <i class="fas fa-table me-1"></i> Admin Management
                    </div>
                    <div class="col col-md-6" align="right">
                        <a href="admin.php?action=add" class="btn btn-success btn-sm">Add</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>Admin ID</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Status</th>
                            <th>Created On</th>
                            <th>Updated On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Admin ID</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Status</th>
                            <th>Created On</th>
                            <th>Updated On</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php

                        if ($statement->rowCount() > 0) {
                            foreach ($statement->fetchAll() as $row) {
                                $admin_status = '';
                                if ($row['admin_status'] == 'Enable') {
                                    $admin_status = '<div class="badge bg-success">Enable</div>';
                                } else {
                                    $admin_status = '<div class="badge bg-danger">Disable</div>';
                                }
                                echo '
        				<tr>
        					<td>' . $row["admin_id"] . '</td>
        					<td>' . $row["admin_email"] . '</td>
        					<td>' . $row["admin_password"] . '</td>
        					<td>' . $admin_status . '</td>
        					<td>' . $row["admin_added_on"] . '</td>
        					<td>' . $row["admin_updated_on"] . '</td>
        					<td>
        						<a href="admin.php?action=edit&code=' . convert_data($row["admin_id"]) . '" class="btn btn-sm btn-primary">Edit</a>
        						<button type="button" name="delete_button" class="btn btn-danger btn-sm" onclick="delete_data(`' . $row["admin_id"] . '`, `' . $row["admin_status"] . '`)">Enable/Disable</button>
        					</td>
        				</tr>
        				';
                            }
                        } else {
                            //If no Admin is found.
                            echo '
        			<tr>
        				<td colspan="10" class="text-center">No Data Found</td> 
        			</tr>
        			';
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script>
            function delete_data(code, status) {
                var new_status = 'Enable';
                if (status == 'Enable') {
                    new_status = 'Disable';
                }

                if (confirm("Are you sure you want to " + new_status + " this Admin?")) {
                    window.location.href = "admin.php?action=delete&code=" + code + "&status=" + new_status + "";
                }
            }
        </script>
    <?php
    }
    ?>
</div>


<?php

include '../footer.php';

?>
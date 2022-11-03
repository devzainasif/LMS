<?php

//header.php

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title>Library Management System</title>
    <link rel="canonical" href="">
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>asset/css/simple-datatables-style.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>asset/css/styles.css" rel="stylesheet" />
    <script src="<?php echo base_url(); ?>asset/js/font-awesome-5-all.min.js" crossorigin="anonymous"></script>
    <!-- Favicons -->
    <link rel="apple-touch-icon" href="<?php echo base_url(); ?>upload/favicon.ico" sizes="180x180">
    <link rel="icon" href="<?php echo base_url(); ?>upload/favicon.ico" sizes="32x32" type="image/png">
    <link rel="icon" href="<?php echo base_url(); ?>upload/favicon.ico" sizes="16x16" type="image/png">
    <link rel="manifest" href="<?php echo base_url(); ?>upload/favicon.ico">
    <link rel="mask-icon" href="<?php echo base_url(); ?>upload/favicon.ico" color="#7952b3">
    <link rel="icon" href="<?php echo base_url(); ?>upload/favicon.ico">
    <meta name="theme-color" content="#7952b3">
    <!-- My customize CSS -->
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        a {
            text-decoration: none;
            color: black;
        }

        a:hover {
            text-decoration: none;
            color: white;
        }

        .design {
            text-decoration: none;
            border: 1px solid black;
            padding: 5px;
            border-radius: 5px;
            background-color: black;
            color: white;
        }

        .adminLoginDesign {
            border-radius: 25px;
            box-shadow: 5px 5px #e0e0e0;
        }

        .userLoginDesign {
            border-radius: 25px;
            box-shadow: 5px 5px #e0e0e0;
        }

        .loginBtn {
            border-radius: 10px;
        }
    </style>
</head>

<?php

if (is_admin_login()) { //if Admin is logged on.

?>

    <body class="sb-nav-fixed">

        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php">Library System</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">

            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <!-- Dropdown options for Admin. -->
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="setting.php">Setting</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <!-- Admin navbar options. -->
                            <a class="nav-link" href="admin.php">Admins</a>
                            <a class="nav-link" href="book.php">Books</a>
                            <a class="nav-link" href="user.php">User</a>
                            <a class="nav-link" href="issue_book.php">Issued Book</a>
                            <a class="nav-link" href="logout.php">Logout</a>

                        </div>
                    </div>
                    <div class="sb-sidenav-footer">

                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>


                <?php
            } else { //if User is logged in.

                ?>

                    <body>

                        <main>

                            <div class="container py-4">

                                <header class="pb-3 mb-4 border-bottom">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="index.php" class="d-flex align-items-center text-dark text-decoration-none">
                                                <span class="fs-4 fw-bold text-dark">Library Management System</span>
                                            </a>
                                        </div>
                                        <div class="col-md-6">
                                            <?php

                                            //User Header Links.
                                            if (is_user_login()) {
                                            ?>
                                                <ul class="list-inline mt-4 float-end">
                                                    <li class="list-inline-item">User ID: <?php echo $_SESSION['user_id']; ?></li>
                                                    <li class="list-inline-item"><a href="issue_book_details.php" class="design">Issued Book</a></li>
                                                    <li class="list-inline-item"><a href="search_book.php" class="design">Search Book</a></li>
                                                    <li class="list-inline-item"><a href="profile.php" class="design">Profile</a></li>
                                                    <li class="list-inline-item"><a href="logout.php" class="design">Logout</a></li>
                                                </ul>
                                            <?php
                                            }

                                            ?>
                                        </div>
                                    </div>

                                </header>
                            <?php
                        }
                            ?>
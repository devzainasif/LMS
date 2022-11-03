<?php

//index.php

include '../database_connection.php'; //Database Connectivity & Availabilty.
include '../function.php'; //Functions that we can use in our this file.

if (!is_admin_login()) {
	header('location:../admin_login.php');
}

include '../header.php';

?>
<!-- Functions are call to display total book issued returned and not returned book. -->
<div class="container-fluid py-4">
	<h1 class="mb-5">Dashboard</h1>
	<div class="row">
		<div class="col-xl-3 col-md-6">
			<div class="card bg-secondary text-white mb-4">
				<a href="issue_book.php" class="text-light">
					<div class="card-body">
						<h1 class="text-center"><?php echo Count_total_issue_book_number($connect); ?></h1>
						<h5 class="text-center">Total Book Issued</h5>
					</div>
				</a>
			</div>
		</div>
		<div class="col-xl-3 col-md-6">
			<div class="card bg-dark text-white mb-4">
				<a href="issue_book.php" class="text-light">
					<div class="card-body">
						<h1 class="text-center"><?php echo Count_total_returned_book_number($connect); ?></h1>
						<h5 class="text-center">Total Book Returned</h5>
					</div>
				</a>
			</div>
		</div>
		<div class="col-xl-3 col-md-6">
			<div class="card bg-secondary text-white mb-4">
				<a href="issue_book.php" class="text-light">
					<div class="card-body">
						<h1 class="text-center"><?php echo Count_total_not_returned_book_number($connect); ?></h1>
						<h5 class="text-center">Total Book Not Return</h5>
					</div>
				</a>

			</div>
		</div>
		<div class="col-xl-3 col-md-6">
			<div class="card bg-dark text-white mb-4">
				<a href="issue_book.php" class="text-light">
					<div class="card-body">
						<h1 class="text-center"><?php echo get_currency_symbol($connect) . Count_total_fines_received($connect); ?></h1>
						<h5 class="text-center">Total Fines Received</h5>
					</div>
				</a>
			</div>
		</div>
		<div class="col-xl-3 col-md-6">
			<div class="card bg-dark text-white mb-4">
				<a href="book.php" class="text-light">
					<div class="card-body">
						<h1 class="text-center"><?php echo Count_total_book_number($connect); ?></h1>
						<h5 class="text-center">Total Book</h5>
					</div>
				</a>

			</div>
		</div>
	</div>
</div>

<?php

include '../footer.php';

?>
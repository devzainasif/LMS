<?php

//search_book.php

include 'database_connection.php'; //Database Connectivity & Availabilty.
include 'function.php'; //Functions that we can use in our this file.

if(!is_user_login()) //If user is logged in.
{
	header('location:user_login.php'); 
}

//Query to get all books
$query = "
	SELECT * FROM lms_book 
    WHERE book_status = 'Enable' 
    ORDER BY book_id DESC
";

$statement = $connect->prepare($query);
$statement->execute();

include 'header.php';

?>

<div class="container-fluid py-4" style="min-height: 700px;">

	<h1>Search Book</h1>

	<div class="card mb-4">
		<div class="card-header">
			<div class="row">
				<div class="col col-md-6">
					<i class="fas fa-table me-1"></i> Book List
				</div>
				<div class="col col-md-6" align="right">

				</div>
			</div>
		</div>
		<div class="card-body">
			<table id="datatablesSimple">
				<thead>
					<tr>
						<th>Book Name</th>
						<th>ISBN No.</th>
						<th>No. of Available Copy</th>
						<th>Status</th>
						<th>Added On</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Book Name</th>
						<th>ISBN No.</th>
						<th>No. of Available Copy</th>
						<th>Status</th>
						<th>Added On</th>
					</tr>
				</tfoot>
				<tbody>
				<?php 

				if($statement->rowCount() > 0)
				{
					foreach($statement->fetchAll() as $row)
					{
						$book_status = '';
						//Check the availabilty of books.
						if($row['book_no_of_copy'] > 0) 
						{
							$book_status = '<div class="badge bg-success">Available</div>';
						}
						else
						{
							$book_status = '<div class="badge bg-danger">Not Available</div>';
						}
						echo '
							<tr>
								<td>'.$row["book_name"].'</td>
								<td>'.$row["book_isbn_number"].'</td>
								<td>'.$row["book_no_of_copy"].'</td>
								<td>'.$book_status.'</td>
								<td>'.$row["book_added_on"].'</td>
							</tr>
						';
					}
				}
				else
				{
					// When no book found.
					echo '
					<tr>
						<td colspan="8" class="text-center">No Data Found</td> 
					</tr>
					';
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php 

include 'footer.php';

?>
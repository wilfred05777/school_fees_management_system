<?php

//action.php

include('database_connection.php');

if(isset($_POST['action']))
{
	if($_POST["action"] == 'fetch_user')
	{
		$query = "
		SELECT * FROM sfms_admin 
		WHERE admin_type = 'User' AND 
		";

		if(isset($_POST["search"]["value"]))
		{
			$query .= '(admin_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR admin_email LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR admin_status LIKE "%'.$_POST["search"]["value"].'%") ';
		}

		if(isset($_POST["order"]))
		{
			$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$query .= 'ORDER BY admin_id DESC ';
		}

		$query1 = '';

		if($_POST['length'] != -1)
		{
			$query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$statement = $connect->prepare($query);

		$statement->execute();

		$filtered_rows = $statement->rowCount();

		$result = $connect->query($query . $query1);

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();

			$status = '';

			$delete_button = '';

			if($row['admin_status'] == 'Enable')
			{
				$status = '<div class="badge bg-success">Enable</div>';

				$delete_button = '<button type="button" class="btn btn-danger btn-sm" onclick="delete_data(`'.$row["admin_id"].'`, `'.$row["admin_status"].'`)"><i class="fa fa-toggle-off" aria-hidden="true"></i> Disable</button>';
			}
			else
			{
				$status = '<div class="badge bg-danger">Disable</div>';

				$delete_button = '<button type="button" class="btn btn-success btn-sm" onclick="delete_data(`'.$row["admin_id"].'`, `'.$row["admin_status"].'`)"><i class="fa fa-toggle-on" aria-hidden="true"></i> Enable</button>';
			}

			$sub_array[] = $row['admin_name'];
			$sub_array[] = $row['admin_email'];
			$sub_array[] = $row['admin_password'];
			$sub_array[] = $row['admin_type'];
			$sub_array[] = $status;
			$sub_array[] = '<a href="user.php?action=edit&id='.$row["admin_id"].'" class="btn btn-sm btn-primary">Edit</a>&nbsp;' . $delete_button;
			$data[] = $sub_array;
		}

		$output = array(
			"draw"		=>	intval($_POST["draw"]),
			"recordsTotal"	=>	get_total_user_all_records($connect),
			"recordsFiltered"	=>	$filtered_rows,
			"data"		=>	$data
		);

		echo json_encode($output);
	}

	if($_POST['action'] == 'fetch_academic_year')
	{
		$query = "
		SELECT * FROM sfms_acedemic_year 
		";

		if(isset($_POST['search']['value']))
		{
			$query .= 'WHERE (acedemic_start_year LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR acedemic_start_month LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR acedemic_end_year LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR acedemic_end_month LIKE "%'.$_POST["search"]["value"].'%") ';			
		}

		if(isset($_POST['order']))
		{
			$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$query .= 'ORDER BY acedemic_year_id DESC ';
		}

		$query1 = '';

		if($_POST['length'] != -1)
		{
			$query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$statement = $connect->prepare($query);

		$statement->execute();

		$filtered_rows = $statement->rowCount();

		$result = $connect->query($query . $query1, PDO::FETCH_ASSOC);

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();

			$status = '';

			$delete_button = '';

			if($row['acedemic_year_status'] == 'Enable')
			{
				$status = '<div class="badge bg-success">Enable</div>';

				$delete_button = '<button type="button" class="btn btn-danger btn-sm" onclick="delete_data(`'.$row["acedemic_year_id"].'`, `'.$row["acedemic_year_status"].'`)"><i class="fa fa-toggle-off" aria-hidden="true"></i> Disable</button>';
			}
			else
			{
				$status = '<div class="badge bg-danger">Disable</div>';

				$delete_button = '<button type="button" class="btn btn-success btn-sm" onclick="delete_data(`'.$row["acedemic_year_id"].'`, `'.$row["acedemic_year_status"].'`)"><i class="fa fa-toggle-on" aria-hidden="true"></i> Enable</button>';
			}

			$sub_array[] = $row['acedemic_start_year'];
			$sub_array[] = $row['acedemic_start_month'];
			$sub_array[] = $row['acedemic_end_year'];
			$sub_array[] = $row['acedemic_end_month'];
			$sub_array[] = $status;
			$sub_array[] = '<a href="academic_year.php?action=edit&id='.$row["acedemic_year_id"].'" class="btn btn-sm btn-primary">Edit</a>&nbsp;' . $delete_button;

			$data[] = $sub_array;
		}

		$output = array(
			'draw'		=>	intval($_POST['draw']),
			'recordsTotal'	=>	get_total_academic_year_records($connect),
			'recordsFiltered'	=>	$filtered_rows,
			'data'	=>	$data
		);

		echo json_encode($output);
	}
}

function get_total_user_all_records($connect)
{
	$query = "
	SELECT * FROM sfms_admin WHERE admin_type = 'User'
	";

	$statement = $connect->prepare($query);

	$statement->execute();

	return $statement->rowCount();
}

function get_total_academic_year_records($connect)
{
	$query = 'SELECT * FROM sfms_acedemic_year';

	$statement = $connect->prepare($query);

	$statement->execute();

	return $statement->rowCount();
}

?>
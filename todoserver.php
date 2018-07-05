<?php
	session_start();

	// connect to database
	$db = mysqli_connect('localhost:3360', 'root', '', 'timely');
//add data todo--daily
		if (isset($_POST['adddata'])){
			$tname = mysqli_real_escape_string($db, $_POST['tname']);
			$tcategory = mysqli_real_escape_string($db, $_POST['tcategory']);
			$tdesc = mysqli_real_escape_string($db, $_POST['tdesc']);

			// baki
			$endtime = date('Y-m-d H:i:s', strtotime($_POST['endtime']));
			$starttime = date('Y-m-d H:i:s', strtotime($_POST['starttime']));

			$iscompleted = mysqli_real_escape_string($db, $_POST['iscompleted']);
			$query = "INSERT INTO todogoal (tname,tcategory,tdesc,starttime,endtime,iscompleted)
						VALUES('$tname','$tcategory','$tdesc','$starttime','$endtime',0)";
			$result = mysqli_query($db, $query);
			header('location: todo.php');

		}
	//delete data from todo--daily
	if (isset($_POST['deletedata'])){
		$tid = mysqli_real_escape_string($db, $_POST['tid']);
		$RESULT1= mysqli_query($db, "SELECT tcategory FROM todogoal WHERE tid=".$tid);
		header('location: todo.php');
	}
	//edit data in todo--daily
	if (isset($_POST['editdata'])){
		$tid = mysqli_real_escape_string($db, $_POST['tid']);
		$tname = mysqli_real_escape_string($db, $_POST['tname']);
		$tcategory = mysqli_real_escape_string($db, $_POST['tcategory']);
		$tdesc = mysqli_real_escape_string($db, $_POST['tdesc']);
		//baki//kadach done--
		$endtime = date('H:i:s', strtotime($_POST['endtime']));
		$starttime = date('H:i:s', strtotime($_POST['$starttime']));

		$query = "UPDATE  todogoal SET tname=".$tname.",tdesc=".$tdesc.",starttime=".$starttime." ,endtime=".$endtime." WHERE tid=".$tid;

		$result = mysqli_query($db, $query);
		if(!$result)
			echo "couldn't update data".mysqli_error($db);
		else
			header('location: todo.php');
	}
	//done in todo--daily
	if (isset($_POST['done'])){
		$tid = mysqli_real_escape_string($db, $_POST['tid']);
		$query = "UPDATE todogoal SET iscompleted=1 WHERE tid=".$tid;

		$result = mysqli_query($db, $query);
		if(!$result)
			echo "couldn't update data".mysqli_error($db);
		else
			header('location: todo.php');
	}
	//done in todo--daily
	if (isset($_POST['undone'])){
		$tid = mysqli_real_escape_string($db, $_POST['tid']);
		$query = "UPDATE todogoal SET iscompleted=0 WHERE tid=".$tid;

		$result = mysqli_query($db, $query);
		if(!$result)
			echo "couldn't update data".mysqli_error($db);
		else
			header('location: todo.php');
	}

?>

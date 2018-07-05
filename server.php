<?php
	session_start();

	// variable declaration
	$username = "";
	$email    = "";
	$errors = array();
	$_SESSION['success'] = "";

	// connect to database
	$db = mysqli_connect('localhost:3360', 'root', '', 'timely');

	// REGISTER USER
	if (isset($_POST['reg_user'])) {


		// receive all input values from the form
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
		$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

		// form validation: ensure that the form is correctly filled
		if (empty($username)) { array_push($errors, "Username is required"); }
		if (empty($email)) { array_push($errors, "Email is required"); }
		if (empty($password_1)) { array_push($errors, "Password is required"); }

		if ($password_1 != $password_2) {
			array_push($errors, "The two passwords do not match");
		}

		// register user if there are no errors in the form
		if (count($errors) == 0) {
			$password = md5($password_1);//encrypt the password before saving in the database
			$query = "INSERT INTO registration (emailid,name,password)
					  VALUES('$email','$username','$password')";
			mysqli_query($db, $query);

			$_SESSION['username'] = $username;
			$_SESSION['success'] = "You are now logged in";
			header('location: index.php');
		}

	}
	// LOGIN USER
	if (isset($_POST['login_user'])) {

				$username = mysqli_real_escape_string($db, $_POST['username']);
		$password = mysqli_real_escape_string($db, $_POST['password']);

		if (empty($username)) {
			array_push($errors, "Username is required");
		}
		if (empty($password)) {
			array_push($errors, "Password is required");
		}

		if (count($errors) == 0) {
			$password = md5($password);
			$query = "SELECT * FROM registration WHERE name='$username' AND password='$password'";
			$results = mysqli_query($db, $query);

			if (mysqli_num_rows($results) == 1) {
				$_SESSION['username'] = $username;
				$_SESSION['success'] = "You are now logged in";
				header('location: index.php');
			}else {
				array_push($errors, "Wrong username/password combination");
			}
		}
	}
	//add data to Planner--weekly
		if (isset($_POST['adddata'])){
			$gname = mysqli_real_escape_string($db, $_POST['gname']);
			$gdesc = mysqli_real_escape_string($db, $_POST['gdesc']);
			$enddate = date('Y-m-d H:i:s', strtotime($_POST['enddate']));
			$startdate = date('Y-m-d H:i:s', strtotime($_POST['startdate']));
			$iscompleted = mysqli_real_escape_string($db, $_POST['iscompleted']);
			mysqli_query($db,"INSERT INTO plannercategory (name) VALUES('$gname')");
			$query = "INSERT INTO plannergoal (gname,gdesc,startdate,enddate,iscompleted)
						VALUES('$gname','$gdesc','$startdate','$enddate',0)";
			$result = mysqli_query($db, $query);
			header('location: planner.php');

		}
	//delete data from Planner--weekly
	if (isset($_POST['deletedata'])){
		$gid = mysqli_real_escape_string($db, $_POST['gid']);

		//deletion from plannercategory and hence leads to ondeletecascade
		$RESULT1= mysqli_query($db, "SELECT gname FROM plannergoal WHERE gid=$gid");
		mysqli_query($db, "DELETE FROM plannercategory WHERE name=$RESULT1");

		$query = "DELETE FROM plannergoal WHERE gid=".$gid;
		$result = mysqli_query($db, $query);
		header('location: planner.php');
	}
	//edit data in Planner--weekly
	if (isset($_POST['editdata'])){
		$gid = mysqli_real_escape_string($db, $_POST['gid']);
		$gname = mysqli_real_escape_string($db, $_POST['gname']);
		$gdesc = mysqli_real_escape_string($db, $_POST['gdesc']);
		$enddate = date('Y-m-d H:i:s', strtotime($_POST['enddate']));
		$startdate = date('Y-m-d H:i:s', strtotime($_POST['startdate']));

		$query = "UPDATE  plannergoal SET gname=".$gname.",gdesc=".$gdesc.",startdate=".$startdate." ,enddate=".$enddate." WHERE gid=".$gid;

		$result = mysqli_query($db, $query);
		if(!$result)
			echo "couldn't update data".mysqli_error($db);
		else
			header('location: planner.php');
	}
	//done in Planner--weekly
	if (isset($_POST['done'])){
		$gid = mysqli_real_escape_string($db, $_POST['gid']);
		$query = "UPDATE plannergoal SET iscompleted=1 WHERE gid=".$gid;

		$result = mysqli_query($db, $query);
		if(!$result)
			echo "couldn't update data".mysqli_error($db);
		else
			header('location: planner.php');
	}
	//done in Planner--weekly
	if (isset($_POST['undone'])){
		$gid = mysqli_real_escape_string($db, $_POST['gid']);
		$query = "UPDATE plannergoal SET iscompleted=0 WHERE gid=".$gid;

		$result = mysqli_query($db, $query);
		if(!$result)
			echo "couldn't update data".mysqli_error($db);
		else
			header('location: planner.php');
	}

?>

<?php
	session_start();

	if (!isset($_SESSION['username'])) {
		$_SESSION['msg'] = "You must log in first";
		header('location: login.php');
	}

	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['username']);
		header("location: login.php");
	}
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<title>timely</title>
<meta charset="iso-8859-1">
<link rel="stylesheet" href="styles/layout.css" type="text/css">
<style>
.button {
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
		float:left;
}

table {
    border-collapse: collapse;
		width: 50%;
		float:none;
}

th, td {
    text-align: left;
    padding: 8px;
}

tr:nth-child(even){background-color: #f2f2f2}

th {
    background-color: #4CAF50;
    color: white;
}
#sticky1{
	position: sticky;
	  top: 0;
}
</style>
</head>
<body>
<div class="wrapper row1" id="sticky1">
  <header id="header" class="clear">
    <div id="hgroup">
      <h1><a href="index.php">timely</a></h1>
      <h2>Be effiecient with timely</h2>
    </div>
    <nav>
      <ul>
        <li><a href="todo.php">to-do</a></li>
        <li><a href="planner.php">Planner</a></li>
        <li><a href="#">Retrospect</a></li>
        <li><a href="#">OutLine</a></li>
        <li class="last"><a href="#">Text Link</a></li>
      </ul>
    </nav>
  </header>
</div>
<!-- content -->
<div class="content">
<!------------------------------------------------------------------------------->
  <!-- notification message -->
  <?php if (isset($_SESSION['success'])) : ?>
    <div class="error success" >
      <h3>
        <?php
          echo $_SESSION['success'];
          unset($_SESSION['success']);
        ?>
      </h3>
    </div>
  <?php endif ?>
<!------------------------------------------------------------------------------->
  <!-- logged in user information -->
  <?php  if (isset($_SESSION['username'])) : ?>
    <p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
    <p> <a id="logout_symbol" href="index.php?logout='1'" style="color: red;">logout</a> </p>
  <?php endif ?>
</div>
<br><br><br>
<p>
	Planner--weekly
</p>
<!------------------------------------------------------------------------------->
<!--  display alredy set goals-->
<?php

	// connect to database
	$conn = mysqli_connect('localhost:3360', 'root', '', 'timely');
if (!$conn) {
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$sql = 'SELECT * FROM plannergoal WHERE iscompleted=0';
$query = mysqli_query($conn, $sql);

if (!$query) {
	die ('SQL Error: ' . mysqli_error($conn));
}
?>
	<table class="data-table" border="1">
		<caption class="title">Goals:</caption>
		<thead>
			<tr>
				<th>id</th>
				<th>name</th>
				<th>description</th>
				<th>startdate</th>
				<th>enddate</th>
				<th>done</th>
			</tr>
		</thead>
		<tbody>
		<?php
		while ($row = mysqli_fetch_array($query))
		{
			echo '<tr>

					<td>'.$row['gid'].'</td>
					<td>'.$row['gname'].'</td>
					<td>'.$row['gdesc'].'</td>
					<td>'. date('F d, Y', strtotime($row['startdate'])) . '</td>
					<td>'. date('F d, Y', strtotime($row['enddate'])) . '</td>
					<td>'.$row['iscompleted'].'</td>
				</tr>';

		}

// completed globals
$sql = 'SELECT * FROM plannergoal WHERE iscompleted=1';
$query = mysqli_query($conn, $sql);

if (!$query) {
	die ('SQL Error: ' . mysqli_error($conn));
}
?>
	<table class="data-table" border="1">
		<caption class="title">completed Goals:</caption>
		<thead>
			<tr>
				<th>id</th>
				<th>name</th>
				<th>description</th>
				<th>startdate</th>
				<th>enddate</th>
				<th>done</th>
			</tr>
		</thead>
		<tbody>
		<?php
		while ($row = mysqli_fetch_array($query))
		{
			echo '<tr>

					<td>'.$row['gid'].'</td>
					<td>'.$row['gname'].'</td>
					<td>'.$row['gdesc'].'</td>
					<td>'. date('F d, Y', strtotime($row['startdate'])) . '</td>
					<td>'. date('F d, Y', strtotime($row['enddate'])) . '</td>
					<td>'.$row['iscompleted'].'</td>
				</tr>';

		}
		?>
		</tbody>
	</table>
<!------------------------------------------------------------------------------->
<!--  planner form for data entry-->
<?php  if (isset($_POST['dataentry'])) : ?>

	<form action="server.php" method="post">
		<fieldset>
  		<legend>New Entry:</legend>
<pre>
		name:     		<input type="text" name="gname"><br>
		description:		<input type="text" name="gdesc"><br>
		start @:		<input type="date" name="startdate" value="<?php echo date('Y-m-d'); ?>" /><br>
		end @:			<input type="date" name="enddate" value="<?php echo date('Y-m-d'); ?>" /><br>
		<input type="hidden" name="iscompleted" value="false">
	  		<input type="submit" value="done" name="adddata">
</pre>
	</form>
<?php endif ?>
<!------------------------------------------------------------------------------->
<!--  planner form for data  deletion-->
<?php  if (isset($_POST['datadelete'])) : ?>
	<form action="server.php" method="post">
<pre>
		Enter id:<input type="number" name="gid"><br>
				<input type="submit" value="delete" name="deletedata">
</pre>
	</form>
<?php endif ?>
<!------------------------------------------------------------------------------->
<!--  planner form for data update/edit-->
<?php  if (isset($_POST['dataedit'])) : ?>
	<form action="server.php" method="post">
<pre>
		id:			<input type="number" name="gid"><br>
		name:			<input type="text" name="gname"><br>
		description:		<input type="text" name="gdesc"><br>
		start @:		<input type="date" name="startdate" value="<?php echo date('Y-m-d'); ?>" /><br>
		end @:			<input type="date" name="enddate" value="<?php echo date('Y-m-d'); ?>" /><br>
		<input type="hidden" name="iscompleted" value="false">
	  		<input type="submit" value="edit" name="editdata">
</pre>
	</form>
<?php endif ?>
<!------------------------------------------------------------------------------->
<!--  planner form for done-->
<?php  if (isset($_POST['done'])) : ?>
	<form action="server.php" method="post">
<pre>
		Enter id:<input type="number" name="gid"><br>
				<input type="submit" value="done" name="done">
</pre>
	</form>
<?php endif ?>
<!------------------------------------------------------------------------------->
<!--  planner form for undone-->
<?php  if (isset($_POST['undone'])) : ?>
	<form action="server.php" method="post">
<pre>
		Enter id:<input type="number" name="gid"><br>
				<input type="submit" value="undone" name="undone">
</pre>
	</form>
<?php endif ?>
<!------------------------------------------------------------------------------->
<!--  Buttons for add delete edit-->
<br><br><br>
<div >
	<form action="planner.php" method="post">
		<input id="bt1" class="button"  type="submit" name="dataentry" value="new goal" >
	</form>

	<form action="planner.php" method="post">
		<input class="button" type="submit" name="datadelete" value="delete">
	</form>
	<form action="planner.php" method="post">
		<input class="button" type="submit" name="dataedit" value="edit">
	</form>
	<form action="planner.php" method="post">
		<input class="button" type="submit" name="done" value="done">
	</form>
	<form action="planner.php" method="post">
		<input class="button" type="submit" name="undone" value="undone">
	</form>

</div>
</body>
</html>

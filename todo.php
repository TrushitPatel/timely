<!--  session -->
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
.button:hover{
	background-color: #4CAF99;

	margin: 4px 2px;
	padding:18px 32px;

}
table {
    border-collapse: collapse;
		width: 50%;

		float:none;
}
.category-table{
	border-collapse: collapse;
	width: 20%;
	float:right;
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
.content{
	float:right;
	margin-right:0;
}
</style>

</head>

<body>
  <!--header -->
<div class="wrapper row1" id="sticky1">
  <header id="header" class="clear">
    <div id="hgroup">
      <h1><a href="index.php">timely</a></h1>
      <h2>Be effiecient with timely</h2>
    </div>
    <nav>
      <ul>
        <li><a href="todo.php">to-do</a></li>
        <li><a href="planner.php">planner</a></li>
        <li><a href="#">Retrospect</a></li>
        <li><a href="#">OutLine</a></li>
        <li class="last"><a href="#">Text Link</a></li>
      </ul>
    </nav>

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
  </header>
	<h2>
		<button style="float:right;background-color:red;" id="checking" class="button" onmouseover="document.getElementById('checking').innerHTML='<?php echo Date("d-m-Y H:i:s")?>'">
			Time
		</button>
	</h2>
</div>



	<!-- <div style="	margin: 50px auto 0px;left:30px;"> -->

<p>
	todo--daily
</p>
<!------------------------------------------------------------------------------->
<!--  display alredy set goals-->

<?php
	// connect to database
	$conn = mysqli_connect('localhost:3360', 'root', '', 'timely');
if (!$conn) {
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$sql = 'SELECT * FROM todogoal WHERE iscompleted=0';
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
        <th>category</th>
				<th>name</th>
				<th>description</th>
				<th>starttime</th>
				<th>endtime</th>
				<th>edit</th>
			</tr>
		</thead>
		<tbody>
		<?php
		while ($row = mysqli_fetch_array($query))
		{
			echo '<tr>
					<td>'.$row['tid'].'</td>
          <td>'.$row['tcategory'].'</td>
					<td>'.$row['tname'].'</td>
					<td>'.$row['tdesc'].'</td>
					<td>'. date('F d, Y H:i', strtotime($row['starttime'])) . '</td>
					<td>'. date('F d, Y H:', strtotime($row['endtime'])) . '</td>
					<td>
							<form action="todoserver.php" method="post"  >
								<input type="hidden" name="tid" value="'.$row['tid'].'"/>
								<input type="submit" value="done" name="done">
									<i class="fa fa-check" style="color:red"></i>
								</input>
								<input type="submit" value="del" name="deletedata">
									<i class="fa fa-window-close" aria-hidden="true"></i>
								</input>
							</form>
					</td>
					</tr>';

		}

// completed globals
$sql = 'SELECT * FROM todogoal WHERE iscompleted=1';
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
				<th>category</th>
				<th>name</th>
				<th>desc</th>
        <th>starttime</th>
        <th>endtime</th>
				<th>edit</th>
			</tr>
		</thead>
		<tbody>
		<?php
		while ($row = mysqli_fetch_array($query))
		{
			echo '<br>
      <tr>

					<td>'.$row['tid'].'</td>
          <td>'.$row['tcategory'].'</td>
					<td>'.$row['tname'].'</td>
					<td>'.$row['tdesc'].'</td>
					<td>'. date('F d, Y', strtotime($row['starttime'])) . '</td>
					<td>'. date('F d, Y', strtotime($row['endtime'])) . '</td>
					<td>
							<form action="todoserver.php" method="post"  >
								<input type="hidden" name="tid" value="'.$row['tid'].'"/>
								<input type="submit" value="undone" name="undone">
									<i class="fa fa-times" aria-hidden="true" style="color:red;"></i>
								</input>
								<input type="submit" value="del" name="deletedata">
									<i class="fa fa-window-close" aria-hidden="true"></i>
								</input>
							</form>
					</td>
				</tr>';
		}
		?>
		</tbody>
	</table>

<?php

  $sql = 'SELECT * FROM plannercategory';
  $query = mysqli_query($conn, $sql);

  if (!$query) {
  	die ('SQL Error: ' . mysqli_error($conn));
  }
  ?>

  	<table class="category-table" border="1">
  		<caption class="title">Available_category</caption>
  		<thead>
  			<tr>

  				<th>category</th>

  			</tr>
  		</thead>
  		<tbody>
  		<?php
  		while ($row = mysqli_fetch_array($query))
  		{
  			echo '<br><tr>
        <td>'.$row['name'].'</td>';

  		}
  		?>
  		</tbody>
  	</table>

<!------------------------------------------------------------------------------->
<!--  todo form for data entry-->
<?php  if (isset($_POST['dataentry'])) : ?>

	<form action="todoserver.php" method="post">
		<fieldset>
  		<legend>New Entry:</legend>
<pre>
		name:     		<input type="text" name="tname"><br>
		category:               <input type="text" name="tcategory"><br>
		description:		<input type="text" name="tdesc"><br>
		start @:		<input type="datetime-local" name="starttime"  /><br>
		end @:			<input type="datetime-local" name="endtime" value="<?php echo date('Y-m-d H:i'); ?>" /><br>
		<input type="hidden" name="iscompleted" value="false">
	  		<input type="submit" value="done" name="adddata">
</pre>
	</form>
<?php endif ?>
<!------------------------------------------------------------------------------->
<!--  todo form for data  deletion-->
<?php  if (isset($_POST['datadelete'])) : ?>
	<form action="todoserver.php" method="post">
<pre>
		Enter id:<input type="number" name="tid"><br>
				<input type="submit" value="delete" name="deletedata">
</pre>
	</form>
<?php endif ?>
<!------------------------------------------------------------------------------->
<!--  todo form for data update/edit-->
<?php  if (isset($_POST['dataedit'])) : ?>
	<form action="todoserver.php" method="post">
<pre>
		id:			<input type="number" name="tid"><br>
		name:			<input type="text" name="tname"><br>
		description:		<input type="text" name="tdesc"><br>
		start @:		<input type="date" name="starttime" value="<?php echo date('Y-m-d'); ?>" /><br>
		end @:			<input type="date" name="endtime" value="<?php echo date('Y-m-d'); ?>" /><br>
		<input type="hidden" name="iscompleted" value="false">
	  		<input type="submit" value="edit" name="editdata">
</pre>
	</form>
<?php endif ?>
<!------------------------------------------------------------------------------->
<!--  todo form for done-->
<?php  if (isset($_POST['done'])) : ?>
	<form action="todoserver.php" method="post">
<pre>
		Enter id:<input type="number" name="tid"><br>
				<input type="submit" value="done" name="done">
</pre>
	</form>
<?php endif ?>
<!------------------------------------------------------------------------------->
<!--  todo form for undone-->
<?php  if (isset($_POST['undone'])) : ?>
	<form action="todoserver.php" method="post">
<pre>
		Enter id:<input type="number" name="tid"><br>
				<input type="submit" value="undone" name="undone">
</pre>
	</form>
<?php endif ?>
<!------------------------------------------------------------------------------->
<!--  Buttons for add delete edit-->
<br><br><br>
<div class="task" >
	<form action="todo.php" method="post">
		<input class="button"  type="submit" name="dataentry" value="new goal" >
		<input class="button" type="submit" name="datadelete" value="delete">
		<input class="button" type="submit" name="dataedit" value="edit">
		<input class="button" type="submit" name="done" value="done">
		<input class="button" type="submit" name="undone" value="undone">
	</form>

	<br><br><br><br><br><br><br>
</div>
<!-- </div> -->
</body>
</html>

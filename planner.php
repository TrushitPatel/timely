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
			#toCircle{
				border-radius: 450%;
				padding: 20px;
			}
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
	<body onload="startTime()">
		<!------------------------------------------------------------------------------->
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
				<!------------------------------------------------------------------------------->
				<!-- content -->
				<div class="content">
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
				<button style="float:right;background-color:red;" class="button" id="toCircle">
					<i class="fa fa-clock-o" aria-hidden="true"></i>
					<p id="txt"></p>
				</button>
			</h2>
		</div>
		<br><br><br>
		<!------------------------------------------------------------------------------->
		<!-- body -->
		<div syle="background-color: white;" left=15px; class="body" >
			<p>Planner--weekly</p>
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
			<table class="data-table" border="1" style="position: static;  margin-left:20%; width: 70%">
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
									<td>
										<form action="server.php" method="post">
											<input type="hidden" name="gid" value="'.$row['gid'].'"/>
											<button type="submit" name="done" >
												<i class="fa fa-check" style="color:red"></i>
											</button>
											<button type="submit"  name="deletedata">
												<i class="fa fa-window-close" aria-hidden="true"></i>
											</button>
										</form>
								</td>
								</tr>';

						}
					?>
				</tbody>
			</table>
			<!-------------------------------------------------------------------------------->
			<!-- completed globals -->
			<?php
				$sql = 'SELECT * FROM plannergoal WHERE iscompleted=1';
				$query = mysqli_query($conn, $sql);
				if (!$query) {
					die ('SQL Error: ' . mysqli_error($conn));
					}
			?>
			<table class="data-table" border="1" style="position: static;  margin-left:20%;width: 70%">
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
								<td>
										<form action="server.php" method="post"  >
											<input type="hidden" name="gid" value="'.$row['gid'].'"/>
											<button type="submit"  name="undone">
												<i class="fa fa-times" aria-hidden="true" style="color:red;"></i>
											</button>
											<button type="submit"  name="deletedata">
												<i class="fa fa-window-close" aria-hidden="true"></i>
											</button>
										</form>
								</td>
							</tr>';

					}
				?>
				</tbody>
			</table>
			<!------------------------------------------------------------------------------->
			<!--  planner form for data entry-->
			<div class="edit" style="position: static;  margin-left:8%;">

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
						</fieldset>
					</form>
				<?php endif ?>
			</div>
						<!------------------------------------------------------------------------------->
			<!--  planner form for data  deletion-->
			<div class="edit" style="position: static;  margin-left:8%;">

				<?php  if (isset($_POST['datadelete'])) : ?>
					<form action="server.php" method="post">
						<pre>
						Enter id:<input type="number" name="gid"><br>
								<input type="submit" value="delete" name="deletedata">
						</pre>
					</form>
				<?php endif ?>
			</div>
			<!------------------------------------------------------------------------------->
			<!--  planner form for data update/edit-->
			<div class="edit" style="position: static;  margin-left:8%;">
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
			</div>
			<!------------------------------------------------------------------------------->
			<!--  planner form for done-->
			<div class="edit" style="position: static;  margin-left:8%;">
				<?php  if (isset($_POST['done'])) : ?>
					<form action="server.php" method="post">
						<pre>
						Enter id:<input type="number" name="gid"><br>
								<input type="submit" value="done" name="done">
						</pre>
					</form>
				<?php endif ?>
			</div>
			<!------------------------------------------------------------------------------->
			<!--  planner form for undone-->
			<div class="edit" style="position: static;  margin-left:8%;">			
				<?php  if (isset($_POST['undone'])) : ?>
					<form action="server.php" method="post">
						<pre>
						Enter id:<input type="number" name="gid"><br>
								<input type="submit" value="undone" name="undone">
						</pre>
					</form>
				<?php endif ?>
			</div>
			<!------------------------------------------------------------------------------->
			<!--  Buttons for add delete edit done undone-->
			<br><br><br>	
			<div class="task" style="position: static;  margin-left:33%;">
				<form action="planner.php" method="post">
					<button class="button"  type="submit" name="dataentry">
						<i class="fa fa-plus" aria-hidden="true"></i>
					</button>
					
				</form>
				<br><br><br><br><br><br><br>
			</div>
			<!------------------------------------------------------------------------------->
		</div>
	</body>
</html>
<script >
	function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('txt').innerHTML =
    h + ":" + m + ":" + s;
    var t = setTimeout(startTime, 500);
	}
	function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
	}
</script>
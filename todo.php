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

		<style type="text/css">
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
			.form2edit{
				block:inline;
				float: left;
			}
			.body{
				background-color: rgb(243, 237, 254)
;
				height: 100%
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
		<!------------------------------------------------------------------------------->
		<!--body-->
		<div syle="background-color: white;" left=15px; class="body" >
			<p>todo--daily</p>
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
				<table class="data-table" border="1" style="position: static;  margin-left:20%; width: 70%" >
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
								<td width="1%">'.$row['tid'].'</td>
			         			<td width="10%">'.$row['tcategory'].'</td>
								<td width="10%">'.$row['tname'].'</td>
								<td width="35%">'.$row['tdesc'].'</td>
								<td>'. date('F d, Y H:i', strtotime($row['starttime'])) . '</td>
								<td>'. date('F d, Y H:i', strtotime($row['endtime'])) . '</td>
								<td width="12%">
										<form action="todoserver.php" method="post" class="form2edit">
											<input type="hidden" name="tid" value="'.$row['tid'].'"/>
											<button type="submit" name="done" >
												<i class="fa fa-check" style="color:red"></i>
											</button>
										</form>
										<form action="todoserver.php" method="post" class="form2edit">
											<input type="hidden" name="tid" value="'.$row['tid'].'"/>
											<button type="submit" name="deletedata" >
												<i class="fa fa-window-close" aria-hidden="true"></i>
											</button>																					
										</form>
										<form action="todo.php" method="post" class="form2edit">
												<input type="hidden" name="tid" value="'.$row['tid'].'"/>
												<button type="submit" name="dataedit">
													<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
												</button>
										</form>
								</td>
								</tr>';
					}
					?>
			<!------------------------------------------------------------------------------->
			<!-- completed goals -->
			<?php
				$sql = 'SELECT * FROM todogoal WHERE iscompleted=1';
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

							<td width="1%">'.$row['tid'].'</td>
		          			<td width="10%">'.$row['tcategory'].'</td>
							<td width="10%">'.$row['tname'].'</td>
							<td width="35%">'.$row['tdesc'].'</td>
							<td>'. date("F d, Y H:i", strtotime($row["starttime"])) . '</td>
							<td>'. date("F d, Y H:i", strtotime($row["endtime"])) . '</td>
							<td width="12%">
									<form action="todoserver.php" method="post" class="form2edit">
										<input type="hidden" name="tid" value="'.$row['tid'].'"/>
										<button type="submit"  name="undone">
											<i class="fa fa-times" aria-hidden="true" style="color:red;"></i>
										</button>
									 </form>
									<form action="todoserver.php" method="post" class="form2edit">
										<button type="submit"  name="deletedata">
											<i class="fa fa-window-close" aria-hidden="true"></i>
										</button>
									</form>
									<form action="todo.php" method="post" class="form2edit">
											<input type="hidden" name="tid" value="'.$row['tid'].'"/>
											<button type="submit" name="dataedit">
												<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
											</button>
									</form>
							</td>
						</tr>';
				}
				?>
				</tbody>
			</table>	
			<!------------------------------------------------------------------------------->
			<!--  todo form for data entry-->
			<div class="edit" style="position: static;  margin-left:8%;">
				<?php  if (isset($_POST['dataentry'])) : ?>
						<form action="todoserver.php" method="post">
						<fieldset>
				  		<legend>New Entry:</legend>
						<pre>
						name:     		<input type="text" name="tname" style='width:150px;'><br>
						category:               <?php
			  				$sql = 'SELECT * FROM plannercategory';
			  				$query = mysqli_query($conn, $sql);
							  if (!$query) {
							  	die ('SQL Error: ' . mysqli_error($conn));
							 	 }
									echo "<select name='tcategory' style='width:150px;'>"; 			
									while ($row = mysqli_fetch_array($query))
							  		{
							  			echo '<option value='.$row["name"].'>'.$row["name"].'</option>';
							  		}
							  		echo '</select>';
							?>	

						description:		<input type="text" name="tdesc" style='width:150px;'><br>
						start @:		<input type="datetime-local" name="starttime"  style='width:150px;'/><br>
						end @:			<input type="datetime-local" name="endtime" value="<?php echo date('Y-m-d H:i'); ?>" style='width:150px;'/><br>
						<input type="hidden" name="iscompleted" value="false" style='width:150px;'>
					  		<input type="submit" value="done" name="adddata">
						</pre>
						</form>
					<?php endif ?>
			</div>
			<!------------------------------------------------------------------------------->
			<!--  todo form for data  deletion-->
			<div class="edit" style="position: static;  margin-left:8%;">
				<?php  if (isset($_POST['datadelete'])) : ?>
					<form action="todoserver.php" method="post">
						<pre>
						Enter id:<input type="number" name="tid"><br>
								<input type="submit" value="delete" name="deletedata">
						</pre>
					</form>
				<?php endif ?>
			</div>
			<!------------------------------------------------------------------------------->
			<!--  todo form for data update/edit-->
			<div class="edit" style="position: static;  margin-left:8%;">
				<?php  if (isset($_POST['dataedit'])) : ?>
					<form action="todoserver.php" method="post" >
						<pre>
						<?php 		
							$db = mysqli_connect('localhost:3360', 'root', '', 'timely');
							$tid = mysqli_real_escape_string($db, $_POST['tid']);
							$query="select * from todogoal where tid=".$tid;
							$result = mysqli_query($db, $query);
							$row = mysqli_fetch_array($result);
							$name=$row['tname'];
							$category=$row['tcategory'];
							$desc=$row['tdesc'];

							echo "
						id:			<input type='number' name='tid' value=".$tid." readonly><br><br>";
						?>
						category:               <?php
			  				$sql = 'SELECT * FROM plannercategory';
			  				$query = mysqli_query($conn, $sql);
							  if (!$query) {
							  	die ('SQL Error: ' . mysqli_error($conn));
							 	 }
									echo "<select name='tcategory' style='width:135px;'>"; 			
									while ($row = mysqli_fetch_array($query))
							  		{
							  			echo '<option value='.$row["name"].'>'.$row["name"].'</option>';
							  		}
							  		echo '</select><br>';
									echo "			
						name:			<input type='text' name='tname' value=".$name."><br>
						description:		<input type='text' name='tdesc' value=".$desc."><br>
						start @:		<input type='datetime-local' name='starttime' value=";
						?>
						<?php echo date("Y-m-d H:i"); echo " /><br>
						end @:			<input type='datetime-local' name='endtime' value=";
						?>
						<?php echo date("Y-m-d H:i"); echo " /><br>
						<input type='hidden' name='iscompleted' value='false'>
					  		<input type='submit' value='edit' name='editdata'>
						</pre>
						</form>";
						?>
				<?php endif ?>
				
			</div>
			<!------------------------------------------------------------------------------->
			<!--  todo form for done-->
			<div class="edit" style="position: static;  margin-left:8%;">
				<?php  if (isset($_POST['done'])) : ?>
					<form action="todoserver.php" method="post">
						<pre>
						Enter id:	<input type="number" name="tid"><br>
								<input type="submit" value="done" name="done">
						</pre>
					</form>
				<?php endif ?>
			</div>
				<!------------------------------------------------------------------------------->
			<!--  todo form for undone-->
			<div class="edit" style="outline-color: white;outline-width: 2px;outline: thick; border-width: 2px" style="position: static;  margin-left:8%;"> 
				<?php  if (isset($_POST['undone'])) : ?>
					<form action="todoserver.php" method="post">
						<pre>
						Enter id:	<input type="number" name="tid"><br>
								<input type="submit" value="undone" name="undone">
						</pre>
					</form>
				<?php endif ?>
			</div>
				<!------------------------------------------------------------------------------->
			<!--  Buttons for add delete edit done undone-->
			<br><br><br>	
			<div class="task" style="position: static;  margin-left:33%;">
				<form action="todo.php" method="post">
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
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
</head>
<body>
<div class="wrapper row1">
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
<!-- content -->
		<div class="content" id="loginifo">

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

		  <!-- logged in user information -->
		  <?php  if (isset($_SESSION['username'])) : ?>
		    <p> <strong><?php echo $_SESSION['username']; ?></strong></p>
		    <p> <a id="logout_symbol" href="index.php?logout='1'" style="color: red;">logout</a> </p>
		  <?php endif ?>
		</div>
	</header>

</div>
<!--------------------------------------------------------------------------------------------------------->

<div class="wrapper row2">
  <div id="container" class="clear">
    <!-- content body -->
    <div id="homepage">
      <!-- One Quarter -->
      <section id="latest" class="clear">
        <article class="one_quarter">
          <figure><a href="#"><img src="images/outline.png" width="215" height="315" alt="Hello"></a>
            <figcaption>Plan your<br> year</figcaption>
          </figure>
        </article>
        <article class="one_quarter">
          <figure><a href="planner.php"><img src="images/planner.jpg" width="215" height="315" alt=""></a>
            <figcaption>Plan your <br>month</figcaption>
          </figure>
        </article>
        <article class="one_quarter">
          <figure><a href="#"><img src="images/retro.png" width="215" height="315" alt=""></a>
            <figcaption>retrospect and<br>learn</figcaption>
          </figure>
        </article>
        <article class="one_quarter lastbox">
          <figure><a href="todo.php"><img src="images/todo.jpg" width="215" height="315" alt=""></a>
            <figcaption>Set your day to get goals completed </figcaption>
          </figure>
        </article>
      </section>
      <!-- / One Quarter -->
      <section id="shout">
        <p>hey guys! <br>Out mere goal is to see you more efficient and timely will help you achieve it</p>
      </section>
    </div>

</body>
</html>

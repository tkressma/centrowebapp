<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; 
    charset=UTF-8" />
<title>Centro Bus Web Application</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<script src="bootstrap/js/bootstrap.min.js"></script>
</head>

<body>
<nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="https://www.centro.org/">Centro Bus Web App</a>
		</div>
			<ul class="nav navbar-nav">
            <li class="active"><a href="index.php">Home</a></li>
            </ul>
			<?php 
			if (isset($_SESSION['user_id'])) {
			//capitalize username
			$string = $_SESSION['user_id'];
			$username = ucfirst($string);
			?>
			    <ul class="nav navbar-nav navbar-right">
				<li><a href="#"><span class="glyphicon glyphicon-user"></span> Signed in as <?php echo $username; ?></a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Log Out</a></li>

				</ul>
            <?php 
			} 
			?>
			
        </div>
    </div>
</nav>

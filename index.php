<?php
    include('dbcon.php'); 
    include('check.php');

    if(is_login()){

        if ($_SESSION['user_id'] == 'admin' && $_SESSION['is_admin']==1)
            header("Location: admin.php");
        else 
            header("Location: welcome.php");
    }
?>

<!DOCTYPE html>
<html>
<head>
	<title>Centro Bus Web Application - Login</title>
	<link rel="stylesheet" href="bootstrap/css/bootstrap1.min.css">
</head>


<body>

<div class="container">
	<h1 align="center"><b>Centro Bus Web App</b></h1><hr>
	<h2 align="center">Login</h2><hr>
	<form class="form-horizontal" method="POST">
		<div class="form-group" style="padding: 10px 10px 10px 10px;">
			<label for="user_name">Username:</label>
			<input type="text" name="user_name"  class="form-control" id="inputID" placeholder="Type your username." 
				required autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" />
		</div>
		<div class="form-group" style="padding: 10px 10px 10px 10px;">
			<label for="user_password">Password:</label>
			<input type="password" name="user_password" class="form-control" id="inputPassword" placeholder="Type your password." 
				required  autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" />
		</div>


<?php

    $login_ok = false;

    if ( ($_SERVER['REQUEST_METHOD'] == 'POST') and isset($_POST['login']) )
    {
		$username=$_POST['user_name'];  
		$userpassowrd=$_POST['user_password'];  

		if(empty($username)){
			?>
		<div class="alert alert-danger">
			<strong>Error!</strong> Enter a username.
		</div>
			<?php
		}else if(empty($userpassowrd)){ 
		?>
		<div class="alert alert-danger">
			<strong>Error!</strong> Enter a password.
		</div>
			<?php
		} else {
			

			try { 

				$stmt = $con->prepare('select * from users where username=:username');

				$stmt->bindParam(':username', $username);
				$stmt->execute();
			   
			} catch(PDOException $e) {
				die("Database error. " . $e->getMessage()); 
			}

			$row = $stmt->fetch();  
			$salt = $row['salt'];
			$password = $row['password'];
			
			$decrypted_password = decrypt(base64_decode($password), $salt);

			if ( $userpassowrd == $decrypted_password) {
				$login_ok = true;
			}
		}

		
		if(isset($errMSG)) 
			echo "<script>alert('$errMSG')</script>";
		

        if ($login_ok){

					session_regenerate_id();
					$_SESSION['user_id'] = $username;
					$_SESSION['is_admin'] = $row['is_admin'];

					if ($username=='admin' && $row['is_admin']==1 )
						header('location:admin.php');
					else
						header('location:welcome.php');
					session_write_close();
			} else{
			?>
<div class="alert alert-danger">
  <strong>Login Failed.</strong> Username or password is incorrect.
</div>
			<?php
		}
	}
?>
		<div class="checkbox">
			<label><input type="checkbox">Remember Username</label>
		</div>
		</br>
		<div class="from-group" style="padding: 10px 10px 10px 10px;" >
			<button type="submit" name="login" class="btn btn-success" <span class="glyphicon glyphicon-log-in"></span>Login</button>
			<a class="btn btn-success" href="registration.php" style="margin-left: 50px">
			<span class="glyphicon glyphicon-user"></span>&nbsp;Register</a>
		</div>
		</br>
	</form>
</body>
</html>
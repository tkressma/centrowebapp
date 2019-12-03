<?php
    include('dbcon.php'); 
    include('check.php');

    if(is_login()){

        if ($_SESSION['user_id'] == 'admin' && $_SESSION['is_admin']==1)
            header("Location: admin.php");
        else 
            header("Location: welcome.php");
    }
	include('head.php');
?>

<!DOCTYPE html>
<html>
<head>
	<title>Centro Bus Web Application - Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  font-family: Arial, Helvetica, sans-serif;
  background-color: white;
}

* {
  box-sizing: border-box;
}

/* Add padding to containers */
.container {
  padding: 16px;
  background-color: #f1f1f1;
}

/* Full-width input fields */
input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: white;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: white;
  outline: none;
}

/* Overwrite default styles of hr */
hr {
  border: 1px solid #0071C1;
  margin-bottom: 25px;
}

/* Set a style for the submit button */
.registerbtn {
  background-color: #4CAF50;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity: 1;
}

/* Add a blue text color to links */
a {
  color: dodgerblue;
}

/* Set a grey background color and center the text of the "sign in" section */
.signin {
  background-color: #0071C1;
  text-align: center;
}
</style>
</head>
<body class="text-center">
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
			} else {
			?>
<div class="alert alert-danger">
  <strong>Login Failed.</strong> Username or password is incorrect.
</div>
			<?php
		}
	}
?>
<form id="form" method="post" enctype="multipart/form-data">
  <div class="container">
    <h1><b>Centro Bus Web Application</b></h1>
    <p>Please login to continue.<p>
    <hr>
	
	<label for="user_name">Username:</label>
	<input type="text" name="user_name"  class="form-control" id="inputID" placeholder="Type your username." 
	required autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" />

	<label for="user_password">Password:</label>
	<input type="password" name="user_password" class="form-control" id="inputPassword" placeholder="Type your password." 
	required  autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" />

    <hr>
	
	<div class="checkbox">
		<label><input type="checkbox">Remember Username</label>
	</div>

    <button type="submit" name="login" class="btn btn-primary" <span class="glyphicon glyphicon-log-in"></span>Login</button>
	
  </div>
  
  <div class="container signin">
    <p>Don't have an account? <a href="registration.php">Click here to register</a>!</p>
  </div>
</form>

</body>
</html>
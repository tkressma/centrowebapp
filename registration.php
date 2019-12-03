<?php

    include('dbcon.php');
    include('check.php');
    if (is_login()){

        if ($_SESSION['user_id'] == 'admin' && $_SESSION['is_admin']==1)
            header("Location: admin.php");
        else
            header("Location: welcome.php");
    }


function validatePassword($password){
	//Begin basic testing
	if(strlen($password) < 8 || empty($password)) {
		return 0;//Returns 0 if: password is too short (<8 characters) OR doesn't exist.
	}
	if((strlen($password) > 48)) {
		return 0;//Returns 0 if: password is too long (>48 characters)
	}
	//End basic length tests
	
	//Begin more advanced testing
	
	if(preg_match('/[A-Z]/',$password) == (0 || false)){
		return 1;//Returns 1 if: password does NOT contain upper case letters
	}
	if(!preg_match('/[\d]/',$password) != (0 || false)){
		return 2;//Returns 2 if: password does NOT contain digits
	}
	if(preg_match('/[\W]/',$password) == (0 || false)){
		return 3;//Returns 3 if: password does NOT contain any special characters
	}
	return true;
}

	
        if( ($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['submit']))
	{
 
        foreach ($_POST as $key => $val)
        {
            if(preg_match('#^__autocomplete_fix_#', $key) === 1){
                $n = substr($key, 19);
                if(isset($_POST[$n])) {
                    $_POST[$val] = $_POST[$n];
            }
        }
        } 

		$username=$_POST['newusername'];
		$password=$_POST['newpassword'];
		$confirmpassword=$_POST['newconfirmpassword'];

             //   if (!validatePassword($password)){
	//		$errMSG = "wrong password";
          //      }

                if ($_POST['newpassword'] != $_POST['newconfirmpassword']) {
                        $errMSG = "Password is not match.";
                }

		if(empty($username)){
			$errMSG = "Type your username.";
		}
		else if(empty($password)){
			$errMSG = "Type your password.";
		}

                try { 
                    $stmt = $con->prepare('select * from users where username=:username');
                    $stmt->bindParam(':username', $username);
                    $stmt->execute();

               } catch(PDOException $e) {
                    die("Database error: " . $e->getMessage()); 
               }

               $row = $stmt->fetch();
               if ($row){
                    $errMSG = "This username is already exist.";
               }



		if(!isset($errMSG))
		{
                   try{
			$stmt = $con->prepare('INSERT INTO users(username, password, salt) VALUES(:username, :password, :salt)');
			$stmt->bindParam(':username',$username);
                        $salt = bin2hex(openssl_random_pseudo_bytes(32));
                        $encrypted_password = base64_encode(encrypt($password, $salt));
                        $stmt->bindParam(':password', $encrypted_password);
			$stmt->bindParam(':salt',$salt);		

			if($stmt->execute())
			{
				$successMSG = "New user added.";
				header("refresh:1;index.php");
			}
			else
			{
				$errMSG = "Adding user error";
			}
                     } catch(PDOException $e) {
                        die("Database error: " . $e->getMessage()); 
                     }



		}


	}
	
include('head.php');
?>
<head>
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
	if(isset($errMSG)){
			?>
            <div class="alert alert-danger">
            <span class="glyphicon glyphicon-info-sign"></span> <strong><?php echo $errMSG; ?></strong>
            </div>
            <?php
	}
	else if(isset($successMSG)){
		?>
        <div class="alert alert-success">
              <strong><span class="glyphicon glyphicon-info-sign"></span> <?php echo $successMSG; ?></strong>
        </div>
        <?php
	}
	?>   

<form id="form" method="post" enctype="multipart/form-data">
  <div class="container">
    <h1><b>Register</b></h1>
    <p>Please fill in this form to create an account.</p>
    <hr>
	
	<? $r1 = rmd5(rand().mocrotime(TRUE)); ?>
	<label class="control-label">Username:</label></td>
    <input class="form-control" type="text" name="<? echo $r1; ?>" placeholder="Enter Username." autocomplete="off" readonly 
    onfocus="this.removeAttribute('readonly');" />
    <input type="hidden" name="__autocomplete_fix_<? echo $r1; ?>" value="newusername" /> 

	<? $r2 = rmd5(rand().mocrotime(TRUE)); ?>
    <label class="control-label">Password:</label></td>
    <input class="form-control" type="password" name="<? echo $r2; ?>"  placeholder="Enter password." autocomplete="off" readonly 
    onfocus="this.removeAttribute('readonly');" />
    <input type="hidden" name="__autocomplete_fix_<? echo $r2; ?>" value="newpassword" /> 

	<? $r3 = rmd5(rand().mocrotime(TRUE)); ?>
    <label class="control-label">Confirm your Password:</label></td>
    <input class="form-control" type="password" name="<? echo $r3; ?>"  placeholder="Enter password again." autocomplete="off" readonly 
    onfocus="this.removeAttribute('readonly');" />
    <input type="hidden" name="__autocomplete_fix_<? echo $r3; ?>" value="newconfirmpassword" /> 
	
    <hr>
	<button type="submit" name="submit"  class="btn btn-primary"></span>&nbsp; Create Account</button>
  </div>
  
  <div class="container signin">
    <p><font color=white>Already have an account? </font><a href="index.php">Sign in</a>.</p>
  </div>
</form>
</body>
</html>
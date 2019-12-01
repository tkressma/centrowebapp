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
<link href="signin.css" rel="stylesheet">
</head>
<body class="text-center">
<div class="container">
	<div>
	<h1 class="h2" align="center">&nbsp; Register an Account</h1><hr>
    </div>
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

<form id="form" method="post" enctype="multipart/form-data" class="form-horizontal" style="margin: 0 300px 0 300px;border: solid 1px;border-radius:4px">
	<table class="table table-responsive">
    <tr>
        <? $r1 = rmd5(rand().mocrotime(TRUE)); ?>
    	<td><label class="control-label">Username</label></td>
        <td><input class="form-control" type="text" name="<? echo $r1; ?>" placeholder="Enter Username." autocomplete="off" readonly 
    onfocus="this.removeAttribute('readonly');" />
            <input type="hidden" name="__autocomplete_fix_<? echo $r1; ?>" value="newusername" /> 

        </td>
    </tr>
    <tr>
        <? $r2 = rmd5(rand().mocrotime(TRUE)); ?>
    	<td><label class="control-label">Password</label></td>
        <td>
            <input class="form-control" type="password" name="<? echo $r2; ?>"  placeholder="Enter password." autocomplete="off" readonly 
                   onfocus="this.removeAttribute('readonly');" />
            <input type="hidden" name="__autocomplete_fix_<? echo $r2; ?>" value="newpassword" /> 
        </td>
    </tr>
    <tr>
        <? $r3 = rmd5(rand().mocrotime(TRUE)); ?>
    	<td><label class="control-label">Confirm your Password</label></td>
        <td>
            <input class="form-control" type="password" name="<? echo $r3; ?>"  placeholder="Enter password again." autocomplete="off" readonly 
                   onfocus="this.removeAttribute('readonly');" />
            <input type="hidden" name="__autocomplete_fix_<? echo $r3; ?>" value="newconfirmpassword" /> 
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center">
        <button type="submit" name="submit"  class="btn btn-primary"><span class="glyphicon glyphicon-floppy-save"></span>&nbsp; Create Account</button>
        </td>
    </tr>
    </table>
</form>
</div>
</body>
</html>
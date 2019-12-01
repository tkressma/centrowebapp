<?php
    include('dbcon.php');
    include('check.php');

    if (is_login()){
        ;
    }else
        header("Location: index.php"); 

	include('head.php');
?>
<div align="center">
<?php
	$user_id = $_SESSION['user_id'];

	try { 
		$stmt = $con->prepare('select * from users where username=:username');
		$stmt->bindParam(':username', $user_id);
		$stmt->execute();

   } catch(PDOException $e) {
		die("Database error: " . $e->getMessage()); 
   }

   $row = $stmt->fetch();
   
?>

<?php
$tableContent = '';
$start = '';
$selectStmt = $con->prepare('SELECT * FROM routes');
$selectStmt->execute();
$users = $selectStmt->fetchAll();

foreach ($users as $user)
{
    $tableContent = $tableContent.'<tr>'.
            '<td>'.$user['id'].'</td>'
            .'<td>'.$user['route_name'].'</td>'
            .'<td>'.$user['route_stops'].'</td>'
            .'<td>'.$user['stop_time'].'</td>';
}

if(isset($_POST['search']))
{
$start = $_POST['start'];
$tableContent = '';
$selectStmt = $con->prepare('SELECT * FROM routes WHERE route_name like :start');
$selectStmt->execute(array(
        
         ':start'=>$start.'%'
));

$users = $selectStmt->fetchAll();

foreach ($users as $user)
{
    $tableContent = $tableContent.'<tr>'.
            '<td>'.$user['id'].'</td>'
            .'<td>'.$user['route_name'].'</td>'
            .'<td>'.$user['route_stops'].'</td>'
            .'<td>'.$user['stop_time'].'</td>';
}
    
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Search & Display Using Selected Values</title>  
        <style>
            table,tr,td
            {
               border: 1px solid #000; 
            }
            
            td{
                background-color: #ddd;
            }
        </style>   
    </head>
    <body>
        
        <form action="routes.php" method="POST">
            <!-- 
For The First Time The Table Will Be Populated With All Data
But When You Choose An Option From The Select Options And Click The Find Button, The Table Will Be Populated With specific Data 
             -->
            <select name="start">
                <option value="">[Select a Route]</option>
                <option value="SUNY Oswego Green Route" <?php if($start == 'SUNY Oswego Green Route'){echo 'selected';}?>>SUNY Oswego Green Route</option>
                <option value="SUNY Oswego Blue Route" <?php if($start == 'SUNY Oswego Blue Route'){echo 'selected';}?>>SUNY Oswego Blue Route</option>
            </select>
            <input type="submit" name="search" value="Find">
            <div class="container">
            <table class="table">
			<thead>
                <tr>
                    <th>#ID</th>
                    <th>Route Name</th>
                    <th>Route Stop</th>
                    <th>Stop Time</th>
                </tr>
            </thead>
			<tbody>
                <?php
                echo $tableContent;
                ?>
				</tbody>
            </table>
            </div>
        </form>
        
    </body>    
</html>
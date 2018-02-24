<?php 
header("Access-Control-Allow-Origin:*");


$con = mysqli_connect ('localhost','root','root');
mysqli_select_db($con,'angularjs');



$sql= "select * from employee";
$result=mysqli_query($con,$sql) or die(mysqli_error());

while($row=mysqli_fetch_assoc($result))
{
    $myJSON[]=$row;
}
// header('Content-Type: application/json');
$a=json_encode($myJSON);
print_r($a);
?>
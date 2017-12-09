<?php 
$con = mysqli_connect ('localhost','root','');
mysqli_select_db($con,'angularjs');

$sql= "select * from employee";
$result=mysqli_query($con,$sql) or die(mysqli_error());

while($row=mysqli_fetch_assoc($result))
{
    $myJSON[]=$row;
}

$a=json_encode($myJSON);
print_r($a);
?>
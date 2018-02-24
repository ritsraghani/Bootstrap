<?php 

$data=json_decode(file_get_contents("php://input"));

echo "Hello";
echo $emid= $data->id;


$con = mysqli_connect ('localhost','root','root');
mysqli_select_db($con,'angularjs');

echo $sql= "delete from employee where id='$emid' ";

$result=mysqli_query($con,$sql) or die(mysqli_error());

if(!$result)
{
    die("Query Failed".mysqli_error($con));
}

?>
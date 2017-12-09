<?php 

$data=json_decode(file_get_contents("php://input"));

echo "Hello";
echo $emid= $data->id;
echo $emname=$data->name;
echo $emdob=$data->dob;
echo $ememail=$data->emailid;
echo $emmobile = $data->mobile;
echo $emsalary = $data->salary;
echo $emcity = $data->city;

$con = mysqli_connect ('localhost','root','');
mysqli_select_db($con,'angularjs');

echo $sql= "update employee set employee_name = '$emname',dob = '$emdob',email='$ememail',
mobile_no='$emmobile',salary='$emsalary',city='$emcity' where id='$emid' ";

$result=mysqli_query($con,$sql) or die(mysqli_error());

if(!$result)
{
    die("Query Failed".mysqli_error($con));
}

?>
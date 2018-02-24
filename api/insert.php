<?php 

$data=json_decode(file_get_contents("php://input"));

echo $emname=$data->name;
echo $emdob=$data->dob;
echo $ememail=$data->emailid;
echo $emmobile = $data->mobile;
echo $emsalary = $data->salary;
echo $emcity = $data->city;

$con = mysqli_connect ('localhost','root','root');
mysqli_select_db($con,'angularjs');

echo $sql= "insert into employee (employee_name,dob,email,mobile_no,salary,city) 
values('$emname','$emdob','$ememail','$emmobile','$emsalary','$emcity')";

$result=mysqli_query($con,$sql) or die(mysqli_error());

if(!$result)
{
    die("Query Failed".mysqli_error($con));
}
// while($row=mysqli_fetch_assoc($result))
// {
//     $myJSON[]=$row;
// }


// $a=json_encode($myJSON);
// print_r($a);
?>
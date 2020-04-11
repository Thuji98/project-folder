<?php
    $nameErr=$leaderErr=$disErr=$emailErr=$phoneErr='';           //Defining error message values
    $org_name=$leader=$district=$email=$phone_num=$discription='';    //Definig variables and initiate them to empty values

    /*if($_SERVER['REQUEST_METHOD']=='POST'){
        $org_name=$_POST['org_name'];
        if(isset($_POST['leader'])){
            $leader=$_POST['leader'];
        }
        $district=$_POST['district'];
        $email=$_POST['email'];
        $phone_num=$_POST['phone_num'];
        $discription=$_POST['discription'];
    }*/

    if($_SERVER['REQUEST_METHOD']=='POST' and isset($_POST['submit_button'])){
        //echo '<script type="text/javascript">alert("submit button clicked")</script>';
        $isOk=1;
        if(empty($_POST['org_name'])){
            $nameErr="Organization name is required";
            $isOk=0;
        }else{
            $org_name=filter($_POST['org_name']);
            $validate_name_query="select * from organizations where org_name='$org_name'";
            $query_run=mysqli_query($con,$validate_name_query);
            if(mysqli_num_rows($query_run)>0){
                echo '<script type="text/javascript">alert("Organization name already exits...")</script>';
                $isOk=0;
            }
            if(!preg_match("/^[a-zA-Z ]*$/",$org_name)){
                $nameErr='Only letters and white space allowed';
            }
        }
        
        if(empty($_POST['leader'])){
            $leaderErr="Organization name is required";
            $isOk=0;
        }else{
            if($_POST['leader']=='you'){
                $leader=$_SESSION['user_nic'];
            }
            elseif (isset($_POST['leader_nic'])) {
                $leader=$_POST['leader_nic'];
            }
        }

        if(empty($_POST['district'])){
            $disErr="Service district is required";
            $isOk=0;
        }else{
            $district=filter($_POST['district']);
        }

        if(empty($_POST['email'])){
            $emailErr="Email is required";
            $isOk=0;
        }else{
            $email=filter($_POST['email']);
        }

        if(empty($_POST['phone_num'])){
            $phoneErr="Phone number is required";
            $isOk=0;
        }else{
            $phone_num=filter($_POST['phone_num']);
        }

        $discription=$_POST['discription'];

        if($isOk==1){
            $str_members=$_POST['hidden'];
            
            $query="INSERT INTO organizations (org_name, leader, district, email, phone_num, members, discription) VALUES ('$org_name','$leader','$district','$email','$phone_num','$str_members','$discription')";
            $query_run=mysqli_query($con,$query);
            if($query_run){
                header('location:organizations.php');
                echo '<script type="text/javascript">alert("Successfully created")</script>';
                
            }else{
                echo '<script type="text/javascript">alert("Error")</script>';
            }
            #header('location:home_page.php');
        }else{
            echo "try again";
        }
    }
    function filter($input){
        return(htmlspecialchars(stripslashes(trim($input))));
    }
?>
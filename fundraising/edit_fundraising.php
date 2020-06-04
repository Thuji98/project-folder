<?php
    session_start();
    require 'dbconfi/confi.php';
	$f_id=intval($_GET['edit_btn']);
	$query="select * from fundraisings where id=".$f_id;
    $result=($con->query($query))->fetch_assoc();
	
	$fun_id=$result['id'];
	$fundraising_name=$result['name'];
	$service_area=$result['service_area'];
	$description=$result['description'];
	$org_name="";
	$type=$result['type'];
	$for_event=$result['for_event'];
	$for_any=$result['for_any'];
	$money=$result['expecting_money'];
	$things=$result['expecting_things'];
	$post_id=$result['post_id'];
	
	if($_SERVER['REQUEST_METHOD']=='POST' and isset($_POST['submit_button'])){
	$isOk=1;	
		if(empty($_POST['fundraising_name'])){
			echo '<script type="text/javascript">alert("Fundraising event name is required")</script>';
            $isOk=0;
        }else{
            $fundraising_name=filter($_POST['fundraising_name']);
			if($fundraising_name != $_POST['fundraise']){
				$validate_name_query="select * from fundraisings where name='$fundraising_name'";
				$query_run=mysqli_query($con,$validate_name_query);
				if(mysqli_num_rows($query_run)>0){
					echo '<script type="text/javascript">alert("fundraising name already exits...")</script>';
					$isOk=0;
				}
            }
        }
		if($_POST['organization']==""){
			$org_name= "NULL";
		}
		else{
			$org_name=$_POST['organization'];
		}
		
		$for_event=$_POST['for_event'];
		$for_any=$_POST['other_purpose'];
		
		$for_opt=$_POST['purp'];
		$purpose='';
		if($for_opt=="00"){
			echo '<script type="text/javascript">alert("fill purpose field")</script>';
			$isOk=0;
		}
		elseif($for_opt=="1"){
			$for_any=NULL;
			$query2="select * from disaster_events where event_id=". $for_event;
			$result2=($con->query($query2))->fetch_assoc();
			$purpose=$result2['name'];
		}
		else{
			$for_event="NULL";
			$purpose=$for_any;
			if(empty($for_any)){
				echo '<script type="text/javascript">alert("purpose is required")</script>';
				$isOk=0;
			}
		}
		$mon=$_POST['mon'];
		$thin=$_POST['thin'];
		
		if(($mon=="0") and ($thin=="0")){
			echo '<script type="text/javascript">alert("select your requests")</script>';
			$isOk=0;
		}
		elseif(($mon=="1") and ($thin=="1")){
			$type="money and things";
			$money=$_POST['expecting_money'];
			$things=$_POST['expecting_things'];
			if((empty($money)) or (empty($things))){
				echo '<script type="text/javascript">alert("fill your request")</script>';
				$isOk=0;
			}
			$type_message="Ammount : ".$money."<br> Things :".$things;
		}
		elseif( $mon=="1"){
			$type="money only";
			$money=$_POST['expecting_money'];
			$things="NULL";
			if(empty($money)){
				echo '<script type="text/javascript">alert("ammount is required")</script>';
				$isOk=0;
			}
			$type_message="Ammount : ".$money;
		}
		elseif($thin=="1"){
			$type="things only";
			$money="NULL";
			$things=$_POST['expecting_things'];
			if(empty($things)){
				echo '<script type="text/javascript">alert("things required")</script>';
				$isOk=0;
			}
			$type_message="Things :".$things;
		}
		
		$service_area=$_POST['service_area'];
		$description=$_POST['description'];
		$by=$_SESSION['user_nic'];
		
		$content="Fundraising event name: ".$fundraising_name. "<br> Purpose:".$purpose."<br> Requesting :".$type."<br>".$type_message."<br> Service area :".$service_area."<br> Description :".$description;
		
		if($isOk==1){
			$query1="UPDATE public_posts set author='$by', org=$org_name, date=NOW(), content='$content' WHERE post_index=".$_POST['post_id'];
			$query_run1=mysqli_query($con,$query1);
			
			$query="UPDATE fundraisings set name='$fundraising_name' ,by_org=$org_name, type='$type',for_event=$for_event, for_any='$for_any', expecting_money='$money', expecting_things='$things', service_area='$service_area', description='$description' WHERE id=".$_POST['idd'];

            $query_run=mysqli_query($con,$query);
            if($query_run){
				header("Location:fundraising.php");
                echo '<script type="text/javascript">alert("Successfully updated")</script>';
                
            }else{
                echo '<script type="text/javascript">alert("Error")</script>';
            }
		}
		else{
            echo "try again";
        }
		
	}
	function filter($input){
        return(htmlspecialchars(stripslashes(trim($input))));
    }
	
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Edit fundraising event</title>
        <link rel="stylesheet" href="css_codes/create_fundraising.css">
    </head>
    <body>
    <?php 
	require "header.php" ;
	?>

    <script>
        btnPress(8);
    </script>

    <div id="main_body">
		<center><h2>Edit fundraising event</h2></center>
		<small style="margin:10px;">Edit the details</small>
			<form method='post' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>'>
                <table id='sub_body'>
                    <tr>
                        <td colspan='2'>
                            <span id='error'>* required field</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for='fundraising_name'>Fundraising event name</label>
                        </td>
                        <td>
							<input type="hidden" id="idd" name="idd" value=<?php echo $fun_id; ?> />
							<input type="hidden" id="fundraise" name="fundraise" value=<?php echo $fundraising_name; ?> />
							<input type="hidden" id="post_id" name="post_id" value=<?php echo $post_id; ?> />
                            <input type='text' name="fundraising_name" value=<?php echo $fundraising_name; ?>>
                            <span id='error' class="error">* </span>
                        </td>
                    </tr>
					<tr>
                        <td>
                            <label for='organization'>Select organization</label>
                        </td>
                        <td>
                            <select name="organization">
								<option value=''>Not organization based</option>
								<?php
								$query='select * from organizations';
								$result=$con->query($query);
								while($row=$result->fetch_assoc()){
								if($row["leader"]==$_SESSION['user_nic']){
									echo "<option value=" . $row["org_id"] . ">" . $row["org_name"] . "</option>>";
								}else{
									$co_leaders=$row["co_leader"];
									$co_leader = explode(" ", $co_leaders);
									foreach($co_leader as $leader){
										if($leader==$_SESSION['user_nic']){
											echo "<option value=" . $row["org_id"] . ">" . $row["org_name"] . "</option>>";
											break;
										}
									}
								}
								}
								?>
						</td>
					</tr>
					<tr>
                        <td>
                            <label for='event'>Select purpose</label>
                        </td>
                        <td>
							<input type="hidden" id="purp" name="purp" value="00" />
                            <input type='radio' name="purpose" id='event_purpose' value=''  onclick='purposeFun()'>For event</br>
							<select name="for_event" id="for_event"  style='display:none'>
                                <?php
								$query='select * from disaster_events';
								$result=$con->query($query);
								while($row=$result->fetch_assoc()){
								echo "<option value=" . $row["event_id"] . ">" . $row["name"] . "</option>>";		
								}
								?>
                            <div style="display:flex; height:20px;">
                                <input type='radio' name="purpose" id='other_purpose_opt' value=''  onclick='purposeFun()'>Other purpose
                                <input type='text' name='other_purpose' id='other_purpose' style='display:none' value=<?php echo $for_any; ?>>
                            </div>
                            
							
                            <script type="text/javascript">
							
								var for_event = '<?php echo $for_event ?>';

								if(for_event==''){
									document.getElementById("other_purpose_opt").checked=true;
									document.getElementById('other_purpose').style.display='block';
									document.getElementById("for_event").value = "";
									document.getElementById("purp").value = "2";
								}else{
									document.getElementById("event_purpose").checked=true;
									document.getElementById('for_event').style.display='block';
									document.getElementById("other_purpose").value = " ";
									document.getElementById("purp").value = "1";
								}
							
                                function purposeFun(){
                                    if(document.getElementById("other_purpose_opt").checked){
                                        document.getElementById('other_purpose').style.display='block'
										document.getElementById('for_event').style.display='none'
										document.getElementById("purp").value = "2";
										
                                        
                                    }else{
                                        document.getElementById('other_purpose').style.display='none'
										document.getElementById('for_event').style.display='block'
										document.getElementById("purp").value = "1";
										
                                    }
                                }
                            </script>
							<span id='error' class="error">* </span>
						</td>
					</tr>
					<tr>
                        <td>
                            <label for='type'>Your Request</label>
                        </td>
                        <td>
							<input type="hidden" id="mon" name="mon" value="0" />
							<input type="hidden" id="thin" name="thin" value="0" />
							
							<input type="checkbox" id="money" onclick="checkmoneyfn()">Money
							<input type='text' name="expecting_money" id="expecting_money" placeholder='expected money in Rs' style='display:none' value=<?php echo $money; ?>>
							<input type="checkbox" id="things" onclick="checkthingsfn()">Things
							<input type='text' name="expecting_things" id="expecting_things" placeholder='enter your expected things' style='display:none' value=<?php echo $things; ?>>
							
							<script>
								var type = '<?php echo $type ?>';

								if(type=="money only"){
									document.getElementById("money").checked=true;
									document.getElementById('expecting_money').style.display='block';
									document.getElementById("expecting_things").value = "";
									document.getElementById("mon").value = "1";
									document.getElementById("thin").value = "0";
									
								}else if(type=="things only"){
									document.getElementById("things").checked=true;
									document.getElementById('expecting_things').style.display='block';
									document.getElementById("expecting_money").value = "";
									document.getElementById("thin").value = "1";
									document.getElementById("mon").value = "0";
								}else{
									document.getElementById("money").checked=true;
									document.getElementById('expecting_money').style.display='block';
									document.getElementById("things").checked=true;
									document.getElementById('expecting_things').style.display='block';
									document.getElementById("thin").value = "1";
									document.getElementById("mon").value = "1";
								}
							
							
                                function checkmoneyfn(){
                                    if(document.getElementById("money").checked==true){
                                        document.getElementById('expecting_money').style.display='block';
										document.getElementById("mon").value = "1";
                                        
                                    }else{
                                        document.getElementById('expecting_money').style.display='none';
										document.getElementById("mon").value = "0";
                                    }
                                }
								
								function checkthingsfn(){
                                    if(document.getElementById("things").checked==true){
                                        document.getElementById('expecting_things').style.display='block';
                                        document.getElementById("thin").value = "1";
										
                                    }else{
                                        document.getElementById('expecting_things').style.display='none';
										document.getElementById("thin").value = "0";
                                    }
                                }
                            </script>
                            <span id='error' class="error">* </span>
						</td>
					</tr>
					<tr>
                        <td>
                            <label>Service area</label>
                        </td>
                        <td>
                            <input type='text' name="service_area" value=<?php echo $service_area; ?>>
                        </td>
                    </tr>
					<tr>
                        <td>
                            <label>Description</label>
                        </td>
                        <td>
                            <textarea name='description'><?php echo $description; ?></textarea>
                        </td>
                    </tr>
					<tr>
                        <td colspan='2'>
                            <input type='submit' name='submit_button' id='submitBtn' value="Update">
                        </td>
                    </tr>
				</table>
            </form>
    
	</div>
    </body>
</html>
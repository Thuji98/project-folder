<?php
    ob_start();
    ignore_user_abort();

    require $_SERVER['DOCUMENT_ROOT']."/confi/db_confi.php";
    require $_SERVER['DOCUMENT_ROOT']."/confi/verify.php";
 
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $by=$_POST['by'];
        $event_id=$_POST['event_id'];
        $to=$_POST['to'];
        $by_person=$_SESSION['user_nic'];
        if(isset($_POST['pro_cancel_button'])){
            if($by=="your_self"){
                $pri_query="DELETE from event_".$event_id."_pro_don_content WHERE don_id=(select id from event_".$event_id."_pro_don WHERE by_person='".$by_person."' and to_person='".$to."');
                DELETE from event_".$event_id."_pro_don WHERE by_person='".$by_person."' and to_person='".$to."';";
                $msg=" cancel his/her promise to help you";
            }else{
                $pri_query="DELETE from event_".$event_id."_pro_don_content WHERE don_id=(select id from event_".$event_id."_pro_don WHERE by_org='".$by."' and to_person='".$to."');
                DELETE from event_".$event_id."_pro_don WHERE by_org='".$by."' and to_person='".$to."';";
                $msg=" cancel their promise to help you";
            }
        }else{
            $item = array_filter($_POST['item']);
            $amount=$_POST["amount"];
            $update_id=$_POST['update_id'];   
            $note=filt_inp($_POST['note'])?:'';
            $mark=$_POST['mark'];

            if($_POST['entry_update_id']!=0){
                $del_detail = array_filter(explode(',', $_POST['del']));
                $pri_query='';
                foreach( $del_detail as $row_del){
                    $pri_query.= "delete from event_".$event_id."_pro_don_content where id=$row_del;";
                }
                $msg=" edited their promised to help you";
                $pri_query.="update event_".$event_id."_pro_don set note='".$note."' where id={$_POST['entry_update_id']};";
            
                for($x=0 ; $x < count($item) ; $x++){
                    if(!empty($item[$x])){
                      $item1=filt_inp(ready_input($item[$x]));
                        $amount1=filt_inp($amount[$x]);
                        if(empty($amount[$x])){
                                $amount1=0;
                            }
                        if($update_id[$x]=='0'){
                             $pri_query .= "INSERT INTO event_".$event_id."_pro_don_content (don_id, item, amount, pro_don) VALUES ({$_POST['entry_update_id']}, '".$item1."', '".$amount1."', '{$mark[$x]}');";
                        }else{
                            $pri_query .= "UPDATE `event_".$event_id."_pro_don_content` SET item='".$item1."', amount = '".$amount1."', pro_don='{$mark[$x]}' where id=$update_id[$x];";
                        }
                    }
                }
            }else{
                if($by=='your_self'){
                    $by_org='NULL';
                }else{
                    $by_person='';
                    $by_org=$by;
                }
                $msg=" promised to help you";
                $pri_query = "insert into event_".$event_id."_pro_don (by_org, by_person, to_person, note) values ($by_org, '$by_person', '$to', '".$note."');";
                if(count($item)>0){
                    $querry_arr = array();
                    for($x=0; $x < count($item); $x++ ){
                        $item1=filt_inp(ready_input($item[$x]));
                        $row_amount = filt_inp($amount[$x])?:'0';
                        array_push($querry_arr, "(last_insert_id(),'".$item1."','$row_amount','$mark[$x]')");
                    }
                    $pri_query.= "INSERT INTO `event_".$event_id."_pro_don_content`(`don_id`, `item`, `amount`, `pro_don`) VALUES ". implode(", ", $querry_arr).";";
                }
            }
        }

        if($by=='your_self'){
            $sql="";
        }else{
            $sql="SELECT org_name FROM `organizations` WHERE org_id=$by;";
        }
        if(mysqli_multi_query($con,$sql.$pri_query)){
            $size = ob_get_length();
            header("Content-Encoding: none");
            header("Content-Length: {$size}");
            header("location:".$_SERVER['HTTP_REFERER']);
            header("Connection: close");

            header('location:'.$_SERVER['HTTP_REFERER']);

            ob_end_flush();
            ob_flush();
            flush();

            $sql_res=mysqli_store_result($con);
            $result1=$sql_res->fetch_row();
            mysqli_free_result($sql_res);

            require $_SERVER['DOCUMENT_ROOT']."/notification/notification_sender.php";
            $sender = new Notification_sender($to,$result1[0].$msg,"/event/view_my_individual_promises.php?event_id=".$event_id,true);
            $sender->send();
            
        }else{
            //header("location:/event/help?event_id=".$event_id."&by=".$by."&to=".$to);
            echo "not sucess";
        }
    }
?>
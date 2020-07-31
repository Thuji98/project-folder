<?php
    require $_SERVER['DOCUMENT_ROOT']."/includes/header.php";

    $id=$_SESSION['user_nic'];
    $event_id = $_GET['event_id'];
    $query="select * from event_".$event_id."_pro_don left join civilian_detail on civilian_detail.NIC_num = event_".$event_id."_pro_don.by_person left join organizations on organizations.org_id = event_".$event_id."_pro_don.by_org where to_person='".$id."';
    select * from event_".$event_id."_pro_don_content inner join event_".$event_id."_pro_don on event_".$event_id."_pro_don_content.don_id = event_".$event_id."_pro_don.id where to_person='".$id."';";

    if(mysqli_multi_query($con,$query)){
        $result=mysqli_store_result($con);
        $person_org_detail=[];
        if(mysqli_num_rows($result)>0){
            $person_org_detail=mysqli_fetch_all($result,MYSQLI_ASSOC);
        }

        $content_detail=[];
        mysqli_next_result($con);
        $result = mysqli_store_result($con);
        $content_detail = mysqli_fetch_all($result,MYSQLI_ASSOC);
        mysqli_free_result($result);
    }
    ?>

<!DOCTYPE html>
<html>
    <head>
        <title>view promises on me</title>
        <link rel="stylesheet" href='/css_codes/view_my_event_individual_promise.css'>
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        <link href="/css_codes/bootstrap-toggle.css" rel="stylesheet">
    </head>
    <body>
        <script>
            btnPress(4);
        </script>

<div class='promise_body'>
        <div class="promise_table_body">
            <table class='promise_table'>
                <tr class="first_head">
                    <th colspan=4>Promises</th>
                </tr>
                <tr class="second_head">
                    <th colspan=1>Full name </th>   
                    <th colspan=1>Promises</th>
                    <th colspan=1>Status</th>
                    <th colspan=1>Note</th>
                </tr>
                <?php
                    foreach($person_org_detail as $row_req){
                        if ($row_req['org_name']==""){
                            $name=$row_req['first_name']." ".$row_req['last_name'];
                            $by=$row_req['NIC_num'];
                        }
                        else{
                            $name=$row_req['org_name'];
                            $by=$row_req['org_id'];
                        }
                        $note=$row_req['note'];
                        $promise_array=[];
                        $pending_array=[];
                        $full_array=[];
                        foreach($content_detail as $row_req1){
                            if ($row_req1['don_id']==$row_req['id']){
                                $item_amount=$row_req1['item'].":".$row_req1['amount'];
                                if ($row_req1['pro_don']=="promise"){
                                    array_push($promise_array,$item_amount);
                                }else if ($row_req1['pro_don']=="pending"){
                                    array_push($pending_array,$item_amount);
                                }
                                array_push($full_array,$item_amount);
                            }
                        }
                        for($x=0; $x < count($full_array); $x++ ){
                            $value=$full_array[$x];
                            if(in_array($value,$pending_array)){
                                $checked="checked='checked'";}
                            else{
                                $checked="";}
                            if ($x==0){
                                $name_data_row="<td rowspan=".count($full_array).">".$name."</td>";
                                $note_data_row="<td rowspan=".count($full_array).">".$note."</td>";}
                            else{
                                $name_data_row=$note_data_row="";}
                         
                        echo "  <tr onclick='edit_promise(\"/event/help/?event_id=".$event_id."&by=".$by."&to=".$id."\")'>
                            ".$name_data_row."
                            <td>".$value."</td>
                            <td class='not_click'>
                            <input type='checkbox'".$checked."data-toggle='toggle' data-on='Helped' data-off='Not helped' data-width='100' data-height='15' data-offstyle='warning' data-onstyle='success' onchange=''>
                            </td>
                            ".$note_data_row."
                        </tr>";
                
                        }
                    }    
                ?>
            </table>
        </div>
    </div>
    <div class='promise_body'>
        <div class="promise_table_body">
            <table class='promise_table'>
                <tr class="first_head">
                    <th colspan=4> Donations </th>
                </tr>
                <tr class="second_head">
                    <th colspan=1>Full name </th>   
                    <th colspan=1>Donations</th>
                    <th colspan=1>Note</th>
                </tr>
                <?php
                    foreach($person_org_detail as $row_req){
                        if ($row_req['org_name']==""){
                            $name=$row_req['first_name']." ".$row_req['last_name'];
                            $by=$row_req['NIC_num'];
                        }
                        else{
                            $name=$row_req['org_name'];
                            $by=$row_req['org_id'];
                        }
                        $item_amount="";
                        $note=$row_req['note'];
                        foreach($content_detail as $row_req1){
                            if ($row_req1['don_id']==$row_req['id']){
                                if ($row_req1['pro_don']=="donated"){
                                $item_amount=$item_amount.$row_req1['item'].":".$row_req1['amount']."<br>";
                            }
                            }
                        }
                        if ($item_amount!=""){
                        echo "  <td>".$name."</td><td>".$item_amount."</td>
                                    <td>".$note."</td>
                                </tr>";}
                    }   
                ?>
            </table>
        </div>
    </div>
    <?php include $_SERVER['DOCUMENT_ROOT']."/includes/footer.php" ?>

    </body>

    <script>
        function edit_promise(url){
            var target = event.target ? event.target : event.srcElement;
            if(!'not_click toggle btn btn-warning off toggle-group btn btn-success toggle-on btn btn-warning active toggle-off toggle-handle btn btn-default'.includes(target.className)){
                window.location=url;
            }else{
                
            }
        }
    </script>
</html>
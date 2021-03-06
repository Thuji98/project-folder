<?php
    require $_SERVER['DOCUMENT_ROOT']."/staff/header.php";

    $query="select * from civilian_detail";
    $result=($con->query($query))->fetch_assoc();
    $result=$con->query($query);
?>

<title>Member</title>
<link rel="stylesheet" href="/staff/css_codes/member.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script>
    btnPress(2);
</script> 
    
<div class="civi_detail">
    <form action='/staff/view_member.php' method='get'>
        <table class="civilian_table">
            <tr>
                <th colspan="3">
                    <a class="tag" href="create_civilian_acc.php">
                        <i class="fa fa-user-plus" aria-hidden="true" style="font-size:20px;color:#6b7c93;">  Add new member</i>
                    </a>
                </th>
            </tr>
            <tr>
                <th> Full name</th>
                <th> NIC number</th>
                <th> </th>
            </tr>
            <?php 
                while($row=$result->fetch_assoc()){
                    echo "<tr><td><div class='user_img'><img src='/common/documents/Profiles/resized/".$row['NIC_num'].".jpg'/></div>" . $row['first_name']." " .$row['last_name'] ."<button type=submit class=civilian_name name=nic value=". $row['NIC_num'] ."></button></td><td class='nic_row'>" . $row['NIC_num'] . "</td><td><a href='/staff/delete_member.php?nic=".$row['NIC_num']."'><i class='fa fa-trash' style='font-size:16px;color:#6b7c93;' aria-hidden='true'>  Delete</i></a></td></tr>";
                }
            ?>
        </table> 
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/staff/footer.php" ?>
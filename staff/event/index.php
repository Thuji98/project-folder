<?php  
    require $_SERVER['DOCUMENT_ROOT']."/staff/header.php";
    $query="select event_id,name,status from disaster_events";
    $result=$con->query($query);
?>

<title>Events</title>
<link rel="stylesheet" href="/staff/css_codes/events.css">
    
<script>
    btnPress(3);
</script>

<div id=body>
    <div>
        <div class="new_event"> 
            <a class="tag" href="/staff/event/create_event.php">
                <i class="fa fa-calendar" aria-hidden="true" style="font-size:25px;color:black;">  Create new event</i>
            </a>
        </div>
    </div>
    <div>
        <form action='/staff/event/view_event.php' method='get'>
            <div class="event_detail">
                <table class="event_table">
                    <tr>
                        <th> Event name</th>
                        <th> Status</th>
                    </tr>
                    <?php
                        while($row=$result->fetch_assoc()){
                            echo "<tr><td>" . $row['name'] . "<button type=submit class=event_name name=event_id value=". $row['event_id'] ."></button></td><td>" . $row['status'] . "</td></tr>";
                        }
                    ?>
                </table>
            </div>
        </form>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/staff/footer.php" ?>
<?php require $_SERVER['DOCUMENT_ROOT']."/organization/event/org_event_header.php" ?>
        <div id=org_detail_body>
            <div id='org_event_table_caontainer'>
                <div class='org_event_head' colspan=2>
                    Event Detail
                </div>
                <div class='org_event_con'>
                    <table id=org_view_event_table>           
                        <?php
                            echo "<tr><td class='org_view_event_td'>" . ucfirst('Name') . "</td><td id=column2>" . ucfirst($result['name']) . "</td></tr>";
                            echo "<tr><td class='org_view_event_td'>" . ucfirst('Affected districts') . "</td><td id=column2>" . ucfirst($result['affected_districts']) . "</td></tr>";
                            echo "<tr><td class='org_view_event_td'>" . ucfirst('Start date') . "</td><td id=column2>" . ucfirst($result['start_date']) . "</td></tr>";
                            echo "<tr><td class='org_view_event_td'>" . ucfirst('End date') . "</td><td id=column2>" . ucfirst($result['end_date']) . "</td></tr>";
                            echo "<tr><td class='org_view_event_td'>" . ucfirst('Status') . "</td><td id=column2>" . ucfirst($result['status']) . "</td></tr>";
                            echo "<tr><td class='org_view_event_td'>" . ucfirst('Deaths and damages') . "</td><td id=column2>" . ucfirst($result['detail']) . "</td></tr>";
                        ?>
                    </table>
                </div>
            </div>
            <div id=help_requested>
                <div class='org_event_requests' colspan=2>
                    Help Requested People
                </div>
				<?php
                    $query1="select civilian_detail.first_name, civilian_detail.last_name, civilian_detail.NIC_num, event_".$_GET['event_id']."_help_requested.* 
                    from civilian_detail INNER JOIN event_".$_GET['event_id']."_help_requested on civilian_detail.NIC_num = event_".$_GET['event_id']."_help_requested.NIC_num 
                    WHERE event_".$_GET['event_id']."_help_requested.now='yes' 
                    ORDER BY event_".$_GET['event_id']."_help_requested.district, event_".$_GET['event_id']."_help_requested.village, event_".$_GET['event_id']."_help_requested.street";
                    $result1=$con->query($query1);
                    $all_requested = $result1->fetch_all(MYSQLI_ASSOC);
                    //print_r($req_result);

                    $districts = array_unique(array_column($all_requested,'district'));
                    echo "<div class='request_people_div'>";
                        echo "<div class='dis_con_div'>";
                        echo    "<table class='dis_container'>";
                                    foreach($districts as $row_dis){
                                        echo "<tr><td class='dis_table_data'><span class='arrow dis_data' onclick='select_con(this)'><i class='fa fa-caret-down' aria-hidden='true'></i><input type='checkbox' class='help_check'>". $row_dis . "</span><div class='village_div'>";
                                        innerDis($row_dis,$all_requested);
                                        echo "</div></td></tr>";
                                    }
                        echo    "</table>";
                        echo "</div>";
                    echo "</div>";

                    function innerDis($selected_dis,$all_requested){
                        $dis_requested = array_filter(array_map(function($item) use($selected_dis) {return dis_filter($item,$selected_dis);},$all_requested));
                        $villages = array_unique(array_column($dis_requested,'village'));
                        echo "<table class='vil_container org_event_active'>";
                        foreach($villages as $row_village){
                            echo "<tr><td><span class='arrow' onclick='select_con(this)'><i class='fa fa-caret-right' aria-hidden='true'></i><input type='checkbox' class='help_check'>" . $row_village . "</span>";
                            innerVil($row_village,$dis_requested);
                            echo "</td></tr>";
                        }
                        echo "</table>";
                    }

                    function dis_filter($row,$dis){
                        if($row['district'] == $dis){
                            return $row;
                        }
                    }
                    
                    function innerVil($selected_vil,$dis_requested){
                        $vil_requested = array_filter(array_map(function($item) use($selected_vil) {return vill_filter($item,$selected_vil);},$dis_requested));
                        $streets = array_unique(array_column($vil_requested,'street'));
                        echo "<table class='str_container'>";
                        foreach($streets as $row_street){
                            echo "<tr><td><span class='arrow street_data' onclick='select_con(this)'><i class='fa fa-caret-right' aria-hidden='true'></i><input type='checkbox' class='help_check'>" . $row_street . "</span>";
                            innerStr($row_street,$vil_requested);
                            echo "</td></tr>";
                        }
                        echo "</table>";
                    }

                    function vill_filter($row,$vil){
                        if($row['village'] == $vil){
                            return $row;
                        }
                    }

                    function innerStr($selected_str,$vil_requested){
                        $str_requested = array_filter(array_map(function($item) use($selected_str) {return str_filter($item,$selected_str);},$vil_requested));
                        $people = array_map(function($item){return array(($item['first_name'] . " " . $item['last_name']),$item['NIC_num']);},$str_requested);
                        echo "<table class='per_container'>";
                        foreach($people as $person){
                            echo "<tr><td><span><input type='checkbox' class='help_check requester' value='".$person[1]."'>" . $person[0] . "</span></td></tr>";
                        }
                        echo "</table>";
                    }

                    function str_filter($row,$str){
                        if($row['street'] == $str){
                            return $row;
                        }
                    }
                    
                ?>
                <button id="promise_buttton" onclick="promise()">Promise to help</button>
            </div>
        </div>
        <div id=social_events>
            <div id=affected>
                <div class='org_event_requests' colspan=2>
                    Affected people detail
                </div>
            </div>
            <div id=organizations>
                <div class='org_event_requests' colspan=2>
                    Organizations on action
                </div>
            </div>
        </div>
        <div id='help_popup' class="help_popup">
        </div>
        <div id='overlay' class="overlay" onclick="remove_all_popup()">
        </div>
        <script>
            function select_con(element){
                const table_ele = element.parentElement.parentElement.querySelector('table');
                //console.log(table_ele.className)
                var target = event.target ? event.target : event.srcElement;
                if(target.className!='help_check'){
                    table_ele.classList.toggle('org_event_active');
                }else{
                    const all_check = table_ele.getElementsByClassName('help_check');
                    if(element.children[1].checked==true){
                        for(var i = 0; i < all_check.length ; i++){
                            all_check[i].checked=true;
                        }
                    }else{
                        for(var i = 0; i < all_check.length ; i++){
                            all_check[i].checked=false;
                        }
                    }
                }
            }
            const checkboxes = document.getElementsByClassName('help_check');
            for(var i = 0;i<checkboxes.length;i++){
                checkboxes[i].onclick = null;
            }

            function check(element){
                
            }

            function promise(){
                var inputs = document.querySelectorAll("input[class~='requester']:checked");
                var selected = [];
                for (input of inputs){
                    selected.push(input.value);
                }
                window.location.href="promise.php?type=1&event_id=<?php echo $_GET['event_id']?>&org_id=<?php echo $_GET['selected_org'] ?>&selected="+selected.toString();
                /*var help_popup = document.getElementById("help_popup");
                help_popup.classList.toggle("active_popup");
                document.getElementById("overlay").classList.toggle("active_overlay");
                help_popup.innerText=selected;*/
            }
            function remove_all_popup(){
                document.getElementById("help_popup").classList.remove('active_popup');
                document.getElementById("overlay").classList.remove("active_overlay");
            }
        </script>
        <?php require $_SERVER['DOCUMENT_ROOT']."/organization/org_footer.php"; ?>
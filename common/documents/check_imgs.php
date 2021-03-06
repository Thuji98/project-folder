<?php
    //require_once $_SERVER['DOCUMENT_ROOT']."/confi/db_confi.php";
    $root=$_SERVER['DOCUMENT_ROOT'].'/common/documents/';

    check_dir('select NIC_num item from civilian_detail;',['Profiles/','Profiles/resized/','Covers/']);
    check_dir('select org_id item from organizations;',['Organization/Profiles/','Organization/Profiles/resized/','Organization/Covers/']);
    check_dir('select img item from public_posts where img<>"";',['public_posts/']);
    check_dir('select img item from goveposts where img<>"";',['gov_posts/']);
    check_dir('select event_id item from disaster_events;',['Event/','Event/resized/']);
    check_dir('select id item from fundraisings;',['Fundraising/', 'Fundraising/resized/']);

    check_multiple('select img item from fundraisings;',['Fundraising/secondary/']);
    check_multiple('select img item from disaster_events;',['Event/secondary/']);

    function check_multiple($query, $folders){
        global $con;
        $imgs=[];
        $result=$con->query($query);
        while($row=$result->fetch_assoc()){
            $tem=array_filter(explode(',',$row['item']));
            foreach($tem as $x){
                array_push($imgs, ['item'=>$x]);
            }
        }
        check_dir($imgs,$folders);
    }

    function check_dir($query, $folders){
        global $con;
        global $root;
        $data=[];
        if(is_string(($query))){
            $result = mysqli_query($con, $query);
            while($row=$result->fetch_assoc()){
                if($row['item']!=''){
                    array_push($data,$row['item']);
                    foreach($folders as $folder){
                        if( !file_exists($root.$folder.$row['item'].'.jpg') ){
                            copy($root.$folder.'default.jpg', $root.$folder.$row['item'].'.jpg');
                            echo '<br>'.$root.$folder.$row['item'].'.jpg';
                        }
                    }
                }
            }
        }else{
            while($row=next($query)){
                if($row['item']!=''){
                    array_push($data,$row['item']);
                    foreach($folders as $folder){
                        if( !file_exists($root.$folder.$row['item'].'.jpg') ){
                            copy($root.$folder.'default.jpg', $root.$folder.$row['item'].'.jpg');
                            echo '<br>'.$root.$folder.$row['item'].'.jpg';
                        }
                    }
                }
            }
        }
        foreach($folders as $folder){
            echo '<h5>'.$folder.'</h5>';
            $files=array_filter(array_map( function($v){
                return explode('.',$v)[0];
            }, scandir($root.$folder)), function($item){
                global $root;
                global $folder;
                return (!is_dir($root.$folder . $item) and $item!='default' and $item!='resized' and $item!='secondary' and $item!='next_img_name' and $item!='upload_fundraising' and $item!='upload_event');
            });
            $extra = array_diff($files, $data);
            foreach($extra as $e)unlink($root.$folder.$e.'.jpg');
            print_r($extra);
        }
    }
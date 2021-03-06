<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/confi/db_confi.php";
    require_once $_SERVER['DOCUMENT_ROOT']."/confi/verify.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="https://kit-free.fontawesome.com/releases/latest/css/free.min.css" rel="stylesheet">
        <link rel="stylesheet" href="/css_codes/header.css">
        <link rel='stylesheet' type='text/css' href='/css_codes/side_nav.css'>
        <link rel="shortcut icon" href="/common/logo.png" type="image/x-icon" /> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <meta name="description" content="Disaster And Crisis Assistance">
        <meta name="keywords" content="disaster,srilanka,help,emergency,volunteer,flood,strom">
        <meta name="author" content="Salus Team">
        <script src='/js/font_awesome.js' defer></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src='/common/autocomplete/auto_complete.js'></script>
        <link rel='stylesheet' type='text/css' href='/common/autocomplete/auto.css'>
    </head>

    <body>
        <div id="wrapper_container">
            <div class="wrapper">
                <div class="circle"></div>
                <div class="circle"></div>
                <div class="circle"></div>
                <div class="shadow"></div>
                <div class="shadow"></div>
                <div class="shadow"></div>
                <span>Loading</span>
            </div>
        </div>
            <div id="titlebar">
                <span id="dcaname">Disaster and Crisis Assistance</span>
            </div>

            <div>
                <div class="logo_box">
                    <div class="logo"><img src="/common/logo.png" alt="logo" class="logo"></div>
                </div>
                <div id="menubar">
                <div class='menubar_icons'>
                    <!-- <a href="/home_page.php"> <div class="menubar_buttons" id='menu_bar_btn_1'>Home</div> </a> -->
                    <a href="/govpost" class="menubar_buttons_cont"> <div type="submit" class="menubar_buttons"><div class="menu_icon_tooltip"><i class='fas fa-university menu_icon menu_bar_btn_2'></i><span class="menu_icon_tooltiptext">Goverment post</span></div></div> </a>
                    <a href="/publicpost" class="menubar_buttons_cont"><div class="menubar_buttons" ><div class="menu_icon_tooltip"><i class='fas fa-book-reader menu_icon menu_bar_btn_3'></i><span class="menu_icon_tooltiptext">Public post</span></div></div></a>
                    <div class='menubar_button_container menubar_buttons_cont'><div class="menubar_buttons" onclick='showevent(this)'><div class="menu_icon_tooltip"><i class='far fa-calendar-alt menu_icon menu_bar_btn_4'></i><span class="menu_icon_tooltiptext">Events</span></div></div><div id=event_container></div></div>
                    <div class='menubar_button_container menubar_buttons_cont'><div type="submit" class="menubar_buttons" name='menubar_buttons'  value=6 onclick='show_org(this)'><div class="menu_icon_tooltip"><i class='fas fa-users menu_icon menu_bar_btn_6'></i><span class="menu_icon_tooltiptext">Organization</span></div></div><div id=menubar_org_container></div></div>
                    <a href="/fundraising" class="menubar_buttons_cont"><div class="menubar_buttons" ><div class="menu_icon_tooltip"><i class='fas fa-hand-holding-heart menu_icon menu_bar_btn_7'></i><span class="menu_icon_tooltiptext">Fundraising</span></div></div></a>
                    <a href="/message" class="menubar_buttons_cont">
                        <div type="submit" class="menubar_buttons" >
                            <div class="menu_icon_tooltip">
                                <i class='fas fa-comments menu_icon menu_bar_btn_8'></i>
                                <span class="menu_icon_tooltiptext">Chat</span>
                                <span class='header_indicator_8 header_indicator empty_indicator'></span>
                            </div>
                        </div>
                    </a>
                    <div class='menubar_button_container menubar_buttons_cont' >
                        <div class="menubar_buttons"  onclick="show_notification(this)">
                            <div class="menu_icon_tooltip">
                                <i class='fas fa-bell menu_icon menu_bar_btn_5'></i>
                                <span class="menu_icon_tooltiptext">Notification</span>
                                <span class='header_indicator_5 header_indicator empty_indicator'></span>
                            </div>
                        </div>
                        <div id=notification_container>
                        </div>
                    </div>
</div>
                    <div id='header_search_box_cont'>
                        <div id='header_search_box'>
                            <div id='search_icon'><i class="fa fa-search" aria-hidden="true"></i></div>
                            <input id='header_search_box_inp' type='text' placeholder='Search here'>
                        </div>
                    </div>

                    <div class='dropdown_cont'><div  class="menubar_buttons dropdown_btn" onclick='show_dropdown(this)'><div class="menu_icon_tooltip">
                                <i class="fa fa-caret-down" style="font-size:30px"></i>
                                <span class="menu_icon_tooltiptext">More</span>
                            </div>
                        </div>                 

                        <div id=dropdown_container class='dropdown_container'>
                            <a href="/home_page.php"  class="header_dropdown_item username">
                                <div class='header_dropdown_profile dropdown_profile_image'>
                                    <img src='/common/documents/Profiles/resized/<?php echo $_SESSION['user_nic']?>.jpg' />
                                </div>
                                <div class='header_dropdown_profile'>
                                    <div >
                                        <?php 
                                        echo  $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
                                        
                                        ?>
                                    </div>
                                </div>
                            </a>
                            <div class='header_horizontal_line'></div>
                            <div class="header_dropdown_item header_dropdown_toggle">
                                <div class="toggle_btn_text" > Show/Hide User Detail </div>
                                <div class="dropdown_toggle_btn" >
                                    <label class="switch">
                                        <input type="checkbox" id='dropdown_toggle_checkbox' <?php echo ($_SESSION['side_nav']=='1')?'checked':''; ?>>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class='header_subline'></div>
                            <div class="header_dropdown_item ">
                                <a class='logout_btn' href="/update_cd.php" >Edit Profile</a>
                            </div>
                            <div class='header_subline'></div>
                            <div class="header_dropdown_item " > 
                                <a href="/about.php"  class="logout_btn" >About</a>
                            </div>
                            <div class='header_subline'></div>
                            <div class="header_dropdown_item ">
                                <a class='logout_btn' href="/logs/logout.php" >Logout</a>
                            </div>
                        </div>
                        <div id='main_overlay' onclick='hide_dropdown()'></div>
                    </div>

                    <!-- <a href=""> <button type="submit" class="menubar_buttons" id='menu_bar_btn_8'>About</button> </a> -->
                </div>
            </div>

        <script src="/js/header.js"></script>
        <script>
            autocomplete_ready(document.getElementById("header_search_box_inp"), 'all', 'inp', 'click');
        </script>
        <div id='main_body'>
	        <div id='sub_body' class='sub_body <?php echo (($_SESSION['side_nav']=='1')?'':'full_sub_body'); ?>'>
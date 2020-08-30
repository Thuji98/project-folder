<?php
ob_start(); 
require $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php";
$for_opt = "00";
$fundraising_name = $org_name = $for_event = $for_any = $service_area = $description = $org_id = "";
$item = $amount = [];
$district = "";
require $_SERVER['DOCUMENT_ROOT'] . '/fundraising/create_fundraising_php.php';
$query = "select * from org_members inner join organizations on organizations.org_id = org_members.org_id where (role='leader' or role='coleader') and NIC_num='" . $_SESSION['user_nic'] . "';
    select * from disaster_events;";
if (mysqli_multi_query($con, $query)) {
    $result = mysqli_store_result($con);
    $org_result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);

    mysqli_next_result($con);
    $result = mysqli_store_result($con);
    $event_name_result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
}
$district = explode(",", $district);
?>
<title>Create new fundraising event</title>
<link rel="stylesheet" href="/css_codes/create_fundraising.css">
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>

</head>

<script>
    btnPress(7);
</script>

<form method='post' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' ng-app="" name="createFundraisingForm" id='createFundraisingForm' novalidate ng-init="fundraisingName='<?php echo $fundraising_name ?>';">
    <div id="main_fund_form_body">
        <div class="form_header_div">Create a new fundraising event</div>


        <div id='sub_fund_form_body'>
            <div class="fund_name_div">
                <label class="create_fund_label" for='fundraising_name'>Fundraising event name</label>
                <div>
                    <div ng-class="{'has-error': (createFundraisingForm.fundraising_name.$invalid && createFundraisingForm.fundraising_name.$touched)}">
                        <input class="create_fund_input" type='text' id="fun_name" name="fundraising_name" ng-model="fundraisingName" required>
                    </div>
                    <span class='error'><?php echo $nameErr; ?></span>
                    <span class='error' data-ng-show="createFundraisingForm.fundraising_name.$invalid && createFundraisingForm.fundraising_name.$touched"><i class='fas fa-exclamation-circle'></i> Name Required</span>
                </div>
            </div>

            <div class="sel_org_div">
                <label class="create_fund_label" for='organization'>Select organization</label>
                <div class="custom-select" style="width:200px;line-height=1">
                    <select class="create_fund_select" name="organization" id="org_id">
                        <option value=''>Not organization based</option>
                        <?php
                        foreach ($org_result as $row) {
                            echo "<option value='" . $row["org_id"] . "'";
                            if ($row["org_id"] == $org_id) {
                                echo 'selected';
                            }
                            echo  ">" . $row["org_name"] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>


            <div class="purpose_div">
                <label class="create_fund_label" for='event'>Select purpose</label>
                <?php
                if ($for_opt == '00' || $for_opt == '1') {
                    $for_event_check = 'checked';
                    $for_any_style = 'display:none';
                    $for_event_style = 'display:block';
                    $other_purp = '';
                } else {
                    $for_event_check = '';
                    $for_event_style = 'display:none';
                    $other_purp = 'checked';
                    $for_any_style = 'display:block';
                }

                ?>
                <div class="radio_btn_div">
                    <div class="for_event_div">
                        <input type="hidden" id="purp" name="purp" value="00" />
                        <input type="radio" name="purpose" id="purpose" value='' <?php echo $for_event_check ?> onclick='purposeFun()'>
                        <label class="create_fund_label" for="purpose">For event</label>
                        <div id="for_event" style='<?php echo $for_event_style; ?>'>
                            <div class="custom-select" style="width:200px;">
                                <select class="create_fund_select" name="for_event">
                                    <option value='0'>Select Event</option>
                                    <?php
                                    foreach ($event_name_result as $row) {
                                        echo "<option value='" . $row["event_id"] . "'";
                                        if ($row["event_id"] == $for_event) {
                                            echo 'selected';
                                        }
                                        echo  ">" . $row["name"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div>
                                <span class='error'><?php echo $noSelEveErr ?></span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <input type="radio" name="purpose" id='other_purpose_opt' value='' <?php echo $other_purp ?> onclick='purposeFun()'>
                        <label class="create_fund_label" for="other_purpose_opt">Other purpose</label>
                        <input class="create_fund_input" type='text' name='other_purpose' id='other_purpose' style='<?php echo $for_any_style; ?>' value='<?php echo $for_any; ?>'>
                    </div>
                </div>

            </div>
            <div class="expects_div">
                <label class="create_fund_label" for='type'>Your Expectations</label>
                <?php
                for ($x = 0; $x < count($item); $x++) {
                    echo "<div class=\"input_sub_container\" id='add_i'>";
                    echo "<input type='text' name='item[]' class='text_input_fund' value='" . $item[$x] . "'>";
                    echo "<input type='text' name='amount[]' class='text_input_fund' value='" . $amount[$x] . "' >";
                    echo "<button type='button' onclick='remove_input(this)' class='add_rem_btn'>Remove</button>";
                    echo "</div>";
                }
                ?>
                <div class="input_container" id=add_i>
                    <div class="input_sub_container">
                        <input type="text" class="text_input_fund" name="item[]" placeholder="item">
                        <input type="text" class="text_input_fund" name="amount[]" placeholder="amount">
                        <button type="button" onclick="add_input(this)" class="add_rem_btn">Add</button>
                    </div>
                </div>
            </div>

            <div class="service_area_div">
                <label class="create_fund_label" name="service_area">Service area</label>
                <div class="dropdown">
                    <button type="button" onclick="show_menu()" class="dropbtn">District/s</button>
                    <div id="myDropdown" class="dropdown-content drp">
                        <?php
                        $district_arr = array(
                            'Ampara', 'Anurashapura', 'Badulla', 'Batticaloa', 'Colombo', 'Galle', 'Gampha', 'Hambatota', 'Jaffna', 'Kaltura', 'Kandy',
                            'Kegalle', 'Kilinochchi', 'Kurunegala', 'Mannar', 'Matale', 'Mathara', 'Moneragala', 'Mullaitivu', 'Nuwara-Eliya', 'Polonnaruwa', 'Puttalam',
                            'Ratnapura', 'Tricomalee', 'Vavuniya');
                        foreach ($district_arr as $dis) {
                            echo "<a class='drp' data-value='$dis' onclick='{this.firstElementChild.firstElementChild.toggleAttribute(\"checked\")}'>";
                            echo "<label class=\"container drp\">$dis";
                            if ($district != '') {
                                if (in_array($dis, $district)) {
                                    echo "<input type=\"checkbox\" class=\"drp\" name=\"district[]\" value=\"$dis\" checked=\"checked\">
                                                <span class=\"checkmark drp\"></span>
                                                </label>
                                            </a>";
                                } else {
                                    echo "<input type=\"checkbox\" class=\"drp\" name=\"district[]\" value=\"$dis\" >
                                            <span class=\"checkmark drp\"></span>
                                            </label>
                                            </a>";
                                }
                            } else {
                                echo "<input type=\"checkbox\" class=\"drp\" name=\"district[]\" value=\"$dis\" >
                                            <span class=\"checkmark drp\"></span>
                                            </label>
                                            </a>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="descrip_div">
                <label class="create_fund_label">Description</label>
                <textarea class="create_fund_textarea" name='description' id="description"><?php echo $description; ?></textarea>
            </div>

        </div>
        <div class="create_fund_btn_container">
            <button type='submit' name='submitBtn' class="create_fund_submit_btn" id='submitBtn'>Submit</button>
            <a href="<?php echo $_SERVER['HTTP_REFERER'] ?>"><button type='button' name='cancelBtn' class="create_fund_cancel_btn" id='submitBtn'>Cancel</button></a>
        </div>

    </div>
</form>
<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php" ?>

<script>
    function purposeFun() {
        if (document.getElementById("other_purpose_opt").checked) {
            document.getElementById('other_purpose').style.display = 'block'
            document.getElementById('for_event').style.display = 'none'
            document.getElementById("purp").value = "2";

        } else {
            document.getElementById('other_purpose').style.display = 'none'
            document.getElementById('for_event').style.display = 'block'
            document.getElementById("purp").value = "1";

        }
    }

    function add_input(element) {
        var parent = element.parentElement.parentElement;
        if (element.parentElement.children[0].value !== '' || element.parentElement.children[1].value !== '') {
            for (var ele of parent.children) {
                ele.children[0].setAttribute("value", ele.children[0].value);
                ele.children[1].setAttribute("value", ele.children[1].value);
                ele.children[2].outerHTML = "<button type='button' onclick='remove_input(this)' class='add_rem_btn'>Remove</button>"
            }
            parent.innerHTML += '<div class="input_sub_container">\n' +
                '        <input type="text" name="item[]" class="text_input_fund" placeholder="item">\n' +
                '        <input type="text" name="amount[]" class="text_input_fund" placeholder="amount">\n' +
                '        <button type="button" onclick="add_input(this)" class="add_rem_btn">Add</button>\n' +
                '    </div>';
        }
    }

    function remove_input(element) {
        element.parentElement.outerHTML = '';
    }

    function show_menu() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!(event.target.matches('.dropbtn') || event.target.matches('.drp'))) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }


    var module = angular.module("createFundraisingForm", []);

    module.controller("formCtrl", ['$scope', function($scope) {

        $scope.data = {};

    }]);




    //         Custom select           //

    var x, i, j, l, ll, selElmnt, a, b, c;
    /*look for any elements with the class "custom-select":*/
    x = document.getElementsByClassName("custom-select");
    l = x.length;
    for (i = 0; i < l; i++) {
        selElmnt = x[i].getElementsByTagName("select")[0];
        ll = selElmnt.length;
        /*for each element, create a new DIV that will act as the selected item:*/
        a = document.createElement("DIV");
        a.setAttribute("class", "select-selected");
        a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
        x[i].appendChild(a);
        /*for each element, create a new DIV that will contain the option list:*/
        b = document.createElement("DIV");
        b.setAttribute("class", "select-items select-hide");
        for (j = 1; j < ll; j++) {
            /*for each option in the original select element,
            create a new DIV that will act as an option item:*/
            c = document.createElement("DIV");
            c.innerHTML = selElmnt.options[j].innerHTML;
            c.addEventListener("click", function(e) {
                /*when an item is clicked, update the original select box,
                and the selected item:*/
                var y, i, k, s, h, sl, yl;
                s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                sl = s.length;
                h = this.parentNode.previousSibling;
                for (i = 0; i < sl; i++) {
                    if (s.options[i].innerHTML == this.innerHTML) {
                        s.selectedIndex = i;
                        h.innerHTML = this.innerHTML;
                        y = this.parentNode.getElementsByClassName("same-as-selected");
                        yl = y.length;
                        for (k = 0; k < yl; k++) {
                            y[k].removeAttribute("class");
                        }
                        this.setAttribute("class", "same-as-selected");
                        break;
                    }
                }
                h.click();
            });
            b.appendChild(c);
        }
        x[i].appendChild(b);
        a.addEventListener("click", function(e) {
            /*when the select box is clicked, close any other select boxes,
            and open/close the current select box:*/
            e.stopPropagation();
            closeAllSelect(this);
            this.nextSibling.classList.toggle("select-hide");
            this.classList.toggle("select-arrow-active");
        });
    }

    function closeAllSelect(elmnt) {
        /*a function that will close all select boxes in the document,
        except the current select box:*/
        var x, y, i, xl, yl, arrNo = [];
        x = document.getElementsByClassName("select-items");
        y = document.getElementsByClassName("select-selected");
        xl = x.length;
        yl = y.length;
        for (i = 0; i < yl; i++) {
            if (elmnt == y[i]) {
                arrNo.push(i)
            } else {
                y[i].classList.remove("select-arrow-active");
            }
        }
        for (i = 0; i < xl; i++) {
            if (arrNo.indexOf(i)) {
                x[i].classList.add("select-hide");
            }
        }
    }
    /*if the user clicks anywhere outside the select box,
    then close all select boxes:*/
    document.addEventListener("click", closeAllSelect);

    // $('form').attr("ng-app","");
    // $('form').attr("name","myForm");
    // $('form input[type="email"]').attr("ng-model","text");


    $(".checkbox-menu").on("change", "input[type='checkbox']", function() {
        $(this).closest("li").toggleClass("active", this.checked);
    });

    $(document).on('click', '.allow-focus', function(e) {
        e.stopPropagation();
    });
</script>
<?php ob_end_flush();
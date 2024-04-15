<?php

include('config.php');
include('session.php');
require_once('inc/functions.php');
// ini_set('display_errors', 1); 
//  ini_set('display_startup_errors', 1); error_reporting(E_ALL);

// print_r($_SESSION) ;
$manager_id = $_SESSION['user_id'];
$id = $_GET['edit'];



if (isset($_POST['subbtnn'])) {
    // echo '<pre>';
    // print_r($_REQUEST);
    // print_r($_POST['website']);
    // echo '</pre>';


    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];

    if ($fname == "" && $lname == "" && $phone == "") {

        $_SESSION['error'] = "all fields are not empty!";
    } else {

        $admin_users_data1 = getTableData($conn, " admin_users ", " id ='" . $id . "' ");
            if (count($admin_users_data1) > 0) {
                $first__name1 = $admin_users_data1['firstname'];
                $last__name1 = $admin_users_data1['lastname'];
                $phone_admin1 = $admin_users_data1['phone'];
                // $idss=$admin_users_data['id'];
                // echo $phone_admin ;

            }

        if (strlen($phone) >= 10 && strlen($phone) < 20) {
            $check_num = "SELECT * FROM `admin_users` WHERE `phone` LIKE '$phone'";
            $check__result = $conn->query($check_num);
            $phone_num="";
            if ($phone_admin1==$phone) {
            $phone_num=$phone_admin1;
            // echo "phone_num".$phone_num."<br>";
            // echo $phone_admin1;
            // die();
            }
            if ($check__result->num_rows <= 0 || $phone_num!="") {
                $update1 = "update admin_users set `firstname`='$fname', `lastname`='$lname', `phone`='$phone'  where id='$id'";
                // echo "update1:".$update1."<br>";

                $done = mysqli_query($conn, $update1);
                $delete = "DELETE FROM `team_access` WHERE team_id='$id'";
                // echo "delete ".$delete;
                if ($delete_data = mysqli_query($conn, $delete)) {



                    $website = $_POST['website'];




                    for ($i = 0; $i < count($website); $i++) {


                        $dashboard = $website[$i]["dashboard"];
                        // $exhelp = $website[$i]['exhelp'];
                        $speed_tracking = $website[$i]['speed_tracking'];
                        $speed_warranty = $website[$i]['speed_warranty'];
                        $speedy_experts = $website[$i]['speedy_experts'];
                        $pgsped = $website[$i]["pgsped"];
                        $scinst = $website[$i]["scinst"];
                        // $nohelp =$website[$i]["nohelp"];
                        $websites = $website[$i]['id'];


                        // $insertt2 = "INSERT INTO `team_access`(`team_id`, `website_id`, `dashboard`, `expert_help`,`speed_tracking`,`speed_warranty`,`page_speed`, `script_install`, `need_other`) VALUES ('$id','$websites','$dashboard','$exhelp','$speed_tracking','$speed_warranty','$pgsped','$scinst','$nohelp')";
                        $insertt2 = "INSERT INTO `team_access`(`team_id`, `website_id`, `dashboard`,`speed_tracking`,`speed_warranty`,`page_speed`, `script_install`) VALUES ('$id','$websites','$dashboard','$speed_tracking','$speed_warranty','$pgsped','$scinst')";

                        // echo "insertt2 ".$insertt2."<br>";
                        $done2 = mysqli_query($conn, $insertt2);
                    }
                }

                if ($done == true || $done2 == true) {

                    $_SESSION['success'] = "Data Updated successfully!";
                    header( "refresh:2;url=".HOST_URL."adminpannel/manager_settings.php?active=teams" );
                    die();
                } else {

                    $_SESSION['error'] = "Data Not Updated!";
                }
            } else {
                $_SESSION['error'] = "phone number already exists !";
            }
        } else {
            $_SESSION['error'] = "Your phone number Must Contain At Least 10 digits !";
        }
    }
}



$admin_users_data = getTableData($conn, " admin_users ", " id ='" . $id . "' ");
if (count($admin_users_data) > 0) {
    $first__name = $admin_users_data['firstname'];
    $last__name = $admin_users_data['lastname'];
    $phone_admin = $admin_users_data['phone'];
    // $idss=$admin_users_data['id'];
    // echo $phone_admin ;

}


?>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Admin Dashboard</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <?php require_once('inc/style-and-script.php'); ?>
    <style type="text/css">
        #getcsv {
            float: right;
            margin-bottom: 1em;
        }

        .custom-tabel .display {
            padding - top: 20px;
        }

        .custom-tabel .display th {
            min - width: 50px;
        }

        table.display.dataTable.no-footer {
            width: 1600px !important;
        }

        .Payment_method input {
            width: 100%;
            margin-bottom: 20px;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .Payment_method label {
            margin - bottom: 10px;
            display: block;

        }


        .payment_method_btn_wrap {
            width: 10%;
        }

        .text-h {
            font - size: 25px;
            text-align: center;
        }

        /* The Modal (background) */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            padding-top: 100px;
            /* Location of the box */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: auto;
            padding: 0;
            border: 1px solid #888;
            width: 80%;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            -webkit-animation-name: animatetop;
            -webkit-animation-duration: 0.4s;
            animation-name: animatetop;
            animation-duration: 0.4s
        }

        /* Add Animation */
        @-webkit-keyframes animatetop {
            from {
                top: -300px;
                opacity: 0
            }

            to {
                top: 0;
                opacity: 1
            }
        }

        @keyframes animatetop {
            from {
                top: -300px;
                opacity: 0
            }

            to {
                top: 0;
                opacity: 1
            }
        }

        /* The Close Button */
        .close {
            color: white;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-header {
            padding: 2px 16px;
            background-color: #5cb85c;
            color: white;
        }

        .modal-body {
            padding: 2px 16px;
        }

        .modal-footer {
            padding: 2px 16px;
            background-color: #5cb85c;
            color: white;
        }
    </style>
</head>

<body class="custom-tabel">
    <div class="d-flex" id="wrapper">
    <div class="top-bg-img"></div>
        <?php require_once("inc/sidebar.php"); ?>

        <!-- Page content wrapper-->
        <div id="page-content-wrapper">

            <?php require_once("inc/topbar.php"); ?>

            <!-- Page content-->
            <div class="container-fluid edit_teams content__up">
                <h1>Edit Teams</h1>
                <div class="back_btn_wrap ">
                    <a href="<?= HOST_URL ?>adminpannel/manager_settings.php?active='team'" class="Polaris-Button">
                        <button type="button" class="back_btn btn btn-primary ">Back</button>
                    </a>
                </div>
                <div class="form_h">
                    <?php require_once("inc/alert-status.php"); ?>
                    <div class="Payment_method_wrap">
                        <form method="POST">
                            <div class="form-group">
                                <label for="name">First Name</label>
                                <input type="text" class="form-control" value=" <?= $first__name;  ?>" name="fname">
                            </div>
                            <div class="form-group">
                                <label for="cpassword">Last Name</label>
                                <input type="text" class="form-control" value=" <?= $last__name;  ?>" name="lname"></td>
                            </div>
                            <div class="form-group">
                                <label for="number">Phone</label>
                                <input type="number" class="form-control" value="<?= $phone_admin; ?>" maxlength="20" id="ph___number" name="phone">
                            </div>
                            <?php

                            // $sele="select * from boost_website where manager_id='$manager_id'";
                            // $sele_qr=mysqli_query($conn,$sele);

                            ?>

                            <div class="addtean_web">

                                <?php
                                // while($run=mysqli_fetch_array($sele_qr))
                                // $run=mysqli_fetch_array($sele_qr);
                                $run = getTableData($conn, " boost_website ", " manager_id ='" . $manager_id . "'", "", 1);
                                for ($i = 0; $i < count($run); $i++) {

                                    // }
                                    // {
                                    $team_data = getTableData($conn, " team_access ", " website_id ='" . $run[$i]['id'] . "'AND team_id='" . $id . "'   ");
                                    // print_r($team_data);


                                ?>
                                    <div class="card">
                                        <div class="body-card">
                                            <div class="form-check" id="group1" required>

                                                <input hidden class="form-check-input position-static website" type="text" name="website[<?= $i ?>][id]" value="<?php echo $run[$i]['id']; ?>">
                                                <h4><?php echo parse_url($run[$i]['website_url'])['host']; ?></h4>


                                            </div>

                                            <div class="form-check">
                                                <input id="dashboard_s<?= $i ?>" class="form-check-input position-static" <?php if ($team_data['dashboard'] == 1) {
                                                                                                                            echo "checked";
                                                                                                                        } ?> type="checkbox" class="group1" name="website[<?= $i ?>][dashboard]" value="1">
                                                <label for="dashboard_s<?= $i ?>">Dashboard</label>
                                            </div>

                                            <!-- <div class="form-check">
            <input id="expert_help<?= $i ?>" class="form-check-input position-static" <?php if ($team_data['expert_help'] == 1) {
                                                                                        echo "checked";
                                                                                    } ?>  type="checkbox" class="group1" name="website[<?= $i ?>][exhelp]" value="1" >
            <label for="expert_help<?= $i ?>">Expert Help</label>
        </div> -->
                                            <div class="form-check">
                                                <input id="expert_help<?= $i ?>" class="form-check-input position-static" <?php if ($team_data['speed_tracking'] == 1) {
                                                                                                                            echo "checked";
                                                                                                                        } ?> type="checkbox" class="group1" name="website[<?= $i ?>][speed_tracking]" value="1">
                                                <label for="expert_help<?= $i ?>">Speed Tracking</label>
                                            </div>
                                            <div class="form-check">
                                                <input id="expert_help<?= $i ?>" class="form-check-input position-static" <?php if ($team_data['speed_warranty'] == 1) {
                                                                                                                            echo "checked";
                                                                                                                        } ?> type="checkbox" class="group1" name="website[<?= $i ?>][speed_warranty]" value="1">
                                                <label for="expert_help<?= $i ?>">Speed Warranty</label>
                                            </div>
                                            <div class="form-check">
                                                <input id="page_speed_s<?= $i ?>" class="form-check-input position-static" <?php if ($team_data['page_speed'] == 1) {
                                                                                                                                echo "checked";
                                                                                                                            } ?> type="checkbox" class="group1" name="website[<?= $i ?>][pgsped]" value="1">
                                                <label for="page_speed_s<?= $i ?>">Page Speed</label>
                                            </div>
                                            <div class="form-check">
                                                <input id="scripti_s<?= $i ?>" class="form-check-input position-static" <?php if ($team_data['script_install'] == 1) {
                                                                                                                            echo "checked";
                                                                                                                        } ?> type="checkbox" class="group1" name="website[<?= $i ?>][scinst]" value="1">
                                                <label for="scripti_s<?= $i ?>">script Installation</label>
                                            </div>
                                            <!-- <div class="form-check">
            <input id="needoh<?= $i ?>"class="form-check-input position-static" <?php if ($team_data['need_other'] == 1) {
                                                                                    echo "checked";
                                                                                } ?>  type="checkbox" class="group1" name="website[<?= $i ?>][nohelp]" value="1" >
            <label for="needoh<?= $i ?>">Need Other Help</label>
        </div> -->

                                        </div>
                                    </div>
                                <?php
                                }
                                ?>


                            </div>
                            <div class="form_h_submit">
                                <button type="submit" name="subbtnn" class="btn btn-primary text-center mt-1">Update</button>
                            </div>
                        </form>


                    </div>

                </div>
            </div>








        </div>
    </div>
    </div>

<script type="text/javascript">
       $('#ph___number').on('keypress change blur', function () {
        $(this).val(function (index, value) {
            return value.replace(/[^a-z0-9]+/gi, '').replace(/(.{20})/g, '$1 ');
        });
    });
</script>
</body>

</html>
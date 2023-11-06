<?php include('functions_new.php');
if (!isset($_SESSION['partner_id'])) {
    echo '<script>window.location="signin"</script>';
    exit();
}


//$question = get_site_setting_data();


$select_val = '25';
$select_val_sub = 28;
$vendor_id = $_SESSION['partner_id'];
$vendor_rowid =  $_SESSION['partner_rowid'];
include('company_dtl.php');
include('php_libs/funtion_lib.php');



?>
<!DOCTYPE html>

<html lang="en">
<!--<![endif]  BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <title><?php echo $company_name_title ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="<?php echo $company_name_title ?>" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <?php include('include/head_links.php'); ?>
    <script src="js/jquery.js" type="text/javascript"></script>

    <script>
        $(document).ready(function() {


            $('#load_deals_data').html('<div align="center">Please wait while uploading..<img src="images/spin.gif"/></div>');
            $('#load_deals_data').load('ajaxpageloading/load_deals_data');
        });

        function deleteBox(id, type, col) {
            if (confirm("Are you sure you want to delete this record?")) {
                //alert('adsads');
                var dataString = 'id=' + id;
                $("#flash_" + id).show();
                $("#flash_" + id).fadeIn(400).html('<img src="image/loading.gif" /> ');
                $.ajax({
                    type: "POST",
                    url: "del-account.php",
                    data: {
                        id: id,
                        type: type,
                        col: col
                    },
                    cache: false,
                    success: function(result) {
                        //alert(result);
                        if (result) {
                            $("#flash_" + id).hide();
                            // if data delete successfully
                            if (result == 'success') {
                                //Check random no, for animated type of effect
                                var randNum = Math.floor((Math.random() * 100) + 1);
                                if (randNum % 2 == 0) {
                                    // Delete with slide up effect
                                    $("#list_" + id).slideUp(1000);
                                } else {
                                    // Just hide data
                                    $("#list_" + id).hide(500);
                                }

                            } else {
                                var errorMessage = result.substring(position + 2);
                                alert(errorMessage);
                            }
                        }
                    }
                });
            }
        }

        function SavingAddDealsData(id, form, btn, ajaxpage) {

            //alert('adf');
            $('#load_errors').html('<div align="center">Please wait saving data..<img src="../assets/spin.gif"/></div>');
            var rizwan = document.getElementById(form);


            $('#' + btn).prop('disabled', true);
            $.ajax({
                type: "POST",
                url: ajaxpage,
                data: new FormData(rizwan), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false,
                success: function(result) {
                    //alert(result);
                    if (result == 1) {
                        $('#load_errors').html('<div class="alert alert-success">Saved !!!</div>');
                        //$(".alert").slideDown('slow').delay(3000).slideUp('slow');
                        $('#' + btn).prop('disabled', false);
                        $("#basic").modal('toggle');
                        $('#load_deals_data').load('ajaxpageloading/load_deals_data');
                        //window.location = redirect; 

                    } else {

                        $('#load_errors').html('<div class="alert alert-danger"><strong>Invalid Errors:<br></strong>' + result + '</div>');
                        $('#' + btn).prop('disabled', false);

                    }




                }

            });
            //}));


        }


        function SavingEditDealsData(id, form, btn, ajaxpage) {

            //alert('adf');
            $('#load_errors').html('<div align="center">Please wait saving data..<img src="../assets/spin.gif"/></div>');
            var rizwan = document.getElementById(form);


            $('#' + btn).prop('disabled', true);
            $.ajax({
                type: "POST",
                url: ajaxpage,
                data: new FormData(rizwan), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false,
                success: function(result) {
                    //alert(result);
                    if (result == 1) {
                        $('#load_errors').html('<div class="alert alert-success">Saved !!!</div>');
                        //$(".alert").slideDown('slow').delay(3000).slideUp('slow');
                        $('#' + btn).prop('disabled', false);
                        $("#basic_edit").modal('toggle');
                        $('#load_deals_data').load('ajaxpageloading/load_deals_data');
                        //window.location = redirect; 

                    } else {

                        $('#load_errors').html('<div class="alert alert-danger"><strong>Invalid Errors:<br></strong>' + result + '</div>');
                        $('#' + btn).prop('disabled', false);

                    }




                }

            });
            //}));


        }

        function GetSPDates() {

            var vehicle_id = $("#vehicle_id").val();

            $.ajax({
                type: "POST",
                url: "ajaxpageloading/ajax",
                data: {
                    GetSP_date: 'yes',
                    vehicle_id: vehicle_id
                },
                success: function(result) {
                    var parts = result.split('##');

                    $("#start_date").val(parts[0]);
                    $("#end_date").val(parts[1]);

                }

            });

        }


        function calcTime(city, offset) {

            // create Date object for current location
            d = new Date();

            // convert to msec
            // add local time zone offset
            // get UTC time in msec
            utc = d.getTime() + (d.getTimezoneOffset() * 60000);

            // create new Date object for different city
            // using supplied offset
            nd = new Date(utc + (3600000 * offset));

            // return time as a string
            // return rr =  "The local time in " + city + " is " + nd.toLocaleString();
            return nd;
            //alert(rr);
        }


        function GetCounterdowns(new_date, box_id, reff_btn_id) {
            // Set the date we're counting down to "Sep 30, 2019 14:43:25"

            var countDownDate = new Date(new_date).getTime();

            // Update the count down every 1 second
            var x = setInterval(function() {

                // Get today's date and time
                //var now = new Date().getTime();
                var now = calcTime('UAE', '+4');

                // ,toLocaleString("en-US", {timeZone: "Asia/Dubai"});

                //alert();

                // Find the distance between now and the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Output the result in an element with id="demo"
                document.getElementById(box_id).innerHTML = minutes + "m " + seconds + "s ";

                // If the count down is over, write some text 
                if (distance < 0) {
                    clearInterval(x);
                    //document.getElementById(box_id).innerHTML = "EXPIRED";
                    $('#' + reff_btn_id).show();
                    $('#' + box_id).hide();
                }
            }, 1000);

        }
    </script>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
    <div class="page-wrapper">
        <!-- BEGIN HEADER -->
        <?php include('header.php'); ?>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <?php include('include/sidebar.php'); ?>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <!-- BEGIN THEME PANEL -->
                    <?php include('include/theme_panel.php'); ?>
                    <!-- BEGIN PAGE BAR -->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="index">Dashboard</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>CMS</span>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Car Details Meta Description & Title</span>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <!-- <li>
                                       <span>Add/Edit Car Details Page Meta Description & Titles</span>
                                   </li> -->
                                <!-- <li>
                                       <a href="#">CMS</a>
                                       <i class="fa fa-circle"></i>
                                   </li>
                                   <li>
                                       <span>Car Details Brand Meta Description & Title</span>
                                   </li> -->
                            </ul>
                            </li>
                        </ul>

                    </div>
                    <?php include('include/alemad_ae.php'); ?>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h1 class="page-title">Showing: Car Details Meta Description & Title</h1>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <!-- BEGIN DASHBOARD STATS 1-->
                    <div class="row table-responsive">

                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-stripped table-hover">
                            <tr>
                                <th scope="row">
                                    <div align="left">
                                        <a href="Country?p=AddNew" class="btn btn-primary">+Add New </a>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <td>

                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-stripped table-hover">
                                        <tr>
                                            <td width="2%"><strong>S.no.</strong></td>
                                            <td width="37%"><strong>Country Name</strong></td>
                                            <td width="37%"><strong>Lat</strong></td>
                                            <td width="37%"><strong>Lng</strong></td>
                                            <!-- <td width="37%"><strong>Arabic Main Heading</strong></td> -->
                                            <td width="9%"><strong>Status</strong></td>
                                            <!-- <td width="9%"><strong>Arabic Status</strong></td> -->
                                            <td width="11%"><strong>Action</strong></td>
                                        </tr>

                                        <?php
                                        $rslt = "SELECT DISTINCT country, lat,lng,id,status FROM `all_airport` GROUP BY country";

                                        $r = query($rslt);
                                        $i = 1;
                                        while ($row = fetch_array($r)) {

                                            $continental_id = $row['continental_id'];
                                            $main_heading = $row['country'];
                                            $lat = $row['lat'];
                                            $lng = $row['lng'];
                                            // $ar_main_heading = $row['ar_main_heading'];
                                            // $short_text = $row['short_text'];
                                            // $ar_short_text = $row['ar_short_text'];
                                            // $language = $row['language'];
                                            //$short_desc = $row['short_desc'];
                                            // $img = $row['img'];
                                            $status = $row['status'];
                                            $faqId = $row['id'];
                                            // $ar_title = $row['ar_title'];
                                            // $create_date = date('d-m-Y', strtotime($row['create_date']));



                                            if ($status == 1) {
                                                $status_label = "<span class=' label label-success'>Enable</span>";
                                            } else if ($status == 2) {
                                                $status_label = "<span class=' label label-danger'>Disabled</span>";
                                            }
                                            // $arabic_label = '';
                                            // if (!empty($ar_main_heading)) {
                                            //     $arabic_label = "<span class=' label label-success'>Added</span>";
                                            // }

                                        ?>

                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $main_heading; ?></td>
                                                <td><?php echo $lat; ?></td>
                                                <td><?php echo $lng; ?></td>
                                                <td><?php echo $status_label; ?></td>
                                                <td>


                                                    <a href="Country?p=AddNew&id=<?php echo $faqId; ?>">
                                                        <i class="fa fa-edit tooltips" data-toggle="tooltip" title="English Edit"></i> </a>
                                                    &nbsp;
                                                    <a href="https://maps.google.com?q=<?php echo $lat . ',' . $lng; ?>">
                                                        <i class="fa fa-map-marker tooltips" data-toggle="tooltip" title="View Marker"></i> </a>
                                                    &nbsp;
                                                    &nbsp;
                                                    <button class="btn btn-primary" onclick="UpdateMarker('chart_ajax','<?php echo $faqId ?>','<?php echo $main_heading; ?>','LatLng')">Update</button>
                                                    &nbsp;
                                                    <?php
                                                    if ($status == 1) { ?>
                                                        <button class="btn btn-danger" onclick="UpdateMarker('chart_ajax','<?php echo $faqId ?>','<?php echo $main_heading; ?>','Disable')">Disable</button>

                                                    <?php } else {
                                                    ?>
                                                        <button class="btn btn-primary" onclick="UpdateMarker('chart_ajax','<?php echo $faqId ?>','<?php echo $main_heading; ?>','Enable')">Enable</button>

                                                    <?php } ?>
                                                    <!--  -->
                                                </td>
                                            </tr>

                                        <?php $i++;
                                        }

                                        ?>
                                    </table>




                                </td>
                        </table>
                        </tr>

                        <p id="demo"></p>

                        <!--begin::Modal-->
                        <div class="modal fade" id="basicModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document" id="modal-size">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="modalbody">

                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--end::Modal-->


                        <!--begin::Modal-->
                        <div class="modal fade" id="Editmodal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document" id="modal-size2">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel2">New message</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="modalbody2">

                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--end::Modal-->



                        <!--begin::Modal-->
                        <div class="modal fade" id="Editmodal4" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal_neww" role="document" id="modal-size2" style="width:80%">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel4"></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="modalbody4">

                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--end::Modal-->
                        <style>
                            .modal_neww {
                                max-width: 95% !important;
                                /*  margin: 1.75rem auto;
	position:relative;
	pointer-events: none;*/


                            }
                        </style>

                    </div>
                    <!-- END QUICK SIDEBAR -->
                </div>
                <!-- END CONTAINER -->
                <!-- BEGIN FOOTER -->
                <?php include('include/footer.php'); ?>
                <!-- END FOOTER -->
            </div>
            <!-- BEGIN QUICK NAV -->
            <?php include('include/quick_nav.php'); ?>
            <div class="quick-nav-overlay"></div>
            <?php include('include/footer_script.php'); ?>
            <script src="js/functions_modal.js?v=<?php echo rand(); ?>" type="text/javascript"> </script>

            <script>
                function ChangeStatus(id, type, col, status_id, status_td_id) {
                    if (confirm("Are you sure you want to Change The Status?")) {
                        //adsads');
                        var dataString = 'id=' + id;

                        $.ajax({
                            type: "POST",
                            url: "DealsPages/ajax_deal",
                            data: {
                                UpdateDealsStatus: 'yes',
                                id: id,
                                type: type,
                                col: col,
                                status_id: status_id
                            },
                            cache: false,
                            success: function(result) {

                                if (result == 1) {

                                    // alert(status_td_id);
                                    if (status_id == 2) {
                                        $("#" + status_td_id).html("<span class=' label label-success'>Enable</span>");
                                    } else if (status_id == 3) {
                                        $("#" + status_td_id).html("<span class=' label label-danger'>Disabled</span>");
                                    }

                                }
                            }
                        });
                    }
                }
                $(document).ready(function() {
                    //$('.tooltip').tooltip();

                    $('body').addClass('page-header-fixed page-sidebar-closed-hide-logo page-content-white page-sidebar-closed');

                });

                // 
                //    
                function UpdateMarker(url, id, location_name, identity) {
                    // alert(status + url + userid); var
                    var CountryFetcher = 'yes';
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            id: id,
                            identity: identity,
                            location_name: location_name,
                            CountryFetcher: CountryFetcher
                        },
                        success: function(data) {
                            alert(data);
                            // $("#ReportingData").html(data);
                        }
                    });
                }

                // 
                // 
            </script>

</body>

</html>
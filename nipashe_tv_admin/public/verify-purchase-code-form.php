<?php include 'includes/config.php' ?>
<?php require_once 'roles.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <title>The Stream - Android TV Streaming</title>

    <!-- Favicons-->
    <link rel="icon" href="assets/images/favicon/favicon-32x32.png" sizes="32x32">
    <!-- Favicons-->
    <link rel="apple-touch-icon-precomposed" href="assets/images/favicon/apple-touch-icon-152x152.png">
    <!-- For iPhone -->
    <meta name="msapplication-TileColor" content="#00bcd4">
    <meta name="msapplication-TileImage" content="assets/images/favicon/mstile-144x144.png">
    <!-- For Windows Phone -->


    <!-- CORE CSS-->

    <link href="assets/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="assets/css/style.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="assets/css/sticky-footer.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="assets/css/dropify.css" type="text/css" rel="stylesheet" media="screen,projection">

    <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
    <link href="assets/css/prism.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="assets/js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet"
          media="screen,projection">
    <link href="assets/js/plugins/chartist-js/chartist.min.css" type="text/css" rel="stylesheet"
          media="screen,projection">


    <!-- datatable -->
    <link href="http://cdn.datatables.net/1.10.6/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="assets/js/plugins/data-tables/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet" media="screen,projection">

    <style type="text/css">
        .hide-column {
            display: none;
        }

        .label-alert {
            display: inline-block;
            width: 6em;
            margin-right: .5em;
            padding-top: 1.5em;
        }
        span.highlight {
            background-color: #2196F3;
            padding: 5px 5px 5px 5px;
            color: white;
        }
        span.highlight-off {
            background-color: #F44336;
            padding: 5px 5px 5px 5px;
            color: white;
        }
    </style>

    <style type="text/css">
        .input-field div.error{
            position: relative;
            top: -1rem;
            left: 0rem;
            font-size: 0.8rem;
            color:#FF4081;
            -webkit-transform: translateY(0%);
            -ms-transform: translateY(0%);
            -o-transform: translateY(0%);
            transform: translateY(0%);
        }

        .div-error{
            position: relative;
            left: 0rem;
            font-size: 0.8rem;
            color:#FF4081;
            -webkit-transform: translateY(0%);
            -ms-transform: translateY(0%);
            -o-transform: translateY(0%);
            transform: translateY(0%);
        }

        .input-field label.active{
            width:100%;
        }
        .left-alert input[type=text] + label:after,
        .left-alert input[type=password] + label:after,
        .left-alert input[type=email] + label:after,
        .left-alert input[type=url] + label:after,
        .left-alert input[type=time] + label:after,
        .left-alert input[type=date] + label:after,
        .left-alert input[type=datetime-local] + label:after,
        .left-alert input[type=tel] + label:after,
        .left-alert input[type=number] + label:after,
        .left-alert input[type=search] + label:after,
        .left-alert textarea.materialize-textarea + label:after{
            left:0px;
        }
        .right-alert input[type=text] + label:after,
        .right-alert input[type=password] + label:after,
        .right-alert input[type=email] + label:after,
        .right-alert input[type=url] + label:after,
        .right-alert input[type=time] + label:after,
        .right-alert input[type=date] + label:after,
        .right-alert input[type=datetime-local] + label:after,
        .right-alert input[type=tel] + label:after,
        .right-alert input[type=number] + label:after,
        .right-alert input[type=search] + label:after,
        .right-alert textarea.materialize-textarea + label:after{
            right:70px;
        }
    </style>

</head>

<body>
<!-- Start Page Loading -->
<!--   <div id="loader-wrapper">
      <div id="loader"></div>
      <div class="loader-section section-left"></div>
      <div class="loader-section section-right"></div>
  </div> -->
  <!-- End Page Loading -->
<!-- //////////////////////////////////////////////////////////////////////////// -->

<!-- START HEADER -->
<header id="header" class="page-topbar">
    <!-- start header nav-->
    <div class="navbar-fixed">
        <nav class="cyan">
            <div class="nav-wrapper">
                <h1 class="logo-wrapper"><a href="verify-purchase-code.php" class="brand-logo darken-1"><img
                        src="assets/images/materialize-logo.png" alt="materialize logo"></a> <span
                        class="logo-text">The Stream</span></h1>
                <ul class="right hide-on-med-and-down">
                    <!-- <li><a href="#push-notification"
                           class="waves-effect waves-block waves-light modal-trigger"><i
                            class="mdi-social-notifications"></i></a> -->
                    <!-- Dropdown Trigger -->
                    <li><a class="dropdown-button" href="javascript:void(0);"
                           data-activates="dropdown1"><i class="mdi-navigation-arrow-drop-down"></i></a>
                    </li>

                    <!-- Dropdown Structure -->
                    <ul id="dropdown1" class="dropdown-content">
                        <li><a href="logout.php">Logout</a></li>
                    </ul>

                </ul>
            </div>
        </nav>
    </div>
    <!-- end header nav-->
</header>
<!-- END HEADER -->

<!-- START MAIN -->
<div>
    <!-- START WRAPPER -->
    <div class="wrapper">


<?php

    $error = false;

    if (isset($_POST['btnSubmit'])) {

        $item_purchase_code = $_POST['item_purchase_code'];

        if (strlen($item_purchase_code) < 36) {
            $error[] = 'Invalid Purchase Code!';
        }

        if (strlen($item_purchase_code) > 36) {
            $error[] = 'Invalid Purchase Code!';
        }

        if (empty($item_purchase_code)) {
                $error[] = 'Purchase code can not be empty!';
            }

        if(!$error) {

            $sql = "INSERT INTO tbl_purchase_code (item_purchase_code) VALUES (?)";

            $insert = $connect->prepare($sql);
            $insert->bind_param('s', $item_purchase_code);
            $insert->execute();

            $succes =<<<EOF
            <script>
            alert('Thank you');
            window.location = 'dashboard.php';
            </script>
EOF;
            echo $succes;
        }

    }

?>

    <!-- START CONTENT -->
    <section id="content">

        <!--start container-->
        <div class="container">
            <div class="section">
                <div class="row">
                    <br><br><br><br><br>
                    <div class="col s3 m3 l3" style="color:#FCFCFC;">.</div>

                    <div class="col s6 m6 l6">
                        <div class="card-panel">
                            <div class="row">
                                <form method="post" class="col s12" id="form-validation" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <center>
                                                <img src="assets/images/ic_envato.png" width="24" height="24"> Please Verify your Purchase Code to Continue Using Admin Panel.
                                                <?php echo $error ? '<div style="color:#F44336;">'. implode('<br>', $error) . '</div>' : '';?>
                                            </center>
                                            <br>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="text" name="item_purchase_code" id="item_purchase_code" required/>
                                                    <label for="item_purchase_code">Item Purchase Code</label><?php echo isset($error['item_purchase_code']) ? $error['item_purchase_code'] : '';?>
                                                </div>
                                            </div>

                                            <center>
                                                <button class="btn cyan waves-effect waves-light"
                                                        type="submit" name="btnSubmit">Submit
                                                    <i class="mdi-content-send right"></i>
                                                </button>
                                           
                                                <br><br>
                                                <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank"><b>Where Is My Purchase Code?</b></a>
                                                <br>
                                                <a href="https://codecanyon.net/item/the-stream-tv-video-streaming-app/19956555" target="_blank"><b>Don't Have Purchase Code? I Want to Purchase it first.</b></a>
                                            </center>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col s3 m3 l3"></div>

                </div>
            </div>
        </div>
    </section>

</div>
<!-- END WRAPPER -->

</div>

<!-- ================================================
Scripts
================================================ -->

<!-- jQuery Library -->
<script type="text/javascript" src="assets/js/jquery-1.11.2.min.js"></script>
<!--materialize js-->
<script type="text/javascript" src="assets/js/materialize.js"></script>
<!--prism-->
<script type="text/javascript" src="assets/js/prism.js"></script>
<!--scrollbar-->
<script type="text/javascript" src="assets/js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>

<!-- data-tables -->
<script type="text/javascript" src="assets/js/plugins/data-tables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/data-tables/data-tables-script.js"></script>

<!-- chartist -->
<script type="text/javascript" src="assets/js/plugins/chartist-js/chartist.min.js"></script>

<!--plugins.js - Some Specific JS codes for Plugin Settings-->
<script type="text/javascript" src="assets/js/plugins.js"></script>
<script type="text/javascript" src="assets/js/dropify.js"></script>


</body>

</html>
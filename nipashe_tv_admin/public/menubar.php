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
    <title>Nipashe TV</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

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
        .txt-padding {
            padding-top: 6px;
            padding-bottom: 10px;
        }

        .txt-padding2 {
            padding-bottom: 10px;
        }
    </style>

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
            background-color: #000000;
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
                <h1 class="logo-wrapper"><a href="dashboard.php" class="brand-logo darken-1"><h4>Nipashe TV</h></a> <span
                        class="logo-text">Nipashe TV</span></h1>
                <ul class="right hide-on-med-and-down">
                    <!-- <li><a href="#push-notification"
                           class="waves-effect waves-block waves-light modal-trigger"><i
                            class="mdi-social-notifications"></i></a> -->

                    <li><a href="notification.php"><i
                            class="mdi-social-notifications"></i></a>

                    </li>
                    <!-- Dropdown Trigger -->
                    <li><a class="dropdown-button" href="javascript:void(0);"
                           data-activates="dropdown1"><i class="mdi-navigation-arrow-drop-down"></i></a>
                    </li>

                    <!-- Dropdown Structure -->
                    <ul id="dropdown1" class="dropdown-content">
                        <li><a href="settings.php">Settings</a></li>
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
<div id="main">
    <!-- START WRAPPER -->
    <div class="wrapper">

        <!-- START LEFT SIDEBAR NAV-->
        <aside id="left-sidebar-nav">
            <ul id="slide-out" class="side-nav fixed leftside-navigation">
                <li class="user-details cyan darken-2">
                    <div class="row">
                        <div class="col col s4 m4 l4">
                            <center>
                                <img src="assets/images/ic_launcher-web.png" width="100px" height="100px">
                            </center>
                        </div>
                    </div>
                </li>
                <li class="bold">
                    <a href="dashboard.php" class="waves-effect waves-cyan">
                        <i class="mdi-action-dashboard"></i>Dashboard
                    </a>
                </li>

                <li class="bold">
                    <a href="category.php" class="waves-effect waves-cyan">
                        <i class="mdi-action-view-list"></i>Category
                    </a>
                </li>

                <li class="bold">
                    <a href="channel.php" class="waves-effect waves-cyan">
                        <i class="mdi-hardware-desktop-mac"></i>Channel
                    </a>
                </li>

                <li class="bold">
                    <a href="notification.php" class="waves-effect waves-cyan">
                        <i class="mdi-social-notifications"></i>Push Notification
                    </a>
                </li>

                <li class="bold">
                    <a href="settings.php" class="waves-effect waves-cyan">
                        <i class="mdi-action-settings"></i>Settings
                    </a>
                </li>

                <li class="bold">
                    <a href="members.php" class="waves-effect waves-cyan">
                        <i class="mdi-social-people"></i>Administrators
                    </a>
                </li>

                <li class="bold">
                    <a href="logout.php" class="waves-effect waves-cyan">
                        <i class="mdi-action-settings-power"></i>Logout
                    </a>
                </li>

            </ul>
            <a href="#" data-activates="slide-out"
               class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only darken-2"><i
                    class="mdi-navigation-menu"></i></a>
        </aside>
        <!-- END LEFT SIDEBAR NAV-->

        <!-- //////////////////////////////////////////////////////////////////////////// -->
        <div id="api-key" class="modal modal-fixed-footer">

            <div class="modal-content">
                <h5>Where I have to put my API Key?</h5>
                <hr>
                <ol>
                    <li>for security needed, Update <b>API_KEY</b> String value.</li>
                    <li>Open Android Studio Project.</li>
                    <li>Click <b>CHANGE API KEY</b> to generate new API Key.</li>
                    <li>go to app > java > yourpackage name > <b>Config.java</b>, and update with your own API Key. <img src="assets/images/api_key.jpg" width="100%"></li>
                </ol>
            </div>
            <div class="modal-footer">
                <a class="waves-effect waves-red btn-flat modal-action modal-close">OK, I am Understand</a>
            </div>

        </div>

        <div id="server-key" class="modal modal-fixed-footer">

            <div class="modal-content">
                <h5>Obtaining your Firebase Server API Key</h5>
                <hr>
                <p>Firebase provides Server API Key to identify your firebase app. To obtain your Server API Key, go to firebase console, select the project and go to settings, select Cloud Messaging tab and copy your Server key.</p>
                <img src="assets/images/fcm-server-key.jpg" width="100%">
            </div>
            <div class="modal-footer">
                <a class="waves-effect waves-red btn-flat modal-action modal-close">OK, I am Understand</a>
            </div>

        </div>

        <div id="onesignal-key" class="modal modal-fixed-footer">

            <div class="modal-content">
                <h5>Where do I get my OneSignal APP ID and Rest API Key?</h5>
                <hr>
                <li>Login to your <a href="https://onesignal.com/" target="_blank"><b>OneSignal</b></a> Account and select your app</li>
                <li>Select <b></> SETTINGS > Keys & IDs.</b> <img src="assets/images/onesignal_key.jpg" width="100%"></li>
            </div>
            <div class="modal-footer">
                <a class="waves-effect waves-red btn-flat modal-action modal-close">OK, I am Understand</a>
            </div>

        </div>

        <div id="package-name" class="modal modal-fixed-footer">

            <div class="modal-content">
                <h5>What is my package name??</h5>
                <hr>
                <li>Open Android Studio Project</li>
                <li>Select <b></> Gradle Scripts > build.gradle (Module: app)</b> <img src="assets/images/package_name.jpg" width="100%"></li>
            </div>
            <div class="modal-footer">
                <a class="waves-effect waves-red btn-flat modal-action modal-close">OK, I am Understand</a>
            </div>

        </div>

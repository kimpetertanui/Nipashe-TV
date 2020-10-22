<?php include('session.php'); ?>
<?php include('public/menubar.php'); ?>

<?php

  if (isset($_GET['id'])) {
    $ID = $_GET['id'];
  } else {
    $ID = "";
  }
      
  // create array variable to handle error
  $error = array();
      
  // create array variable to store data from database
  $data = array();
    
  // get data from reservation table
  $sql_query = "SELECT id, title, message, image FROM tbl_fcm_template WHERE id = ?";
    
  $stmt = $connect->stmt_init();
  if ($stmt->prepare($sql_query)) { 
    // Bind your variables to replace the ?s
    $stmt->bind_param('s', $ID);
    // Execute query
    $stmt->execute();
    // store result 
    $stmt->store_result();
    $stmt->bind_result(
      $data['id'],
      $data['title'],
      $data['message'],
      $data['image']
    );
    $stmt->fetch();
    $stmt->close();
  }
      
?>

<?php
  $setting_qry    = "SELECT * FROM tbl_fcm_api_key where id = '1'";
  $setting_result = mysqli_query($connect, $setting_qry);
  $settings_row   = mysqli_fetch_assoc($setting_result);

  $onesignal_app_id = $settings_row['onesignal_app_id']; 
  $onesignal_rest_api_key = $settings_row['onesignal_rest_api_key'];
  $protocol_type = $settings_row['protocol_type'];

  define("ONESIGNAL_APP_ID", $onesignal_app_id);
  define("ONESIGNAL_REST_KEY", $onesignal_rest_api_key);

  $cat_qry = "SELECT * FROM tbl_fcm_template ORDER BY message";
  $cat_result = mysqli_query($connect, $cat_qry); 
 

  if (isset($_POST['submit'])) {

        $cat_name = '';
        $external_link = false;

        $big_image = $protocol_type.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI']).'/upload/notification/'.$data['image'];
        //$big_image = $protocol_type.'10.0.2.2/the_stream/upload/notification/'.$data['image'];

        $content = array(
                         "en" => $_POST['notification_msg']                                                 
                         );

        $fields = array(
                        'app_id' => ONESIGNAL_APP_ID,
                        'included_segments' => array('All'),                                            
                        'data' => array("foo" => "bar","cat_id"=> "0","cat_name"=>$cat_name, "external_link"=>$external_link),
                        'headings'=> array("en" => $_POST['notification_title']),
                        'contents' => $content,
                        'big_picture' => $big_image         
                        );

        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic '.ONESIGNAL_REST_KEY));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);        
        
        $_SESSION['msg'] = "Congratulations, push notification sent...";
        header("Location:notification.php");
        exit; 

  }
  
?>

    <!-- START CONTENT -->
    <section id="content">

        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
            <div class="container">
                <div class="row">
                    <div class="col s12 m12 l12">
                        <h5 class="breadcrumbs-title">Send Push Notification</h5>
                        <ol class="breadcrumb">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="notification.php">Push Notification</a></li>
                            <li><a class="active">Send Push Notification</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!--breadcrumbs end-->

        <!--start container-->
        <div class="container">
            <div class="section">
                <div class="row">
                    <div class="col s12 m12 l12">
                        <div class="card-panel">
                            <div class="row">
                                <form id="form_validation" class="col s12" action="" name="addeditcategory" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="input-field col s12">

                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="text" name="notification_title" id="notification_title" value="<?php echo $data['title']; ?>" required/>
                                                    <label for="notification_title">Title</label>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="text" name="notification_msg" id="notification_msg" value="<?php echo $data['message']; ?>" required/>
                                                    <label for="message">Message</label>
                                                </div>
                                            </div>

                                            <?php
                                            if($data['image'] == NULL) {
                                                ?>
                                                <div class="row">
                                                    <div class="input-field col s12 m12 l5">
                                                        <input type="file" name="image" id="image" class="dropify-notification" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif" data-default-file="assets/images/no-image.png" data-show-remove="false" disabled/>
                                                    </div>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="row">
                                                    <div class="input-field col s12 m12 l5">
                                                        <input type="file" name="image" id="image" class="dropify-notification" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif" data-show-remove="false" data-default-file="upload/notification/<?php echo $data['image']; ?>" disabled/>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <button class="btn cyan waves-effect waves-light right"
                                                    type="submit" name="submit">Send Now
                                                <i class="mdi-content-send right"></i>
                                            </button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


<?php include('public/footer.php'); ?>
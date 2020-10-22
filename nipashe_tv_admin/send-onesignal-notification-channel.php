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
  $sql_query = "SELECT id, channel_name, channel_type, channel_image, channel_description, video_id FROM tbl_channel WHERE id = ?";
    
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
      $data['channel_name'],
      $data['channel_type'],
      $data['channel_image'],
      $data['channel_description'],
      $data['video_id']
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

  function get_cat_name($cat_id) {
    include('includes/config.php');
    $cat_qry = "SELECT * FROM tbl_channel WHERE id = '".$cat_id."'";
    $cat_result = mysqli_query($connect, $cat_qry); 
    $cat_row = mysqli_fetch_assoc($cat_result); 
     
    return $cat_row['channel_name'];

  }

  $cat_qry = "SELECT * FROM tbl_channel ORDER BY channel_name";
  $cat_result = mysqli_query($connect, $cat_qry); 
 

  if (isset($_POST['submit'])) {
     
     $external_link = false;

     if ($_POST['cat_id'] != 0) {
        $cat_name = get_cat_name($_POST['cat_id']);
     } else {
        $cat_name = '';
     }    
         
        if ($data['channel_type'] == 'YOUTUBE') {
          $big_image = 'https://img.youtube.com/vi/'.$data['video_id'].'/mqdefault.jpg';
        } else {
          $big_image = $protocol_type.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI']).'/upload/'.$data['channel_image'];
          //$big_image = $protocol_type.'10.0.2.2/the_stream/upload/'.$data['channel_image'];
        }

        $content = array(
                         "en" => $_POST['notification_msg']                                                 
                         );

        $fields = array(
                        'app_id' => ONESIGNAL_APP_ID,
                        'included_segments' => array('All'),                                            
                        'data' => array("foo" => "bar","cat_id"=>$_POST['cat_id'],"cat_name"=>$cat_name,"external_link"=>$external_link),
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
        header("Location:channel.php");
        exit; 

  }
  
?>


<?php
    $value = $data['channel_description'];
    if (strlen($value) > 100)
    $value = substr($value, 0, 97) . '...';
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
                            <li><a href="channel.php">Manage Channel</a></li>
                            <li><a class="active">Send Notification Channel</a></li>
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
                                            <input type="hidden" name="cat_id" id="cat_id" value="<?php echo $data['id']; ?>" required>
                                            <input type="hidden" name="notification_title" id="notification_title" value="<?php echo $data['channel_name']; ?>" required/>
                                            <input type="hidden" name="notification_msg" id="notification_msg" value="<?php echo $data['channel_description']; ?>" required/>

                                            <div class="row">
                                                 <div class="input-field col s12">
                                                    <font size="2" color="#9e9e9e">Title</font>
                                                    <div class="txt-padding"><?php echo $data['channel_name']; ?></div>
                                                    <hr>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <font size="2" color="#9e9e9e">Message</font>
                                                    <div class="txt-padding2"><?php echo $value; ?></div>
                                                    <hr>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="input-field col s12 m12 l5">
                                                    <?php if ($data['channel_type'] == 'YOUTUBE') { ?>
                                                        <input type="file" class="dropify-notification" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif" data-show-remove="false" data-default-file="https://img.youtube.com/vi/<?php echo $data['video_id'];?>/mqdefault.jpg" disabled/>
                                                    <?php } else { ?>
                                                        <input type="file" class="dropify-notification" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif" data-show-remove="false" data-default-file="upload/<?php echo $data['channel_image']; ?>" disabled/>
                                                    <?php } ?>
                                                </div>
                                            </div>

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
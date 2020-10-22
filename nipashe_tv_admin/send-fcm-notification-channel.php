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
    $protocol_type = $settings_row['protocol_type'];

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
                                <form id="form_validation" class="col s12" action="send-push.php" method="post">
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input type="hidden" name="title" id="title" value="<?php echo $data['channel_name']; ?>" required/>
                                            <input type="hidden" name="message" id="message" value="<?php echo $data['channel_description']; ?>" required/>
                                            <input type="hidden" name="link" id="link" />
                                            <input type="hidden" name="id" id="id" value="<?php echo $data['id']; ?>" />

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

                                                        <input type="hidden" name="image" value="https://img.youtube.com/vi/<?php echo $data['video_id'];?>/mqdefault.jpg">
                                                        
                                                    <?php } else { ?>

                                                        <input type="file" class="dropify-notification" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif" data-show-remove="false" data-default-file="upload/<?php echo $data['channel_image']; ?>" disabled/>

                                                        <input type="hidden" name="image" value="<?php echo $protocol_type.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI']).'/upload/'.$data['channel_image']; ?>">

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
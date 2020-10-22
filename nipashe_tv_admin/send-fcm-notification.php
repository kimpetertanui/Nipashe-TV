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
                                <form id="form_validation" class="col s12" action="push-services.php" method="post">
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="text" name="title" id="title" value="<?php echo $data['title']; ?>" required/>
                                                    <label for="title">Title</label>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="text" name="message" id="message" value="<?php echo $data['message']; ?>" required/>
                                                    <label for="message">Message</label>
                                                </div>
                                            </div>
                                            
                                            <?php
                                            if($data['image'] == NULL) {
                                                ?>
                                                <div class="row">
                                                    <div class="input-field col s12 m12 l5">
                                                        <input type="file" class="dropify-notification" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif" data-default-file="assets/images/no-image.png" data-show-remove="false" disabled/>
                                                    </div>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="row">
                                                    <div class="input-field col s12 m12 l5">
                                                        <input type="file" class="dropify-notification" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif" data-show-remove="false" data-default-file="upload/notification/<?php echo $data['image']; ?>" disabled/>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <br>

                                            <input type="hidden" name="link" id="link" />
                                            <input type="hidden" name="id" id="id" value="0" />
                                            <input type="hidden" name="image" id="image" value="<?php echo $data['image']; ?>" />

                                            <button class="btn cyan waves-effect waves-light right"
                                                    type="submit">Send Now
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
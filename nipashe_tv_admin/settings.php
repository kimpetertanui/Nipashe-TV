<?php include('session.php'); ?>
<?php include("public/menubar.php"); ?>
<script src="assets/js/ckeditor/ckeditor.js"></script>

<?php

include('public/fcm.php');

$qry = "SELECT * FROM tbl_fcm_api_key where id = '1'";
$result = mysqli_query($connect, $qry);
$settings_row = mysqli_fetch_assoc($result);

if(isset($_POST['submit'])) {

    $sql_query = "SELECT * FROM tbl_fcm_api_key WHERE id = '1'";
    $img_res = mysqli_query($connect, $sql_query);
    $img_row =  mysqli_fetch_assoc($img_res);

    $data = array(
        'app_fcm_key' => $_POST['app_fcm_key'],
        'api_key' => $_POST['api_key'],
        'package_name' => $_POST['package_name'],
        'onesignal_app_id' => $_POST['onesignal_app_id'],
        'onesignal_rest_api_key' => $_POST['onesignal_rest_api_key'],
        'privacy_policy' => $_POST['privacy_policy'],
        'providers' => $_POST['providers'],
        'protocol_type' => $_POST['protocol_type']
    );

    $news_edit = Update('tbl_fcm_api_key', $data, "WHERE id = '1'");

    if ($news_edit > 0) {
        $_SESSION['msg'] = "";
        header( "Location:settings.php");
        exit;
    }
}

?>

<!-- START CONTENT -->
<section id="content">

    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class=" grey lighten-3">
        <div class="container">
            <div class="row">
                <div class="col s12 m12 l12">
                    <h5 class="breadcrumbs-title">Settings</h5>
                    <ol class="breadcrumb">
                        <li><a href="dashboard.php">Dashboard</a>
                        </li>
                        <li><a class="active">Settings</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs end-->

    <div class="container">
        <div class="section">
            <div class="row">
                <form method="post" enctype="multipart/form-data">
                    <div class="col s12 m12 l12">
                        <button type="submit" name="submit" class="btn cyan waves-effect waves-light right">Save Settings</button>
                    </div>
                    <div class="col s12 m12 l12">
                        <div class="card-panel">
                            <div class="row">
                                <div class="row">
                                    <div class="input-field col s12">

                                        <?php if(isset($_SESSION['msg'])) { ?>
                                            <div class='card-panel teal lighten-2'>
                                            <span class='white-text text-darken-2'>
                                                Successfully Saved...
                                            </span>
                                            </div>
                                            <?php unset($_SESSION['msg']); }?>

                                        <div class="row">
                                            <div class="input-field col s3">
                                                <b>applicationId (Package Name)</b>
                                                <br>
                                                <a href="#package-name" class="modal-trigger">What is my package name?</a>
                                            </div>

                                            <div class="input-field col s9">
                                                <input type="text" name="package_name" id="package_name" value="<?php echo $settings_row['package_name'];?>" required />
                                                <label for="package_name">applicationId (Package Name)</label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="input-field col s3">
                                                <b>Push Notification Providers</b>
                                                <br>
                                                <a>Choose your provider for sending push notification</a>
                                            </div>

                                            <div class="input-field col s9">
                                                <select name="providers" id="providers">
                                                        <?php if ($settings_row['providers'] == 'onesignal') { ?>
                                                            <option value="onesignal" selected="selected">OneSignal</option>
                                                            <option value="firebase">Firebase Cloud Messaging (FCM)</option>
                                                        <?php } else { ?>
                                                            <option value="onesignal">OneSignal</option>
                                                            <option value="firebase" selected="selected">Firebase Cloud Messaging (FCM)</option>
                                                        <?php } ?>
                                                </select>
                                                <label for="providers">Providers</label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="input-field col s3">
                                                <b>Site Protocol</b>
                                                <br>
                                                <a>Choose your website protocol type</a>
                                            </div>

                                            <div class="input-field col s9">
                                                <select name="protocol_type" id="protocol_type">
                                                        <?php if ($settings_row['protocol_type'] == 'http://') { ?>
                                                            <option value="http://" selected="selected">HTTP</option>
                                                            <option value="https://">HTTPS</option>
                                                        <?php } else { ?>
                                                            <option value="http://">HTTP</option>
                                                            <option value="https://" selected="selected">HTTPS</option>
                                                        <?php } ?>
                                                </select>
                                                <label for="protocol_type">Protocol Type</label>
                                            </div>
                                        </div>                                        

                                        <div class="row">
                                            <div class="input-field col s3">
                                                <b>Your Server Key</b>
                                                <br>
                                                <a href="#server-key" class="modal-trigger">How to obtain your FCM Server Key?</a>
                                            </div>

                                            <div class="input-field col s9">
                                                <textarea name="app_fcm_key" class="materialize-textarea" id="app_fcm_key" required><?php echo $settings_row['app_fcm_key'];?></textarea>
                                                <label for="app_fcm_key">FCM Server Key</label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="input-field col s3">
                                                <b>Your OneSignal APP ID</b>
                                                <br>
                                                <a href="#onesignal-key" class="modal-trigger">Where do I get my OneSignal APP ID?</a>
                                            </div>

                                            <div class="input-field col s9">
                                                <input type="text" name="onesignal_app_id" id="onesignal_app_id" value="<?php echo $settings_row['onesignal_app_id'];?>" required />
                                                <label for="onesignal_app_id">OneSignal APP ID :</label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="input-field col s3">
                                                <b>Your OneSignal REST API KEY</b>
                                                <br>
                                                <a href="#onesignal-key" class="modal-trigger">Where do I get my OneSignal REST API KEY?</a>
                                            </div>

                                            <div class="input-field col s9">
                                                <input type="text" name="onesignal_rest_api_key" id="onesignal_rest_api_key" value="<?php echo $settings_row['onesignal_rest_api_key'];?>" required />
                                                <label for="onesignal_rest_api_key">OneSignal REST API KEY :</label>
                                            </div>
                                        </div>

                                            <div class="row">
                                              <div class="input-field col s3">
                                                <b>Your API Key</b>
                                                <br>
                                                <a href="#api-key" class="modal-trigger"><i>Where I have to put my API Key?</i></a>
                                              </div>

                                              <div class="input-field col s6">
                                                <input type="text" name="api_key" id="api_key" value="<?php echo $settings_row['api_key'];?>" required />
                                                <label for="api_key">API Key :</label>
                                              </div>

                                              <div class="input-field col s3">
                                                <a href="change-api-key.php" class="btn cyan waves-effect waves-light right">Change API Key</a>
                                              </div>
                                            </div>

                                            <div class="row">
                                              <div class="input-field col s3">
                                                <b>Privacy Policy</b>
                                                <br><a>This privacy policy will be displayed in your android app</a>
                                              </div>

                                              <div class="input-field col s9">
                                                <label for="privacy_policy">Privacy Policy</label>
                                                <br>
                                                <textarea name="privacy_policy" id="privacy_policy" class="form-control" cols="60" rows="10"><?php echo $settings_row['privacy_policy'];?></textarea>
                                                    <script>                             
                                                        CKEDITOR.replace( 'privacy_policy' );
                                                    </script>   
                                              </div>
                                            </div>                                       

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include('public/footer.php'); ?>

<?php include_once('functions.php'); ?>

<?php
if(isset($_GET['id'])){
    $ID = $_GET['id'];
}else{
    $ID = "";
}

// create array variable to store category data
$category_data = array();

$sql_query = "SELECT image FROM tbl_fcm_template WHERE id = ?";

$stmt_category = $connect->stmt_init();
if($stmt_category->prepare($sql_query)) {
    // Bind your variables to replace the ?s
    $stmt_category->bind_param('s', $ID);
    // Execute query
    $stmt_category->execute();
    // store result
    $stmt_category->store_result();
    $stmt_category->bind_result($previous_image);
    $stmt_category->fetch();
    $stmt_category->close();
}


if(isset($_POST['btnEdit'])){
    $title = $_POST['title'];
    $message = $_POST['message'];

    // get image info
    $menu_image = $_FILES['image']['name'];
    $image_error = $_FILES['image']['error'];
    $image_type = $_FILES['image']['type'];

    // create array variable to handle error
    $error = array();

    if(empty($title)){
        $error['title'] = " <span class='label label-danger'>Must Insert!</span>";
    }

    if(empty($message)){
        $error['message'] = " <span class='label label-danger'>Must Insert!</span>";
    }

    // common image file extensions
    $allowedExts = array("gif", "jpeg", "jpg", "png");

    // get image file extension
    error_reporting(E_ERROR | E_PARSE);
    $extension = end(explode(".", $_FILES["image"]["name"]));

    if(!empty($menu_image)){
        if(!(($image_type == "image/gif") ||
                ($image_type == "image/jpeg") ||
                ($image_type == "image/jpg") ||
                ($image_type == "image/x-png") ||
                ($image_type == "image/png") ||
                ($image_type == "image/pjpeg")) &&
            !(in_array($extension, $allowedExts))){

            $error['image'] = " <span class='label label-danger'>Image type must jpg, jpeg, gif, or png!</span>";
        }
    }

    if(!empty($message) && empty($error['image'])){

        if(!empty($menu_image)) {

            // create random image file name
            $string = '0123456789';
            $file = preg_replace("/\s+/", "_", $_FILES['image']['name']);
            $function = new functions;
            $image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;

            // delete previous image
            $delete = unlink('upload/notification/'."$previous_image");

            // upload new image
            $upload = move_uploaded_file($_FILES['image']['tmp_name'], 'upload/notification/'.$image);

            $sql_query = "UPDATE tbl_fcm_template
							SET title = ?, message = ?, image = ?
							WHERE id = ?";

            $upload_image = $image;
            $stmt = $connect->stmt_init();
            if($stmt->prepare($sql_query)) {
                // Bind your variables to replace the ?s
                $stmt->bind_param('ssss',
                    $title,
                    $message,
                    $upload_image,
                    $ID);
                // Execute query
                $stmt->execute();
                // store result
                $update_result = $stmt->store_result();
                $stmt->close();
            }
        } else {

            $sql_query = "UPDATE tbl_fcm_template
							SET title = ?, message = ?
							WHERE id = ?";

            $stmt = $connect->stmt_init();
            if($stmt->prepare($sql_query)) {
                // Bind your variables to replace the ?s
                $stmt->bind_param('sss',
                    $title,
                    $message,
                    $ID);
                // Execute query
                $stmt->execute();
                // store result
                $update_result = $stmt->store_result();
                $stmt->close();
            }
        }

        // check update result
        if($update_result) {
            $error['update_category'] = "<div class='card-panel teal lighten-2'>
												    <span class='white-text text-darken-2'>
													    Push Notification Template Successfully Updated...
												    </span>
												</div>";
        } else {
            $error['update_category'] = "<div class='card-panel red darken-1'>
												    <span class='white-text text-darken-2'>
													    Update Failed
												    </span>
												</div>";
        }
    }

}

// create array variable to store previous data
$data = array();

$sql_query = "SELECT *
				FROM tbl_fcm_template
				WHERE id = ?";

$stmt = $connect->stmt_init();
if($stmt->prepare($sql_query)) {
    // Bind your variables to replace the ?s
    $stmt->bind_param('s', $ID);
    // Execute query
    $stmt->execute();
    // store result
    $stmt->store_result();
    $stmt->bind_result($data['id'],
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
                        <h5 class="breadcrumbs-title">Edit Template</h5>
                        <ol class="breadcrumb">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="notification.php">Push Notification</a></li>
                            <li><a class="active">Edit Template</a></li>
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
                                <form method="post" class="col s12" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <?php echo isset($error['update_category']) ? $error['update_category'] : '';?>

                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="text" name="title" id="title" value="<?php echo $data['title']; ?>" required/>
                                                    <label for="title">Title</label><?php echo isset($error['title']) ? $error['title'] : '';?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="text" name="message" id="message" value="<?php echo $data['message']; ?>" required/>
                                                    <label for="message">Message</label><?php echo isset($error['message']) ? $error['message'] : '';?>
                                                </div>
                                            </div>

                                            <!-- <div class="file-field input-field col s12">
                                                <input class="file-path validate" type="text" disabled/>
                                                <div class="btn">
                                                    <span>Image</span>
                                                    <input type="file" name="image" id="image" value="" />
                                                </div>
                                            </div> -->

                                            <?php
                                            if($data['image'] == NULL) {
                                                ?>
                                                <div class="row">
                                                    <div class="input-field col s12 m12 l5">
                                                        <input type="file" name="image" id="image" class="dropify-notification" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif" data-default-file="assets/images/no-image.png" />
                                                    </div>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="row">
                                                    <div class="input-field col s12 m12 l5">
                                                        <input type="file" name="image" id="image" class="dropify-notification" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif" data-default-file="upload/notification/<?php echo $data['image']; ?>" />
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <button class="btn cyan waves-effect waves-light right"
                                                    type="submit" name="btnEdit">Update
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

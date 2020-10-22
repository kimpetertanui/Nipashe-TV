<?php include_once('functions.php'); ?>

<?php
if(isset($_POST['btnAdd'])){
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

            $error['category_image'] = " <span class='label label-danger'>Image type must jpg, jpeg, gif, or png!</span>";
        }
    }

    if(!empty($message) && empty($error['image'])) {

        if(!empty($menu_image)) {
            // create random image file name
            $string = '0123456789';
            $file = preg_replace("/\s+/", "_", $_FILES['image']['name']);
            $function = new functions;
            $menu_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;

            // upload new image
            $upload = move_uploaded_file($_FILES['image']['tmp_name'], 'upload/notification/'.$menu_image);

            // insert new data to menu table
            $sql_query = "INSERT INTO tbl_fcm_template (title, message, image)
							VALUES(?, ?, ?)";

            $upload_image = $menu_image;
            $stmt = $connect->stmt_init();
            if($stmt->prepare($sql_query)) {
                // Bind your variables to replace the ?s
                $stmt->bind_param('sss',
                    $title,
                    $message,
                    $upload_image
                );
                // Execute query
                $stmt->execute();
                // store result
                $result = $stmt->store_result();
                $stmt->close();
            }

        } else {

            // insert new data to menu table
            $sql_query = "INSERT INTO tbl_fcm_template (title, message) VALUES (?, ?)";

            $stmt = $connect->stmt_init();
            if($stmt->prepare($sql_query)) {
                // Bind your variables to replace the ?s
                $stmt->bind_param('ss',
                    $title,
                    $message
                );
                // Execute query
                $stmt->execute();
                // store result
                $result = $stmt->store_result();
                $stmt->close();
            }

        }

        if($result) {
            $error['add_category'] = "<div class='card-panel teal lighten-2'>
											    <span class='white-text text-darken-2'>
												   New Push Notification Template Added Successfully...
											    </span>
											</div>";
        } else {
            $error['add_category'] = "<div class='card-panel red darken-1'>
											    <span class='white-text text-darken-2'>
												    Added Failed
											    </span>
											</div>";
        }
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
                        <h5 class="breadcrumbs-title">Add New Template</h5>
                        <ol class="breadcrumb">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="notification.php">Push Notification</a></li>
                            <li><a class="active">Add New Template</a></li>
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
                                <form method="post" class="col s12" id="form-validation" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <?php echo isset($error['add_category']) ? $error['add_category'] : '';?>

                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="text" name="title" id="title" required/>
                                                    <label for="title">Title</label><?php echo isset($error['title']) ? $error['title'] : '';?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="text" name="message" id="message" required/>
                                                    <label for="message">Message</label><?php echo isset($error['message']) ? $error['message'] : '';?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="input-field col s12 m12 l5">
                                                    <input type="file" name="image" id="image" id="image" class="dropify-notification" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif" />
                                                </div>
                                            </div>

                                            <button class="btn cyan waves-effect waves-light right"
                                                    type="submit" name="btnAdd">Submit
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

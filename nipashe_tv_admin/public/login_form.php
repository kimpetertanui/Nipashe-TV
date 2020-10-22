<?php

    include_once('includes/config.php');
    // start session
    //session_start();

    $setting_qry    = "SELECT * FROM tbl_purchase_code ORDER BY id DESC LIMIT 1";
    $setting_result = mysqli_query($connect, $setting_qry);
    $settings_row   = mysqli_fetch_assoc($setting_result);
    $purchase_code    = $settings_row['item_purchase_code'];

    // if user click Login button
    if(isset($_POST['btnLogin'])) {

        // get username and password
        $username = $_POST['username'];
        $password = $_POST['password'];

        // set time for session timeout
        $currentTime = time() + 25200;
        $expired = 3600;

        // create array variable to handle error
        $error = array();

        // check whether $username is empty or not
        if(empty($username)) {
            $error['username'] = "*Username should be filled.";
        }

        // check whether $password is empty or not
        if(empty($password)) {
            $error['password'] = "*Password should be filled.";
        }

        // if username and password is not empty, check in database
        if(!empty($username) && !empty($password)) {

            // change username to lowercase
            $username = strtolower($username);

            //encript password to sha256
            $password = hash('sha256',$username.$password);

            // get data from user table
            $sql_query = "SELECT * FROM tbl_user WHERE username = ? AND password = ?";

            $stmt = $connect->stmt_init();
            if($stmt->prepare($sql_query)) {
                // Bind your variables to replace the ?s
                $stmt->bind_param('ss', $username, $password);
                // Execute query
                $stmt->execute();
                /* store result */
                $stmt->store_result();
                $num = $stmt->num_rows;
                // Close statement object
                $stmt->close();
                if($num == 1) {
                    if (strlen($purchase_code) >= 36) {
                        $_SESSION['user'] = $username;
                        $_SESSION['timeout'] = $currentTime + $expired;
                        header("location: dashboard.php");
                    } else {
                        $_SESSION['user'] = $username;
                        $_SESSION['timeout'] = $currentTime + $expired;
                        header("location: verify-purchase-code.php");
                    }

                } else {
                    $error['failed'] = "Invalid Username or Password!";
                }
            }

        }
    }
?>

<body class="cyan">

  <div id="login-page" class="row">
    <div class="col s12 z-depth-4 card-panel">

     <form class="login-form" method="post">
        <div class="row">
          <div class="input-field col s12 center">
            <img src="assets/images/ic_launcher-web.png" width="100px" height="100px">
            <p class="center login-form-text">Nipashe TV</p>
          </div>
        </div>
        <div style="color:#F44336;">
        <center>
        <?php echo isset($error['failed']) ? $error['failed'] : '';?>
        </center>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-social-person prefix"></i>
            <input name="username" id="username" type="text" required>
            <label for="username" class="center-align">Username</label>
          </div>
        </div>
        <div class="row margin">
          <div class="input-field col s12">
            <i class="mdi-action-lock prefix"></i>
            <input name="password" id="password"  onfocus="this.value=''" type="password" required>
            <label for="password">Password</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <button class="btn waves-effect waves-light col s12" type="submit" name="btnLogin">Login</button>
          </div>
        </div>

      </form>
    </div>
  </div>

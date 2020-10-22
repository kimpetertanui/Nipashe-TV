<?php

	include_once('functions.php'); 

	$error = false;

	if (isset($_POST['btnAdd'])) {

		$username   = $_POST['username'];
		$password   = $_POST['password'];
		$repassword = $_POST['repassword'];
		$email = $_POST['email'];
		//$role  = $_POST['role'] ? : '102';
        $role   = $_POST['role'];

		if (strlen($username) < 3) {
			$error[] = 'Username is too short!';
		}

		if (empty($password)) {
			$error[] = 'Password can not be empty!';
		}

		if ($password != $repassword) {
			$error[] = 'Password does not match!';
		}

		$password = hash('sha256',$username.$password);

		if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
			$error[] = 'Email is not valid!'; 
		}

		// $query = mysqli_query($connect, "SELECT email FROM tbl_user where email = '$email' ");
		// if(mysqli_num_rows($query) > 0) {
		//     $error[] = 'Email already exists!'; 
		// }

		if (!$error) {

			$sql = "SELECT * FROM tbl_user WHERE (username = '$username' OR email = '$email');";
            $result = mysqli_query($connect, $sql);
            if (mysqli_num_rows($result) > 0) {

            	$row = mysqli_fetch_assoc($result);

            	if ($username == $row['username']) {

                	$error[] = 'Username already exists!';

            	} 

            	if ($email == $row['email']) {

                	$error[] = 'Email already exists!';

            	}

	        } else {

				$sql = "INSERT INTO tbl_user (username, password, email, user_role) VALUES (?, ?, ?, ?)";

				$insert = $connect->prepare($sql);
				$insert->bind_param('ssss', $username, $password, $email, $role);
				$insert->execute();

				$succes =<<<EOF
				<script>
				alert('Insert User Success');
				window.location = 'members.php';
				</script>
EOF;
				echo $succes;
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
                    <h5 class="breadcrumbs-title">Add User</h5>
                    <ol class="breadcrumb">
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="members.php">Manage Users</a></li>
                        <li><a class="active">Add User</a></li>
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
                            <form method="post" class="col s12">
                                <div class="row">
                                    <div class="input-field col s12">

                                        <?php echo $error ? '<div class="card-panel teal lighten-2" role="alert"><span class="white-text text-darken-2">'. implode('<br>', $error) . '</span></div>' : '';?>

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="text" name="username" id="username" />
                                                <label for="category_name">Username</label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="email" name="email" id="email" />
                                                <label for="email">Email</label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="password" name="password" id="password" />
                                                <label for="password">Password</label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="password" name="repassword" id="repassword" />
                                                <label for="repassword">Re Password</label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="hidden" name="role" id="role" value="100" />
                                                <!-- <label for="role">User Level</label> -->
                                            </div>
                                        </div>

                                        <!-- <div class="row">
        							        <div class="input-field col s12">
        	                                      <select name="role" id="role">
                                                    <option value="100" selected>Super Admin</option>
													<option value="101">Admin</option>
													<option value="102">Moderator</option>
                                                </select>
                                                <label>User Level</label>
                                            </div>
                                       </div> -->

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
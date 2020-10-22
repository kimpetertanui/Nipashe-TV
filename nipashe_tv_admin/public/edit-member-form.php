<?php
	include_once('functions.php');

	$error = false;

	/**
	 * Call Detail Member by id
	 */
	if (isset($_GET['id']) && is_numeric($_GET['id'])) {
		$id =  $_GET['id'];

		$sql = "SELECT * FROM tbl_user WHERE id = ? LIMIT 1";
		$stmt = $connect->prepare($sql);
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($id, $username, $password, $email, $role);
		$stmt->fetch();

	} else {
		die('404 Oops!!!');
	}

	$error = false;
	/**
	 * Update Command
	 */
	if (isset($_POST['btnEdit'])) {
		$newusername   = $_POST['username'];
		$newpassword   = trim($_POST['password']);
		$newrepassword = trim($_POST['repassword']);
		$newemail = $_POST['email'];
        //$newrole  = $_POST['role'] ? : '102';
		$newrole  = $_POST['role'];

		if (strlen($newusername) < 3) {
			$error[] = 'Username is too short!';
		}

		if (empty($newpassword)) {
			$error[] = 'Password can not be empty!';
		}

		if ($newpassword != $newrepassword) {
			$error[] = 'Password does not match!';
		}

		$newpassword = hash('sha256',$newusername.$newpassword);

		if (filter_var($newemail, FILTER_VALIDATE_EMAIL) === FALSE) {
			$error[] = 'Email is not valid!';
		}

		if (! $error) {
			$sql = "UPDATE tbl_user SET username = ?,
			 							password = ?,
			 							email = ?,
			 							user_role = ?
			 						WHERE
			 							id = ?";
			$update = $connect->prepare($sql);
			$update->bind_param(
				'ssssi',
				$newusername,
				$newpassword,
				$newemail,
				$newrole,
				$id
			);

			$update->execute();

			$succes =<<<EOF
			<script>
			alert('Update User Success');
			window.location = 'edit-member.php?id=$id';
			</script>

EOF;
			echo $succes;
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
                    <h5 class="breadcrumbs-title">Edit User</h5>
                    <ol class="breadcrumb">
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="members.php">Manage Users</a></li>
                        <li><a class="active">Edit User</a></li>
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
                                                <input type="text" name="username" id="username" placeholder="username" value="<?php echo $username; ?>" />
                                                <label for="category_name">Username</label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input type="email" name="email" id="email" placeholder="john@mail.com" value="<?php echo $email; ?>" />
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
                                                <input type="hidden" name="role" id="role" value="<?php echo $role; ?>"/>
                                                <!-- <label for="role">User Level</label> -->
                                            </div>
                                        </div>
                              
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

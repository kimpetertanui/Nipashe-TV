<?php

	include_once('functions.php');
?>

<?php
    $setting_query = "SELECT * FROM tbl_fcm_api_key where id = '1'";
    $setting_result = mysqli_query($connect, $setting_query);
    $setting_row = mysqli_fetch_assoc($setting_result);
?>

<?php

	$sql_query = "SELECT * FROM tbl_channel p, tbl_category c WHERE p.category_id = c.cid ORDER BY id DESC";
	$result = mysqli_query($connect, $sql_query);

 ?>

	<!-- START CONTENT -->
    <section id="content">

        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          	<div class="container">
            	<div class="row">
              		<div class="col s12 m12 l12">
               			<h5 class="breadcrumbs-title">Manage Channel</h5>
                        <ol class="breadcrumb">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a class="active">Manage Channel</a></li>
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
		        	<div align="right"><a href="add-channel.php" class="btn waves-effect waves-light indigo">Add New Channel</a></div>

		        		<div class="card-panel">

                            <?php if(isset($_SESSION['msg'])) { ?>
                                <div class='card-panel teal lighten-2'>
                                    <span class='white-text text-darken-2'>
                                        <?php echo $_SESSION['msg']; ?>
                                    </span>
                                </div>
                            <?php unset($_SESSION['msg']); }?>          			

		<table id="table_channel" class="responsive-table display" cellspacing="0">		         
			<thead>
				<tr>
					<th class="hide-column">ID</th>
					<th width="5%">No.</th>
					<th width="30%">Channel Name</th>
					<th width="15%">Channel Image</th>
					<th width="20%">Category</th>
					<th width="20%">Type</th>
					<th width="10%">Action</th>
				</tr>
			</thead>

			<tbody>
				<?php
					$i = 1;
					while($data = mysqli_fetch_array($result)) {
				?>

	            <tr>
	            	<td class="hide-column"><?php echo $data['id'];?></td>
	            	<td>
	            		<?php
		                    echo $i;
		                    $i++;
		                ?>
	            	</td>
	                <td><?php echo $data['channel_name'];?></td>
	            	<td>
	            		<?php if ($data['channel_type'] == 'YOUTUBE') { ?>
			            	<img src="https://img.youtube.com/vi/<?php echo $data['video_id'];?>/mqdefault.jpg" height="54px" width="72px"/>
			            <?php } else { ?>
			            	<img src="upload/<?php echo $data['channel_image'];?>" height="54px" width="72px"/>
			            <?php } ?>
	            	</td>
	            	<td><?php echo $data['category_name'];?></td>
	            	<td><?php echo $data['channel_type'];?></td>
	                <td>
                        <?php if ($setting_row['providers'] == 'onesignal') { ?>
                        <a href="send-onesignal-notification-channel.php?id=<?php echo $data['id'];?>">
                            <i class="material-icons">notifications_active</i>
                        </a>
                        <?php } else { ?>
                        <a href="send-fcm-notification-channel.php?id=<?php echo $data['id'];?>">
                            <i class="material-icons">notifications_active</i>
                        </a>
                        <?php } ?>	                	
                        

						<a href="edit-channel.php?id=<?php echo $data['id'];?>">
							<i class="material-icons">mode_edit</i>
						</a>
						<a href="delete-channel.php?id=<?php echo $data['id'];?>" onclick="return confirm('Are you sure want to delete this channel?')" >
							<i class="material-icons">delete</i>
						</a>
					</td>
	            </tr>

	            <?php } ?>
			</tbody>

		</table>

				    </div>
						</div>
					</div>
				</div>
			</div>
		</div>

</section>

<?php 
    include('public/fcm.php');
	require_once("public/thumbnail_images.class.php");
	include_once('functions.php');

 	if(isset($_POST['submit'])) {

		$video_id = '';

		if ($_POST['upload_type'] == 'URL') {

			$video = $_POST['channel_url'];

			$channel_image = time().'_'.$_FILES['channel_image']['name'];
			$pic2			 = $_FILES['channel_image']['tmp_name'];
			$tpath2			 = 'upload/'.$channel_image;
			copy($pic2, $tpath2);

		} else {
			$video = $_POST['youtube'];
			$channel_image = '';		

			function youtube_id_from_url($url) {

		    	$pattern = 
		        '%^# Match any youtube URL
		        (?:https?://)?  # Optional scheme. Either http or https
		        (?:www\.)?      # Optional www subdomain
		        (?:             # Group host alternatives
		          youtu\.be/    # Either youtu.be,
		        | youtube\.com  # or youtube.com
		          (?:           # Group path alternatives
		            /embed/     # Either /embed/
		          | /v/         # or /v/
		          | /watch\?v=  # or /watch\?v=
		          )             # End path alternatives.
		        )               # End host alternatives.
		        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
		        $%x'
		        ;

			    $result = preg_match($pattern, $url, $matches);

			    if (false !== $result) {
			        return $matches[1];
			    }
		    	return false;

			}

			$video_id = youtube_id_from_url($_POST['youtube']);

		}

                $data = array(

					'category_id'  			=> $_POST['category_id'],			
					'channel_name'  		=> $_POST['channel_name'],
					'channel_url'  			=> $video,									
					'video_id' 				=> $video_id,
					'channel_image' 		=> $channel_image,
                    'channel_description'	=> $_POST['channel_description'],
					'channel_type' 			=> $_POST['upload_type']
					);		

 					  $qry = Insert('tbl_channel', $data);									
                      
  					  $_SESSION['msg'] = "";
					  header( "Location:add-channel.php");
					  exit;

 	}

	$wall_qry = "SELECT * FROM tbl_category";
	$wall_result = mysqli_query($connect, $wall_qry);

?>

<script type="text/javascript">

	$(document).ready(function(e) {

	    $("#upload_type").change(function() {
			var type = $("#upload_type").val();

				if (type == "YOUTUBE") {
					$("#direct_url").hide();
					$("#youtube").show();
				}

				if (type == "URL") {
					$("#youtube").hide();
					$("#direct_url").show();
				}
					
		});

		$( window ).load(function() {
		var type=$("#upload_type").val();

			if (type == "YOUTUBE")	{
				$("#direct_url").hide();
				$("#youtube").show();
			}

			if (type == "URL") {
				$("#youtube").hide();
				$("#direct_url").show();
			}

		});

	});

</script>

	<!-- START CONTENT -->
    <section id="content">

        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          	<div class="container">
            	<div class="row">
              		<div class="col s12 m12 l12">
               			<h5 class="breadcrumbs-title">Add New Channel</h5>
		                <ol class="breadcrumb">
		                  <li><a href="dashboard.php">Dashboard</a></li>
		                  <li><a href="channel.php">Manage Channel</a></li>
		                  <li><a class="active">Add New Channel</a></li>
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
		                 		<form method="post" id="form-validation" enctype="multipart/form-data">
		                  			<div class="row">
		                  			   
		                    			<div class="input-field">

			                    			<div class="col s12 m12 l12">
				                    			<?php if(isset($_SESSION['msg'])) { ?>
				                    			<div class="col s12 m12 l12">
													<div class="card-panel teal lighten-2">
													    <span class="white-text text-darken-2">
														New Channel Added Successfully...
														</span>
													</div>
												</div>
												<?php unset($_SESSION['msg']); } ?>
											</div>

			                    		<div class="col s12 m12 l5"> 
												
											<div class="col s12 m12 l12"> 
												<div class="row">
													<div class="input-field col s12">
													    <input type="text" name="channel_name" id="channel_name" placeholder="Channel Name" required/>
													    <label for="channel_name">Channel Name</label>
													</div>
												</div>
											</div>

											<div class="col s12 m12 l12"> 
												<div class="row">
													<div class="input-field col s12">
													    <select name="category_id" id="category_id">
															<?php while ($data = mysqli_fetch_array ($wall_result)) { ?>
															<option value="<?php echo $data['cid'];?>"><?php echo $data['category_name'];?></option>
															<?php } ?>
														</select>
													    <label for="category_id">Category</label>
													</div>
												</div>
											</div>

											<div class="col s12 m12 l12"> 
												<div class="row">
													<div class="input-field col s12">
													    <select name="upload_type" id="upload_type">
															<option value="URL">Streaming URL</option>
															<option value="YOUTUBE">YouTube</option>
														</select>
													    <label for="upload_type">Channel Source</label>
													</div>
												</div>
											</div>

											<div id="youtube" class="col s12 m12 l12"> 
												<div class="row">
													<div class="input-field col s12">
													    <input type="text" name="youtube" id="youtube" placeholder="https://www.youtube.com/watch?v=33F5DJw3aiU" autofocus required/>
													    <label for="youtube">Youtube URL</label>
													</div>
												</div>
											</div>

	                                        <div id="direct_url" class="col s12 m12 l12">

	                                        	<div class="row">
													<div class="input-field col s12">
													    <input type="text" name="channel_url" id="channel_url" placeholder="http://live.metube.id/tv/channel2000022/index512.m3u8" autofocus required/>
													    <label for="youtube">Channel URL</label>
													</div>
												</div>

												<div class="row">
													<div class="input-field col s12">
													    <input type="file" id="input-file-now" name="channel_image" id="channel_image" class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg png gif"/>
													    <br>
													</div>
												</div>

	                                        </div>

                                        </div>

                                        <div class="col s12 m12 l7">
                                        	<div class="row">
								                <div class="input-field col s12">
									                <span class="grey-text text-grey lighten-2">Description</span>
													<textarea name="channel_description" id="channel_description" class="form-control" cols="60" rows="10"></textarea>
													<script>                             
														CKEDITOR.replace( 'channel_description' );
													</script>		
												</div>
								            </div>

								            <br>
								            <button class="btn cyan waves-effect waves-light right"
	                                                type="submit" name="submit">Submit
	                                            <i class="mdi-content-send right"></i>
	                                        </button>

                                        </div>


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
<h2><?php echo $page_title; ?></h2>
<?php 
if (count($_POST)>0) {
	//echo 'Posted';
	if ($updated === TRUE){
		echo '<div class="alert alert-success"><strong>Saved</strong> The form was updated</div>';
		//Go to workouts list
		echo '<script> window.location.href = "'.admin_url().'admin.php?page=edit_workouts"; </script>';
	}
	if ($validated === FALSE){
		echo '<div class="alert alert-success"><strong>That failed, try again</strong>'.$error_msg.'</div>';
		echo '<script> window.location.href = "'.admin_url().'admin.php?page=edit_workouts"; </script>';
	}
}
?>

<?php
//print_r($single_workout);
//$rows = count($single_workout);
//echo 'Size '.$rows. '<br /> Set_name: '.$single_workout[0]->set_name;
?>

<div>
<form method="post" action="<?php echo admin_url(); ?>admin.php?page=edit_single_workout&setid=<?php echo $single_workout[0]->set_id; ?>&action=save">

<table class="table">
<thead>
	<tr>
		<td>Update workout</td>
		<td><?php echo $single_workout[0]->set_id; ?></td>
	</tr>
</thead>
<tbody>	
	<tr>
		<td class="label">Set Id</td>
		<td><input type="text" name="set_id" placeholder="<?php echo $single_workout[0]->set_id; ?>" value="<?php echo $single_workout[0]->set_id; ?>" /></td>
	</tr>
	<tr>
		<td class="label">Set Name</td>
		<td><input type="text" name="set_name" placeholder="<?php echo $single_workout[0]->set_name; ?>" value="<?php echo $single_workout[0]->set_name; ?>" /></td>
	</tr>
	<tr>
		<td class="label">Body Area</td>
		<td><input type="text" name="body_area" placeholder="<?php echo $single_workout[0]->body_area; ?>" value="<?php echo $single_workout[0]->body_area; ?>" /></td>
	</tr>
	
	<tr>
		<td class="label">Sets No</td>
		<td><input type="text" name="sets_number" placeholder="<?php echo $single_workout[0]->sets_number; ?>" value="<?php echo $single_workout[0]->sets_number; ?>" /></td>
	</tr>
	<tr>
		<td class="label">Workout Sheet Id</td>
		<td><input type="text" name="workout_sheet_id" placeholder="<?php echo $single_workout[0]->workout_sheet_id; ?>" value="<?php echo $single_workout[0]->workout_sheet_id; ?>" /></td>
	</tr>
	<tr>
		<td class="label">Video link <a href='https://www.youtube.com/embed/<?php echo $single_workout[0]->video_link; ?>?rel=0&amp;autoplay=1' data-featherlight='iframe' data-featherlight-iframe-width='640' data-featherlight-iframe-height='480' data-featherlight-iframe-frameborder='0' data-featherlight-iframe-allow='autoplay; encrypted-media' data-featherlight-iframe-allowfullscreen='true'> Play video</a></td>
		<td><input type="text" name="video_link" placeholder="<?php echo $single_workout[0]->video_link; ?>" value="<?php echo $single_workout[0]->video_link; ?>" /></td>
	</tr>	
	<tr>
		<td class="label">Reps</td>
		<td><input type="text" name="reps" placeholder="<?php echo $single_workout[0]->reps; ?>" value="<?php echo $single_workout[0]->reps; ?>" /></td>
	</tr>
	<tr>
		<td class="label">Tempo</td>
		<td><input type="text" name="tempo" placeholder="<?php echo $single_workout[0]->tempo; ?>" value="<?php echo $single_workout[0]->tempo; ?>" /></td>
	</tr>
	<tr>
		<td class="label">Rest</td>
		<td><input type="text" name="rest" placeholder="<?php echo $single_workout[0]->rest; ?>" value="<?php echo $single_workout[0]->rest; ?>" /></td>
	</tr>
	<tr>
		<td class="label">Notes</td>
		<td><input type="text" name="notes" placeholder="<?php echo $single_workout[0]->notes; ?>" value="<?php echo $single_workout[0]->notes; ?>" /></td>
	</tr>		
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" class="button" value="Save"></td>
	</tr>
</tbody>
</table>
</form>
</div>
<!-- <script type="text/javascript" src="http://localhost:8888/gazbfit/prod/wp-content/plugins/wp-featherlight/js/wpFeatherlight.pkgd.min.js" id="wp-featherlight-js"></script> -->
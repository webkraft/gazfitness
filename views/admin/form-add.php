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
<form method="post" action="<?php echo admin_url(); ?>admin.php?page=add_single_workout&action=save">

<table class="table">
<thead>
	<tr>
		<td>Add workout</td>
		<td>#</td>
	</tr>
</thead>
<tbody>	
	<tr>
		<td class="label">Set Name</td>
		<td><input type="text" name="set_name"/></td>
	</tr>
	<tr>
		<td class="label">Body Area</td>
		<!-- <td><input type="text" name="body_area"/></td> -->
		<td>
		<select name="body_area">
		  <option value="Lower body">Lower body</option>
		  <option value="Upper body">Upper body</option>
		  <option value="Legs">Legs</option>
		  <option value="Push">Push</option>
		  <option value="Pull">Pull</option>
		  <option value="Upper body 2nd">Upper body 2nd</option>
		</select>
		</td>
		
	</tr>
	<tr>
		<td class="label">Number of Sets</td>
		<td><input type="text" name="sets_number"/></td>
	</tr>
	<tr>
		<td class="label">Workout Sheet</td>
		<!-- <td><input type="text" name="workout_sheet_id"/></td> -->
		<td>
		<select name="workout_sheet_id">
			<option value="1">5 Day Workout 1</option>
			<option value="2">5 Day Workout 2</option>
			<option value="3">5 Day Workout 3</option>
			<option value="4">5 Day Workout 4</option>
			<option value="5">5 Day Workout 5</option>
			<option value="6">5 Day Workout 6</option>
		</select>
		</td>
	</tr>
	<tr>
		<td class="label">Video Link</td>
		<td><input type="text" name="video_link"/></td>
	</tr>	
	<tr>
		<td class="label">Reps</td>
		<td><input type="text" name="reps"/></td>
	</tr>
	<tr>
		<td class="label">Tempo</td>
		<td><input type="text" name="tempo"/></td>
	</tr>
	<tr>
		<td class="label">Rest</td>
		<td><input type="text" name="rest"/></td>
	</tr>
	<tr>
		<td class="label">Notes</td>
		<td><input type="text" name="notes"/></td>
	</tr>		
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" class="button" value="Save"></td>
	</tr>
</tbody>
</table>
</form>
</div>
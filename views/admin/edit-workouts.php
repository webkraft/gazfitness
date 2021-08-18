<?php 
/*
*
* CRUD workout details - name, video link, workout number, no sets, reps...
*
*/
?>
<h2><?php echo $page_title; ?></h2>
<?php
//print_r($all_workouts);
$rows = count($all_workouts);
echo 'Rows: '.$rows;
?>

<div class="table-wrapper">
<table class="table table-responsive">
<thead>
<tr>
<th>Set ID</th>
<th>Set Name</th>
<th>Body Area</th>
<th>Set Number</th>
<th>Workout Sheet Id</th>
<th>Video link</th>
<!-- <th>Weight</th> -->
<th>Reps</th>
<th>Temp</th>
<th>Notes</th>
</tr>
</thead>
<tbody>
<?php foreach ($all_workouts as $workout) : ?>
<tr>
<td><?php echo $workout->set_id; ?></td>
<td><?php echo $workout->set_name; ?></td>
<td><?php echo $workout->body_area; ?></td>
<td><?php echo $workout->sets_number; ?></td>
<td><?php echo $workout->workout_sheet_id; ?></td>
<td><a href='https://www.youtube.com/embed/<?php echo $workout->video_link; ?>?rel=0&amp;autoplay=1' data-featherlight='iframe' data-featherlight-iframe-width='640' data-featherlight-iframe-height='480' data-featherlight-iframe-frameborder='0' data-featherlight-iframe-allow='autoplay; encrypted-media' data-featherlight-iframe-allowfullscreen='true' target='_blank'><?php echo $workout->video_link; ?></a></td>
<!-- <td>Added by user</td> -->
<td><?php echo $workout->reps; ?></td>
<td><?php echo $workout->tempo; ?></td>
<td><?php echo $workout->rest; ?></td>
<td><?php echo $workout->notes; ?></td>
<td><a href="<?php echo admin_url(); ?>admin.php?page=edit_single_workout&set_id=<?php echo $workout->set_id; ?>" class="button">Edit</a></td>
<tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
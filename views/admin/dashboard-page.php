<div>
<h2><?php echo $page_title; ?></h2>
<a href="<?php echo site_url() ?>/wp-admin/admin.php?page=edit_workouts">Edit workouts</a>
</div>

<?php
/*
print_r($subscriber_activity);
$rows = count($subscriber_activity);
echo 'Rows: '.$rows;
*/
?>

<div class="table-wrapper-x">
<h3>GazBFit Subscribers</h3>
<table class="table table-responsive">
<thead>
<tr>
<th>ID</th>
<th>Display Name</th>
<th>Meta Value</th>
<th>User Email</th>
</tr>
</thead>
<tbody>
<?php foreach ($subscribers as $subscriber) : ?>
<tr>
<td><?php echo $subscriber->ID; ?></td>
<td><?php echo $subscriber->display_name; ?></td>
<td><?php echo $subscriber->meta_value; ?></td>
<td><?php echo $subscriber->user_email; ?></td>
<tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<div class="table-wrapper-x">
<h3>Subscriber activity (User id 9)</h3>
<table class="table table-responsive">
<thead>
<tr>
<th>Weight</th>
<th>Sheet Name</th>
<th>Set Name</th>
<th>Complete Date</th>
</tr>
</thead>
<tbody>
<?php foreach ($subscriber_activity as $activity) : ?>
<tr>
<td><?php echo $activity->weight; ?></td>
<td><?php echo $activity->sheet_name; ?></td>
<td><?php echo $activity->set_name; ?></td>
<td><?php echo $activity->complete_date; ?></td>
<tr>
<?php endforeach; ?>
</tbody>
</table>
</div>


<?php
//$current_user = wp_get_current_user();
//print_r($current_user);
//print_r($current_user->wp_capabilities);
//echo '<br><br>User type: '.$current_user->roles[0];
?>


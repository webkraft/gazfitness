<div style="padding: 30px;border: 1px solid red;">
<h2><?php echo $page_title; ?></h2>
<p>Clients...</p>
<p>Run scripts for development</p>
</div>

<?php 

$current_user = wp_get_current_user();
//print_r($current_user);
//print_r($current_user->wp_capabilities);
echo '<br><br>User type: '.$current_user->roles[0];
?>
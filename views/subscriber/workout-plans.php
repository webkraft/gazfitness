<!--
*
* Display workout links
* Links display all the workouts, user plays video and enters weights.
*
-->
<h2><?php echo $page_title; ?></h2>
Hi <?php echo($name)." (".$user_id.")" ?>

<?php
//echo do_shortcode("[sc_save_workout_set]");
?>

<!--
~~ If page is reloaded the workouts are cleared - use the ajax link to load back the results
~~ Use the WP heartbeat to check network connection
?Link to plan - myPlan(workoutId, bodyAreaName, setId)
-->

<!-- Notices -->
<div id="workout_plans_notice"></div>

<!-- Workout links -->
<div id="workout_plans_worksheet_links"></div>

<!-- Body areas notice -->
<div id="workout_plans_bodyareas_message"></div>
<!-- Body areas links -->
<div id="workout_plans_bodyareas_links"></div>

<!-- Workout sets -->
<div id="workout_sets"></div>
<div id="workout_plans_results"></div>
<!-- <div id="workout_plans_mydata"></div> -->

<!--  -->
<p id="selected_workoutplan"></p>
<div id="workout_display_div">
<?php //print_r($workouts); ?>
<?php //print_r($workout_sheets); ?>
</div>
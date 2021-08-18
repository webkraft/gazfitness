<!--
*
* Display workout links
* Links display all the workouts, user plays video and enters weights.
*
-->
<!-- <h2><?php //echo $page_title; ?></h2> -->
Hi <?php echo($name)." (".$user_id.")" ?>
<div id="feedback_notice" style="margin-bottom: 20px;"></div>
<?php
echo '<p id="default_current_day" style="display:none;">'.$default_current_day.'</p>';
echo '<p id="default_current_workout_sheet" style="display:none;">'.$default_current_workout_sheet.'</p>';
echo '<p id="workout_level" style="display:none;">'.$workout_level.'</p>';
echo '<p id="workout_goal" style="display:none;">'.$workout_goal.'</p>';
echo '<p id="workout_days" style="display:none;">'.$workout_days.'</p>';
echo '<p id="workout_sheets" style="display:none;">'.$workout_sheets.'</p>';
?>
 
<?php //echo do_shortcode("[sc_save_workout_set]"); ?>

<!--
~~ If page is reloaded the workouts are cleared - use the ajax link to load back the results
~~ Use the WP heartbeat to check network connection
?Link to plan - myPlan(workoutId, bodyAreaName, setId)
-->

<!-- //////////////////- App -//////////////////// -->
<div id="workout_user_settings" style="margin-bottom: 10px;"></div>

<div id="workout_plan_days_notice" style="margin-top: 20px;"></div>
<div id="workout_plan_days"></div>

<!-- Notices -->
<div id="workout_plans_notice" style="margin-top: 20px;"></div>
<!-- <p style="font-size: 8px;">Show day progress</p> -->

<!-- Workout links - areas from predefined workout number of days and level (body areas)-->
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

<div style="padding: 30px;border: 1px solid red;">
<h2><?php echo $page_title; ?></h2>
<p>Demo page</p>

<a href="<?php echo site_url() ?>/workout-plans">workout-plans</a>

<pre>
Todo: shortcode to


----- Save data
member_admin_save

Admin page to save workouts


--- Set Workout Plan
-- Display user data and get their workout plan or links to the plan pages
[user_workout_plan]
http://localhost:8888/gazbfit/prod/set-workout-plan/


--- Member admin
[membership_admin]
localhost:8888/gazbfit/prod/member-admin/


--- MY account - display workouts


</pre>

</div>

<?php 
$current_user = wp_get_current_user();
echo '<br><br>User type: '.$current_user->roles[0];
?>
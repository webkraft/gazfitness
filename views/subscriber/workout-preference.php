<h2><?php echo $page_title; ?></h2>
<?php //echo($name)." (".$user_id.")" ?>
<h3>Enter your workout preference to get started</h3>

<!-- Start with selecting a difficulty level -->
<div id="workout_personal_details" style="margin-bottom: 20px;">
<h4>Body specs</h4>
<div class="feedback_notice" style="margin-bottom: 20px;"></div>
<label>Age</label>
<input type="number" min="16" max="99" class="number checknull" name="age" id="age" placeholder="Your age" value=""/>

<label>Height</label>
<input type="number" min="60" max="220" class="number checknull" name="height" id="height" placeholder="Your height (cm)" value=""/>

<label>Weight</label>
<input type="number" min="40" max="140" class="number checknull" name="weight" id="weight" placeholder="Your weight (kg)" value=""/>

<input type="radio" class="radio-workoutlevel" value="m" name="gender" id="gender_male" checked="true">
<label for="gender_male">Male</label>
<input type="radio" class="radio-workoutlevel" value="f" name="gender" id="gender_female">
<label for="gender_female">Female</label>
</div>

<!-- Start with selecting a difficulty level -->
<div id="workout_level" style="margin-bottom: 20px;">
<!-- If has been entered then dont show -->
<h4>Select your workout level</h4>
<?php //if( @$company->name_changed == "yes" ) { echo "checked"; } ?>

<input type="radio" class="radio-workoutlevel" value="1" name="workout_level" id="workout_level_1" checked="true">
<label for="workout_level_1">Beginner</label>

<input type="radio" class="radio-workoutlevel" value="1" name="workout_level" id="workout_level_2">
<label for="workout_level_2">Intermediate</label>

<input type="radio" class="radio-workoutlevel" value="3" name="workout_level" id="workout_level_3">
<label for="workout_level_3">Advanced</label>
</div>

<!-- Select workout goal -->
<div id="workout_days" style="margin-bottom: 20px;">
<h4>What's your workout goal?</h4>
<input type="radio" class="radio-workoutlevel" value="1" name="workout_goal" id="workout_goal_weight" checked="true">
<label for="workout_goal_weight">Weight loss</label>

<input type="radio" class="radio-workoutlevel" value="2" name="workout_goal" id="workout_goal_building">
<label for="workout_goal_building">Muscle Building</label>
</div>

<!-- Select the number of days workout -->
<div id="workout_days" style="margin-bottom: 20px;">
<h4>Select number of workout days</h4>
<input type="radio" class="radio-workoutlevel" value="4" name="workout_days" id="workout_days_1" checked="true">
<label for="workout_days_1">4 days/week</label>

<input type="radio" class="radio-workoutlevel" value="5" name="workout_days" id="workout_days_2">
<label for="workout_days_2">5 days/week</label>
</div>
<div class="feedback_notice" style="margin-bottom: 20px;"></div>
<a href="#" onclick="validateForm();return false;" id="submit-btn" class="button enter-weights-btn">Save</a>


<script type="text/javascript">
function validateForm() {
	
	var $ = jQuery;
	var fields_count = 0;
	var validation_count = 0;
	
	jQuery(".checknull").each(function(){
		fields_count ++;
		if ($(this).val() != ''){
			validation_count ++;
		}
    });
	
	if (validation_count == fields_count){
		savePersonalDetails();
	}else{
		//console.log('Not Validated');
		$('.feedback_notice').html("<div class='error-message' style='color:red;'>Check fields for errors</div>");
	}
}
</script>

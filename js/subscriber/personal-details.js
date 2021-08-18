/*
*
* Display table of workouts for user to view videos and enter weights
* Operations: read, write to user 
*/
function savePersonalDetails(){
	
	//Links under the workout shhet links
	var feedback_notice = jQuery('#feedback_notice');
	var save_btn = jQuery('.button');
	
	//Form details
	var _age = jQuery('#age').val();
	var _weight = jQuery('#weight').val();
	var _height = jQuery('#height').val();
	var _gender = jQuery("input:radio[name='gender']:checked").val();
	var _workout_level = jQuery("input:radio[name='workout_level']:checked").val();
	var _workout_goal = jQuery("input:radio[name='workout_goal']:checked").val();
	var _workout_days = jQuery("input:radio[name='workout_days']:checked").val();
	var _workout_sheets = 5;
	
	if(_workout_level == 3){
		_workout_sheets = 6;
	}
	
	
	jQuery.ajax({
		type: 'post',
		url: save_personal_details_form_file.ajaxurl,
		dataType: 'json',
        method: 'POST',
        data: { action: 'save_personal_details_form',
				age: _age,
				weight: _weight,
				height: _height,
				gender: _gender,
				workout_level: _workout_level,
				workout_goal: _workout_goal,
				workout_days: _workout_days,
				workout_sheets: _workout_sheets,
				security: save_personal_details_form_file.nonce
		},

        beforeSend: function() {
			
			//check fields
			feedback_notice.html("<div class='loading-message'><i class='fa fa-refresh fa-spin fa-fw'></i> Saving details..</div>");
			save_btn.html("<div class='loading-message'><i class='fa fa-refresh fa-spin fa-fw'></i> Saving</div>");
        },
		success: function(data){

            console.log(data);
			//True0
			feedback_notice.html("<div class='loading-message'>Saved</div>");
			save_btn.html("<div class='loading-message'>Saved</div>");
			
        },
		complete: function(data){

            console.log(data);
			//True0
			feedback_notice.html("<div class='loading-message'>Saved - Loading, please wait</div>");
			save_btn.html("<div class='loading-message'>Saved - Loading, please wait</div>");
			location.reload();
			
        },
        error: function(jqXHR, textStatus, errorThrown){
            //console.log('error: ' + textStatus + ': ' + errorThrown);
			console.log('error: ' + textStatus);
        }
    });
	
//
}
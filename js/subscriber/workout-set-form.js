/*
*
* Display call to get the form markup with values etc
* 
*/
function getWorkoutSetForm(args) {
	
	//console.log('args: ' + args);
	var _args = args;	
	
	jQuery.ajax({
		type: 'post',
		url: get_workout_set_form_file.ajaxurl,
		dataType: 'text',
		//dataType: 'json',
        method: 'POST',
        data: { action: 'get_workout_set_form',
				workoutset_args: _args, 
				security: get_workout_set_form_file.nonce
		},        
        beforeSend: function() {
			
            jQuery('#'+ _args).html("<div class='loading-message'><i class='fa fa-refresh fa-spin fa-fw'></i> Loading weights</div>");
        },
		
        success: function(data){
			
            //console.log('Workout set form data' + data);			
			jQuery('#'+ _args).html(data);
            
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('error: ' + textStatus + ': ' + errorThrown);
        }	
		
    });

//
}
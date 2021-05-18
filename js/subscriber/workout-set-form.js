/*
*
* Display call to get the form markup with values etc
* 
*/
function getWorkoutSetForm(args) {
	
	console.log('args: ' + args);
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
			
            jQuery('#'+ _args).text('Loading entry form...');
        },
        success: function(data){
			
            console.log(data);			
			jQuery('#'+ _args).html(data);
			//("<form><input type='text' name='notes' placeholder='Enter weight' value='' /><input type='submit' class='button' value='Save'></form>");
            
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('error: ' + textStatus + ': ' + errorThrown);
        }
	
		/*{
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
    		
            jQuery('#'+ _args).text('Loading entry form...');
        },
        success: function(data){
    		
            //console.log(data);			
    		jQuery('#'+ _args).html(data);
    		//("<form><input type='text' name='notes' placeholder='Enter weight' value='' /><input type='submit' class='button' value='Save'></form>");
            
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('error: ' + textStatus + ': ' + errorThrown);
        }
		*/
    });

//
}
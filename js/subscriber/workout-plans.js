jQuery('document').ready(function(){

	var $ = jQuery;
	//alert('Workout plans');
	get_workout_links();
	//console.log('Admin');
	
});

/* This gets all the links to choose from */
function get_workout_links(){

    var worksheet_links = jQuery('#worksheet_links');

    jQuery.ajax({
		type: 'post',
		url: get_workout_sheet_names_file.ajaxurl,
		//dataType: 'text',
		dataType: 'json',
        method: 'POST',
        data: { action: 'get_workout_sheet_names',
				security: get_workout_sheet_names_file.nonce
		},        
        beforeSend: function() {
            worksheet_links.text('Loading links...');
        },
        success: function(data){

            console.log(data);
			worksheet_links.text(data);
            
			var link = '';
            jQuery.each(data, function( index, value ) {
                
                link += '<a title="Workout" href="#" onclick="doWorkout('+ value.id +');return false;">'+ value.sheet_name +'</a>&nbsp';
            });
            worksheet_links.html(link);
			
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('error: ' + textStatus + ': ' + errorThrown);
        }
    });
}

/*
//line 245
function account_workouts_display(userid){

    var display_div = jQuery('#wokout_display_div');
    var message = '';

    //Prod
    message += '<p>Thanks for completing the Lifestyle Questionnaire, these workout plans will work for you:</p>';
    message += '<a href="/week-1-advanced-5-day/">Week 1 – Advanced 5 day</a>';
    message += '<br /><br />';

    //Admin set the workout(s), multiple workouts when first have been completed.
    //display_div.html(message);

    jQuery.ajax({
        url: frontend_ajax.ajax_url,
        dataType: 'json',
        method: 'POST',
        data: {
            action: 'get_user_info',
            user_id: userid
        },
      
        beforeSend: function() {
            display_div.text('Loading your user info...');
        },

        success: function(data){

            jQuery.each(data, function( index, value ) {
                message += 'Hi ' + value.user_nicename + ', you are starting on <a href="'+value.workout_url+'" target="_blank">'+ value.sheet_name +'</a>';
            });

            //userinfo.html(user_data);
            display_div.html(message);
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('Line 279 error: ' + textStatus + ': ' + errorThrown);
        }
    });

}

*/
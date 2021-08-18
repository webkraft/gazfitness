/*
*
* Display links of workout sheet body areas
* 
*/
function show_BodyAreaLinks(args){
	
	//Display the workout id for the body areas get
	jQuery('#selected_workoutplan').text(args);
	
	//hide the sets tables
	jQuery('#workout_plans_results').text('');
	jQuery('#workout_plans_notice').html('');
	
	//Links under the workout shhet links
	var bodyarea_links = jQuery('#workout_plans_bodyareas_links');
	//bodyarea_links.text('Loading body areas');
	bodyarea_links.html("<div class='loading-message'><i class='fa fa-refresh fa-spin fa-fw'></i></div>");
	
	var workout_plans_bodyareas_message = jQuery('#workout_plans_bodyareas_message');
	workout_plans_bodyareas_message.html('<span>Choose workout body area</span>');
	
	var _args = args;
	console.log('_args: ' + _args);
	
	//Div for all the workout sets
	var workout_plans_results = jQuery('#workout_plans_results');
	
	/*var workout_plans_bodyareas_links = jQuery('#workout_plans_bodyareas_links');
	var workout_plans_results = jQuery('#workout_plans_results');
	
	//Clear workout_plans_bodyareas_links content and workout_plans_results
	workout_plans_bodyareas_links.html('');
	workout_plans_results.html('');
	*/

	jQuery.ajax({
		type: 'post',
		url: get_workout_bodyarea_file.ajaxurl,
		//dataType: 'text',
		dataType: 'json',
        method: 'POST',
        data: { action: 'get_workout_bodyarea',
				workoutsheetno: _args, 
				security: get_workout_bodyarea_file.nonce
		},        
        beforeSend: function() {
            bodyarea_links.html("<div class='loading-message'><i class='fa fa-refresh fa-spin fa-fw'></i> Loading body areas</div>");
        },
        success: function(data){

            console.log('data' + data);
			workout_plans_results.html('');
			workout_plans_bodyareas_message.html('<span>Choose workout body area</span>');
			bodyarea_links.text(data);
            
			var link = '';
			link += '<ul class="workout-links">';
            jQuery.each(data, function( index, value ) {
				link += '<li><a title="'+ value.body_area +'" href="#" onclick="show_BodyAreaWorkouts(\'' + value.body_area + '\');return false;">'+ value.set_name +'</a></li>';
            });
			link += '</ul>';
            bodyarea_links.html(link);
        }
        /*error: function(jqXHR, textStatus, errorThrown){
            console.log('error: ' + jqXHR + ': ');
        }*/
    });
	
//
}
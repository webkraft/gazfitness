/*
*
* Display table of workouts for user to view videos and enter weights
* Operations: read, write to user 
*/
function show_BodyAreaWorkouts(args){
	
	//Links under the workout shhet links
	var bodyarea_links = jQuery('#workout_plans_results');
	var workout_plans_results = jQuery('#workout_plans_results');
	var workout_plans_mydata = jQuery('#workout_plans_mydata')
	
	var workout_sets = jQuery('#workout_sets');
	
	var workout_plans_bodyareas_message = jQuery('#workout_plans_bodyareas_message');
	//workout_plans_bodyareas_message.text('Choose workout body area');
	var _args = args;
	var _workoutsheetno_text = jQuery('#selected_workoutplan').text();
	var _workoutsheetno = parseInt(_workoutsheetno_text);
	//console.log('args: ' + args);
	
	// add a form
	//var form_markup = '<div class="workout-set-form"><form method="post" action="">';
	//</form></div>	
	//var field_markup = '<input type="text" name="notes" placeholder="Enter weight" value="" />';

	jQuery.ajax({
		type: 'post',
		url: get_bodyarea_workouts_file.ajaxurl,
		//dataType: 'text',
		dataType: 'json',
        method: 'POST',
        data: { action: 'get_bodyarea_workouts',
				bodyareaname: _args,
				workoutsheetno: _workoutsheetno,
				security: get_bodyarea_workouts_file.nonce
		},        
        beforeSend: function() {
            //bodyarea_links.text('Loading workouts');			
			bodyarea_links.html("<div class='loading-message'><i class='fa fa-refresh fa-spin fa-fw'></i> Loading workouts</div>");			
			//workout_plans_mydata.text('Results...');	
        },
        success: function(data){

            //console.log(data);
			//workout_plans_mydata.text(JSON.stringify(data));
			
			/* Display sets */			
			var table = '';
			var tablerow = '';			
			table += "<table><thead style='background: white;'><tr><th></th><th>No Sets</th><th>Reps</th><th>Tempo</th><th>Rest</th><th>Notes</th></tr></thead>";
			table += "<tbody>";
			
			jQuery.each(data, function(index, value){
				
				var i=0;
				var tableform = '';
				
				//Get the forms from an ajax request triggered by getWorkoutSetForm
				var form_args = _workoutsheetno_text+"_"+value.set_id+"_"+value.sets_number;
				var div_args = _workoutsheetno_text+value.set_id;
				
                tablerow += "<tr style='background: #f2f2f2;'><td><span style='padding-left:5px;'><strong>" + value.set_name + "</strong></span><a class='btn-clear' href='https://www.youtube.com/embed/" + value.video_link + "?rel=0&amp;autoplay=1' data-featherlight='iframe' data-featherlight-iframe-width='640' data-featherlight-iframe-height='480' data-featherlight-iframe-frameborder='0' data-featherlight-iframe-allow='autoplay; encrypted-media' data-featherlight-iframe-allowfullscreen='true'><i class='fa fa-play-circle' aria-hidden='true'></i> Play video</a> <a href='#' onclick='getWorkoutSetForm(\""+form_args+"\");return false;' class='button enter-weights-btn'>Enter weights</a></td><td>" + value.sets_number + "</td><td>" + value.reps + "</td><td>" + value.tempo + "</td><td>" + value.rest + "</td><td>" + value.notes + "</td></tr>";
				
				tablerow += "<tr style='background: white;'><td colspan='6'>";
				tablerow += "<div id='"+form_args+"'>";
				tablerow += "</div></td></tr>";
				
            });
			
			table += tablerow;
			table += "</tbody>";
			table += "</table>";
						
			workout_plans_results.html(table);
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('error: ' + textStatus + ': ' + errorThrown);
        }
    });
	
//
}

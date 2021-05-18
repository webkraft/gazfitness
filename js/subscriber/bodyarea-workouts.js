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
	workout_plans_bodyareas_message.text('Choose workout body area');
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
            bodyarea_links.text('Loading workouts');
			workout_plans_mydata.text('Results...');	
        },
        success: function(data){

            console.log(data);
			//workout_plans_mydata.text(JSON.stringify(data));	
			
			/* Display sets */
			//workout_sets = 'sdfsdf';
			
			var table = '';
			var tablerow = '';
			
			table += "<table><thead style='background: #f2f2f2;'><tr><th>Workout</th><th>No Sets</th><th>Reps</th><th>Tempo</th><th>Rest</th><th>Notes</th></tr></thead>";
			table += "<tbody>";
			
			jQuery.each(data, function(index, value){
				
				var i=0;
				var tableform = '';
				
				//Get the forms from an ajax request triggered by getWorkoutSetForm
				//Form parameters
				var form_args = _workoutsheetno_text+"_"+value.set_id+"_"+value.sets_number;
				var div_args = _workoutsheetno_text+value.set_id;
				
				//getWorkoutSetForm();return false;
				//var click = "getWorkoutSetForm('"+form_args+"');return false;";
				
				//var workoutsheetno_text_stg = _workoutsheetno_text.toString();
				//var set_id_stg = value.set_id.toString();				
				//var form_args = workoutsheetno_text_stg+"_"+set_id_stg;
				
                tablerow += "<tr><td>" + value.set_name + " <a href='https://www.youtube.com/embed/" + value.video_link + "?rel=0&amp;autoplay=1' data-featherlight='iframe' data-featherlight-iframe-width='640' data-featherlight-iframe-height='480' data-featherlight-iframe-frameborder='0' data-featherlight-iframe-allow='autoplay; encrypted-media' data-featherlight-iframe-allowfullscreen='true'> Play video</a> <a href='#' onclick='getWorkoutSetForm(\""+form_args+"\");return false;' class='button enter-weights-btn'>Enter weights (drop down)</a></td><td>" + value.sets_number + "</td><td>" + value.reps + "</td><td>" + value.tempo + "</td><td>" + value.rest + "</td><td>" + value.notes + "</td></tr>";	
				
				//+form_args+
				
				//var form_header = "<form method='post' action='http://localhost:8888/gazbfit/prod/wp-admin/admin.php?page=save_workout_set&workoutplan="+workout_plan+"&setid="+set_id+"&setno="+value.sets_number+"&action=save'>";
				
				//For each value.reps add fields	
				/*var ii = 0;
                for (i=0; i<value.sets_number; ++i){
					ii++;
					tableform += "<input type='text' name='"+ ii +"' placeholder='Enter weight' value='' />";
				}*/
				
				//tablerow += "<tr style='background: #f5f5f5;'><td colspan='6'>";
				//tablerow += "<div class='workout-set-form'>";
				//tablerow += form_header;//"<form method='post' action='save'>";
				//tablerow += tableform;
				//tablerow += "<input type='submit' class='button' value='Save'>";
				//tablerow += "</form></div>";
				//tablerow += "</td></tr>";
				
				tablerow += "<tr style='background: #f5f5f5;'><td colspan='6'>";
				tablerow += "<div id='"+form_args+"'>";
				tablerow += "</div></td></tr>";
				
            });
			
			table += tablerow;
			table += "</tbody>";
			table += "</table>";
			
			/*
			* Drop down/list workouts (Deadlift, )
			* Insert a form to save / show the weights
			*
			*/
			
			//var tablerow = '';
			/*tablerow += "<table><thead style='background: #f2f2f2;'><tr><th></th><th>Workout</th><th>No Sets</th><th>Reps</th><th>Tempo</th><th>Rest</th><th>Notes</th></tr></thead>";
			tablerow += "<tbody>";
			
			tablerow += "<tr>";
			tablerow += "<td>XXXX</td>";
			tablerow += "</tr>";
			
			tablerow += "</tbody>";
			tablerow += "</table>";
			*/
			
			workout_plans_results.html(table);
			
			/*
			var link = '';
            jQuery.each(data, function( index, value ) {
				link += '<a title="Body area" href="#" onclick="show_BodyAreaWorkouts('+ value.id +');return false;">'+ value.body_area +'</a>&nbsp';
				
            });
			*/
            //bodyarea_links.html(link);
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('error: ' + textStatus + ': ' + errorThrown);
        }
    });
	
//
}

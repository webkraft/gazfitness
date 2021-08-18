/*
*
* Display table of workouts for user to view videos and enter weights
* Operations: read, write to user 
*/
function show_BodyAreaWorkouts(args){
	
	//console.log('show_BodyAreaWorkouts args: ' + args);
	
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

	jQuery.ajax({
		type: 'post',
		url: get_bodyarea_workouts_file.ajaxurl,
		//dataType: 'text',
		dataType: 'json',
        method: 'POST',
        data: { action: 'get_bodyarea_workouts',
				bodyarea_ids: _args,
				//workoutsheetno: _workoutsheetno,
				security: get_bodyarea_workouts_file.nonce
		},        
        beforeSend: function() {
			bodyarea_links.html("<div class='loading-message'><i class='fa fa-refresh fa-spin fa-fw'></i> Loading workouts</div>");		
        },
        success: function(data){

            //console.log(data);
			//workout_plans_mydata.text(JSON.stringify(data));
			
			/* Display sets in divs*/
			var set_wrapper_div = "";
			var set_inner_div = "";
			
			set_wrapper_div += "<div class='set-wrapper'>";
			
			jQuery.each(data, function(index, value){
				
				var i=0;				
				//Get the forms from an ajax request triggered by getWorkoutSetForm
				var form_args = value.set_id+"_"+value.set_id+"_"+value.sets_number;
				//_workoutsheetno_text+"_"+value.set_id+"_"+value.sets_number;
				var div_args = _workoutsheetno_text+value.set_id;
				
				//process weight entries
				var weights_comp = 0;
				var weights_done = 0;
				var weights_progress = '';
				
				if (value.weight !== null){
					var existing_weights = value.weight;
					var finalData = value.weight.replace(/\\/g, "");	
					var weightsArr = JSON.parse(finalData);
					console.log("weightsArr: " + weightsArr);
					
					//body area progress
					//var body_area;
					
					jQuery.each( weightsArr, function( i, val ) {
						//$( "#" + val ).text( "Mine is " + val + "." );
						if(val != ''){
							weights_comp ++;
						}
					});
					
					//weights_comp = weightsArr.length;
					//console.log("weightsArr: " + weightsArr.join("kg, "));
					weights_done = weightsArr.join("Kg, ");
					
					//progress bar
					var progressbar = (weights_comp / value.sets_number)*100;
					
					weights_progress += "<div class='progress' style='background:#f1f1f1;padding:5px;margin-top: 5px;'><span style='text-transform: uppercase;font-size: 12px;'>Progress</span>";
					weights_progress +=	"<div style='width:"+progressbar+"%; background:rgb(18 171 36 / 50%);margin-top: -28px;'>&nbsp;</div>";
					weights_progress += "</div>";
				}
				
				//notes - null values
				var notes_text = "";
				if (value.notes !== null){
					notes_text = "<span style='font-size:12px'>Note: "+ value.notes +"</span>";					
				}
				
				
				set_inner_div += "<div class='set-inner' id='"+div_args+"'>";
				
				set_inner_div += "<div class='set-title'><span><strong>" + value.set_name + "</strong></span></div>";
				
				set_inner_div += "<div class='play-link'><a class='btn-clear' href='https://www.youtube.com/embed/" + value.video_link + "?rel=0&amp;autoplay=1' data-featherlight='iframe' data-featherlight-iframe-width='640' data-featherlight-iframe-height='480' data-featherlight-iframe-frameborder='0' data-featherlight-iframe-allow='autoplay; encrypted-media' data-featherlight-iframe-allowfullscreen='true'><i class='fa fa-play-circle' aria-hidden='true'></i> Play video</a></div>";
				
				set_inner_div += "<div class='edit-link'><a href='#' style='max-width:100%' onclick='getWorkoutSetForm(\""+form_args+"\");return false;' class='button enter-weights-btn'>Enter/View Weights</a></div>";
				
				//set_inner_div += "<div class='progress'>Completed: " + weights_comp + " of " + value.sets_number + ": "+weights_done+"Kg</div>";
				//set_inner_div += process_weights(value.weight, value.sets_number);
				set_inner_div += weights_progress;
				
				set_inner_div += "<div class='set-specs'><div class='sets'><i class='fa fa-th' aria-hidden='true'></i><p>" + value.sets_number + "</p><span>Sets</span></div><div class='reps'><i class='fa fa-repeat' aria-hidden='true'></i><p>" + value.reps + "</p><span>Reps</span></div><div class='tempo'><i class='fa fa-signal' aria-hidden='true'></i><p>" + value.tempo + "</p><span>Tempo</span></div><div class='rest'><i class='fa fa-retweet' aria-hidden='true'></i><p>" + value.rest + "</p><span>Rest</span></div><div class='notes' style='width:100%;text-align: left;'>"+ notes_text +"</div></div>";
				
				set_inner_div += "<div id='"+form_args+"'></div>";
				set_inner_div += "</div>";				
            });
			
			set_wrapper_div += set_inner_div;
			set_wrapper_div += "</div>";
						
			workout_plans_results.html(set_wrapper_div);
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('error: ' + textStatus + ': ' + errorThrown);
        }
    });
	
//
}

/*
function process_weights(value.weight, value.sets_number) {
	
	var weights_comp = 0;
	var weights_done = 0;
	
	return "";
}
				
var weights_comp = 0;
var weights_done = 0;
if (value.weight !== null){
	var existing_weights = value.weight;
	var finalData = value.weight.replace(/\\/g, "");	
	var weightsArr = JSON.parse(finalData);
	weights_comp = weightsArr.length;
	//console.log("weightsArr: " + weightsArr.join("kg, "));
	weights_done = weightsArr.join("Kg, ");
}
*/
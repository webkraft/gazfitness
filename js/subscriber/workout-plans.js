/*
*
* Display top level links
* 
*/
jQuery('document').ready(function(){
	
	console.log('Startup...');
	get_workout_sheet();
	//get_workout_links();	
});

function get_workout_sheet(){
	
	//Get local storage if exists
	//console.log('Running get_workout_days - get number of workout days and display day links - buttons dont work, just an indicator');
	
	//Select from gbf_workout_user	
	var workout_user_settings = jQuery('#workout_user_settings');
	
	var _workout_days = 0;
	var _workout_goal = 0;
	var _workout_level = 0;
	var _workout_sheets = 0;
	
	jQuery.ajax({
		type: 'post',
		url: get_workout_user_file.ajaxurl,
		//dataType: 'text',
		dataType: 'json',
        method: 'POST',
        data: { action: 'get_workout_user',
				security: get_workout_user_file.nonce
		},        
        beforeSend: function() {
			workout_user_settings.html("<div class='loading-message'><i class='fa fa-refresh fa-spin fa-fw'></i> Loading workouts</div>");			
        },
        success: function(data){

            //console.log(data);
			workout_user_settings.html('');
			jQuery('#workout_plan_days_notice').html('<h4 style="margin-bottom: 10px;">Choose workout set</h4>');
			
			jQuery.each(data, function(index, value){
				//value.notes
				_workout_days = value.workout_days;
				_workout_sheets = value.workout_sheets;
				_workout_goal = value.workout_goal;
				_workout_level = value.workout_level;
				
				workout_user_settings.append('<h4 style="margin-bottom:10px">Workout meal plans</h4>');
				//workout_user_settings.append('<span style="font-size:14px;display:block;">Workout days: ' + value.workout_days + '</span>');
				//workout_user_settings.append('<span style="font-size:14px;display:block;">Workout sheets: ' + value.workout_sheets + '</span>');
				//workout_user_settings.append('<span style="font-size:14px;display:block;">Workout level: ' + value.workout_level + '</span>');
				
				//workout_user_settings.append('Workout goal: ' + value.workout_goal);
				if(value.workout_goal == 1){
				//workout_user_settings.append('<span style="font-size:14px;display:block;">Workout goal: Weight loss <a href="#">View meal plans</a></span>');
				
				workout_user_settings.append('<span style="font-size:14px;display:block;">Weight loss meal plans</span>'); //Workout goal:
				workout_user_settings.append('<span style="font-size:14px;display:block;"><a href="/wp-content/uploads/1600.pdf" target="_blank">Daily target 1600</a></span>');
				workout_user_settings.append('<span style="font-size:14px;display:block;"><a href="/wp-content/uploads/1800.pdf" target="_blank">Daily target 1800</a></span>');
				workout_user_settings.append('<span style="font-size:14px;display:block;"><a href="/wp-content/uploads/2000.pdf" target="_blank">Daily target 2000</a></span>');
				
				}
				if(value.workout_goal == 2){
				//workout_user_settings.append('<span style="font-size:14px;display:block;">Workout goal: Muscle Building <a href="#">View meal plans</a></span>');
				
				workout_user_settings.append('<span style="font-size:14px;display:block;">Muscle building meal plans</span>'); //Workout goal: 
				workout_user_settings.append('<span style="font-size:14px;display:block;"><a href="/wp-content/uploads/1700.pdf" target="_blank">Daily target 1700</a></span>');
				workout_user_settings.append('<span style="font-size:14px;display:block;"><a href="/wp-content/uploads/1900.pdf" target="_blank">Daily target 1900</a></span>');
				}
				
            });			
			
			/*
			- Select from gbf_workout_sheet 
			
			or from workout_day
			workout sheets > Day > workout sets
			
			workout sheets:
			ID	sheet name						Level	days
			7	Level 1 5 Day workout templates	3		5
			
			workout day:			
			
			
			
			and get workouts from there
			*/
			
			// ------------			
			var workout_plan_days = jQuery('#workout_plan_days');
			var days_no = _workout_days;
			//var workout_sheets = _workout_sheets;
			var _disabled = '';
			var _checked = '';
			
			//Saved in local storage and database
			// How many days have passed since stated?
			
			// What day has been completed?			
			var _current_day = parseInt(jQuery('#default_current_day').text());//_workout_days;
			var _current_level = _workout_level; //3;
			//var _workout_days = 4;
			
			day_link_radio = '';	
			for(var i=1; i<=_workout_sheets; i++){
				
				/*
				if(i == _current_day){
					_checked = 'checked="true"';
				}else {
					_checked = '';
				}	
				
				if(i != _current_day){
					_disabled = 'disabled';
				}else {
					_disabled = '';
				}*/
				
				/*
				--- For display for now
				*/
				day_link_radio += '<input type="radio" class="radio-workoutlevel" value="'+i+'" name="workout_level" id="day_'+i+'" '+_disabled+' '+_checked+' onclick="worksheet_select('+i+');return false;"><label for="day_'+i+'">Workout set '+i+'</label>';
			}
			workout_plan_days.html(day_link_radio);
			
			/*
			* On select or auto select run workout links
			* get_workout_links($workout_day);
			* save the level to local storage
			* -- Select from gbt_workout_user - workout_level
			*/
			
			//var _current_day = parseInt(jQuery('#default_current_day').text());//_workout_days;
			//var _current_level = parseInt(jQuery('#workout_level').text());//_workout_days;
			
			
			/* ---------------------------------------------------------------
			-- Click on the workout sheet option - reload the get_workout_links
			-- Get workout sheet number from the radio list
			*/
			
			//var workout_radio_selected = 5;
			//get_workout_links(workout_radio_selected, _current_level);
			//get_workout_links(_workout_days, _current_level); //_current_day
			
		
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('error: ' + textStatus + ': ' + errorThrown);
        }
    });
	
	/*
	var day_link = '<ul class="workout-links">';
	for(var i=1; i<days_no; i++){
		day_link += '<li><a href="#" onclick="save_daySelection('+i+');return false;">Day '+i+'</a></li>';
	}
	day_link += '</ul>';
	workout_plan_days.html(day_link);
	*/	
}

/* This gets all the links to choose from */
function get_workout_links(_current_day, _current_level){

	//Get local storage if exists -- selection
	//console.log('_current_day: ' + _current_day);
    var workout_plans_worksheet_links = jQuery('#workout_plans_worksheet_links');
	
	jQuery.ajax({
		type: 'post',
		url: get_workout_sheet_names_file.ajaxurl,
		
		//Get the progress of each link
		
		//dataType: 'text',
		dataType: 'json',
        method: 'POST',
        data: { action: 'get_workout_sheet_names',
				day: _current_day,
				level: _current_level,
				security: get_workout_sheet_names_file.nonce
		},        
        beforeSend: function() {
            workout_plans_worksheet_links.html("<div class='loading-message'><i class='fa fa-refresh fa-spin fa-fw'></i> Loading workouts</div>");
        },
        success: function(data){
			
			//console.log (JSON.stringify(data));
			//workout_plans_worksheet_links.text(data);
			jQuery('#workout_plans_notice').html('<h4 style="margin-bottom: 10px;">Choose body area</h4>');
			
			var links = '';
			var body_area_radio = '';
			
			links += '<ul class="workout-links">';
            jQuery.each(data, function(index, value){
				
				var ids = "'"+value+"'";
				var optionId = index.replace(/ /g,"_");
				var options = "'"+optionId+"'";
				//console.log('links: ' + ids);
				//This will call a query with set ids (1,2,3..) and area name				
				//links += '<li><a title="Body area" href="#" onclick="show_BodyAreaLinks('+ ids +');return false;">'+ index +'</a></li>';
				//links += '<li><a title="'+index+'" href="#" onclick="show_BodyAreaWorkouts('+ ids +');return false;">'+ index +'</a></li>';
				
				// Get progress from the SDS function
				body_area_radio += '<input type="radio" class="radio-workoutlevel" value="'+ ids +'" name="body_areas" id="'+optionId+'" onclick="makeChecked('+options+'); show_BodyAreaWorkouts('+ids+');return false;"><label for="'+optionId+'">'+index+' <br /></label>';
				//<span style="font-size:11px;">(1 of 5 completed)<span>
            });
			//write database results
			//links += '</ul>';
            workout_plans_worksheet_links.html(body_area_radio); //links
			
        },
        error: function(jqXHR, textStatus, errorThrown){
            //console.log('error: ' + textStatus + ': ' + errorThrown);
        }
    });
}

function makeChecked(attr){
		//console.log('attr: ' + attr);
		jQuery('#workout_plans_worksheet_links .radio-workoutlevel').removeClass('active');
		jQuery('#workout_plans_worksheet_links #'+attr+'').addClass('active');
}

function worksheet_select(attr) {
	
	//Clear workouts from screen
	jQuery('#workout_plans_results').html("");	
	var current_level = parseInt(jQuery('#workout_level').text());
	jQuery('#workout_plan_days .radio-workoutlevel').removeClass('active');
	jQuery('#workout_plan_days #day_'+attr+'').addClass('active');
	get_workout_links(attr, current_level);
}




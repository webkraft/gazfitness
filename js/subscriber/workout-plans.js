/*
*
* Display links workouts
* 
*/
jQuery('document').ready(function(){

	var $ = jQuery;
	//alert('Workout plans');
	get_workout_links();
	//console.log('Admin');
	
});

/* This gets all the links to choose from */
function get_workout_links(){

    var workout_plans_worksheet_links = jQuery('#workout_plans_worksheet_links');
	
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
            workout_plans_worksheet_links.html("<div class='loading-message'><i class='fa fa-refresh fa-spin fa-fw'></i> Loading workouts</div>");
        },
        success: function(data){

            //console.log(data);
			workout_plans_worksheet_links.text(data);
            
			var links = '';
            jQuery.each(data, function( index, value ) {
				links += '<a title="Body area" href="#" onclick="show_BodyAreaLinks('+ value.id +');return false;">'+ value.sheet_name +'</a>&nbsp';
				
            });			
			//write database results
            workout_plans_worksheet_links.html(links);
			
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('error: ' + textStatus + ': ' + errorThrown);
        }
    });
}
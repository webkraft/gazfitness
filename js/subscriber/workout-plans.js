jQuery('document').ready(function(){

	var $ = jQuery;
	//alert('Workout plans');
	get_workout_links();
	//console.log('Admin');
	
});

/* This gets all the links to choose from */
function get_workout_links(){

    var worksheet_links = jQuery('#workout_plans_worksheet_links');

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
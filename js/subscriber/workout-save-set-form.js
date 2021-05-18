/*
*
* Display call to get the form markup with values etc
* 
*/
function saveWorkoutSetForm(args) {
	
	//console.log(args);
	
	//foreach input in div
	var i=0;
	var weights = new Array();
	var _args = args;
	jQuery("#"+_args+" input[type=text]").each(function() {
		i++;
		console.log(jQuery(this).val());
		weights.push(jQuery(this).val());
	});
	
	var weights_stg = JSON.stringify(weights);
	console.log(weights_stg);

	//Form parent div
	var parent_div = jQuery("#"+_args+"").parent().attr('id');
	var formAtts = parent_div.split('_');
	console.log('formAtts: ' + formAtts);
	
    if(weights.length > 0) {
        
		jQuery.ajax({
			
			type: 'post',
			url: save_workout_set_form_file.ajaxurl,
			dataType: 'json',
	        method: 'POST',
	        data: { action: 'save_workout_set_form',
					set_id: formAtts[1],
					workout_id: formAtts[0],
					weights: weights_stg,
					security: save_workout_set_form_file.nonce
			},
            beforeSend: function() {
                
				jQuery("#"+args+" input[type=text]").each(function() {
					jQuery(this).hide();
				});
				
				jQuery("#"+args+" a").hide();
				jQuery("#"+args+"").append("<div class='wait'>Saving <i class='fa fa-circle-o-notch fa-spin fa-3x fa-fw'></i></div>");
                
            },
			complete: function(data) {
                
				console.log('complete: '+ JSON.stringify(data));
				jQuery('.wait').remove();
				jQuery("#"+args+"").append('Saved');
				
				jQuery("#"+args+" input[type=text]").each(function() {
					jQuery(this).show();
				});
			}, 
			success: function(data){
				
				console.log('Success: '+ data);
			
	        },
	        error: function(jqXHR, textStatus, errorThrown){
				
	            console.log('error: ' + textStatus + ': ' + errorThrown);
			}
            
        });
    }
//
};
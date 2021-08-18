/*
*
* Display call to get the form markup with values etc
* 
*/
function saveWorkoutSetForm(args) {
	
	var i=0;
	var weights = new Array();
	var _args = args;
	console.log(args);
	
	jQuery("#"+_args+" input[type=text]").each(function() {
		i++;
		//console.log(jQuery(this).val());
		weights.push(jQuery(this).val());
	});
	
	var weights_stg = JSON.stringify(weights);
	//console.log(weights_stg);

	//Form parent div
	var parent_div = jQuery("#"+_args+"").parent().attr('id');
	var formAtts = parent_div.split('_');
	//console.log('formAtts: ' + formAtts);
	
    if(weights.length > 0) {
        
		jQuery.ajax({
			
			type: 'post',
			url: save_workout_set_form_file.ajaxurl,
			dataType: 'json',
	        method: 'POST',
	        data: { action: 'save_workout_set_form',
					entry_id: _args,
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
				jQuery("#"+args+"").append("<div class='form-message wait'><span><i class='fa fa-refresh fa-spin fa-fw'></i> Saving weights</span></div>");
                
            },
			complete: function(data) {
                
				//console.log('complete: '+ JSON.stringify(data));
				jQuery('.wait').remove();
				jQuery("#"+args+"").append('<span class="form-message msg-success" style="padding-left:10px;"><i class="fa fa-check" aria-hidden="true"></i> Saved</span>');
				
				/*jQuery("#"+args+" input[type=text]").each(function() {
					jQuery(this).hide();
				});*/
			}, 
			success: function(data){				
				//console.log('Success: '+ data);
			
	        },
	        error: function(jqXHR, textStatus, errorThrown){				
	            //console.log('error: ' + textStatus + ': ' + errorThrown);
			}
            
        });
    }
//
};

/*
*
* Hide the form from the display
* 
*/
function hideWorkoutSetForm(args) {
	
	jQuery("div."+args+"").toggle(500, function(){
			
		var vis_state = jQuery("div."+args+"").attr('style');		
		if(vis_state == "display: none;"){
			jQuery("a."+args+"").html('<i class="fa fa-chevron-circle-down" aria-hidden="true"></i> Show');
		}else{
			jQuery("a."+args+"").html('<i class="fa fa-chevron-circle-up" aria-hidden="true"></i> Hide');
		}
		
    });
	
}

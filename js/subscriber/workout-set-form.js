/*
*
* Display call to get the form markup with values etc
* 
*/
function getWorkoutSetForm(args) {
	
	console.log('args: ' + args);
	var _args = args;
	
	jQuery.ajax({
		type: 'post',
		url: get_workout_set_form_file.ajaxurl,
		dataType: 'text',
		//dataType: 'json',
        method: 'POST',
        data: { action: 'get_workout_set_form',
				workoutset_args: _args, 
				security: get_workout_set_form_file.nonce
		},        
        beforeSend: function() {
			
            jQuery('#'+ _args).text('Loading entry form...');
        },
        success: function(data){
			
            //console.log(data);			
			jQuery('#'+ _args).html(data);
			//("<form><input type='text' name='notes' placeholder='Enter weight' value='' /><input type='submit' class='button' value='Save'></form>");
            
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('error: ' + textStatus + ': ' + errorThrown);
        }
    });

//
}

function saveWorkoutSetForm(args) {
	//jQuery("#submit").click(function(data) { 
	//get input field values
	
		//get the form ID and link to button ID
		//var btn_id = data
		console.log(args);
		
	    /*
		var name            = $('#name').val(); 
	    var email           = $('#email').val();
	    var message         = $('#comment').val();
	    var flag = true;
		*/
		
	    /********validate all our form fields***********/
		
	    /* Name field validation  */
	    /*
		if(name==""){ 
	        $('#name').css('border-color','red'); 
	        flag = false;
	    }
		
	    if(email==""){ 
	        $('#email').css('border-color','red'); 
	        flag = false;
	    } 
	
	    if(message=="") {  
	       $('#comment').css('border-color','red'); 
	        flag = false;
	    }
		*/
		
	    /********Validation end here ****/
	    /* If all are ok then we send ajax request to email_send.php *******/
	    /*
		if(flag) 
	    {
	        $.ajax({
	            type: 'post',
	            url: "email_send.php", 
	            dataType: 'json',
	            data: 'username='+name+'&useremail='+email+'&message='+message,
	            beforeSend: function() {
	                $('#submit').attr('disabled', true);
	                $('#submit').after('<span class="wait">&nbsp;<img src="image/loading.gif" alt="" /></span>');
	            },
	            complete: function() {
	                $('#submit').attr('disabled', false);
	                $('.wait').remove();
	            },  
	            success: function(data)
	            {
	                if(data.type == 'error')
	                {
	                    output = '<div class="error">'+data.text+'</div>';
	                }else{
	                    output = '<div class="success">'+data.text+'</div>';
	                    $('input[type=text]').val(''); 
	                    $('#contactform textarea').val(''); 
	                }
	
	                $("#result").hide().html(output).slideDown();           
	                }
	        });
	    }
		*/
	//});
};
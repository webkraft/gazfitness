/* ----------------------------------------------------------------------------
-- Workout page to save the client weights lifter
-- Gets the workout based on the worksheet_id from the short code
---------------------------------------------------------------------------- */
function doWorkout(args){
    
    var message = jQuery('#workout_plans_results');
    message.text('Loading...');
    var output_text;
    var _bodyareas = [];
    var bodyareas_workouts = [];
    var _bodyareas_workouts = {};

    var workout_entries = {};
    var workout_entries_arr = [];
    //var weight_entries = {};
    
    jQuery.ajax({
		
		type: 'post',
		url: get_workout_sheets_file.ajaxurl,
		//dataType: 'text',
		dataType: 'json',
        method: 'POST',
        data: { action: 'get_workout_sheets',
				security: get_workout_sheets_file.nonce
		},		
		
        /*
		url: frontend_ajax.ajax_url,
        dataType: 'json',
        method: 'POST',
        data: {
            action: 'dyn_workoutsets',
            worksheet_id: args
        },
		*/

        success: function(data){

            message.text('');
            var pulldown = '';
            var tablerow = '';
            pulldown += '<ul class="accordion">';
            tablerow += "<table><thead style='background: #f2f2f2;'><tr><th></th><th>Workout</th><th>No Sets</th><th>Reps</th><th>Tempo</th><th>Rest</th><th>Notes</th></tr></thead>";

            /*
            weight_entries = {
                0: [25,15,45,60],
                1: [1,2,3,4],
                2: [5,6,7,8],
                3: [9,10,11,12],
                4: [13,14,15,16]
            }
            */

            /*
            0: {setid: "1", weight: "50", inputid: "1", entryid: "16"}
            1: {setid: "1", weight: "30", inputid: "2", entryid: "19"}
            2: {setid: "1", weight: "55", inputid: "3", entryid: "20"}
            3: {setid: "1", weight: "40", inputid: "4", entryid: "21"}
            */

            /*
            var obString = jQuery("#weights_div").text();
            var ret = obString.replace(/"setid":/g,'');
            var ret1 = ret.replace(/"weight"/g,'');
            var ret2 = ret1.replace(/,:/g,':');
            var ret3 = ret2.replace(/},{/g,',');
            var ret4 = ret3.slice(1,-1);
            */
            //console.log('ret4');
            //console.log(ret4);

            //var exObj = '[{"setid":"1","weight":["50","30","55","40"],"entryid":["16","19","20","21"]},{"setid":"2","weight":["70","71","72","73"],"entryid":["22","23","24","25"]},{"setid":"3","weight":["99","88"],"entryid":["26","27"]}]';
			
			var global_weight_entries_arr = [];
            var weight_entries = global_weight_entries_arr;//JSON.parse(exObj); //ret4
            console.log('global_weight_entries_arr 2: ' + JSON.stringify(global_weight_entries_arr));

            //console.log('weight_entries');
            //console.log(weight_entries);

            //console.log('weight_entries array');
            //console.log(weight_entries[0]['entryid']);
            //console.log('weight_entries.length: ' + weight_entries.length);

            var object = {
                key: function(n) {
                    return this[Object.keys(this)[n]];
                }
            };

            function key1(obj, idx, elm) {
                
                if (idx < Object.keys(obj).length){
                    group = object.key.call(obj, idx);
                    return group[elm];
                }else{
                    return '';
                }
            }

            //Weight entry id
            //var weight_entry_id_div = jQuery("#weight_entry").text();
            //var weight_entry_id_arr = JSON.parse(weight_entry_id_div);
            //console.log('weight_entry_id_arr');
            //console.log(weight_entry_id_arr);

            var new_data_obj = {};
            var new_data_arr = [];
            // Build the rows first then split into sets with weight entries
            jQuery.each(data, function(index, value){

                //console.log('----- DATA -----');
                //console.log(data);

                //var _entryid = [value.set_id,value.set_id,value.set_id,value.set_id];
                var _entryid = [0,0,0,0];
                var _weightentry = ['','','',''];
                //Set Id

                //console.log('_entryid' + weight_entries[index]['entryid']);
                //console.log('_weightentry' + weight_entries[index]['weight']);

                if ( index <= (weight_entries.length)-1 ){

                    _entryid = weight_entries[index]['entryid'];
                    _weightentry = weight_entries[index]['weight'];
                    //console.log ('_entryid: ' + _entryid);
                    //console.log ('_weightentry: ' + _weightentry);
                }

                /*
                if(weight_entries[index]['entryid'] !== "undefined"){
                    var _entryid = weight_entries[index]['entryid'];
                    console.log ('_entryid: ' + _entryid);
                }
                if(weight_entries[index]['weight'] !== "undefined"){
                     var _weightentry = weight_entries[index]['weight'];
                    console.log ('_weightentry: ' + _weightentry);
                }
                */

                //Add element from weight_entry_id_arr to data
                new_data_obj = {
                    'body_area': value.body_area,
                    'id': value.id,
                    'notes': value.notes,
                    'reps': value.reps,
                    'rest':  value.rest,
                    'set_id': value.set_id,
                    'set_name': value.set_name,
                    'sets_number': value.sets_number,
                    'sheet_name': value.sheet_name,
                    'tempo': value.tempo,
                    'video_link': value.video_link,
                    'workout_sheet_id': value.workout_sheet_id,
                    'weight_entry': _weightentry, //array
                    'entry_id': _entryid //array [1,1,1,1] -- getting undefined with only empty elements
                    //weight_entry_id_arr[index]
                    //set an input id
                    //'input_id': weight_entry_id_arr[index]
                }
                new_data_arr.push(new_data_obj);


                var ii = index+1;
                for (var i=0; i<value.sets_number; ++i){

                    var entry_id_arr = new_data_arr[index]['entry_id'][i];
                    //console.log('entry_id_arr');
                    //console.log(entry_id_arr);

                    //weight_entry
                    var weight_entry_elm = new_data_arr[index]['weight_entry'][i];
                    console.log('XX:' + weight_entry_elm);

                    if (weight_entry_elm == '' || weight_entry_elm == 'undefined'){
                        weight_entry_elm = 0;
                    }else{
                        weight_entry_elm = new_data_arr[index]['weight_entry'][i];
                    }


                    //console.log ('entry_id: ' + new_data_arr[index]['entry_id'][i]);
                    //var _entry_id = 0;
                    if (entry_id_arr == '' || entry_id_arr == 'undefined'){
                        entry_id_arr = 0;
                    }else{
                        entry_id_arr = new_data_arr[index]['entry_id'][i];
                    }

                    //entry id / set_id / row number
                    var onclick_param = entry_id_arr+'-'+new_data_arr[index]['set_id']+'-'+i;
                    //console.log ('_entryid: ' + _entryid);
                    //console.log ('onclick_param: ' + onclick_param);

                    var values = '';
                    if (new_data_arr[index]['weight_entry'][i] === ''){
                        values = '';
                    }else{
                        values = new_data_arr[index]['weight_entry'][i];
                    }

                    workout_entries = {
                    'setname' : new_data_arr[index]['set_name'],
                    'rows' : '<tr><td></td><td><input type="text" placeholder="Enter weight for '+new_data_arr[index]['set_name']+'" id="'+onclick_param+'" value="'+values+'"></td><td colspan="4"><a href="#" id="'+onclick_param+'" onclick="saveWorkout('+onclick_param+');return false;">Save</a></td></tr>'}
                    workout_entries_arr.push(workout_entries);
                }
                
            });

            //new_data_arr
            //console.log('new_data_arr');
            //console.log(new_data_arr);

            //console.log('workout_entries_arr');
            //console.log(workout_entries_arr);


            //console.log('--- new_data_arr ---');
            //console.log(new_data_arr[0]['weight_entry']);
            //console.log(new_data_arr[0][1]);

            /*var new_data_object = {
                key: function(n) {
                    return this[Object.keys(this)[n]];
                }
            };

            function key2(obj, idx) {
                return  new_data_object.key.call(obj, idx);
            }
            */
            //console.log(key2(new_data_arr,0));
            //console.log('::: '+ new_data_arr[0]['weight_entry']);



            //Merge array
            var workout_entries_arr_singles = workout_entries_arr.reduce(function(res, currentValue) {
                if (res.indexOf(currentValue.setname) === -1) {
                    res.push(currentValue.setname);
                }
                return res;
                }, []).map(function(setname) {
                return {
                    setname: setname,
                    rows: workout_entries_arr.filter(function(_el) {
                        return _el.setname === setname;
                    }).map(function(_el) {
                    return _el.rows
                })
              }
            });
            //console.log('workout_entries_arr_singles');
            //console.log(workout_entries_arr_singles);

            /*
            "<tr><td></td><td><input type="text" placeholder="Enter weight for Deadlift" id="16-1-16,19,20,21" value="50"></td><td colspan="4"><a href="#" id="16-1-16,19,20,21" onclick="saveWorkout(16-1-16,19,20,21);return false;">Save</a></td></tr>"
            
            1: "<tr><td></td><td><input type="text" placeholder="Enter weight for Deadlift" id="16-1-16,19,20,21" value="30"></td><td colspan="4"><a href="#" id="16-1-16,19,20,21" onclick="saveWorkout(16-1-16,19,20,21);return false;">Save</a></td></tr>"
            */


            var workoutrows = [];
            workoutrows_string = [];
            jQuery.each(workout_entries_arr_singles, function(i, val) {
                //workoutrows.push();
                //console.log(workout_entries_arr_singles[i].rows);
                workoutrows_string = workout_entries_arr_singles[i].rows.join();
                workoutrows.push(workoutrows_string);
            });
            //console.log('workoutrows');
            //console.log(workoutrows);

            //workout_entries_arr_singles.join('');
            //jQuery("#weight_results").html(input_array);

            //console.log(data);
            //Loop through body areas, then insert content in table
            jQuery.each(data, function(index, value){

                _bodyareas.push(value.body_area);
                _bodyareas_workouts = {
                    'bodyarea' : value.body_area,
                    'workout_set' : "<tr><td>" + value.set_name + " <a href='https://www.youtube.com/embed/" + value.video_link + "?rel=0&amp;autoplay=1' data-featherlight='iframe' data-featherlight-iframe-width='640' data-featherlight-iframe-height='480' data-featherlight-iframe-frameborder='0' data-featherlight-iframe-allow='autoplay; encrypted-media' data-featherlight-iframe-allowfullscreen='true'> Play video</a></td><td>" + value.sets_number + "</td><td>" + value.reps + "</td><td>" + value.tempo + "</td><td>" + value.rest + "</td><td>" + value.notes + "</td></tr>" + workoutrows[index]}
                
                bodyareas_workouts.push(_bodyareas_workouts);
            });
            //console.log('bodyareas_workouts');
            //console.log(bodyareas_workouts);

            //
            // -- Add nested entry fields for weight sets
            //

            var res = bodyareas_workouts.reduce(function(res, currentValue) {
                if (res.indexOf(currentValue.bodyarea) === -1) {
                    res.push(currentValue.bodyarea);
                }
                return res;
                },[]).map(function(bodyarea) {
                return {
                    bodyarea: bodyarea,
                    workout_set: bodyareas_workouts.filter(function(_el) {
                        return _el.bodyarea === bodyarea;
                    }).map(function(_el) {
                    return _el.workout_set;
                })
              }
            });

            jQuery.each(res, function( i, val ) {

                pulldown += "<li><a class='toggle' href='javascript:RefreshSomeEventListener();'><strong>" + val.bodyarea + "</strong></a><div class='inner'>" + tablerow + " " + val.workout_set + "</table></div></li>";
            });

            pulldown += '</ul>';
            jQuery("#workout_plans_mydata").html(pulldown);
            RefreshSomeEventListener();
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('Line 727 error: ' + textStatus + ': ' + errorThrown);
        }  

    });
}


function RefreshSomeEventListener() {

    jQuery(".toggle").on("click", function(e) {
        e.preventDefault();
        //removeJunk();
      
        let $this = jQuery(this);     
        if ($this.next().hasClass('show')) {
            $this.next().removeClass('show');
            $this.next().slideUp(350);
        } else {
            $this.parent().parent().find('li .inner').removeClass('show');
            $this.parent().parent().find('li .inner').slideUp(350);
            $this.next().toggleClass('show');
            $this.next().slideToggle(350);
        }
    });
}

function removeJunk(){

    jQuery('.inner').each(function() {
        var text = jQuery(this).text();
        console.log('text' + text);
        jQuery(this).html(text.replace('>,,,,<table>', '><table>')); 
    });
}

function tempAlert(msg,duration){

    var el = document.createElement("div");
    el.setAttribute("style","position:fixed;top:0;left:0;background-color: rgb(0 0 0 / 80%); width:100%; height:100vh; min-height:100vh;z-index: 99;text-align:center;padding:60px;");
    el.innerHTML = msg;
    setTimeout(function(){
        el.parentNode.removeChild(el);
        },duration);
    document.body.appendChild(el);
}

/*
//line 245
function account_workouts_display(userid){

    var display_div = jQuery('#wokout_display_div');
    var message = '';

    //Prod
    message += '<p>Thanks for completing the Lifestyle Questionnaire, these workout plans will work for you:</p>';
    message += '<a href="/week-1-advanced-5-day/">Week 1 – Advanced 5 day</a>';
    message += '<br /><br />';

    //Admin set the workout(s), multiple workouts when first have been completed.
    //display_div.html(message);

    jQuery.ajax({
        url: frontend_ajax.ajax_url,
        dataType: 'json',
        method: 'POST',
        data: {
            action: 'get_user_info',
            user_id: userid
        },
      
        beforeSend: function() {
            display_div.text('Loading your user info...');
        },

        success: function(data){

            jQuery.each(data, function( index, value ) {
                message += 'Hi ' + value.user_nicename + ', you are starting on <a href="'+value.workout_url+'" target="_blank">'+ value.sheet_name +'</a>';
            });

            //userinfo.html(user_data);
            display_div.html(message);
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log('Line 279 error: ' + textStatus + ': ' + errorThrown);
        }
    });

}

*/
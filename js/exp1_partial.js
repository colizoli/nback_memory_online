
// N-back test
// Matching objects partially - ex. pairs of rhyming words


////////////////////////////////////////////////FUNCTIONS: Showing the Images ////////////////////////////////////////////////
define_trials_partial = function()
{
	var targets_partial = [];
	if(practice == 0)
	{
		targets_partial = thirty_percent-3;
	}
	else if (practice == 1)
	{
		targets_partial = 3;
	}
	
	// Choose a position p , cannot be the first nback positions, do this 'thirty_percent' times
	for(i=0; i<targets_partial; i++) // this is 33-3 because there are usually more than 3 extra targets...
	{
		do
		{
			var p = rand(nback,max_trials-1);  
		}
		while(positions_free[p] == false || positions_free[p-nback] == false);
			
		var n = rand(0,imgNames.length-1);
		var x = rand(0,imgNames[n].length-1);  // HERE CHANGES FOR PARTIAL MATCHING
			
		trial_order[p] = n;	
		trial_order[p-nback] = n;
		
		// HERE CHANGES FOR PARTIAL MATCHING
		do{var y = rand(0,imgNames[n].length-1);}
		while (y == x); // Making sure a target is not equal to itself for partial matching
		
		partial_order[p] = imgNames[n][x];
		partial_order[p-nback] = imgNames[n][y]

		/////////////////////////////////////////
		positions_free[p] = false;
		positions_free[p-nback] = false;
	}
	
	for (var j = 0; j<positions_free.length; j++) 	// Fill in the blank positions
	{
		if(positions_free[j] == true)
		{
			do
			{
				r = rand(0,imgNames.length-1);
			}
			while(r == trial_order[j-nback] || r == trial_order[j+nback] );  // This number must not generate another target
			trial_order[j] = r;  
			positions_free[j] = false;
			
			// HERE CHANGES FOR PARTIAL MATCHING
			var s = rand(0,imgNames[r].length-1);
			partial_order[j] = imgNames[r][s];
			
		}		
	}
	
	for(var k=0; k<trial_order.length; k++)  // Count the number of targets
	{
		if(contains(partial_order[k+nback], imgNames[trial_order[k]]) && partial_order[k] != partial_order[k+nback])
		//if(trial_order[k+nback] === trial_order[k])
		{
			targets[k] = 1;  // The targets array[0] => trial_order[nback]
			position_of_targets.push(k+nback);
		}
		else
		{
			targets[k] = 0;
		}
		number_of_targets = sumElements(targets);
	}
	
	start_time = new Date();
}


change_picture_partial = function()   //Shows pictures in the order defined in the array: partial_order
	{	
		if(trial_number > 0)
		{
			var name_stimuli = stimuli_names[counter];
			
			if(response == 0 
			&& contains(partial_order[counter-nback], imgNames[trial_order[counter]]) 
			&& partial_order[counter] != partial_order[counter-nback])
			{miss = 1;} 
			//Defines a missed trial  

			var current_trial_info = {"trial_number":trial_number, "position":counter, "name_stimuli":name_stimuli, "response": response, "hit":hit, "miss":miss, "rt_correct":rt_correct, "rt_incorrect":rt_incorrect };
			
			theRM.all_responses_func(response);
			theRM.hits_func(hit);
			theRM.misses_func(miss);
			theRM.rt_correct_func(rt_correct);
			theRM.rt_incorrect_func(rt_incorrect);
			theRM.meta_data_func(current_trial_info);
			reset();
		}
	
		// Sends data every 10 trials, except for the first and last
		if (trial_number%post_unit == 0 && trial_number != max_trials && trial_number != 0) 
		{
			calculate_responses();
			send_data();
		}
	
		trial_number = trial_number + 1;  //Next trial number now
		counter = counter + 1;

		if(trial_number == max_trials + 1) // End of test
		{
			total_time_exp = getElapsedTime(start_time);
			total_time_exp = total_time_exp/1000; // seconds
			total_time_exp = Math.ceil(total_time_exp/60); // minutes

			calculate_responses();
			
			if(sumElements(theRM.all_responses) == 0)
			{
				completed = 0;
			}
			else
			{
				completed = 1;
			}
			
			if(practice == 0){send_data();}
			
			//console.log(stimuli_names);

			setTimeout("blank_screen()", isi_time);
			if(practice == 0)
			{setTimeout("send_data()", isi_time + 1);}
            
			 setTimeout("finished()", isi_time + 50);
		}
		else // Normal trial
		{
			setStartTime_RT();

			if(block_tracker == 0)
			{
                            if(practice == 0)
                                {var img = document.getElementById("current1");}
                            else if (practice == 1)
                                {var img = document.getElementById("practice_current1");}
                        }
			if(block_tracker == 1)
			{
                            if(practice == 0)
                                {var img = document.getElementById("current2");}
                            else if (practice == 1)
                                {var img = document.getElementById("practice_current2");}
                        }
			if(block_tracker == 2)
			{
                            if(practice == 0)
                                {var img = document.getElementById("current3");}
                            else if (practice == 1)
                                {var img = document.getElementById("practice_current3");}
                        }
			if(block_tracker == 3)
			{
                            if (practice == 0)
                                {var img = document.getElementById("current4");}
                            else if (practice == 1)
                                {var img = document.getElementById("practice_current4");}
                        }


			var index = trial_order[counter];
            //////////////////////// HARD CODED
			img.src = '../../assets/images/exp1/'+category+'/' + partial_order[counter] + '.png';  //here it changes
			////////////////////////////////
            stimuli_names[counter] = partial_order[counter];
			
			setTimeout("blank_screen()", isi_time);
			setTimeout("change_picture_partial()", pic_time);
		}	
	}
	

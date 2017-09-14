
 
////////////////////////////////////////////////FUNCTIONS: Showing the Images ////////////////////////////////////////////////
define_trials_identity = function()
{
	// Choose a position p , cannot be the first nback positions, do this 'thirty_percent' times
	for(i=0; i<thirty_percent; i++)
	{
		do
		{
			var p = rand(nback,max_trials-1);
		}
		while(positions_free[p] == false || positions_free[p-nback] == false);

		var n = rand(0,imgNames.length-1);

		trial_order[p] = n;
		trial_order[p-nback] = n;

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
		}
	}

	for(var k=0; k<trial_order.length; k++)  // Count the number of targets
	{
		if(trial_order[k+nback] === trial_order[k])
		{
			targets[k] = 1; // The targets array[0] => trial_order[nback]
			position_of_targets.push(k+nback);
		}
		else
		{
			targets[k] = 0;
		}
		number_of_targets = sumElements(targets);
		var difference = number_of_targets - thirty_percent;
	}

	if(difference > 0)  // Sometimes there are more targets than 33%, try and get it back to 33%
	{
		var shuffled = shuffle(position_of_targets);
		for(i=0; i<difference; i++)
		{
			do
			{
				r = rand(0,imgNames.length-1);
			}
			while(r == trial_order[shuffled[i]-nback] || r == trial_order[shuffled[i]+nback] );  // This number must not generate another target
			trial_order[shuffled[i]] = r;
			shuffled = shuffled.slice(1); // returns the array without the 0th element
		}

		position_of_targets = [];

		for(var k=0; k<trial_order.length; k++)  // Count the number of targets again
		{
			if(trial_order[k+nback] === trial_order[k])
			{
				targets[k] = 1;  // The targets array[0] => trial_order[nback]
				position_of_targets.push(k+nback);
			}
			else
			{
				targets[k] = 0;
			}
		}
		number_of_targets = sumElements(targets);
	}

	start_time = new Date();
}



change_picture_identity = function()   //Shows pictures in the order defined in the array: trial_order
	{	
		if(trial_number > 0)
		{
			var name_stimuli = stimuli_names[counter];
			
			if(response == 0 && stimuli_names[counter] === stimuli_names[counter-nback]){miss = 1;} //Defines a missed trial

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
			if(practice == 0){send_data();}
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

			//////////////////////// HARD CODED
			img.src = '../../assets/images/exp1/'+category+'/' + imgNames[trial_order[counter]] + '.png';  //here it changes
			////////////////////////////////
                        //no php in js files
			stimuli_names[counter] = imgNames[trial_order[counter]]; //puts name of presented stimulus into array stimuli_names
			
			setTimeout("blank_screen()", isi_time);
			setTimeout("change_picture_identity()", pic_time);
		}	
	}
	






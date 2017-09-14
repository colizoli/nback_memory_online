// 0-back, Reaction Time
	
define_trials_rt = function()
{
	// Choose a target, n (number in array, not name of stimuli)
	var n = rand(0,imgNames.length-1);
	
	t = imgNames[n];  // Stimuli name
	
	// Choose a position p, do this 'thirty_percent' times
	for(i=0; i<thirty_percent; i++)
	{
		do
		{
			var p = rand(0,max_trials-1);  
		}
		while(positions_free[p] == false);
				
		trial_order[p] = n;		
			
		positions_free[p] = false;
	
	}
	
	for (var j = 0; j<positions_free.length; j++) 	// Fill in the blank positions, can not be another target
	{
		if(positions_free[j] == true)
		{
			do
			{
				r = rand(0,imgNames.length-1);
			}
			while( r == n );
			
			trial_order[j] = r;  
			positions_free[j] = false;
		}		
	}
	
	for(var k=0; k<trial_order.length; k++)  // Count the number of targets
	{
		if(trial_order[k] == n)
		{
			targets[k] = 1;
			position_of_targets.push(k);
		}
		else
		{
			targets[k] = 0;
		}
		number_of_targets = sumElements(targets);
		var difference = number_of_targets - thirty_percent;
	}

	start_time = new Date();      
}

show_target = function()
{
     var tell_target = [];
     if (t == "reaction_time1"){tell_target = 0}
     else if (t == "reaction_time2"){tell_target = 1}
     else if (t == "reaction_time3"){tell_target = 2}
     else if (t == "reaction_time4"){tell_target = 3}
     else if (t == "reaction_time5"){tell_target = 4}
     else if (t == "reaction_time6"){tell_target = 5}
     else if (t == "reaction_time7"){tell_target = 6}
     else if (t == "reaction_time8"){tell_target = 7}
     else if (t == "reaction_time9"){tell_target = 8}
     else if (t == "reaction_time10"){tell_target = 9}

     alert("Your target number is: "+ tell_target +"\n Remember it, and click Match each time you see a "+tell_target);
}

change_picture_rt = function()   //Shows pictures in the order defined in the array: trial_order
	{	
		if(trial_number > 0)
		{
			var name_stimuli = stimuli_names[counter];
			
			if(response == 0 && stimuli_names[counter] === t){miss = 1;} //Defines a missed trial

			var current_trial_info = {"trial_number":trial_number, "target": t, "position":counter, "name_stimuli":name_stimuli, "response": response, "hit":hit, "miss":miss, "rt_correct":rt_correct, "rt_incorrect":rt_incorrect };
			
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
			
                        //console.log(stimuli_names);

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
                        stimuli_names[counter] = imgNames[trial_order[counter]];
						
			setTimeout("blank_screen()", isi_time);
			setTimeout("change_picture_rt()", pic_time);

		}	
	}
	






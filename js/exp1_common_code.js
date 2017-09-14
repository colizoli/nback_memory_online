
// N-back test, common code for identity, partial and 0-back matching conditions



//////////////PRELOAD IMAGES////////////////////////

var imgObjects = [];

preload = function()
{
	for (var i = 0; i < imgNames.length; i++)
		{
			imgObjects[i] = new Image();
			
			if(nback_matching == 'partial')
			{
				if(imgNames[0].length == 2)
				{
					imgObjects[i][0] = '../../assets/images/exp1/'+category+'/' + imgNames[i][0] + '.png';  //here it changes
					imgObjects[i][1] = '../../assets/images/exp1/'+category+'/' + imgNames[i][1] + '.png';  //here it changes
				}
				else if(imgNames[0].length == 3)
				{
					imgObjects[i][0] = '../../assets/images/exp1/'+category+'/' + imgNames[i][0] + '.png';  //here it changes
					imgObjects[i][1] = '../../assets/images/exp1/'+category+'/' + imgNames[i][1] + '.png';  //here it changes
					imgObjects[i][2] = '../../assets/images/exp1/'+category+'/' + imgNames[i][2] + '.png';  //here it changes
				}
			}
			else
			{
				imgObjects[i].src = '../../assets/images/exp1/'+category+'/' + imgNames[i] + '.png';  //here it changes
			}
			
		}
}


/*
if (imgNames.length > 6)
{
imgNames = imgNames.slice(0,6);  // Picks a random 6 out of the total number of stimuli
}
*/


//////////////VARIABLES////////////////////////
var trial_id = 0; // For database updating

// Session table
var start_time = [];
var completed = 0;

// Trial table
var isi_time = 1000;  // 1 sec
var pic_time = 2000;  // Presentation time = difference between pic_time & isi_time
var number_of_targets = [];
var total_responses = [];
var total_hits = [];
var total_misses = [];
var total_incorrect = [];  // all_responses - hits
var final_score = [];
var average_rt_correct = [];
var average_rt_incorrect = [];


// Other
var post_unit = 10;  // Data is sent every 10 trials
var max_trials = [];
var thirty_percent = [];  // This variable keeps track of the number of targets needed to reach 33 percent
var trial_order = [];  // This array will have the entire sequence order in it
var targets = [];
var positions_free = [];  // This array keeps track of which positions are free in the generation of the sequence
var stimuli_names = [];
var position_of_targets = [];  // Gives the positions of the targets, starting with position 0
var total_time_exp = [];
var start_time_RT = [];
var trial_number = 0;
var counter = -1; // goes through the array with the images in it
var response = 0;
var hit = 0;
var miss = 0;
var rt_correct = 0;
var rt_incorrect = 0;

var partial_order = []; // The sequence order with the stimuli names in it
var t = [];

////////////////////////////////////////////////FUNCTIONS: Time ////////////////////////////////////////////////

setStartTime_RT = function()     //Sets the start time per trial
	{
		var start = new Date();
		start_time_RT = start.getTime();
	}

getElapsedTime = function(x)   // Calculates Total Time of experiment
	{
		var date = new Date();
		return date.getTime() - x;
	}

////////////////////////////////////////////////FUNCTIONS: Other ////////////////////////////////////////////////
set_initial_positions = function()
{
	for (var i = 0; i<max_trials; i++)
	{
		positions_free[i] = true;
	}
}

rand = function(l,u) // lower bound and upper bound
 {
     return Math.floor((Math.random() * (u-l+1))+l);
 }

reset = function()
	{
		response = 0;
		hit = 0;
		miss = 0;
		rt_correct = 0;
		rt_incorrect = 0;
	}

finished = function()
	{                                        
        if(practice == 1){practice = 0;}
        else if (practice == 0)
        {
            practice = 1;
            scores[block_tracker] = final_score;
				//alert(scores);
            block_tracker = block_tracker + 1;                    
        }
		
		trial_id = 0;
		session_id = 0;
		show_scores();
		reset_everything();
		//alert(trial_id);
		//alert(session_id);
		dijit.byId('stackContainer').forward();
	}

////////////////////////////////////////////////FUNCTIONS: Showing the Images ////////////////////////////////////////////////

blank_screen = function()
	{
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

		img.src =  "../../assets/images/exp1/white.png"; //no php in js files
	}


//////////////////////////////////////////////FUNCTIONS: Response Managers //////////////////////////////////////////////

indicate_response = function()
	{
		if(difference == 0)
		{
			document.getElementById('box1').style.borderColor = "grey";
			document.getElementById('box2').style.borderColor = "grey";
			document.getElementById('box3').style.borderColor = "grey";
			document.getElementById('box4').style.borderColor = "grey";
			document.getElementById('practice_box1').style.borderColor = "black";
			document.getElementById('practice_box2').style.borderColor = "black";
			document.getElementById('practice_box3').style.borderColor = "black";
			document.getElementById('practice_box4').style.borderColor = "black";
		}
		else if(difference == 1)
		{
			document.getElementById('box1').style.borderColor = "grey";
			document.getElementById('box2').style.borderColor = "grey";
			document.getElementById('box3').style.borderColor = "grey";
			document.getElementById('practice_box1').style.borderColor = "black";
			document.getElementById('practice_box2').style.borderColor = "black";
			document.getElementById('practice_box3').style.borderColor = "black";
		}
		else if(difference == 2)
		{
			document.getElementById('box1').style.borderColor = "grey";
			document.getElementById('box2').style.borderColor = "grey";
			document.getElementById('practice_box1').style.borderColor = "black";
			document.getElementById('practice_box2').style.borderColor = "black";
		}
		else if(difference == 3)
		{
			document.getElementById('box1').style.borderColor = "grey";	
			document.getElementById('practice_box1').style.borderColor = "black";
		}
	}

indicate_response_correct = function()
	{
		if(difference == 0)
		{
			document.getElementById('practice_box1').style.borderColor = "#15CD1E";
			document.getElementById('practice_box2').style.borderColor = "#15CD1E";
			document.getElementById('practice_box3').style.borderColor = "#15CD1E";
			document.getElementById('practice_box4').style.borderColor = "#15CD1E";
		}
		else if(difference == 1)
		{
			document.getElementById('practice_box1').style.borderColor = "#15CD1E";
			document.getElementById('practice_box2').style.borderColor = "#15CD1E";
			document.getElementById('practice_box3').style.borderColor = "#15CD1E";
		}
		else if(difference == 2)
		{
			document.getElementById('practice_box1').style.borderColor = "#15CD1E";
			document.getElementById('practice_box2').style.borderColor = "#15CD1E";
		}
		else if(difference == 3)
		{
			document.getElementById('practice_box1').style.borderColor = "#15CD1E";
		}
	}

indicate_response_incorrect = function()
	{
		if(difference == 0)
		{
			document.getElementById('practice_box1').style.borderColor = "red";
			document.getElementById('practice_box2').style.borderColor = "red";
			document.getElementById('practice_box3').style.borderColor = "red";
			document.getElementById('practice_box4').style.borderColor = "red";
		}
		else if(difference == 1)
		{
			document.getElementById('practice_box1').style.borderColor = "red";
			document.getElementById('practice_box2').style.borderColor = "red";
			document.getElementById('practice_box3').style.borderColor = "red";
		}
		else if(difference == 2)
		{
			document.getElementById('practice_box1').style.borderColor = "red";
			document.getElementById('practice_box2').style.borderColor = "red";
		}
		else if(difference == 3)
		{
			document.getElementById('practice_box1').style.borderColor = "red";
		}
	}

match_func = function()
	{
	
	if(difference == 0)
	{
		document.getElementById('box1').style.borderColor = "black";
		document.getElementById('box2').style.borderColor = "black";
		document.getElementById('box3').style.borderColor = "black";
		document.getElementById('box4').style.borderColor = "black";
	}
	else if(difference == 1)
	{
		document.getElementById('box1').style.borderColor = "black";
		document.getElementById('box2').style.borderColor = "black";
		document.getElementById('box3').style.borderColor = "black";
	}
	else if(difference == 2)
	{
		document.getElementById('box1').style.borderColor = "black";
		document.getElementById('box2').style.borderColor = "black";
	}
	else if(difference == 3)
	{
		document.getElementById('box1').style.borderColor = "black";
	}

		setTimeout("indicate_response()", 200);

		if(rt_correct > 0 || rt_incorrect > 0) {}
		else
		{
			if(nback_matching == 'identity')
                        {
                                if(trial_number > nback && stimuli_names[counter] === stimuli_names[counter-nback])
                                {
                                    rt_correct = getElapsedTime(start_time_RT); // Only logs rts for correct responses
                                    hit = 1;
                                }
                                else
                                {
                                    if (trial_number == 0) {rt_incorrect = 0; }
                                    //Necessary for the first trial (0), otherwise RT is huge
                                    else
                                    {
										rt_incorrect = getElapsedTime(start_time_RT);
                                    }
                                }
                        }

            else if(nback_matching == 'partial')
                        {
                                if(trial_number > nback
                                && contains(partial_order[counter-nback], imgNames[trial_order[counter]])
                                && partial_order[counter] != partial_order[counter-nback])
                                {
                                    rt_correct = getElapsedTime(start_time_RT); // Only logs rts for correct responses
                                    hit = 1;
                                }
                                else
                                {
                                    if (trial_number == 0) {rt_incorrect = 0; }
                                    //Necessary for the first trial (0), otherwise RT is huge
                                    else
                                    {
										rt_incorrect = getElapsedTime(start_time_RT);
                                    }
                                }
                        }

            else if(nback_matching == 'reaction_time')
                        {
                                if(stimuli_names[counter] === t)
                                {
                                    rt_correct = getElapsedTime(start_time_RT); // Only logs rts for correct responses
                                    hit = 1;
                                }
                                else
                                {                                    
                                    rt_incorrect = getElapsedTime(start_time_RT);
                                }
                        }

			response = 1;
		}
	}

practice_match_func = function()
	{

		if(rt_correct > 0 || rt_incorrect > 0) {}
		else
		{
			if(nback_matching == 'identity')
                        {
                                if(trial_number > nback && stimuli_names[counter] === stimuli_names[counter-nback])
                                {
                                    indicate_response_correct();
                                    setTimeout("indicate_response()", 200);
                                    rt_correct = getElapsedTime(start_time_RT); // Only logs rts for correct responses
                                    hit = 1;
                                }
                                else
                                {
                                    if (trial_number == 0) {rt_incorrect = 0; }
                                    //Necessary for the first trial (0), otherwise RT is huge
                                    else
                                    {
                                        indicate_response_incorrect();
                                        setTimeout("indicate_response()", 200);
										rt_incorrect = getElapsedTime(start_time_RT);
                                    }
                                }
                        }

            else if(nback_matching == 'partial')
                        {
                                if(trial_number > nback
                                && contains(partial_order[counter-nback], imgNames[trial_order[counter]])
                                && partial_order[counter] != partial_order[counter-nback])
                                {
                                    indicate_response_correct();
                                    setTimeout("indicate_response()", 200);
                                    rt_correct = getElapsedTime(start_time_RT); // Only logs rts for correct responses
                                    hit = 1;
                                }
                                else
                                {
                                    if (trial_number == 0) {rt_incorrect = 0; }
                                    //Necessary for the first trial (0), otherwise RT is huge
                                    else
                                    {
                                        indicate_response_incorrect();
                                        setTimeout("indicate_response()", 200);
										rt_incorrect = getElapsedTime(start_time_RT);
                                    }
                                }
                        }

            else if(nback_matching == 'reaction_time')
                        {
                                if(stimuli_names[counter] === t)
                                {
                                    indicate_response_correct();
                                    setTimeout("indicate_response()", 200);
                                    rt_correct = getElapsedTime(start_time_RT); // Only logs rts for correct responses
                                    hit = 1;
                                }
                                else
                                {
                                    indicate_response_incorrect();
                                    setTimeout("indicate_response()", 200);
                                    rt_incorrect = getElapsedTime(start_time_RT);
                                }
                        }

			response = 1;
		}
	}


calculate_responses = function()
	{
		total_misses = sumElements(theRM.misses);
		total_hits = sumElements(theRM.hits);
		total_responses = sumElements(theRM.all_responses);
		total_incorrect = total_responses - total_hits;

		if(total_hits == 0)
		{
			average_rt_correct = 0;
		}
		else
		{
			average_rt_correct = Math.floor(sumElements(theRM.rt_correct)/total_hits);
		}

		if(total_incorrect == 0)
		{
			average_rt_incorrect = 0;
		}
		else
		{
			average_rt_incorrect = Math.floor(sumElements(theRM.rt_incorrect)/total_incorrect);
		}

		var denominator = total_hits + total_incorrect + total_misses;
		final_score = total_hits / denominator;
		final_score = final_score*100;
		final_score = Math.floor(final_score);
	}

// Response Manager records dependent variables per trial. To access use "theRM_xxxx.xxxx" (see below)
ResponseManager = function()
{
	this.hits = [],  // A hit is a target that they get correct
	this.hits_func = function(h) {this.hits.push(h);},
	this.misses = [],	// A miss is a target that they do not respond to
	this.misses_func = function(s) {this.misses.push(s);},
	this.all_responses = [], // Total number of responses
	this.all_responses_func = function(r) {this.all_responses.push(r);},
	this.rt_correct = [],
	this.rt_correct_func = function(rt) {this.rt_correct.push(rt);},
	this.rt_incorrect = [],
	this.rt_incorrect_func = function(rt) {this.rt_incorrect.push(rt);},
	this.meta_data = [],
	this.meta_data_func = function(m) {this.meta_data.push(m);}
}

// copies the Response Manager with all previous data in it, new data is added to it each time
theRM = new ResponseManager();

// This is necessary for the stackcontainer on the Expression Engine site
reset_everything = function()
{
	theRM.hits = [];
	theRM.misses = [];
	theRM.all_responses = [];
	theRM.rt_correct = [];
	theRM.rt_incorrect = [];
	theRM.meta_data = [];

        session_id = 0; // For database updating
        trial_id = 0;

	// Session table
	start_time = [];
	completed = 0;

	// Trial table
	isi_time = 1000;  // 1 sec
	pic_time = 2000;  // Presentation time = difference between pic_time & isi_time
	number_of_targets = [];
	total_responses = [];
	total_hits = [];
	total_misses = [];
	total_incorrect = [];  // all_responses - hits
	final_score = [];
	average_rt_correct = [];
	average_rt_incorrect = [];

	// Other
	post_unit = 10;  // Data is sent every 10 trials
	max_trials = [];
	thirty_percent = []; // This variable keeps track of the number of targets needed to reach 33 percent
    trial_order = [];  // This array will have the entire sequence order in it
	targets = [];
	positions_free = [];  // This array keeps track of which positions are free in the generation of the sequence
	stimuli_names = [];
	position_of_targets = [];  // Gives the positions of the targets, starting with position 0
	total_time_exp = [];
	start_time_RT = [];
	trial_number = 0;
	counter = -1; // goes through the array with the images in it
	response = 0;
	hit = 0;
	miss = 0;
	rt_correct = 0;
	rt_incorrect = 0;

    partial_order = []; // The sequence order with the stimuli names in it
    t = [];
	//alert('reset');
}






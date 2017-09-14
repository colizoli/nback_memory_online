<!DOCTYPE html>
<html>
  <head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="description" content="Visual Memory Test" />
<meta name="keywords" content="" />
<meta name="revised" content="Olympia Colizoli, 20/10/2011" />
<meta http-equiv="content-language" content="en-US" />

<title>Visual Memory Test - University of Amsterdam, Brain and Cognition  </title>

<link rel="stylesheet" type="text/css" href="../../assets/css/nbackcss/stack_container.css" />
<link rel="stylesheet" type="text/css" href="../../assets/js/dojoroot/dojo/resources/dojo.css" />
<link rel="stylesheet" type="text/css" href="../../assets/js/dojoroot/dijit/themes/tundra/tundra.css" />

<script type="text/javascript" src="../../assets/js/dojoroot/dojo/dojo.js" djConfig="isDebug: true, parseOnLoad: true"></script>

<script type="text/javascript" src="../../assets/js/livetest/exp1_common_code.js"></script>
<script type="text/javascript" src="../../assets/js/livetest/exp1_identity.js"></script>
<script type="text/javascript" src="../../assets/js/livetest/exp1_partial.js"></script>
<script type="text/javascript" src="../../assets/js/livetest/exp1_rt.js"></script>

<script type="text/javascript" src="../../assets/js/livetest/shuffle.js"></script>
<script type="text/javascript" src="../../assets/js/livetest/sumElements.js"></script>
<script type="text/javascript" src="../../assets/js/livetest/contains.js"></script>

<script type="text/javascript">
    dojo.require("dojo.parser");
    dojo.require("dijit.layout.ContentPane");
    dojo.require("dijit.layout.StackContainer");
    dojo.require("dijit.form.Button");
</script>

<script type="text/javascript">

     var check_new = [];
	 
     var nback_id_all = [];
     var category_all = [];
     var nback_all = [];
     var imgNames_all = [];
     var nback_matching_all = [];
     var instructions1_all = [];
     var instructions2_all = [];
     var pics_all = [];
	 
	 var nback_info = [];
	 
	 var email = "100"; //default
	 var check_questions = 1;

dojo.addOnLoad(function(){

		
    subject_info();
        
	blank_screen();
		
/*
	dojo.connect(dojo.byId("email"), "onkeydown", function(event)
	{
		//Keeps the Begin! button from submitting a form which opens a 404 page
		if (event.keyCode === dojo.keys.ENTER)
		{
			check_email();
			dojo.stopEvent(event);
			return;
		}	
	});
 */	
    dojo.connect(dijit.byId("info"), "onClick", check_info);    

	dojo.connect(dijit.byId("match1"), "onClick", match_func);
	dojo.connect(dijit.byId("match2"), "onClick", match_func);
	dojo.connect(dijit.byId("match3"), "onClick", match_func);
	dojo.connect(dijit.byId("match4"), "onClick", match_func);

    dojo.connect(dijit.byId("practice_match1"), "onClick", practice_match_func);
	dojo.connect(dijit.byId("practice_match2"), "onClick", practice_match_func);
	dojo.connect(dijit.byId("practice_match3"), "onClick", practice_match_func);
	dojo.connect(dijit.byId("practice_match4"), "onClick", practice_match_func);

});

/*
check_email = function() // Checks the form field to make sure an email has been submitted
        {
            email = document.getElementById("email_form").email2.value;
            if(email == "100" || email == " ")
                    {alert("Please add your email address.");}
            else
			{
                //alert(email);
                email = dojo.trim(email);
               // subject_info(email);				
            }
	}
 */   
 

subject_info = function() // Checks database to see if the subject is new or old, returns the tests
       {
                        dojo.xhrPost
				({
				url : "<?php echo $this->config->config['site_url'];   ?>livetest/exp1/block_tracker/",
				content:
				{
					'language': language,
				},
				handleAs: "text",  //comes back either as text or as json, array or object
                                load: function(response) 
                                {							
                                    //console.log("Response: ",response);
    								nback_info = dojo.fromJson(response);  //Check DOM for structure	
                                        //console.log("Here?");// empty response in line before this one gives syntax error
    								check_new = nback_info.new_subject;
    								if(nback_info.nbacks == "finished") 
                                    {
										get_scores_func();
                                        alert("You have completed this experiment! \n Thank you!");
										// should go to all_scores page
										dijit.byId('stackContainer').selectChild('all_scores_pane');
                                    }
    								else
                                    {
                                        tests();
                                    }							
                                },
				error : function(response, args) {dojo.byId('element').innerHTML = "Error: " + response; return response;},
				timeout : 5000
				});                                
       }

check_question_info = function() // Checks database to see if the subject (new or old) has answered the questionnaire
       {
                        dojo.xhrPost
				({
				url : "<?php echo $this->config->config['site_url']; ?>livetest/exp1/check_question_info/",
				content:
				{
				},
				handleAs: "text",  //comes back either as text or as json, array or object
                                load: function(response) {								
								keep_questions = dojo.fromJson(response);  //Check DOM for structure	
								if(keep_questions === false)
                                {check_questions = 1; dijit.byId('stackContainer').forward();}
								else
                                {
								    check_questions = 0;
									var questions = dijit.byId("questions");
                                    stackContainer.removeChild(questions);
                                    dijit.byId('stackContainer').forward();
                                }
									//console.log("keep qs: ",keep_questions);
									//console.log("check qs: ", check_questions);
									//tests();
								//set check_questions to 0 or 1 ->check_questions = 0, remove questionniare, if check_questions == 1, keep it in																				
                                },
				error : function(response, args) {dojo.byId('element').innerHTML = "Error: " + response; return response;},
				timeout : 5000
				});                                
       }	   
	
////////////////////////////// Sending data ////////////////////////////////////////

var tsid = [];
function send_data()
			{
				dojo.xhrPost
				({
				url : "<?php echo $this->config->config['site_url']; ?>livetest/exp1/send_nback_data", // TODO: This URL may be wrong!!!
				content:
				{
				'nback_id': nback_id,
				//'pid': pid,
				'start_time' : start_time,
				'completed' : completed,
				'targets': number_of_targets,
				'responses': total_responses,
				'hits': total_hits,
				'misses': total_misses,
				'incorrect' : total_incorrect,
				'score' : final_score,
				'rt_correct': average_rt_correct,
				'rt_incorrect': average_rt_incorrect,
				'sequence': dojo.toJson(trial_order),
				'image_order': dojo.toJson(imgNames),
				'meta_data': dojo.toJson(theRM.meta_data),
				'trial_id': trial_id
				},
				handleAs: "text",  //comes back either as text or as json, array or object
				load : function(response){

						 tsid = dojo.fromJson(response);  //Check DOM for structure	
						 trial_id = tsid.trial_id;
					   },  //the response seen in the browser comes from here.
				error : function(response, args) {dojo.byId('element').innerHTML = "Error: " + response; return response;},
				timeout : 5000
				});
			}
///////////////////////////////////////
	
	
	
tests = function() //Sorts the tests which come from the block_tracker function (in a JSON structure)
{
	shuffle(nback_info.nbacks);
								//Nback_id_all
								for(i=0;i<nback_info.nbacks.length;i++)
									{
									pushthis = nback_info.nbacks[i].nback_id;
									pushthis = parseInt(pushthis); // converts string to integer
									nback_id_all.push(pushthis);
									}
								
								//category_all
								for(i=0;i<nback_info.nbacks.length;i++)
									{
									pushthis = nback_info.nbacks[i].object_category;
									category_all.push(pushthis);
									}		
									
								//nback_all
								for(i=0;i<nback_info.nbacks.length;i++)
									{
									pushthis = nback_info.nbacks[i].nback;
									pushthis = parseInt(pushthis); // converts string to integer
									nback_all.push(pushthis);
									}	
									
								//imgNames_all
								for(i=0;i<nback_info.nbacks.length;i++)
									{
									pushthis = nback_info.nbacks[i].stimuli_names;					
									imgNames_all.push(pushthis);
									imgNames_all[i] = dojo.fromJson(imgNames_all[i]); 
									// Gets rid of extra quotes																	
									}																	
								
								//nback_matching_all
								for(i=0;i<nback_info.nbacks.length;i++)
									{
									pushthis = nback_info.nbacks[i].matching;
									nback_matching_all.push(pushthis);
									}	
									
								//instructions1_all
								for(i=0;i<nback_info.nbacks.length;i++)
									{
									pushthis = nback_info.nbacks[i].instructions1;
									instructions1_all.push(pushthis);
									}	
									
								//instructions2_all
								for(i=0;i<nback_info.nbacks.length;i++)
									{
									pushthis = nback_info.nbacks[i].instructions2;
									instructions2_all.push(pushthis);
									}	
									
								//pics_all
								for(i=0;i<nback_info.nbacks.length;i++)
									{
									pushthis = nback_info.nbacks[i].example;
									pics_all.push(pushthis);
									}	
									
//console.log(nback_id_all);
//console.log(category_all);
//console.log(nback_all);
//console.log(imgNames_all);
//console.log(nback_matching_all);
//console.log(instructions1_all);
//console.log(instructions2_all);
//console.log(pics_all);

number_of_tests();
}

// Default settings
        var practice = 1;

        var instructions = new Array();
        //should be images with picture examples
        var instruc_pics = new Array();
		var nback_id = new Array();
		var nback = new Array();
		var nback_matching = new Array();
		var category = new Array();
		var imgNames = new Array();
        var block_tracker = 0; // Keeps track of how many tests are done
        var stackContainer = [];
        var difference = [];
		
        var age = 100; //default values
        var sex = 100;
        var education = 100;
        var glasses = 100;
        var color_blind = 100;
        var colored_sequences = 100;
        var colored_sound = 100;
        var spatial_form = 100;
        var netherlands = 100;
        var participation = 100;
        var informed_consent = 100;
		var language = "english"; // change here for dutch!

// To show with the scores
var pic_links =
    ['extra',
    'reaction_time', 'orientation_rectangle', 'orientation_abstract', 'orientation_scissors', 'orientation_face', 'orientation_house',
    'shape_abstract_round', 'shape_abstract_sharp', 'shape_simple_round', 'shape_simple_sharp', 'shape_scissors', 'shape_dogs', 'shape_houses',
    'color_squares', 'color_partial', 'color_cars',
    'faces_generated_white', 'faces_generated_black', 'faces_white', 'faces_black',
    'spatial_partial', 'spatial_gridless', 'spatial_realworld',
    'local_letters', 'global_letters', 'local_shapes', 'global_shapes',
    'verbal_perceptual_english', 'verbal_semantic_english', 'verbal_letters_english', 'verbal_rhyme_english', 'shape_partial'
    ];


 
	
number_of_tests = function()
{   stackContainer = dijit.byId("stackContainer");

// Content Panes	

   var instructions1 = dijit.byId("instructions1");
   var instructions2 = dijit.byId("instructions2");
   var instructions3 = dijit.byId("instructions3");
   var instructions4 = dijit.byId("instructions4");
   var test1 = dijit.byId("test1");
   var test2 = dijit.byId("test2");
   var test3 = dijit.byId("test3");
   var test4 = dijit.byId("test4");

// Divs
   var category1a = document.getElementById("category1a");
    category1a.innerHTML = instructions1_all[0];
   var category1b = document.getElementById("category1b");
    category1b.innerHTML = instructions1_all[0];
   var category2a = document.getElementById("category2a");
    category2a.innerHTML = instructions1_all[1];
   var category2b = document.getElementById("category2b");
    category2b.innerHTML = instructions1_all[1];
   var category3a = document.getElementById("category3a");
    category3a.innerHTML = instructions1_all[2];
   var category3b = document.getElementById("category3b");
    category3b.innerHTML = instructions1_all[2];
   var category4a = document.getElementById("category4a");
    category4a.innerHTML = instructions1_all[3];
   var category4b = document.getElementById("category4b");
    category4b.innerHTML = instructions1_all[3];
    
   var specific_instructions1a = document.getElementById("specific_instructions1a");
    specific_instructions1a.innerHTML = instructions2_all[0]+"<br><br>";
   var specific_instructions1b = document.getElementById("specific_instructions1b");
    specific_instructions1b.innerHTML = instructions2_all[0]+"<br><br>";
   var specific_instructions2a = document.getElementById("specific_instructions2a");
    specific_instructions2a.innerHTML = instructions2_all[1]+"<br><br>";
   var specific_instructions2b = document.getElementById("specific_instructions2b");
    specific_instructions2b.innerHTML = instructions2_all[1]+"<br><br>";
   var specific_instructions3a = document.getElementById("specific_instructions3a");
    specific_instructions3a.innerHTML = instructions2_all[2]+"<br><br>";
   var specific_instructions3b = document.getElementById("specific_instructions3b");
    specific_instructions3b.innerHTML = instructions2_all[2]+"<br><br>";
   var specific_instructions4a = document.getElementById("specific_instructions4a");
    specific_instructions4a.innerHTML = instructions2_all[3]+"<br><br>";
   var specific_instructions4b = document.getElementById("specific_instructions4b");
    specific_instructions4b.innerHTML = instructions2_all[3]+"<br><br>";

   var pic1a = document.getElementById("pic1a");
    pic1a.innerHTML = "<img src=\"../../assets/images/exp1/instructions/"+pics_all[0]+".png\" width=\"500\" height=\"200\"/>";
   var pic1b = document.getElementById("pic1b");
    pic1b.innerHTML = "<img src=\"../../assets/images/exp1/instructions/"+pics_all[0]+".png\" width=\"500\" height=\"200\"/>";
   var pic2a = document.getElementById("pic2a");
    pic2a.innerHTML = "<img src=\"../../assets/images/exp1/instructions/"+pics_all[1]+".png\" width=\"500\" height=\"200\"/>";
   var pic2b = document.getElementById("pic2b");
    pic2b.innerHTML = "<img src=\"../../assets/images/exp1/instructions/"+pics_all[1]+".png\" width=\"500\" height=\"200\"/>";
   var pic3a = document.getElementById("pic3a");
    pic3a.innerHTML = "<img src=\"../../assets/images/exp1/instructions/"+pics_all[2]+".png\" width=\"500\" height=\"200\"/>";
   var pic3b = document.getElementById("pic3b");
    pic3b.innerHTML = "<img src=\"../../assets/images/exp1/instructions/"+pics_all[2]+".png\" width=\"500\" height=\"200\"/>";
   var pic4a = document.getElementById("pic4a");
    pic4a.innerHTML = "<img src=\"../../assets/images/exp1/instructions/"+pics_all[3]+".png\" width=\"500\" height=\"200\"/>";
   var pic4b = document.getElementById("pic4b");
    pic4b.innerHTML = "<img src=\"../../assets/images/exp1/instructions/"+pics_all[3]+".png\" width=\"500\" height=\"200\"/>";
    
	var instructions1a = dijit.byId("instructions1a");
	var instructions1b = dijit.byId("instructions1b");
	var instructions2a = dijit.byId("instructions2a");
	var instructions2b = dijit.byId("instructions2b");
	var instructions3a = dijit.byId("instructions3a");
	var instructions3b = dijit.byId("instructions3b");
	var instructions4a = dijit.byId("instructions4a");
	var instructions4b = dijit.byId("instructions4b");
	
	var practice_test1 = dijit.byId("practice_test1");
	var practice_test2 = dijit.byId("practice_test2");
	var practice_test3 = dijit.byId("practice_test3");
	var practice_test4 = dijit.byId("practice_test4");
	

  difference = 4 - nback_id_all.length;
        //console.log(difference);

  if (difference == 1)
    {
     stackContainer.removeChild(instructions4a);
     stackContainer.removeChild(practice_test4);
     stackContainer.removeChild(instructions4b);
     stackContainer.removeChild(test4);
    }
  else if (difference == 2)
    {
     stackContainer.removeChild(instructions4a);
     stackContainer.removeChild(practice_test4);
     stackContainer.removeChild(instructions4b);
     stackContainer.removeChild(test4);
     stackContainer.removeChild(instructions3a);
     stackContainer.removeChild(practice_test3);
     stackContainer.removeChild(instructions3b);
     stackContainer.removeChild(test3);
    }
  else if (difference == 3)
    {
     stackContainer.removeChild(instructions4a);
     stackContainer.removeChild(practice_test4);
     stackContainer.removeChild(instructions4b);
     stackContainer.removeChild(test4);
     stackContainer.removeChild(instructions3a);
     stackContainer.removeChild(practice_test3);
     stackContainer.removeChild(instructions3b);
     stackContainer.removeChild(test3);
     stackContainer.removeChild(instructions2a);
     stackContainer.removeChild(practice_test2);
     stackContainer.removeChild(instructions2b);
     stackContainer.removeChild(test2);
    }
		
//	dijit.byId('stackContainer').forward();									
}

check_info = function()
       {	  		
    
            function get_radio_value(name)
            {
                var i, retval = 100;

                for (i=0; i < document.info_form[name].length; i++)
                {
                   if (document.info_form[name][i].checked)
                   {
                      retval = document.info_form[name][i].value;
                  }
               }
               return retval;
            }
            
            
            //alert('Value: ' + get_radio_value('edu_button'));
        
                age = document.getElementById("info_form").Age.value;            
 				age = parseFloat(age);
                race = parseFloat(get_radio_value('race_button'));
				color_blind = parseFloat(get_radio_value('color_button'));
				colored_sequences = parseFloat(get_radio_value('cs_button'));
				colored_sound = parseFloat(get_radio_value('cm_button'));
				education = parseFloat(get_radio_value('edu_button'));
				glasses = parseFloat(get_radio_value('eye_button'));
				netherlands = parseFloat(get_radio_value('ned_button'));
				participation = parseFloat(get_radio_value('part_button'));
				sex = parseFloat(get_radio_value('sex_button'));
				spatial_form = parseFloat(get_radio_value('space_button'));			

				informed_consent = document.getElementById('info_form').consent_button.checked;


            var i, field_array = [sex,education,glasses,color_blind,colored_sequences,colored_sound,spatial_form,
                netherlands,participation], corrections = [];

            for (i = 0; i < field_array.length; i++)
            {
                if (field_array[i] == 100)
                {
                    corrections.push(i+2);
                }
            }


            if (corrections.length)
            {
                alert("Please, also answer the following question numbers: " + corrections.join(", "));
            }
            else
            {
                if (!informed_consent)
                {
                    alert("In order to proceed with the test, you must agree to the informed consent statement.");
                    return;
                }
                else
                {
                    informed_consent = 1;
					question_info();
                }
                
                
            }

       }


question_info = function()
       {
            dojo.xhrPost
				({
				url : "<?php echo $this->config->config['site_url']; ?>livetest/exp1/send_subject_info/",
				content:
				{
				//'subject_id': subject_id,
								'age': age,
                                'sex': sex,
                                'education': education,
                                'glasses': glasses,
                                'color_blind': color_blind,
                                'colored_sequences': colored_sequences,
                                'colored_sound': colored_sound,
                                'spatial_form': spatial_form,
                                'netherlands': netherlands,
                                'participation': participation,
                                'informed_consent': informed_consent,
								'language': language // line 244
				},
				handleAs: "text",  //comes back either as text or as json, array or object
                load : function(){
				                dijit.byId('stackContainer').forward();
					   },  //the response seen in the browser comes from here.
				error : function(response, args) {dojo.byId('element').innerHTML = "Error: " + response; return response;},
				timeout : 5000
				});
       }

start_experiment = function(b)
{
        if(practice == 1)
            {
                max_trials = 10; //practice trials
                thirty_percent = Math.round(.33 * max_trials);
            }
        else if(practice == 0)
            {
                max_trials = 100; //test trials
                thirty_percent = Math.round(.33 * max_trials);
            }
            
	nback_id = nback_id_all[b];
	nback = nback_all[b];
	nback_matching = nback_matching_all[b];
	category = category_all[b];      
	imgNames = imgNames_all[b];
	imgNames = shuffle(imgNames);
	//console.log("Image Names: ",imgNames);
	preload();
	set_initial_positions();

        if(nback_matching == 'identity')
		{
            define_trials_identity();
            if(practice == 0) {send_data();}
            setTimeout("change_picture_identity()", 1000);
        }
        else if(nback_matching == 'partial')
        {
            define_trials_partial();
            if(practice == 0) {send_data();}
            setTimeout("change_picture_partial()", 1000);
        }
        else if(nback_matching == 'reaction_time')
        {
            define_trials_rt();
            show_target();
            if(practice == 0) {send_data();}
            setTimeout("change_picture_rt()", 2000);
            // set target
        }       
}

var get_all_scores = [];	   
var all_scores = [];
var score_ids = [];
var scores = [];
var score_pics = [];

show_scores = function() //Shows the scores of the 4 tests in the current block
    {
	
		for(i=0;i<nback_id_all.length;i++)
            {
                score_pics[i] = "<img src=\"../../assets/images/exp1/"+pic_links[nback_id_all[i]]+"/"+pic_links[nback_id_all[i]]+"1.png\" width=\"90\" height=\"90\" />";
            }
			
        if(difference == 0)
            {
				document.getElementById("score_pic1").innerHTML = ('<center>'+ score_pics[0]+'<big> Score: '+scores[0]+'</big><br><br>');
				document.getElementById("score_pic2").innerHTML = ('<center>'+ score_pics[1]+'<big> Score: '+scores[1]+'</big><br><br>');
				document.getElementById("score_pic3").innerHTML = ('<center>'+ score_pics[2]+'<big> Score: '+scores[2]+'</big><br><br>');
				document.getElementById("score_pic4").innerHTML = ('<center>'+ score_pics[3]+'<big> Score: '+scores[3]+'</big><br><br>');				
            }
        else if(difference == 1)
            {
				document.getElementById("score_pic1").innerHTML = ('<center>'+ score_pics[0]+'<big> Score: '+scores[0]+'</big><br><br>');
				document.getElementById("score_pic2").innerHTML = ('<center>'+ score_pics[1]+'<big> Score: '+scores[1]+'</big><br><br>');
				document.getElementById("score_pic3").innerHTML = ('<center>'+ score_pics[2]+'<big> Score: '+scores[2]+'</big><br><br>');
            }
         else if(difference == 2)
            {
			
				document.getElementById("score_pic1").innerHTML = ('<center>'+ score_pics[0]+'<big> Score: '+scores[0]+'</big><br><br>');
				document.getElementById("score_pic2").innerHTML = ('<center>'+ score_pics[1]+'<big> Score: '+scores[1]+'</big><br><br>');
            }
          else if(difference == 3)
            {
				document.getElementById("score_pic1").innerHTML = ('<center>'+ score_pics[0]+'<big> Score: '+scores[0]+'</big><br><br>');
            }
    }

get_scores_func = function() // Gets ALL scores from completed tests
       {
                        dojo.xhrPost
				({
				url : "<?php echo $this->config->config['site_url']; ?>livetest/exp1/subject_scores/",
				content:
				{
				// Empty
				},
				handleAs: "text",  //comes back either as text or as json, array or object
                                load: function(response) {								
								get_all_scores = dojo.fromJson(response);  //Check DOM for structure	
								all_scores = get_all_scores.scores;
								score_ids = get_all_scores.ids;																			
								write_scores(all_scores, score_ids);				
                                },
				error : function(response, args) {dojo.byId('element').innerHTML = "Error: " + response; return response;},
				timeout : 5000
				});                                
       }


var save_pics = [];
var percent = 0;
var average = [];

var string_i = [];

write_scores = function(a, b) //all_scores, score_ids
{
	for(j=0; j<a.length; j++)
	{
		a[j] = parseInt(a[j]);
		//console.log('type of: ', typeof(a[j]));
	}
	
	var sum = 0;
	for (var x = 0; x < a.length; x++)
	{
		sum = sum + a[x];
		//console.log('here ',a[x], typeof(a[x]));
	}
		
	average = Math.round(sum/b.length); // sum all scores, average over all tests (score is already computed out of 100 max)
	//console.log('avg ', average);
	//console.log('sum: ', sum);
	
	var intro1 = document.getElementById("intro1"); 
	intro1.innerHTML = ('<font face="Tahoma"><center>You have completed ' + b.length + '/32 tests</center><br><br>');
	var intro2 = document.getElementById("intro2");
	intro2.innerHTML = ('<font face="Tahoma"><center>Your average score is ' + average + '</center><br><br>');
	var intro3 = document.getElementById("intro3");
	intro3.innerHTML = ('<font face="Tahoma"><center><b><u>Individual test scores:</u></b></center><br><br>');
	
	for(i=1; i<all_scores.length+1; i++)
	{
		string_i = i+''; 
		//console.log(string_i);
		div_id = document.getElementById(string_i);
		div_id.innerHTML = ('<center><img src="../../assets/images/exp1/'+pic_links[b[i-1]]+'/'+pic_links[b[i-1]]+'1.png" width="90" height="90" /></a><big> Score: '+a[i-1]+'</big><br><br>');
	}		
}

function check(radio_button)
{
	var r = radio_button;
	
	switch(r.name)
	{
		case 'sex_button': 		sex = r.value;
								break;
		case 'race_button': 	race = r.value;
								break;
		case 'edu_button': 		education = r.value;
								break;
		case 'eye_button': 		glasses = r.value;
								break;
		case 'color_button': 	color_blind = r.value;
								break;
		case 'cs_button': 		colored_sequences = r.value;
								break;
		case 'cm_button': 		colored_sound = r.value;
								break;
		case 'space_button': 	spatial_form = r.value;
								break;
		case 'ned_button': 		netherlands = r.value;
								break;
		case 'part_button': 	participation = r.value;
								break;
		case 'consent_button': 	informed_consent = r.value;
								break;
	}
	//console.log("sex",sex);
	//console.log("edu",education);
}


</script>
</head>

<body class="tundra">
    <center>
	

<div dojoType="dijit.layout.StackContainer" style="height:600px;width:800px; border:solid 1px;" id="stackContainer" class="format">

<!-- General Intro  -->
    <div dojoType="dijit.layout.ContentPane" id="intro" class="format">
    <img src="../../assets/images/exp1/uva.jpg" />
    <h1>
	<b>
	<big>
	<font color="#990033">
	Visual Memory Test
	</font>
	</big>
	</b>
	</h1>
    <big>
    <h3>How good is your visual memory?</h3>
	</big>	
	<br>
    
	<button id="begin" onclick="check_question_info();" dojoType="dijit.form.Button" type="button"><big>Begin!</big></button>
               <br><br><br>
<img src="../../assets/images/exp1/HumanVisualSystem.JPG" />
    <br><br><br><br><br><br><br>
            For any questions, comments or problems with the website please email: <u>o.colizoli@uva.nl</u><br>    
		
</div>

<!-- Questionnaire for new subjects  -->
<div dojoType="dijit.layout.ContentPane" id="questions" class="format">

<img src="../../assets/images/exp1/uva.jpg" />

<h1><b><font color="#990033">
 Visual Memory Tests</font></b></h1>
    <h3>
    Before beginning the visual memory test, can you please answer some questions? <br><br>
        Your information is anonymous and will never be given to third parties.<br>
		Please do not proceed if you are under 18-years old with out your parents' permission.
            <br><br>
                </h3>
    <div id="form_div">
        <form name="info_form" id="info_form" >
1. <u>Age?</u><br>
<select name="Age">
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
<option value="32">32</option>
<option value="33">33</option>
<option value="34">34</option>
<option value="35">35</option>
<option value="36">36</option>
<option value="37">37</option>
<option value="38">38</option>
<option value="39">39</option>
<option value="40">40</option>
<option value="41">41</option>
<option value="42">42</option>
<option value="43">43</option>
<option value="44">44</option>
<option value="45">45</option>
<option value="46">46</option>
<option value="47">47</option>
<option value="48">48</option>
<option value="49">49</option>
<option value="50">50</option>
<option value="51">51</option>
<option value="52">52</option>
<option value="53">53</option>
<option value="54">54</option>
<option value="55">55</option>
<option value="56">56</option>
<option value="57">57</option>
<option value="58">58</option>
<option value="59">59</option>
<option value="60">60</option>
<option value="61">61</option>
<option value="62">62</option>
<option value="62">62</option>
<option value="63">63</option>
<option value="64">64</option>
<option value="65">65</option>
<option value="66">66</option>
<option value="67">67</option>
<option value="68">68</option>
<option value="69">69</option>
<option value="70">70</option>
<option value="71">71</option>
<option value="72">72</option>
<option value="73">73</option>
<option value="74">74</option>
<option value="75">75</option>
<option value="76">76</option>
<option value="77">77</option>
<option value="78">78</option>
<option value="79">79</option>
<option value="80">80</option>
<option value="81">81</option>
<option value="82">82</option>
<option value="83">83</option>
<option value="84">84</option>
<option value="85">85</option>
<option value="86">86</option>
<option value="87">87</option>
<option value="88">88</option>
<option value="89">89</option>
<option value="90">90</option>
<option value="91">91</option>
<option value="92">92</option>
<option value="93">93</option>
<option value="94">94</option>
<option value="95">95</option>
<option value="96">96</option>
<option value="97">97</option>
<option value="98">98</option>
<option value="99">99</option>
<option value="100">100</option>
</select>
    <br><br>

2. <u>Gender?</u><br>
<font color="grey">
<input type="radio" name="sex_button" onclick="check(this);" value=0 />Female<br>
<input type="radio" name="sex_button" onclick="check(this);" value=1 />Male<br><br>
</font>

3. <u>Race?</u><br>
<font color="grey">
<input type="radio" name="race_button" onclick="check(this);" value=0 />Caucasian<br>
<input type="radio" name="race_button" onclick="check(this);" value=1 />Asian<br>
<input type="radio" name="race_button" onclick="check(this);" value=2 />Middle-Eastern<br>
<input type="radio" name="race_button" onclick="check(this);" value=3 />African<br>
<input type="radio" name="race_button" onclick="check(this);" value=4 />Other (including multi-racial)<br><br>
</font>

4. <u>Level of Education?</u><br>
(include incomplete education if more than 2 years)<br>
<font color="grey">
<input type="radio" name="edu_button" onclick="check(this);" value=0 />Primary/Middle school<br>
<input type="radio" name="edu_button" onclick="check(this);" value=1 />High school<br>
<input type="radio" name="edu_button" onclick="check(this);" value=2 />Bachelor program <br>
<input type="radio" name="edu_button" onclick="check(this);" value=3 />Master/PhD program<br><br>
</font>

5. <u>Do you wear glasses?</u><br>
<font color="grey">
<input type="radio" name="eye_button" onclick="check(this);" value=0 />No<br>
<input type="radio" name="eye_button" onclick="check(this);" value=1 />Yes, near-sighted<br>
<input type="radio" name="eye_button" onclick="check(this);" value=2 />Yes, far-sighted<br><br>
</font>

6. <u>Are you color blind?</u><br>
<font color="grey">
<input type="radio" name="color_button" onclick="check(this);" value=0 />No<br>
<input type="radio" name="color_button" onclick="check(this);" value=1 />Yes<br>
<input type="radio" name="color_button" onclick="check(this);" value=2 />I don't know<br><br>
</font>

7. <u>Do letters, numbers, days of the week or months have color to you?</u><br>
<font color="grey">
<input type="radio" name="cs_button" onclick="check(this);" value=0 />No<br>
<input type="radio" name="cs_button" onclick="check(this);" value=1 />Yes<br>
<input type="radio" name="cs_button" onclick="check(this);" value=2 />I don't know<br><br>
</font>

8. <u>Do sounds, music or noise have color to you?</u><br>
<font color="grey">
<input type="radio" name="cm_button" onclick="check(this);" value=0 />No<br>
<input type="radio" name="cm_button" onclick="check(this);" value=1 />Yes<br>
<input type="radio" name="cm_button" onclick="check(this);" value=2 />I don't know<br><br>
</font>

9. <u>Do numbers, months, weeks, years or other sequences occupy spatial locations to you?</u><br>
<font color="grey">
<input type="radio" name="space_button" onclick="check(this);" value=0 />No<br>
<input type="radio" name="space_button" onclick="check(this);" value=1 />Yes<br>
<input type="radio" name="space_button" onclick="check(this);" value=2 />I don't know<br><br>
</font>

10. <u>Do you live in the Netherlands?</u><br>
<font color="grey">
<input type="radio" name="ned_button" onclick="check(this);" value=0 />No<br>
<input type="radio" name="ned_button" onclick="check(this);" value=1 />Yes<br><br>
</font>

11. <u>Would you like to participate in local studies? (We will contact you via email) </u><br>
<font color="grey">
<input type="radio" name="part_button" onclick="check(this);" value=0 />No<br>
<input type="radio" name="part_button" onclick="check(this);" value=1 />Yes<br><br>
</font>

<u>Informed Consent:</u><br>
I am participating voluntarily and may stop at any time.<br>
The data used here may be published anonymously for scientific purposes. <br>
<font color="grey">
<input type="checkbox" name="consent_button" onclick="check(this);" value=1 />Yes, I understand<br>
</font>

<br>
		<button id="info" dojoType="dijit.form.Button" type="button">Submit</button>
<br>
<br>
<br>
<br>

</form>

</div>

</div>


<!-- General instructions 1  -->
    <div dojoType="dijit.layout.ContentPane" id="welcome_pane1" class="format">
    <img src="../../assets/images/exp1/uva.jpg"/>
    <h1><b><font color="#990033">
    Visual Memory Test</font></b></h1>

    <br>
	<big>
    This experiment is a series of memory tests. <br>
	Each test has different types of visual images.
    <br><br>
    These tests will be given to you in groups of 4.
	<br>
	There are 8 of these groups in total.
    <br>
    Each group takes 15-20 minutes. 
    <br><br>
    Please do <u>not</u> do anything else while you are taking the tests, <br>
        such as surfing the Internet.
    <br><br>
    Please do <u>not</u> write anything down in order to remember it.    
    <br><br>
	</big>
	<button id="next1" dojoType="dijit.form.Button" type="button"><h3>Next</h3>
            <script type="dojo/method" event="onClick" args="evt">
                dijit.byId('stackContainer').forward();
            </script>
        </button>
    </div>

<!-- General instructions 2  -->
    <div dojoType="dijit.layout.ContentPane" id="welcome_pane2" class="format">
    <img src="../../assets/images/exp1/uva.jpg"/>
    <h1><b><font color="#990033">
    Visual Memory Test</font></b></h1>
    <br>
	<big>
     Before each test, you will receive specific instructions.
    <br>
    The tests are very similar, but the instructions change slightly each time. <br><br>
        <font color="red">Please be sure to read the instructions carefully before starting the test</font>.
    <br><br>    
    Before each test, you will be given a short practice session.
    <br><br>
    You will be given your scores at the end of each block (4 tests).
    <br><br>
	</big>
        <button id="back1" dojoType="dijit.form.Button" type="button"><h3>Back</h3>
            <script type="dojo/method" event="onClick" args="evt">
                dijit.byId('stackContainer').back();
            </script>
        </button>

	<button id="next2" dojoType="dijit.form.Button" type="button"><h3>Next</h3>
            <script type="dojo/method" event="onClick" args="evt">
                dijit.byId('stackContainer').forward();
            </script>
        </button>
    </div>


<!-- Instructions for Test 1 -->
     <div dojoType="dijit.layout.ContentPane" id="instructions1a" class="format">
	<h1 style="text-align: center"><u>#1 Instructions:</u></h1>
	<h3 style="text-align: center">
            <div id="category1a"> </div><br>
            <div id="specific_instructions1a"></div>
        </h3>
        <div id="pic1a"></div>
	<br><br>
	Note! Points are deducted for incorrect responses. <br>
	Therefore, it is not beneficial to <u>always</u> click MATCH!
	<br><br>
        <button id="practice1" dojoType="dijit.form.Button" type="button"><h3>Practice</h3>
            <script type="dojo/method" event="onClick" args="evt">
		dijit.byId('stackContainer').forward();                
		start_experiment(block_tracker);
            </script>
	</button>
    </div>

<!-- Practice Test 1 -->
    <div dojoType="dijit.layout.ContentPane" id="practice_test1" class="format">
        <br><br><br><br>PRACTICE
	<div id="practice_box1"><img id="practice_current1" src="../../assets/images/exp1/white.png" alt="" /></div><br><br>
	<div id="practice_match_div1" class="format">
             <form>
                <button id="practice_match1" dojoType="dijit.form.Button" type="button">Match</button>
             </form>
            <br><br>
            Feedback:<br><font color="15CD1E">Correct</font><br><font color="red">Incorrect</font>
        </div>
    </div>

<!-- After Practice Test 1 -->
     <div dojoType="dijit.layout.ContentPane" id="instructions1b" class="format">
	<h1 style="text-align: center"><u>#1 Instructions:</u></h1>
	<h3 style="text-align: center">
            <div id="category1b"> </div><br>
            <div id="specific_instructions1b"></div>
        </h3>
        <div id="pic1b"></div>
	<br><br>
	Note! Points are deducted for incorrect responses. <br>
	Therefore, it is not beneficial to <u>always</u> click MATCH!
	<br><br>
        <button id="start_test1" dojoType="dijit.form.Button" type="button"><h3>Start Test</h3>
            <script type="dojo/method" event="onClick" args="evt">
		dijit.byId('stackContainer').forward();
		start_experiment(block_tracker);
            </script>
	</button>
    </div>


<!-- Test 1 -->
    <div dojoType="dijit.layout.ContentPane" id="test1" class="format">
	<div id="box1"><img id="current1" src="../../assets/images/exp1/white.png" alt="" /></div><br><br>
	<div id="match_div1" class="format">
             <form>
                <button id="match1" dojoType="dijit.form.Button" type="button">Match</button>
             </form>
        </div>
    </div>




<!-- Instructions for Test 2 -->
     <div dojoType="dijit.layout.ContentPane" id="instructions2a" class="format">
	<h1 style="text-align: center"><u>#2 Instructions:</u></h1>
	<h3 style="text-align: center">
            <div id="category2a"> </div><br>
            <div id="specific_instructions2a"></div>
        </h3>
        <div id="pic2a"></div>
	<br><br>
	Note! Points are deducted for incorrect responses. <br>
	Therefore, it is not beneficial to <u>always</u> click MATCH!
	<br><br>
        <button id="practice2" dojoType="dijit.form.Button" type="button"><h3>Practice</h3>
            <script type="dojo/method" event="onClick" args="evt">
		dijit.byId('stackContainer').forward();
		start_experiment(block_tracker);
            </script>
	</button>
    </div>

<!-- Practice Test 2 -->
    <div dojoType="dijit.layout.ContentPane" id="practice_test2" class="format">
        <br><br><br><br>PRACTICE
	<div id="practice_box2"><img id="practice_current2" src="../../assets/images/exp1/white.png" alt="" /></div><br><br>
	<div id="practice_match_div2" class="format">
             <form>
                <button id="practice_match2" dojoType="dijit.form.Button" type="button">Match</button>
             </form>
            <br><br>
            Feedback:<br><font color="15CD1E">Correct</font><br><font color="red">Incorrect</font>
        </div>
    </div>

<!-- After Practice Test 2 -->
     <div dojoType="dijit.layout.ContentPane" id="instructions2b" class="format">
	<h1 style="text-align: center"><u>#2 Instructions:</u></h1>
	<h3 style="text-align: center">
            <div id="category2b"> </div><br>
            <div id="specific_instructions2b"></div>
        </h3>
        <div id="pic2b"></div>
	<br><br>
	Note! Points are deducted for incorrect responses. <br>
	Therefore, it is not beneficial to <u>always</u> click MATCH!
	<br><br>
        <button id="start_test2" dojoType="dijit.form.Button" type="button"><h3>Start Test</h3>
            <script type="dojo/method" event="onClick" args="evt">
		dijit.byId('stackContainer').forward();
		start_experiment(block_tracker);
            </script>
	</button>
    </div>
 

<!-- Test 2 -->
    <div dojoType="dijit.layout.ContentPane" id="test2" class="format">
	<div id="box2"><img id="current2" src="../../assets/images/exp1/white.png" alt="" /></div><br><br>
	<div id="match_div2" class="format">
             <form>
                <button id="match2" dojoType="dijit.form.Button" type="button">Match</button>
             </form>
        </div>
    </div>


<!-- Instructions for Test 3 -->
     <div dojoType="dijit.layout.ContentPane" id="instructions3a" class="format">
	<h1 style="text-align: center"><u>#3 Instructions:</u></h1>
	<h3 style="text-align: center">
            <div id="category3a"> </div><br>
            <div id="specific_instructions3a"></div>
        </h3>
        <div id="pic3a"></div>
	<br><br>
	Note! Points are deducted for incorrect responses. <br>
	Therefore, it is not beneficial to <u>always</u> click MATCH!
	<br><br>
        <button id="practice3" dojoType="dijit.form.Button" type="button"><h3>Practice</h3>
            <script type="dojo/method" event="onClick" args="evt">
		dijit.byId('stackContainer').forward();
		start_experiment(block_tracker);
            </script>
	</button>
    </div>

<!-- Practice Test 3 -->
    <div dojoType="dijit.layout.ContentPane" id="practice_test3" class="format">
        <br><br><br><br>PRACTICE
	<div id="practice_box3"><img id="practice_current3" src="../../assets/images/exp1/white.png" alt="" /></div><br><br>
	<div id="practice_match_div3" class="format">
             <form>
                <button id="practice_match3" dojoType="dijit.form.Button" type="button">Match</button>
             </form>
            <br><br>
            Feedback:<br><font color="15CD1E">Correct</font><br><font color="red">Incorrect</font>
        </div>
    </div>

<!-- After Practice Test 3 -->
     <div dojoType="dijit.layout.ContentPane" id="instructions3b" class="format">
	<h1 style="text-align: center"><u>#3 Instructions:</u></h1>
	<h3 style="text-align: center">
            <div id="category3b"> </div><br>
            <div id="specific_instructions3b"></div>
        </h3>
        <div id="pic3b"></div>
	<br><br>
	Note! Points are deducted for incorrect responses. <br>
	Therefore, it is not beneficial to <u>always</u> click MATCH!
	<br><br>
        <button id="start_test3" dojoType="dijit.form.Button" type="button"><h3>Start Test</h3>
            <script type="dojo/method" event="onClick" args="evt">
		dijit.byId('stackContainer').forward();
		start_experiment(block_tracker);
            </script>
	</button>
    </div>

<!-- Test 3 -->
    <div dojoType="dijit.layout.ContentPane" id="test3" class="format">
        <div id="box3"><img id="current3" src="../../assets/images/exp1/white.png" alt="" /></div><br><br>
	<div id="match_div3" class="style">
            <form>
            <button id="match3" dojoType="dijit.form.Button" type="button">Match</button>
            </form>
         </div>
     </div>

<!-- Instructions for Test 4 -->
     <div dojoType="dijit.layout.ContentPane" id="instructions4a" class="format">
	<h1 style="text-align: center"><u>#4 Instructions:</u></h1>
	<h3 style="text-align: center">
            <div id="category4a"> </div><br>
            <div id="specific_instructions4a"></div>
        </h3>
        <div id="pic4a"></div>
	<br><br>
	Note! Points are deducted for incorrect responses. <br>
	Therefore, it is not beneficial to <u>always</u> click MATCH!
	<br><br>
        <button id="practice4" dojoType="dijit.form.Button" type="button"><h3>Practice</h3>
            <script type="dojo/method" event="onClick" args="evt">
		dijit.byId('stackContainer').forward();
		start_experiment(block_tracker);
            </script>
	</button>
    </div>

<!-- Practice Test 4 -->
    <div dojoType="dijit.layout.ContentPane" id="practice_test4" class="format">
        <br><br><br><br>PRACTICE 
	<div id="practice_box4"><img id="practice_current4" src="../../assets/images/exp1/white.png" alt="" /></div><br><br>
	<div id="practice_match_div4" class="format">
             <form>
                <button id="practice_match4" dojoType="dijit.form.Button" type="button">Match</button>
             </form>
            <br><br>
            Feedback:<br><font color="15CD1E">Correct</font><br><font color="red">Incorrect</font>
        </div>
    </div>

<!-- After Practice Test 4 -->
     <div dojoType="dijit.layout.ContentPane" id="instructions4b" class="format">
	<h1 style="text-align: center"><u>#4 Instructions:</u></h1>
	<h3 style="text-align: center">
            <div id="category4b"> </div><br>
            <div id="specific_instructions4b"></div>
        </h3>
        <div id="pic4b"></div>
	<br><br>
	Note! Points are deducted for incorrect responses. <br>
	Therefore, it is not beneficial to <u>always</u> click MATCH!
	<br><br>
        <button id="start_test4" dojoType="dijit.form.Button" type="button"><h3>Start Test</h3>
            <script type="dojo/method" event="onClick" args="evt">
		dijit.byId('stackContainer').forward();
		start_experiment(block_tracker);
            </script>
	</button>
    </div>

<!-- Test 4 -->
    <div dojoType="dijit.layout.ContentPane" id="test4" class="format">
        <div id="box4"><img id="current4" src="../../assets/images/exp1/white.png" alt="" /></div><br><br>
        <div id="match_div4" class="style">
            <form>
            <button id="match4" dojoType="dijit.form.Button"type="button">Match</button>
            </form>
        </div>
    </div>

<!-- After & Scores -->
    <div dojoType="dijit.layout.ContentPane" id="scores" class="format">
	 <h1><b><font color="#990033">
        UvA Visual Memory Tests</font></b></h1>
	<h2 style="text-align: center">This block is completed.</h2>       	

        <button id="new_block1" dojoType="dijit.form.Button" type="button"><h3>New Block</h3>
            <script type="dojo/method" event="onClick" args="evt">
		window.location.reload();
            </script>
	</button>
	
        <h3>Here are your scores for the visual memory tests. </h3>
		<br>
        They are computed with the formula: <br>
        <i>Correct / (Correct + Misses + Incorrect)</i><br><br>
		All scores are out of 100 points.
        <br><br>
		___________________________________________<br><br>

        <div id="score_pic1"></div>
        <div id="score_pic2"></div>
        <div id="score_pic3"></div>
        <div id="score_pic4"></div>
        <br><br>

        <button id="new_block2" dojoType="dijit.form.Button" type="button"><h3>New Block</h3>
            <script type="dojo/method" event="onClick" args="evt">
		window.location.reload();
            </script>
	</button>

        <button id="show_all_scores" dojoType="dijit.form.Button" type="button"><h3>All Scores</h3>
            <script type="dojo/method" event="onClick" args="evt">			
			get_scores_func();
			dijit.byId('stackContainer').forward();
            </script>
	</button>
	<br><br>
</div>
	 
<!-- All Scores -->
    <div dojoType="dijit.layout.ContentPane" id="all_scores_pane" class="format">
	<h1><b><font color="#990033">
    UvA Visual Memory Tests</font></b></h1>
	<h2 style="text-align: center">This block is completed.</h2>       	

        <button id="new_block3" dojoType="dijit.form.Button" type="button"><h3>New Block</h3>
            <script type="dojo/method" event="onClick" args="evt">
		window.location.reload();
            </script>
	</button>

         <h3>Here are your scores for the visual memory tests. </h3>
		<br>
        They are computed with the formula: <br>
        <i>Correct / (Correct + Misses + Incorrect)</i><br><br>
		All scores are out of 100 points.
        <br><br>	
		___________________________________________<br><br>

		<div id="intro1"></div>
		<div id="intro2"></div>
		<div id="intro3"></div>
		<div id="1"></div>
		<div id="2"></div>
		<div id="3"></div>
		<div id="4"></div>
		<div id="5"></div>
		<div id="6"></div>
		<div id="7"></div>
		<div id="8"></div>
		<div id="9"></div>
		<div id="10"></div>
		<div id="11"></div>
		<div id="12"></div>
		<div id="13"></div>
		<div id="14"></div>
		<div id="15"></div>
		<div id="16"></div>
		<div id="17"></div>
		<div id="18"></div>
		<div id="19"></div>
		<div id="20"></div>
		<div id="21"></div>
		<div id="22"></div>
		<div id="23"></div>
		<div id="24"></div>
		<div id="25"></div>
		<div id="26"></div>
		<div id="27"></div>
		<div id="28"></div>
		<div id="29"></div>
		<div id="30"></div>
		<div id="31"></div>
		<div id="32"></div>
 
        <br><br>

    <button id="new_block4" dojoType="dijit.form.Button" type="button"><h3>New Block</h3>
            <script type="dojo/method" event="onClick" args="evt">
		window.location.reload();
            </script>
	</button>
	<br><br>
</div>
	 

</div><!-- END STACK CONTAINER -->

<div id="disclaimer">
Approved by the <a href="../assets/images/exp1/ethics.php" target="_blank">ethics committee</a> of the Department of Psychology at the University of Amsterdam.
</div>



<div id="element"></div>

    </center>

</body>
</html>


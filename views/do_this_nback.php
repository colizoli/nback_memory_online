<!DOCTYPE html>
<html>
  <head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta charset="UTF-8" />
<meta name="description" content="Visual Memory Test" />
<meta name="keywords" content="" />
<meta name="revised" content="Olympia Colizoli, 20/10/2011" />
<meta http-equiv="content-language" content="en-US" />

<title>Visual Memory Test - Take One Nback - University of Amsterdam, Brain and Cognition  </title>
<link rel="stylesheet" type="text/css" href="../../css/nbackcss/stack_container.css" />
<link rel="stylesheet" type="text/css" href="../../libs/dojoroot/dojo/resources/dojo.css" />
<link rel="stylesheet" type="text/css" href="../../libs/dojoroot/dijit/themes/tundra/tundra.css" />

<script type="text/javascript" src="../../libs/dojoroot/dojo/dojo.js" djConfig="isDebug: true, parseOnLoad: true"></script>

<script type="text/javascript" src="../../js/exp1_common_code.js"></script>
<script type="text/javascript" src="../../js/exp1_identity.js"></script>
<script type="text/javascript" src="../../js/exp1_partial.js"></script>
<script type="text/javascript" src="../../js/exp1_rt.js"></script>

<script type="text/javascript" src="../../js/shuffle.js"></script>
<script type="text/javascript" src="../../js/sumElements.js"></script>
<script type="text/javascript" src="../../js/contains.js"></script>

<script type="text/javascript">
    dojo.require("dojo.parser");
    dojo.require("dijit.layout.ContentPane");
    dojo.require("dijit.layout.StackContainer");
    dojo.require("dijit.form.Button");
</script>

<script type="text/javascript">

    var check_new = [];
	 
 	var nback_info = [];
	var nback_info_first = [];
	 
	var email = "5"; //default
	var check_questions = 1;

	var nback_id_all = [];
	var category_all = [];
	var imgNames_all = [];
	var nback_matching_all = [];
	var instructions1_all = [];
	var instructions2_all = [];
	var pics_all = [];
	
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
    var difference = 3;  // Necessary here do to 1 test
		
	var nback_to_do = [];	   
	var language = 0;

// To show with the scores
var pic_links =
    ['extra',
    'reaction_time', 'orientation_rectangle', 'orientation_abstract', 'orientation_scissors', 'orientation_face', 'orientation_house',
    'shape_abstract_round', 'shape_abstract_sharp', 'shape_simple_round', 'shape_simple_sharp', 'shape_scissors', 'shape_dogs', 'shape_houses',
    'color_squares', 'color_abstract', 'color_cars',
    'faces_generated_white', 'faces_generated_black', 'faces_white', 'faces_black',
    'spatial_grid', 'spatial_gridless', 'spatial_realworld',
    'local_letters', 'global_letters', 'local_shapes', 'global_shapes',
    'verbal_perceptual_english', 'verbal_semantic_english', 'verbal_letters_english', 'verbal_rhyme_english'
    ];


dojo.addOnLoad(function(){

	blank_screen();

	dojo.connect(dijit.byId("english"), "onClick", check_email);
	dojo.connect(dijit.byId("insert_button"), "onClick", test);

	dojo.connect(dijit.byId("match1"), "onClick", match_func);

    dojo.connect(dijit.byId("practice_match1"), "onClick", practice_match_func);

	//This prevents an empty image in the beginning? nope...
	var b1 = document.getElementById("box1");
	b1.src =  "../../images/exp1/white.png";

	var pb1 = document.getElementById("practice_box1");
	pb1.src =  "../../images/exp1/white.png";
	
	stackContainer = dijit.byId("stackContainer");

});

check_email = function() // Checks the form field to make sure an email has been submitted
        {
            email = document.getElementById("email_form").email2.value;
            if(email == "5" || email == " ")
                    {alert("Please add your email address.");}
            else
			{
                //alert(email);
                email = dojo.trim(email);
                subject_info(email);				
            }
	}

subject_info = function(e) // Checks database to see if the subject is new or old, returns the tests
       {	   
                        dojo.xhrPost
				({
				url : "<?php echo site_url(); ?>exp1/exp1/check_email/",
				content:
				{
				'email': e
				},
				handleAs: "text",  //comes back either as text or as json, array or object
                                load: function(response) {								
								nback_info_first = dojo.fromJson(response);  //Check DOM for structure	
								check_new = nback_info_first.new_subject;
								if(check_new == 0)
									{
		document.getElementById("show_all_scores").innerHTML="<a href=\"http://webserver3.fmg.uva.nl/olympia/exp1/exp1/subject_scores\" target=\"_blank\">All scores</a>";									
									}
								stackContainer.forward();
                                },
				error : function(response, args) {dojo.byId('element').innerHTML = "Error: " + response; return response;},
				timeout : 5000
				});                                
       }



test = function() //Sorts the tests which come from the block_tracker function (in a JSON structure)
{

	nback_to_do = document.getElementById("nback_form").insert_here.value;
	   
	for(i=0; i<document.nback_form.language_button.length; i++)
                    {
                        if(document.nback_form.language_button[i].checked)
                        {language = document.nback_form.language_button[i].value;}
                    }
	   //alert(nback_to_do);
	   
		  dojo.xhrPost
				({
				url : "<?php echo site_url(); ?>exp1/exp1/take_one_nback/",
				content:
				{
				'nback': nback_to_do,
				'language': language
				},
				handleAs: "text",  //comes back either as text or as json, array or object
                                load: function(response) {								
								nback_info = dojo.fromJson(response);  //Check DOM for structure
									//alert(nback_info.nbacks[0].nback_id);
								nback_id_all = parseInt(nback_info.nbacks[0].nback_id);
								nback_all = parseInt(nback_info.nbacks[0].nback);
								category_all = nback_info.nbacks[0].object_category;
								imgNames_all = nback_info.nbacks[0].stimuli_names;	
									imgNames_all = dojo.fromJson(imgNames_all); // Gets rid of extra quotes	 
								nback_matching_all = nback_info.nbacks[0].matching;
								instructions1_all = nback_info.nbacks[0].instructions1;
								instructions2_all = nback_info.nbacks[0].instructions2;
								pics_all = nback_info.nbacks[0].example;	

								nback_id = nback_id_all;
								nback = nback_all;
								nback_matching = nback_matching_all;
								category = category_all;      
								imgNames = imgNames_all;
	
								number_of_tests(); //must be in the 'load' part otherwise it starts too soon
     
                           },
				error : function(response, args) {dojo.byId('element').innerHTML = "Error: " + response; return response;},
				timeout : 5000
				});                    
}

number_of_tests = function()
{
	
// Content Panes
   
   var instructions1 = dijit.byId("instructions1");
   var test1 = dijit.byId("test1");
   
// Divs
   var category1a = document.getElementById("category1a");
    category1a.innerHTML = instructions1_all;	
   var category1b = document.getElementById("category1b");
    category1b.innerHTML = instructions1_all;
 
   var specific_instructions1a = document.getElementById("specific_instructions1a");
    specific_instructions1a.innerHTML = instructions2_all+"<br><br>";
   var specific_instructions1b = document.getElementById("specific_instructions1b");
    specific_instructions1b.innerHTML = instructions2_all+"<br><br>";

   var pic1a = document.getElementById("pic1a");
    pic1a.innerHTML = "<img src=\"../../images/exp1/instructions/"+pics_all+".png\" width=\"500\" height=\"200\"/>";
   var pic1b = document.getElementById("pic1b");
    pic1b.innerHTML = "<img src=\"../../images/exp1/instructions/"+pics_all+".png\" width=\"500\" height=\"200\"/>";
 
	var instructions1a = dijit.byId("instructions1a");
	var instructions1b = dijit.byId("instructions1b");

	var practice_test1 = dijit.byId("practice_test1");

   // Only want to do one test
 // difference = 4 - nback_id_all.length;
        //console.log(difference);
		
	dijit.byId('stackContainer').forward();									
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
            
	
	imgNames = shuffle(imgNames);
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
        score_pics = "<img src=\"../../images/exp1/"+pic_links[nback_id_all]+"/"+pic_links[nback_id_all]+"1.png\"/>";
            
		document.getElementById("score_pic1").innerHTML = ('<center>'+ score_pics+'<big>Score: '+scores+', NbackID= '+nback_to_do+'</big><br><br>');           
    }

get_scores_func = function() // Gets ALL scores from completed tests
       {
                        dojo.xhrPost
				({
				url : "<?php echo site_url(); ?>exp1/exp1/subject_scores/",
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
	stackContainer.forward();
	
	average = sumElements(a);
	average = average/a.length;
	average = Math.round(average*100)/100;
	
	var intro1 = document.getElementById("intro1"); 
	intro1.innerHTML = ('<font face="Georgia"><center>Your score is: ' + scores + '</center><br><br>');
	
		string_i = i+''; 
		//console.log(string_i);
		div_id = document.getElementById("score_pic1");
		
		div_id.innerHTML = (nback_to_do+'<center><img src="../../images/exp1/'+pic_links[nback_id-1]+'/'+pic_links[nback_id-1]+'1.png"/></a><big>Score: '+a[i-1]+'</big><br><br>');
			
}

//Keeps the Begin! button from submitting a form which opens a 404 page
document.onkeypress = noEnter;

function noEnter(evt) {
    if (evt.keyCode == dojo.keys.ENTER)
    {
        return false;
    }
}



</script>
</head>

<body class="tundra">
    <center>
<div dojoType="dijit.layout.StackContainer" style="height:600px;width:800px; border:solid 1px" id="stackContainer" class="format">

<!-- General Intro  -->
    <div dojoType="dijit.layout.ContentPane" id="intro" class="format">
    <img src="../../images/exp1/uva.jpg" />
    <h1><font color="#990033">
    Visual Memory Test</font></h1>
    <big><b>
    <h3>This is the site if you want to take ONE Nback test</h3>
	In case you want to do the whole series, click here:
	<a href="http://webserver3.fmg.uva.nl/olympia/images/html/individual_differences.html"> Individual Differences </a>
	<br><br>
    <div id="form_div">
    <form name="email_form" id="email_form" >
    Email Address: <br>
            <input type="text" name="email2" id="email" value=" "  />
            <br><br>
    </div>
    </b>
    </form>
    
	<button id="english" dojoType="dijit.form.Button">Begin!</button>
               <br><br><br>
<img src="../../images/exp1/HumanVisualSystem.JPG" />
    <br><br><br>
            For any questions, comments or problems with the website please email: <u>o.colizoli@uva.nl</u><br>    
</big>			
</div>
    
	

<!-- INSERT NBACK ID  -->
    <div dojoType="dijit.layout.ContentPane" id="insert_id" class="format">
    <img src="../../images/exp1/uva.jpg" />
    <h1><font color="#990033">
    Visual Memory Test</font></h1>
    <big><b>
    <h3>Insert the Nback ID you wish to take: </h3>
	
    <div id="insert_div">
    <form name="nback_form" id="nback_form" >
 <br>
            <input type="integer" name="insert_here" id="insert_here" value=" "  /><br><br>
			<input type="radio" name="language_button" value=0 />English<br>
			<input type="radio" name="language_button" value=1 />Nederlands<br>
			
            <br>
    </div>
    </b>
    </form>
    
	<button id="insert_button" dojoType="dijit.form.Button">Begin!</button>
               <br><br><br>
			   
	<div id="show_all_scores"></div><br><br>
			   
<a href="http://webserver3.fmg.uva.nl/olympia/database/database" target="_blank"> View All Nbacks </a>
    <br><br>
            
</big>			
</div>
 

<!-- General instructions 1  -->
    <div dojoType="dijit.layout.ContentPane" id="welcome_pane1" class="format">
    <img src="../../images/exp1/uva.jpg"/>
    <h1><b><font color="#990033">
    Visual Memory Test</font></b></h1>

    <br>
	You are here to take or retake a certain visual memory test.
	
    <br><br>
    Please do <u>not</u> do anything else while you are taking the tests, <br>
        such as surfing the Internet.
    <br><br>
    Please do <u>not</u> write anything down in order to remember it.    
    <br><br>

	<button id="next1" dojoType="dijit.form.Button"><h3>Next</h3>
            <script type="dojo/method" event="onClick" args="evt">
                dijit.byId('stackContainer').forward();
            </script>
        </button>
    </div>

<!-- General instructions 2  -->
    <div dojoType="dijit.layout.ContentPane" id="welcome_pane2" class="format">
    <img src="../../images/exp1/uva.jpg"/>
    <h1><b><font color="#990033">
    Visual Memory Test</font></b></h1>
    <br>
     Before each test, you will receive specific instructions.
    <br>
    The tests are very similar, but the instructions change slightly each time. <br><br>
        <font color="red"><i>Please be sure to read the instructions carefully before starting the test</i></font>.
    <br><br>
	Points are deducted for incorrect responses,<br>
        therefore, it is not beneficial to <u>always</u> answer.
    <br><br>    
    Before the test, you will be given a short practice session.
    <br><br>
    You will be given your score at the end.
    <br><br>

        <button id="back1" dojoType="dijit.form.Button"><h3>Back</h3>
            <script type="dojo/method" event="onClick" args="evt">
                dijit.byId('stackContainer').back();
            </script>
        </button>

	<button id="next2" dojoType="dijit.form.Button"><h3>Next</h3>
            <script type="dojo/method" event="onClick" args="evt">
                dijit.byId('stackContainer').forward();
            </script>
        </button>
    </div>


<!-- Instructions for Test 1 -->
     <div dojoType="dijit.layout.ContentPane" id="instructions1a" class="format">
	<h1 style="text-align: center"><u>#1 Instructions:</u></h1>
	<h3 style="text-align: center">
            <div id="category1a"> </div><br><br>
            <div id="specific_instructions1a"></div>
        </h3>
        <div id="pic1a"></div>
	<br><br>
        <button id="practice1" dojoType="dijit.form.Button"><h3>Practice</h3>
            <script type="dojo/method" event="onClick" args="evt">
		dijit.byId('stackContainer').forward();                
		start_experiment(block_tracker);
            </script>
	</button>
    </div>

<!-- Practice Test 1 -->
    <div dojoType="dijit.layout.ContentPane" id="practice_test1" class="format">
        <br><br><br><br>PRACTICE
	<div id="practice_box1"><img id="practice_current1" src="" alt="" /></div><br><br>
	<div id="practice_match_div1" class="format">
             <form>
                <button id="practice_match1" dojoType="dijit.form.Button">Match</button>
             </form>
            <br><br>
            Feedback:<br><font color="15CD1E">Correct</font><br><font color="red">Incorrect</font>
        </div>
    </div>

<!-- After Practice Test 1 -->
     <div dojoType="dijit.layout.ContentPane" id="instructions1b" class="format">
	<h1 style="text-align: center"><u>#1 Instructions:</u></h1>
	<h3 style="text-align: center">
            <div id="category1b"> </div><br><br>
            <div id="specific_instructions1b"></div>
        </h3>
        <div id="pic1b"></div>
	<br><br>
        <button id="start_test1" dojoType="dijit.form.Button"><h3>Start Test</h3>
            <script type="dojo/method" event="onClick" args="evt">
		dijit.byId('stackContainer').forward();
		start_experiment(block_tracker);
            </script>
	</button>
    </div>


<!-- Test 1 -->
    <div dojoType="dijit.layout.ContentPane" id="test1" class="format">
	<div id="box1"><img id="current1" src="" alt="" /></div><br><br>
	<div id="match_div1" class="format">
             <form>
                <button id="match1" dojoType="dijit.form.Button">Match</button>
             </form>
        </div>
    </div>


<!-- After & Scores -->
    <div dojoType="dijit.layout.ContentPane" id="scores" class="format">
	
        <h1><b><font color="#990033">
        UvA Visual Memory Tests</font></b></h1>

        <h3>Here is your score for the visual memory test. </h3>
		<br>
        Scores are computed with the formula: <br>
        <i>Correct / (Correct + Misses + Incorrect)</i><br><br>
		All scores are out of 100 points.
        <br><br>
		___________________________________________<br><br>

        <div id="score_pic1"></div>
		
        <br><br>
		<a href="http://webserver3.fmg.uva.nl/olympia/exp1/exp1/subject_scores" target="_blank">All scores</a>
		<br><br>
        <button id="new_block2" dojoType="dijit.form.Button"><h3>New Test</h3>
            <script type="dojo/method" event="onClick" args="evt">
		window.location.reload();
            </script>
	</button>
	<br><br>

     </div>

	 
</div><!-- END STACK CONTAINER -->
Approved by the <a href="../../images/exp1/ethics.php" target="_blank">ethics committee</a> of the Department of Psychology at the University of Amsterdam.
	
<div id="element"></div>

    </center>

</body>
</html>


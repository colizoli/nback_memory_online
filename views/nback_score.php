<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Nback Scores</title>
<link rel="stylesheet" type="text/css" href="http://o.aolcdn.com/dojo/1.4/dojo/resources/dojo.css" />
<link rel="stylesheet" type="text/css" href="http://o.aolcdn.com/dojo/1.4/dijit/themes/tundra/tundra.css" />

<script djConfig="parseOnLoad:true" type="text/javascript" src="http://o.aolcdn.com/dojo/1.4/dojo/dojo.xd.js"></script>

<script type="text/javascript">

var hits = new Array();

generate = function(){

for (j=0; j<34; j++)
	{hits[j] = j;}

}

dojo.addOnLoad(function()				
{
	generate();
	scores();
});



scores = function()
{
	for (m=0; m<34; m++)
	for (n=0; n<67; n++) // The max incorrect is number of trials (100) - number of targets (33) = 67
	{
		var misses = 33 - hits[m];
		var incorrect = n;
		var denominator = hits[m] + incorrect + misses;
		var final_score = hits[m] / denominator;
			final_score = final_score*100;
			//final_score = Math.floor(final_score); // Rounds down
			
		var newdiv = document.createElement('div');
		newdiv.innerHTML = 'hits = '+hits[m]+', misses = '+misses+', incorrect = '+incorrect+', score = '+final_score;
		
		document.body.appendChild(newdiv);
		
		newdiv.style.top = "20 px";

	}
}

</script>
</head>

<body class ="tundra">
<h2> Nback Scores </h2>
Score = hits / hits + misses + incorrect <br><br>
Below: all possible scores with 100 trials, 33 targets. <br>
It is impossible to get more than 67 incorrect trials.<br><br>
</body>
</html>





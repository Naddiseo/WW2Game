
function sel()
{
	if(!document.forms[selmess])
		return;
	var objCheckBoxes = document.forms[selmess].elements[delarr];

	if(!objCheckBoxes)
		return;
	var countCheckBoxes = objCheckBoxes.length;
	if(!countCheckBoxes)
		objCheckBoxes.checked = false;
	else
		// set the check value for all check boxes
		for(var i = 0; i < countCheckBoxes; i++)
			objCheckBoxes[i].checked = ! objCheckBoxes[i].checked ;
}

        /* TIMER STUFF */

var min='';
var sec='';
var resetmin=20;
var resetsec=0;
function StartTimer(){

	setTimeout("UpdateTimer()", 1000);

	
	if (min < 0) {
		min=resetmin;
		sec=resetsec;
	}

	if (sec < 10) {
		temp="0";
	}
	else {
		temp="";
	}

	document.getElementById("idTimer").innerHTML="<span id=\"idMin\" style=\"font-weight: normal; font-size: 14pt\">"+min+"</span>:<span id=\"idSec\" style=\"font-weight: bold; font-size: 12pt\">"+temp+sec+"</span>";

}

function UpdateTimer(){

	setTimeout("UpdateTimer()", 1000);

	sec--;
	if (min <= 0 && sec <= 0)
	{
		min=resetmin;
		sec=resetsec;
	}

	if (sec <= 0)
	{
		sec = 59;
		min--;
	}

	document.getElementById("idMin").innerHTML=min;
	var temp;

	if (sec < 10 && sec > 0) {
		temp="0";
	}
	else {
		temp="";
	}

	temp = temp+sec;
	document.getElementById("idSec").innerHTML=temp;

}
function gm(i){
	if(window.addFriend){
		var ajax=new Ajax.Request('/gm.php',
		{
			method:'get',
			parameters:{
				'i':i
			}
		});
	}
}
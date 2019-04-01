//async.js

var xhttp;
var out_as_array = 0;
var out_as_musicxml = 0;

function GetFormVal()
{
  var ParamForm = document.getElementById("param");
  var c = ParamForm.children;
  var i;
  var tmp="";
  var isFst=1;
  for (i = 0; i < c.length; i++) 
  {
    //if((CurrId = c[i].id)!==undefined)
    //if(typeof c[i].id!==undefined)
	if((c[i].type=="text")||(c[i].type=="checkbox")||(c[i].type=="hidden"))
	{
	  if(isFst==1)
	  {
	    isFst=0;
	  }
	  else
	  {
	    tmp += "&";
	  }
	  tmp += c[i].id + "=";
	  if((c[i].type=="text")||(c[i].type=="hidden"))
	  {
	    tmp +=  c[i].value
	  }
	  else if(c[i].type=="checkbox")
	  {
	    tmp += (+ c[i].checked);
		if(c[i].id=="out_as_array")
		{
		  out_as_array = (+ c[i].checked);
		}
		if(c[i].id=="out_as_musicxml")
		{
		  out_as_musicxml = (+ c[i].checked);
		}
	  }	  
	}
    
  }
  //alert(tmp);
  //alert(out_as_array + " ==== " + out_as_musicxml);
  //document.getElementById("param").submit();
  return tmp;
}

function loadDoc() {
  var status_div = document.getElementById("status");
  status_div.innerHTML = "Wait for user activity ...";
  if (window.XMLHttpRequest) {
    xhttp = new XMLHttpRequest();
    } else {
    // code for IE6, IE5
    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
	  var sOut = "";
	  if((out_as_array == 0) && (out_as_musicxml==1))
	  {
	    //console.log("xhttp.responseXML = " + xhttp.responseXML); doesnt work!!!		
	    //sOut = xhttp.responseXML;
		
	    sOut = xhttp.responseText;
		//console.log("xhttp.responseText = " + xhttp.responseText);
		
		var iConvRes;
		if(iConvRes = ConvMusicxml2Vexflow(sOut))
		{
		  if(iConvRes==1)
		  {
		    console.log("ConvMusicxml2Vexflow Error: no input XML");
		  }
		  else
		  {
		    console.log("ConvMusicxml2Vexflow Error: undefined");
		  }
		}
	  }
	  else
	  {
	     sOut = xhttp.responseText;
	  }
	  //alert(xhttp.responseText);
      document.getElementById("demo").innerHTML = sOut;
	  status_div.innerHTML = "request finished and response is ready";
    }
	/*
	readyState 	Holds the status of the XMLHttpRequest. Changes from 0 to 4:
	0: request not initialized
	1: server connection established
	2: request received
	3: processing request
	4: request finished and response is ready
	*/
	else if(xhttp.status == 404)
	{
	  status_div.innerHTML = "Page not found";
	}
	else if(xhttp.readyState == 0)
	{
	  status_div.innerHTML = "request not initialized";
	}
	//...
  };
  //xhttp.open("GET", "ajax_info.txt", true);
  xhttp.open("POST", document.getElementById("param").action, true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  var QueryStr = GetFormVal();
  xhttp.send(QueryStr);
  //xhttp.send();
}
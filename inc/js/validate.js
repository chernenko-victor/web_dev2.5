function validateField(oEvent) 
{
  oEvent = oEvent || window.event;
  var txtField = oEvent.target || oEvent.srcElement;
  //more code to come
  if (window.XMLHttpRequest) {
    xhttp = new XMLHttpRequest();
    } else {
    // code for IE6, IE5
    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
	  var sOut = "";
	  sOut = xhttp.responseText;
	  
	  //alert(xhttp.responseText);
	  console.log(sOut);
      //document.getElementById("demo").innerHTML = sOut;
	  //status_div.innerHTML = "request finished and response is ready";
    }
	/*
	readyState 	Holds the status of the XMLHttpRequest. Changes from 0 to 4:
	0: request not initialized
	1: server connection established
	2: request received
	3: processing request
	4: request finished and response is ready
	*/
	/*
	else if(xhttp.status == 404)
	{
	  status_div.innerHTML = "Page not found";
	}
	else if(xhttp.readyState == 0)
	{
	  status_div.innerHTML = "request not initialized";
	}
	//...
	*/
  };
  xhttp.open("POST", "gen_form_validate.php", true);
  //xhttp.open("POST", document.getElementById("param").action, true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  var QueryStr = txtField.name + "=" + encodeURIComponent(txtField.value);
  //console.log(QueryStr);
  xhttp.send(QueryStr);
}

window.onload = function () 
{
  num_of_part.onchange = validateField;
}


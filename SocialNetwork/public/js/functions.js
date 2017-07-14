
function loadXMLDoc()
{
	if(window.XMLHttpRequest) {
		{
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		}
		xmlhttp.onreadystatechange = function()
		{
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.getElementById("ajax_chat").innerHTML = xmlhttp.responseText;
			}
		}
		string = document.getElementById("ajax_chat").innerHTML;
		xmlhttp.open("GET", "ajax_loader.php?id="+string+"&blabla="+Math.random(),true);
		xmlhttp.send();
	}
}


function showHint(str) {
  var xhttp;
  if (str.length == 0) { 
    document.getElementById("txtHint").innerHTML = "";
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("txtHint").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "gethint.php?q="+str, true);
  xhttp.send();   
}


// Noch nicht fertig
function showMoreComments(id)
{
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if(this.readyState == 4 && this.status == 200) {
			document.getElementById(id).innerHTML = this.responseText;
		}
	};
	xhttp.open("GET", "", true);
	xhttp.send();
}


function showLikes(id)
{
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if(this.readyState == 4 && this.status == 200) {
			document.getElementById("showLikes"+ id).innerHTML = this.responseText;
		}
	};

	xhttp.open("GET", "js/ajax/getLikes.php?id="+ id, true);
	xhttp.send();
}

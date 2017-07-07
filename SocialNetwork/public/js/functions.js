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
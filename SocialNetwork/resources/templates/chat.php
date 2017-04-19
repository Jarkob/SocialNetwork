<!-- ACHTUNG!!! Diese Seite darf auf keinen Fall aufgerufen werden, sie ist voller Scheiß Code-->

<div class="chat">

<script type="text/javascript">
var latestID = 0; //latestID als globale Variable; wichtig!

function createXMLHttpReqObj() { //erzeugt die XMLHttpRequest Objekte

// für Mozilla etc.
if(window.XMLHttpRequest) {
try { //Fehler abfangen
req = new XMLHttpRequest(); 
} catch(e) {
req = false;
}
// für den InternetExplorer
} else if(window.ActiveXObject) {
try {
req = new ActiveXObject("Msxml2.XMLHTTP");
} catch(e) {
try {
req = new ActiveXObject("Microsoft.XMLHTTP");
} catch(e) {
req = false;
}
}
}
return req;
}
// Initialisierung der beiden Objekte
var httpGetLatestEntries= createXMLHttpReqObj(); 
var httpCreateNewEntry = createXMLHttpReqObj();

//Funktion, mit der Anfragen nach neuen Einträgen gesendet wird
function getLatestEntries() {
//Anfrage senden, wenn Status der letzten Anfrage "completed" ist, bzw. "nicht initialisiert" (d.h. erster Aufruf)
if (httpGetLatestEntries.readyState == 4 || httpGetLatestEntries.readyState == 0) { 
//Übergabe der latestID
httpGetLatestEntries.open("GET","
http://localhost/ajax/ajaxchat.php?
action=getLatestEntries&latestID="+latestID, true);
httpGetLatestEntries.onreadystatechange = handleHttpGetLatestEntries; 
httpGetLatestEntries.send(null);
}
}

//Behandelt die Serverantwort
function handleHttpGetLatestEntries() {

//wenn Anfrage den Status "completed" hat
if (httpGetLatestEntries.readyState == 4) {
//ermitteln der Antwort
response = httpGetLatestEntries.responseXML.documentElement; 
//ermitteln der Messages; Überführung in ein Array
messages = response.getElementsByTagName('message');

//wenn es mindestens eine neue Nachricht hat, dann wird diese angezeigt! 
if(messages.length > 0) {

for (var i=messages.length-1; i>=0; i-=1) {
//Darstellung im Browser mit dem DOM
showEntry= document.getElementById("showEntries");
neuSpan = document.createElement('span');
neuSpan.setAttribute('class','entry'); //CSS Klasse
neuSmall = document.createElement('small');
neuNameDate = document.createTextNode(messages[i].getElementsByTagName
('date')[0].firstChild.nodeValue + ': ' + messages[i].getElementsByTagName('name')[0].firstChild.nodeValue +': ');
neuSmall.appendChild(neuNameDate);
neuSpan.appendChild(neuSmall);
neuSpan.appendChild(document.createElement('br')); 
neuNachricht = document.createTextNode(messages[i].getElementsByTagName
('nachricht')[0].firstChild.nodeValue);
neuSpan.appendChild(neuNachricht);
neuSpan.appendChild(document.createElement('br'));
showEntry.insertBefore(neuSpan, showEntry.firstChild); 
}

//Festlegung der neuen latestID; Zugriff auf die Werte über das DOM
latestID = messages[0].getElementsByTagName('id')[0].firstChild.nodeValue; 
}
//erneuter periodischer Aufruf (eine Art Rekursion)
setTimeout('getLatestEntries();',3000); //Erneute Anfrage in drei Sekunden
}
}

//neue Nachricht auf dem Server erzeugen; die Übergabe der Werte erfolgt über das HTML Formular
function createNewEntry(name, nachricht) {

//Anfrage senden, wenn Status der letzten Anfrage "completed" ist, bzw. "nicht initialisiert" (d.h. erster Aufruf) 
if (httpCreateNewEntry.readyState == 4 || httpCreateNewEntry.readyState == 0) {
//URL für HTTP Anfrage; muss angepasst werden
url = 'http://localhost/ajax/ajaxchat.php?action=createNewEntry&name=' + name + '&nachricht=' + nachricht; 
httpCreateNewEntry.open("GET", url, true);
httpCreateNewEntry.onreadystatechange = handleHttpCreateNewEntry;
httpCreateNewEntry.send();
}
} 

//behandelt die Antwort des Servers
function handleHttpCreateNewEntry() {
if (httpCreateNewEntry.readyState == 4) {
//nachdem eine neue Nachricht erfolgreich erzeugt wurde, wird diese angezeigt
getLatestEntries(); 
}
}
</script>

<?php

$loggedIn = getLoginStatus(session_id());

if($loggedIn) {
	$username = getUserName(session_id());

	$pdo = new PDO('mysql:host=localhost;dbname=socialnetwork', 'root', 'root');



	//falls action=createNewEntry, Erzeugung eines neuen Eintrages
	if ($_GET['action'] == "createNewEntry") { 
	createNewEntry($_GET['sender'], $_GET['empfaenger'], $_GET['content']);
	}
	//falls action=getLatestEntries, Ausgabe der neuesten Einträge
	elseif ($_GET['action'] == "getLatestEntries") {
	getLatestEntries($_GET['id']);
	}

} else {
	?>
	<p>Bitte loggen Sie sich zuerst ein.</p>
	<p><a href="?page=login">Zum Login</a></p>
	<?php
}

?>

</div>
var restservice = "http://192.168.242.100/restservice/";

function init() {
//	alert("ok");
}

function do_query() {
	var a = new Object();
	a.query = $("#query_string").val();
	json = JSON.stringify(a);
// 	alert(json);
	$.post(restservice+"query.php", json, filldiv);
}

function filldiv(json) {
// 	alert(json);
	a = JSON.parse(json);
	$("#queryresult").html("");
	for (i=0;i<a.length;i++) {
		$("#queryresult").append(a[i]["artist"]+" - "+a[i]["title"]+"<br />");
	}
}

function html_entity_decode(str) {
	var  tareadummy=document.createElement('textarea');
	tareadummy.innerHTML = str; return tareadummy.value;
	tareadummy.parentNode.removeChild(tarea);
}
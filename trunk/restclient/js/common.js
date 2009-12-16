function init() {
//	alert("ok");
}

function do_query() {
	var a = new Object();
	a.query = $("#query_string").val();
	json = JSON.stringify(a);
	// alert(json);
	$.post("http://192.168.178.40/restservice/query.php", json,	filldiv);
}

function filldiv(json) {
	a = JSON.parse(json);
	for (i=0;i<a.length;i++) {
		$("#queryresult").append(a[i]["artist"]+" - "+a[i]["title"]+"<br />");
	}
}
<?php
/*********************************************
/* Search Script for GiantDisc Server
   written by Andreas Baierl in 2009

   Project Homepage: http://sourceforge.net/projects/gdrestfulwebint/
   Help: http://sourceforge.net/apps/mediawiki/gdrestfulwebint/index.php

*/

require_once "includes/inc.includes.php";   // Include necessary files

build_header("Search");
?>
<script>
	$(document).ready(function(){
		$('input[name="artist"]').keyup(form_Search_delayed);
		$('input[name="title"]').keyup(form_Search_delayed);
//		$('input[name="album"]').keyup(form_Search_delayed);
		$('#where').hide();
		$('#submitbutton').bind('click',fsubmit);
		$('<div id="loading">Loading ...</div>')
			.insertBefore('#queryresult')
			.ajaxStart(function() {
				$(this).show();
			}).ajaxStop(function() {
				$(this).hide();
			});
		});
	function build_wherestring() {
		var wherestring = " WHERE ";
		$('input[name="artist"]') ? wherestring += "artist LIKE '"+$('input[name="artist"]').attr('value')+"%'" : wherestring +="";
		$('input[name="title"]') ? wherestring += " AND title LIKE '"+$('input[name="title"]').attr('value')+"%'" : wherestring +="";
//		$('input[name="album"]') ? wherestring += " AND album LIKE '"+$('input[name="album"]').attr('value')+"%'" : wherestring +="";
		return wherestring;
	}
	function fsubmit(){
		var selectstring = "SELECT * FROM tracks";
		var wherestring = (build_wherestring());
		$('#where').html(selectstring+wherestring);
		$('#where').show();
		$.post("includes/inc.doquery.php", '{ "query" : "'+selectstring+wherestring+'" }' , function (json) { $('#queryresult').html("").html(json); });
	}	
	function form_Search(){
		$.getJSON('includes/inc.search.php?search='+name+'&value='+txt,form_Search_show);
	}
	function form_Search_delayed(){
		name = $(this).attr('name');
		txt = $(this).attr('value');
		pos=$(this).position();
		el=$(this);
		if(window.SearchTimeout)clearTimeout(window.SearchTimeout);
		window.SearchTimeout=setTimeout(form_Search,500);
	}	
	function form_Search_show(res){
		if($('#search_list'))$('#search_list').remove();
		if(!res.results || !res.results.length)return;
		var left = pos.left;
		var top = pos.top+5+el.height();
		var minwidth = el.width()+2-4;
		var style='position:absolute;left:'+left+'px;top:'+top+'px;min-width:'+minwidth+'px;border:1px solid #000;background:#eee;padding:2px;';
		var html='';
		for(idx in res.results){
			var result=res.results[idx];
			html+='<a href="javascript:;" onclick="$(\'input[name='+name+']\').attr(\'value\',\''+result+'\')">'+result+'</a><br />';
		}
		$('<div id="search_list" style="'+style+'">'+html+'</div>').appendTo(el[0].parentNode);
		
		$(document.body).click(function(){
			$('#search_list').remove();
		});
	}
</script>
<?php build_body("Search"); ?>
		<div class="results">
		<div class="searchform">
		<form id="search_form">
		<table>
				<tr><th>Artist</th><td><input name="artist" /></td></tr>
				<tr><th>Title</th><td><input name="title" /></td></tr>
<!--				<tr><th>Album</th><td><input name="album" /></td></tr> -->
				<tr><th colspan="2" class="but"><input type="reset" value="Clear"><input type="button" id="submitbutton" value="Search"/></th></tr>
		</table>
		</form>
		</div>
		<div class="searchresults border-left">
		<div id="where" class="whereclause"></div>
		<div id="queryresult" class="searchresults2"></div>
		</div>
		</div>
<?php build_footer ("Search"); ?>
<?php
/*********************************************
/* Search Script for GiantDisc Server
   written by Andreas Baierl in 2009

   Project Homepage: http://sourceforge.net/projects/gdrestfulwebint/
   Help: http://sourceforge.net/apps/mediawiki/gdrestfulwebint/index.php

*/

require_once "includes/inc.includes.php";   // Include necessary files
$limit = null;
build_header("Search");
?>
<script>
	$(document).ready(function(){
		$autocomplete = $('<ul id="autocomplete" class="autocomplete"></ul>')
		.hide()
		.insertAfter('#search_form');
		
		var selectedItem = null;
		var setSelectedItem = function(item) {
			selectedItem = item;
			if (selectedItem === null) {
				$autocomplete.hide();
				return;
			}
			if (selectedItem < 0) {
				selectedItem = 0;
			}
			if (selectedItem >= $autocomplete.find('li').length) {
				selectedItem = $autocomplete.find('li').length - 1;
			}
			$autocomplete.find('li').removeClass('selected')
				.eq(selectedItem).addClass('selected');
			$autocomplete.show();
		};

		var populateSearchField = function () {
			$('input[name='+name+']').val($autocomplete
			.find('li').eq(selectedItem).text());
			setSelectedItem(null);
		};
		
		$(':text')
		.attr('autocomplete', 'off')
		.keyup(function (event) {
			if (event.keyCode > 40 || event.keyCode == 8) {
				name = $(this).attr('name');
				var txt = $(this).attr('value');
				var pos=$(this).position();
				var el=$(this);
				if(window.SearchTimeout)clearTimeout(window.SearchTimeout);
				window.SearchTimeout=setTimeout(function () {
					$.getJSON('includes/inc.search.php?search='+name+'&value='+txt,function (res) { 
						var left = pos.left;
						var top = pos.top-7+el.height();
						var minwidth = el.width()+2;
						var style = {
							'left' : left+'px',
							'top' : top+'px',
							'min-width' : minwidth+'px',
							'white-space' : 'nowrap'
						}
						if (res.results.length) {
							$autocomplete.empty();
							$.each(res.results, function (index, term) {
								$('<li></li>').text(term)
									.appendTo($autocomplete)
									.mouseover(function () {
										setSelectedItem(index);
									})
									.click(populateSearchField);
							});
							$autocomplete.css(style);
							setSelectedItem(0);
						}
						else {
							setSelectedItem(null);
						}
					});
				},500);
			}
			else if (event.keyCode == 38 && selectedItem !== null) {
				setSelectedItem(selectedItem - 1);
				event.preventDefault();
			}
			else if (event.keyCode == 40 && selectedItem !== null) {
				setSelectedItem(selectedItem + 1);
				event.preventDefault();
			}
			else if (event.keyCode == 27 && selectedItem !== null) {
				setSelectedItem(null);
			}
		}).keypress(function(event) {
			if (event.keyCode == 13 && selectedItem !== null) {
				populateSearchField();
				event.preventDefault();
			}
		}).blur(function(event) {
			setTimeout(function() {
				setSelectedItem(null);
			}, 250);
		});

		$('#where').hide();
		$('#submitbutton').bind('click',fsubmit);
		
		$('<div id="loading"><img src="images/ajax-loader.gif" />Loading ...</div>')
		.insertAfter('#search_form')
		.ajaxStart(function() {
			$(this).show();
		}).ajaxStop(function() {
			$(this).hide();
		});
		
	});

	function build_wherestring() {
		var wherestring = " WHERE ";
		$('input[name="artist"]') ? wherestring += "artist LIKE \\\"%"+$('input[name="artist"]').attr('value')+"%\\\"" : wherestring +="";
		$('input[name="title"]') ? wherestring += " AND title LIKE \\\"%"+$('input[name="title"]').attr('value')+"%\\\"" : wherestring +="";
		return wherestring;
	}
	function fsubmit(){
		var limit = <?php if (isset($limit)) { echo "' LIMIT $limit'"; } else { echo "' '"; } ?>;
		var selectstring = "SELECT * FROM tracks";
		var wherestring = (build_wherestring());
		var querystring = selectstring+wherestring+" ORDER BY artist"+limit;
		$('#where').html(querystring).show();
		$.post("includes/inc.doquery.php", '{ "query" : "'+querystring+'" }' , function (json) { 
			$('#queryresult').html("").html(json);
			$('table.tracklist tbody tr:odd').addClass('odd');
			$('table.tracklist tbody tr:even').addClass('even');
		});
		var querystring = selectstring+wherestring+" ORDER BY artist";
		$('#where').html(querystring).show();
		$.post("includes/inc.doquery.php", '{ "query" : "'+querystring+'" }' , function (json) { $('#queryresult').html("").html(json); });
	}

</script>

<?php build_body("Search"); ?>
		<div class="results">
		<div class="searchform">
		<form id="search_form">
		<table>
				<tr><th>Artist</th><td><input id="artist" name="artist" /></td></tr>
				<tr><th>Title</th><td><input id="title" name="title" /></td></tr>
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
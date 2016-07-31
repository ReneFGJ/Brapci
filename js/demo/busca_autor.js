/*jslint  browser: true, white: true, plusplus: true */
/*global $, countries */

$(function() {
	'use strict';

	var autoresArray = $.map(availableTags, function(value, key) {
		return {
			value : value,
			data : key
		};
	});
	var keywordsArray = $.map(keysTags, function(value, key) {
		return {
			value : value,
			data : key
		};
	});

	// Initialize autocomplete with custom appendTo:
	$('#dd4b').autocomplete({
		lookup : autoresArray,
		minLength: 5
	});
	$('#dd4d').autocomplete({
		lookup : keywordsArray,
		minLength: 5
	});
});

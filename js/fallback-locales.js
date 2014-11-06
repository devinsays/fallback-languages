/**
 * Custom script for tabs
 */

jQuery(document).ready( function($) {

	var nav = $('.nav-tab');
	var panel = $('.panel');
	nav.on( 'click', function(e) {
		e.preventDefault();
		nav.removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
		panel.hide();
		var selected = $(this).attr('href');
		$(selected).fadeIn();
	});

});
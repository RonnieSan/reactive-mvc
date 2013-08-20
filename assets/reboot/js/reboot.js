$(window).load(function() {

	// ------------------------------
	// SNAP RESPONSIVENESS

	var snapTablet = 960; // Anything lower than 960 will snap to tablet width
	var snapMobile = 700; // Anything lower than 700 will snap to mobile width

	// Snap the viewport to specific widths
	function setViewport() {
		// Get the screen width based on the orientation of the device
		var landscape   = (Math.abs(window.orientation) == 90 || window.orientation == 270) || false;
		var deviceRatio = window.devicePixelRatio;
		// Use the window if it's a desktop
		if (window.orientation === undefined) {
			var screenWidth = $(window).width() * deviceRatio;
		}
		// Use the screen if it's a tablet or phone
		else {
			var screenWidth = window.screen.width * deviceRatio;
			if (landscape) {
				screenWidth = window.screen.height * deviceRatio;
			}
		}

		// Change the snapWidth based on device width
		snapWidth = screenWidth;
		if (screenWidth < snapMobile) {
			snapWidth = 320;
		} else if (screenWidth < snapTablet) {
			snapWidth = 760;
		}

		// Set the viewport width
		$('meta#viewport').attr('content', 'width=' + snapWidth);
	}

	// Bind the viewport changes to load and orientation change
	$(window).bind('load orientationchange', setViewport);

	// END SNAP RESPONSIVENESS
	// ------------------------------

});

$(function() {
	
	// ------------------------------
	// LAST-CHILD COMPATIBILITY

	// Add a last-child class to all last-child elements for compatibility in IE8-
	if (!jQuery.support.leadingWhitespace) {
		$('li:last-child, tr:last-child, th:last-child, td:last-child, .column:last-child, .row:last-child').addClass('last-child');
	}

	// END LAST-CHILD COMPATIBILITY
	// ------------------------------


	// ------------------------------
	// FORM STYLING

	// Make all form elements styleable
	if (jQuery().reboot_forms) {
		$().reboot_forms();
	}

	// END FORM STYLING
	// ------------------------------

});
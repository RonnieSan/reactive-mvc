// -------------------------
// MODAL POPUPS
// -------------------------

(function($){

	// Get the modal layer
	window['reboot_modal'] = { 'index' : 0 };

	var methods = {
		
		init : function(options) {

			var settings = $.extend({

				// Default Options
				closable       : true,
				contentType    : 'inline',
				contentObject  : false,
				easing         : ['easeOutExpo', 'easeInBack'],
				fadeInSpeed    : 1000,
				formData       : {},
				height         : false,
				left           : false,
				modalID        : false,
				preload        : false,
				resizeSpeed    : 250,
				scrollable     : false,
				top            : 80,
				width          : 760,
				url            : null,

				// Available Events
				onOpen         : function() {},
				onReady        : function() {},
				onClose        : function() {},
				onClosed       : function() {}

			}, options);

			return this.each(function() {

				var $this      = $(this),
					modalIndex = window['reboot_modal'].index++;

				// Create the data object
				if ($this.data('reboot_modal') === undefined) {
					$this.data('reboot_modal', {
						target   : $this,
						index    : modalIndex,
						settings : settings
					});
				}
				var data = $this.data('reboot_modal');

				// Set the id for the modal
				if (data.settings.modalID) {
					data.modalID = data.settings.modalID;
				} else {
					data.modalID = 'modal-' + modalIndex;
				}

				// Create the dimming overlay
				data.overlay = $('<div id="' + data.modalID + '-overlay" class="modal-overlay" />').appendTo('body');

				// Create a scrollable container for the modal
				data.modalContainer = $('<div id="' + data.modalID + '-container" class="modal-container" />').appendTo('body');

				// Close the modal when you click the overlay
				if (data.settings.closable) {
					data.modalContainer.bind('click', function() {
						$this.reboot_modal('close');
					});
				}

				// Set whether the modal has scrollable content
				var overflow    = (data.settings.scrollable) ? 'auto' : 'hidden';
				var modalHeight = (data.settings.height) ? data.settings.height : 'auto';

				// Create the modal DOM element and add it to the body
				data.modal = $('<div id="' + data.modalID + '" class="modal" />')
					.css({
						'display'   : 'block',
						'height'    : modalHeight,
						'left'      : '-9999px',
						'margin'    : (data.settings.top + $(window).scrollTop() - 30) + 'px auto 30px',
						'max-width' : data.settings.width,
						'opacity'   : 0,
						'overflow'  : overflow,
						'width'     : '98%'
					})
					.appendTo(data.modalContainer);

				// Add the close button to the modal
				if (data.settings.closable) {
					$('<div class="close close-btn" />')
					.css({
						'cursor' : 'pointer'
					})
					.prependTo(data.modal);
				}

				// Prevent a click in the modal to trigger a click on the container
				data.modal.on('click', function(e) {
					e.stopPropagation();
				});

				// Load the content into the modal
				switch (data.settings.contentType) {

					// Call the content from an inline object
					case 'inline':

						// Get the parent element
						data.parentObj = $this.parent();

						// Append the content to the modal
						data.modalContent = $this;

						data.modal.append(data.modalContent);

						// Clicking anything in the modal with the .close class will close the modal
						if (data.settings.closable) {
							data.modal.find('.close')
							.bind('click', function() {
								$this.reboot_modal('close');
							});
						}

						if (data.settings.preload === false) {
							methods.open.call($this);
						}

						break;

					case 'ajax':

						// Send an AJAX post request to a URL
						$.post(data.settings.url, data.settings.formData, function(response) {
							if (response) {
								data.modalContent = $(response);
							} else {
								data.modalContent = $('<p>Unable to retrieve content...</p>');
							}

							// Append the content to the modal
							data.modal.append(data.modalContent);

							// Clicking anything in the modal with the .close class will close the modal
							data.modal.find('.close')
							.bind('click', function() {
								$this.reboot_modal('close');
							});

							// Open the modal
							if (data.settings.preload === false) {
								methods.open.call($this);
							}
						});

						break;

					case 'iframe':

						// Create the iframe
						var iframe = $('<iframe id="' + data.modalID + '-iframe" name="' + data.modalID + '-content" src="' + data.settings.url + '" width="100%" height="100%" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" />').css('margin-bottom', '-5px');

						// Append the content to the modal
						data.modalContent = iframe;

						data.modal.append(data.modalContent);

						// Clicking anything in the modal with the .close class will close the modal
						if (data.settings.closable) {
							data.modal.find('.close')
							.bind('click', function() {
								$this.reboot_modal('close');
							});
						}

						$('#' + data.modalID + '-iframe').load(function() {

							// Set a reference to the parent modal in the iframe
							this.contentWindow.parentModal = $this;
							
							// Clicking anything in the iframe with the .close class will close the modal
							if (data.settings.closable) {
								$('#' + data.modalID + '-iframe').contents().find('.close')
								.bind('click', function() {
									$this.reboot_modal('close');
								});
							}

							// Update the height of the iframe
							$(this).height($(this).contents().height());
							$(this).contents().bind('resize', function() {
								iframe.filter(':not(:animated)').animate({
									'height' : iframe.contents().height() + 'px'
								}, data.settings.resizeSpeed);
							});

							// Open the modal
							if (data.settings.preload === false) {
								methods.open.call($this);
							}
						});

						break;

				}
				

			});

		},

		// Open the modal
		open : function(options) {

			var $this = $(this),
				data  = $this.data('reboot_modal');

			// Fire the onOpen callback
			if (data.settings.onOpen() !== false) {

				// Set what layer the modal should be on
				var layers = $('.modal.open').length,
					layer  = layers * 10 + 5000;

				// Make the modal closeable
				if (data.settings.closable) {
					// Close the modal when you hit the escape key
					$(document).bind('keyup', function(e) {
						data.keyupFn = arguments.callee;

						var keycode = e.keyCode || e.which;
						if (keycode == 27) {
							if (data.overlay.is(':visible')) {
								$this.reboot_modal('close');
							}
						}
					});
				}

				// Reset the top margin
				data.modal.css('margin-top', (data.settings.top - 30) + 'px');

				// Set the layers for the overlay and the modal
				data.modalContainer.css('z-index', layer);
				data.overlay.css('z-index', layer - 10);

				// Keep the background from scrolling
				document.body.style.overflow = 'hidden';

				// Set the height of the modal
				if (data.settings.height !== false) {
					// If a specific height was set
					data.modal.height(data.settings.height);
				}

				// Fade in the overlay
				if ($('.modal-overlay:visible').length > 0) {
					$('.modal-overlay:visible').hide();
					data.overlay.show();
					data.overlay.css('z-index', layer - 10);
				} else {
					data.overlay.fadeIn(100);
				}

				// Display the modal container
				data.modalContainer.show();

				// Add the class open to the modal
				data.modal.addClass('open');
				data.overlay.addClass('open');

				// Fade in the modal window
				data.modal.delay(100).animate({
					'opacity'    : 1,
					'margin-top' : '+=30'
				},
				data.settings.fadeInSpeed,
				data.settings.easing[0],
				function() {
					// Fire the onReady callback
					data.settings.onReady();
				});

			}

		},

		// Close the modal
		close : function(callback) {

			// Load the data
			var $this = $(this),
				data  = $(this).data('reboot_modal');

			// Fire the onClose callback
			if (data.settings.onClose() !== false) {

				// Unbind the keyup event
				if ($('.modal-overlay.open').length < 2) {
					$(document).unbind('keyup', data.keyupFn);
				}

				// Trigger the before close event
				data.settings.onClose();

				return this.each(function() {

					// Drop the modal
					data.modal.stop().animate({
						'opacity'    : 0,
						'margin-top' : '+=80'
					},
					data.settings.fadeInSpeed / 2,
					data.settings.easing[1],
					function() {
						data.modal.removeClass('open');
						data.overlay.removeClass('open');
						data.modalContainer.hide();

						// Remove the dimmer
						if ($('.modal-overlay.open').length > 0) {
							$('.modal-overlay.open').last().fadeIn(50);
						}

						data.overlay.fadeOut(250, function() {
							if (!data.settings.preload) data.overlay.remove();
						});

						// Hide or remove the modal
						if (data.settings.preload) {
							data.modal.css('left', '-9999px');
						} else {
							$this.appendTo(data.parentObj);
							data.modal.remove();
							data.modalContainer.remove();
						}

						// Fire the onClosed callback
						data.settings.onClosed();

						// Make the body scrollable again
						if ($('.modal-overlay.open').length === 0) {
							document.body.style.overflow = '';
						}
					});

				});

			}

		},

		// Remove the modal completely
		destroy : function() {

			return this.each(function() {
				var modalID = $(this).data('ICM_Modal').modalID;
				$(this).data('ICM_modal', null);
				$('#' + modalID).remove();
			});

		}

	};

	// Decide which function to call
	$.fn.reboot_modal = function(method) {
		
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call( arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Method ' +  method + ' does not exist for reboot_modal plugin.');
		}
	
	};

})(jQuery);
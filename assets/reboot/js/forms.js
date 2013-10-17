// -------------------------
// FORM MODIFIERS
// -------------------------

// Modify select fields
(function($){

	var methods = {
		
		init : function(options) {

			var settings = $.extend({

				// Default Options
				arrowContent : '<img src="/assets/reboot/img/select-arrow.gif">'

			}, options);

			$('select').reboot_forms('selects', options);
			$(':radio, :checkbox').reboot_forms('checks', options);

		},

		selects : function(options) {

			var settings = $.extend({

				// Default Options
				arrowContent : '<img src="http://checkmate.dev.mini/assets/reboot/img/select-arrow.gif">'

			}, options);

			return this.each(function() {

				var $this = $(this),
					data  = $this.data('reboot_forms'),
					id    = $this.attr('id');

				// If the plugin hasn't been initialized yet
				if (!data) {

					// Create the data object
					$this.data('reboot_forms', {
						settings : settings,
						target   : $this
					});

					data = $this.data('reboot_forms');
					
					data.wrapper = $('<div class="select-wrapper" />')
					.css('min-width', $this.outerWidth() + 20);
					if (id) data.wrapper.attr('id', id + '-wrapper');
					data.wrapper.insertBefore($this);
					data.wrapper.append($this)
					.append('<div class="select-content">' + $this.find(':selected').text() + '</div>')
					.append('<div class="select-arrow">' + settings.arrowContent + '</div>');

					$this
					.on('focus', function() {
						data.wrapper.addClass('focused');
					})
					.on('blur', function() {
						data.wrapper.removeClass('focused');
					})
					.on('change keyup', function() {
						data.wrapper.find('.select-content').text($this.find('option:selected').text());
					});

				}
				
			});

		},

		// Make radio buttons anc checkboxes stylable
		checks : function(options) {

			var settings = $.extend({

				// Default Options
				height : 20,
				width  : 20

			}, options);

			return this.each(function() {

				var $this = $(this),
					data  = $this.data('reboot_forms'),
					id    = $this.attr('id'),
					width = $this.outerWidth() + 20;

				// If the plugin hasn't been initialized yet
				if (!data) {

					// Create the data object
					$this.data('reboot_forms', {
						settings : settings,
						target   : $this
					});

					data = $this.data('reboot_forms');

					var className = 'checkbox-wrapper';
					if ($this.is(':radio')) {
						className = 'radio-wrapper';
					}
					
					data.wrapper = $('<div class="' + className + '" />')
					.width(settings.width).height(settings.height);
					if (id) data.wrapper.attr('id', id + '-wrapper');
					data.wrapper.insertBefore($this)
					.append($this);

					if ($this.is(':checked')) data.wrapper.addClass('checked');

					$this
					.on('focus', function() {
						data.wrapper.addClass('focused');
					})
					.on('blur', function() {
						data.wrapper.removeClass('focused');
					})
					.on('check', function() {
						if ($this.is(':radio')) {
							$('[name=' + $this.attr('name') + ']:radio').closest('.radio-wrapper').removeClass('checked');
						}
						$this.prop('checked', true);
						data.wrapper.addClass('checked');
					})
					.on('uncheck', function() {
						if ($this.is(':checkbox')) {
							$this.prop('checked', false);
							data.wrapper.removeClass('checked');
						}
					})
					.on('click', function() {
						if ($this.is(':radio')) {
							$('[name=' + $this.attr('name') + ']:radio').closest('.radio-wrapper').removeClass('checked');
						}
						if ($this.is(':checked')) {
							data.wrapper.addClass('checked');
						} else {
							data.wrapper.removeClass('checked');
						}
					});

				}
				
			});

		}

	};

	// Decide which function to call
	$.fn.reboot_forms = function(method) {
		
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call( arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Method ' +  method + ' does not exist for reboot_forms plugin.');
		}
	
	};

})(jQuery);
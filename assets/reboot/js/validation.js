/*
 *
 * ICM Validation Plugin
 * Version 2.0
 *
 */

// This luhn formula is used during cc number validation
function luhn(s) {
    var i, n, c, r, t;

    // First, reverse the string and remove any non-numeric characters.    
    r = "";
    for (i = 0; i < s.length; i++) {
        c = parseInt(s.charAt(i), 10);
        if (c >= 0 && c <= 9) r = c + r;
    }
    
    // Check for a bad string.
    if (r.length <= 1) return false;
    
    // Now run through each single digit to create a new string. Even digits
    // are multiplied by two, odd digits are left alone.
    t = "";
    for (i = 0; i < r.length; i++) {
        c = parseInt(r.charAt(i), 10);
        if (i % 2 != 0) c *= 2;
        t = t + c;
    }
    
    // Finally, add up all the single digits in this string.
    n = 0;
    for (i = 0; i < t.length; i++) {
        c = parseInt(t.charAt(i), 10);
        n = n + c;
    }
    
    // If the resulting sum is an even multiple of ten (but not zero), the
    // card number is good.
    if (n != 0 && n % 10 == 0) {
        return true;
    } else {
        return false;
    }
}

// The main script code for the plugin
(function($){

	var rules = {

		// Create a custom rule
		custom : function(functionName, functionArgs) {
			if (typeof(functionName) == 'function') {
				return functionName.apply(this, functionArgs);
			} else {
				return true;
			}
		},

		// Field is not empty or spaces
		required : function() {
			if (this.is(':radio, :checkbox')) {
				if (this.filter(':checked').size() < 1) {
					return false;
				}
			} else {
				var value = $.trim(this.val());
				if (this.val() == null || value == '') {
					return false;
				}
			}
			return true;
		},

		// Field does not equal a certain value
		not : function(notValue) {
			return (this.val() != notValue);
		},

		// The value has at least the minimum text length or number of options checked
		minLength : function(min) {
			if (this.is(':radio, :checkbox')) {
				return (this.filter(':checked').size() >= min);
			}
			return (this.val().length >= min);
		},

		// The value has at most the maximum text length or number checked
		maxLength : function(max) {
			if (this.is(':radio, :checkbox')) {
				return (this.filter(':checked').size() <= max);
			}
			return (this.val().length <= max);
		},

		// The value is a valid email address
		email : function() {
			var value = $.trim(this.val().replace(/\.$/, ''));
			var re    = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/gi;
            return re.test(value);
		},

		// The value is a valid postal code
		postalCode : function() {
			var re = /\b[0-9]{5}(?:-[0-9]{4})?\b/;
            return re.test(this.val());
		},

		// The value matches another field's value
		match : function(matchField) {
			return (this.val() == $(matchField).val());
		},

		// The value is a valid credit card number
		ccNumber : function(ccType) {
			// Setup the validation result
            var result = false;
            // Strip non-digit characters from cc number
            var cardNumber = new String(this.val());
            	cardNumber = cardNumber.replace(/[^0-9]/g, '');
            // Check if it's the test cc number
            if (cardNumber == '4895895895895895') {
                return true;
            }
            // Setup the cc type rules
            var re = { 
                         visa : /^4\d{12}(\d\d\d){0,1}$/,
                         mc   : /^5[12345]\d{14}$/,
                         amex : /^3[47][0-9]{13}$/,
                         disc : /^3[47][0-9]{13}$/
                     };
            // Validate each CC type
            var limit = ccType.length;
            for (var n = 0; n < limit; n++) {
                cardType = ccType[n];
                if (re[cardType].test(cardNumber)) {
                    if (cardType == 'visa' || cardType == 'mc') {
                        if (luhn(cardNumber)) {
                            result = true;
                        }
                    }
                    if (cardType == 'amex') {
                        if (cardNumber.charAt(0) == 3) {
                            result = true;
                        }
                    }
                    if (cardType == 'disc') {
                        if (cardNumber.charAt(0) == 6) {
                            result = true;
                        }
                    }
                }
            }
            return result;
		},

		// The value is a date in the future
        ccExpDate : function(expMonth, expYear) {
            if ($(expMonth).val() == '' || $(expYear).val() == '') {
                return false;
            }

            var expDate = new Date($(expMonth).val() + '/1/' + $(expYear).val());
            var today   = new Date();
                today   = new Date((today.getMonth() + 1) + '/1/' + today.getFullYear());

            if (expDate.getTime() < today.getTime()) {
                return false;
            }

            return true;
        },

        // Field is a valid CVV2 code
        cvv : function() {
            var re = /\d{3,4}/;
            return re.test(this.val());
        }

	}

	var methods = {
		
		// Setup the validation for a form
		init : function(options) {

			var settings = $.extend({

				// Default options
				errorClass      : 'form-error',
				errorTemplate   : '<div class="form-error">{errorMsg}</div>',
				onErrorCallback : false

			}, options);

			return this.each(function() {

				var $this = $(this),
					data  = $this.data('reboot_validation');

				// If the plugin hasn't been initialized yet
				if (!data) {

					// Create the data object
					$this.data('reboot_validation', {
						settings    : settings,
						target      : $this
					});

					data = $this.data('reboot_validation');

					// Add fields for validation if form was passed as element
					if ($this.is('form')) {
						// Find all fields that need validation
						data.fields = $this.find('.validate');

						// Bind the validation events
						data.fields.each(function() {
							$(this).on('blur', methods.validate);
						});
						data.fields.filter(':not(:radio, :checkbox)').each(function() {
							$(this).on('keyup', methods.validate);
						});
						data.fields.filter('select').each(function() {
							$(this).on('change', methods.validate);
						});
						data.fields.filter(':radio, :checkbox').each(function() {
							$(this).on('click', function(e) {
								methods.validate.call(this);
							});
						});

						$this.on('submit', function(e) {
							if (!methods.exec.call(this)) {
								e.preventDefault();
							}
						});
					}

				}
				
			});

		},

		// Execute form validation and return a result
		exec : function() {

			// Load the data references
			var $this = $(this),
				data  = $this.data('reboot_validation');

			// Add fields for validation if form was passed as element
			if ($this.is('form')) {
				data.fields = $this.find('.validate');
			} else {
				data.fields = this;
			}

			// Set the default result to true (pass)
			data.result = true;

			// Test each field
			data.fields.each(function() {
				methods.validate.call(this);
			});

			// Return true (pass) or false (fail)
			return data.result;

		},

		validate : function() {

			var e = event;

			if (e.type === 'keyup' && !$(this).hasClass('error')) {
				return false;
			}

			var $field     = $(this),
				$this      = $field.parents('form'),
				data       = $this.data('reboot_validation'),
				fieldRules = $(this).data('validation');


			var limit = fieldRules.length;

			// Loop through the assigned rules
			for (var n = 0; n < limit; n++) {

				// Create a quick reference to the rule
				var rule = fieldRules[n];

				// Get the arguments
				args = rule.args ? rule.args : [];

				// Test the rules
                passed = rules[rule.rule].apply($field, args);

                // If a test doesn't pass, set an error message and post an error message
                if (!passed) {

                	// setTimeout is a workaround to find the right focused element
                	setTimeout(function() {

                		// Append the error to the controls div
	                    if (rule.group) {
	                    	if (!($(rule.group).is(':focus'))) {
	                    		$(rule.group).removeClass('valid').addClass('error');
	                        	addError(rule);
	                    	}
	                    } else {
	                        $field.removeClass('valid').addClass('error');
	                        addError(rule);
	                    }

	                    $field.trigger('validated');

                	}, 0);

                	// Return the result
                	data.result = passed;

                } else {

                	// setTimeout is a workaround to find the right focused element
                	setTimeout(function() {

                		// The test passed, remove the error
	                	if (rule.group) {
	                		if (!($(rule.group).is(':focus'))) {
	                			$(rule.group).removeClass('error').addClass('valid');
	                			removeError();
	                		}
			            } else {
			                $field.removeClass('error').addClass('valid');
			                removeError();
			            }

                	}, 0);

                }

			}

			// Add the error message to the control-group
            function addError(rule) {
            	// Add the error class to the control group
                $field.parents('.control-group').removeClass('valid').addClass('error');
                
                // Remove the error message
                $field.parents('.controls').find('.' + data.settings.errorClass).remove();

                // Build the error template
                errorMsg = data.settings.errorTemplate.replace('{errorMsg}', rule.message);

                // Add the error message
                $field.parents('.controls').append(errorMsg);
            }

            // Remove the error message from the control group
            function removeError() {
            	// Remove the error class and mark as valid
	            $field.parents('.control-group').removeClass('error').addClass('valid');
	            $field.parents('.controls').find('.' + data.settings.errorClass).remove();
            }

			// Return the result
            return passed;

		},

		// Add a validation rule to a field
		addRule : function(rules) {

			return this.each(function() {

				var $this = $(this);

				// Add the validate class so it gets validated
				$this.addClass('validate');

				// Get the existing rules
				if ($this.data('validation') === undefined) {
					$this.data('validation', []);
				}
				var validationRules = $this.data('validation');

				// Add the validation rules
				if ($.isArray(rules)) {
					var limit = rules.length;
					for (var n = 0; n < limit; n++) {
						validationRules.push(rules[n]);
					}
				} else {
					validationRules.push(rules);
				}

			});

		},

		// Remove the select formatting completely
		destroy : function() {	

			return this.each(function() {

				var $this = $(this),
					data  = $this.data('reboot_validation');

				// Unbind the events
				if (data.fields) {
					data.fields.each(function() {
						$(this).unbind('change blur keyup', methods.validate);

					});
				} else {
					$this.unbind('change blur keyup', methods.validate);
				}

				// Remove the data
				$this.removeData('reboot_validation');

				// Remove all error messages
				$this.find('.form-error').remove();

			});

		}

	};

	// Decide which function to call
	$.fn.reboot_validation = function(method) {
		
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call( arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Method ' +  method + ' does not exist for reboot_validation plugin.');
		}
	
	};

})(jQuery);
(function($)
{
	$.widget('ui.myValidator',{
		options : {
			cssClass : {
				form: 'validate',
				error : 'error'
			},
			reset : false,
			changeSubmit : true
		},

		wrapper_options : {
			offset : [0,0],
			effect : 'tooltips'
		},

		_create : function(){this.init(true);},

		_init : function(){this.init(false);},
		//setters
		setValidator : function()
		{
			var options = this.options,
				wrapper_options = this.wrapper_options;
			wrapper_options.errorClass = options.cssClass.error;
			this.oValidator = this.element.validator(wrapper_options);
		},
		//getters
		getValidator : function(){return this.oValidator;},
		//basic implementation
		init : function(_init)
		{
			var self = this,
				options = this.options,
				wrapper_options = this.wrapper_options;

			if(!this.element.is('form.'+options.cssClass.form))
			{
				return $.each(this.element.find('form.'+options.cssClass.form),
					function(i,v){return $(this).myValidator(options,wrapper_options);}
				);
			}
			if(_init)
			{
				this.setValidator();
				if(options.changeSubmit == true)
					this.changeSubmit();
			}
			return this;
		},

		changeSubmit : function()
		{
			var self = this,
				options = this.options;
			this.element.bind({
				'submit' : function(event)
				{
					try
					{
						event.preventDefault();
						var validator = self.getValidator();
						if(typeof validator != 'undefined')
						{
							var isValid = validator.data('validator').checkValidity();
							if(!isValid)
								$.error('form in sot valid');
							return $(this).getFormData();
						}
					}
					catch(e){return false;}
				}
			});
		},

		destroy : function(){},

		resetForm : function()
		{
			this.element.triggerHandler('reset');
			return true;
		}

	});

	$.extend( $.ui.myValidator, {
		version: "1.0",
		class_name : 'oValidator'
	});
})(jQuery);
(function($)
{
	$.widget('ui.myDatePicker',
	{
		options : {
			'cssClass' : 'datePicker',
			'hasTime' : false,
		},

		wrapper_options : {
			showOn: "both",
			buttonImage: "images/calendar.gif",
			buttonImageOnly: false,
			dateFormat: 'dd-mm-yy',
			timeFormat: 'hh:mm',
			hourGrid: 2,
			minuteGrid: 15,
			showButtonPanel: false,
			autoSize: true
		},
		_create : function(){this.init(true);},

		_init : function(){this.init(false);},
		//setters
		setDatePicker : function()
		{
			var self = this,
				options = this.options,
				wrapper_options = this.wrapper_options;
			//beforeShow fix box z-index
			wrapper_options.beforeShow = function(input, inst){
				setTimeout(
					function() {
						var maxIndex = self.setZIndex()+10;
						self.element.css("z-index",maxIndex);
						$("#ui-datepicker-div").css("z-index",maxIndex.toString());
					},  100);
			};
			//serch element class for generic settings
			wrapper_options = this.setWrapperSetting(wrapper_options);
			//replace inline option
			var elOptions = this.getElementOptions();
			if(! (elOptions == false || typeof elOptions === 'undefined') )
				wrapper_options = $.extend(true,wrapper_options,elOptions);

			this._datePicker = this.element.datetimepicker(wrapper_options);
		},
		
		setElementOptions : function()
		{
			this.elOptions = this.element.attr('options');
			if(typeof this.elOptions != 'undefined')
			{
				this.elOptions = $.evalJSON(this.elOptions);
				this.elOptions = this.elOptions.timepicker;
			}
			else
				this.elOptions = false;
		},
		
		setZIndex : function()
		{
			var _parents = this.element.parents('div'),
					maxIndex = 1;
				$.each(_parents,function(){
					var cIndex = $(this).css("z-index");
					if(typeof cIndex != 'undefined')
					{
						if(cIndex > maxIndex)
							maxIndex = cIndex;
					}
				});
			return parseInt(maxIndex);
		},

		setWrapperSetting : function(wrapper_options)
		{
			if(this.element.hasClass('doctorSchedule'))
			{
				wrapper_options = $.extend(true,wrapper_options,{
					minDate: new Date(),
					maxDate: '+1y',
					hourMin : 8,
					hourMax : 19,
					stepMinute : 15,
					stepHour : 1
				});
			}
			return wrapper_options;
		},
		//getters
		getDatePicker : function(){return this._datePicker;},
		getElementOptions : function(){return this.elOptions;},
		//basic implementation
		init : function(_init)
		{
			var self = this,
				options = this.options,
				wrapper_options = this.wrapper_options;

			if(! this.element.is('input.'+options.cssClass+'[id]') )
			{
				return $.each(this.element.find('input.'+options.cssClass+'[id]'),
					function(i,v){return $(this).myDatePicker(options,wrapper_options);}
				);
			}
			this.setElementOptions();
			if(_init)
			{
				this.setDatePicker();
			}
			return this;
		},

		destroy :function(){}

	});

	$.extend( $.ui.myTabs, {
		version: "1.0",
		class_name : 'oTabs'
	});

})(jQuery);
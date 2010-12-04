(function($)
{

	$.widget('ui.myToolTips',
	{
		options : {
			'class':"tootlps"
		},

		wrapper_options : {
			'position':'center right'
		},

		_create : function(){},

		_init : function(){this.init();},
		//setters
		setToolTips : function(){this.oToolTip = this.element.tooltips(this.wrapper_options);},
		//getters
		getToolTips : function(){return this.oToolTip;},
		//basic implementation
		init : function()
		{
			var self = this,
				options = this.options,
				wrapper_options = this.wrapper_options;
			
			if(!this.element.hasClass(options['class']))
			{
				return $.each(this.element.find('.'+options['class']),
					function(i,v){return $(this).myToolTips(options,wrapper_options);}
				);
			}

			this.setToolTips();
			return this;
		},

		destroy :function(){}

	});

	$.extend( $.ui.myToolTips, {
		version: "1.0",
		class_name : 'oToolTips'
	});
})(jQuery);
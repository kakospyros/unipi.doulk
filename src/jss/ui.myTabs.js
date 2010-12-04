(function($)
{
	var myTabs = new $.ui.tabs;
	
	myTabs.newLoad = function(index)
	{
		index = this._getIndex( index );
		var self = this,
			o = this.options,
			a = this.anchors.eq( index )[ 0 ],
			url = $.data( a, "load.tabs" );

		this.abort();
		o.json_args['class'] = this.list.attr('name') || o.json_args['class'];
		o.json_args['args'] = {'index':index,'action':$( a ).attr( "name" )};
		o.json_args.oldSucces = o.json_args.success;
		o.json_args.success = function( responseText, s )
		{
			var templateArguments = responseText._htmlTag || null;
			var _html = responseText.template;
			var element = self._sanitizeSelector( a.hash ); 
			var elem = $( element );

			elem.html( _html );
			$.oLayout('fixGUI',elem,templateArguments);
			$.myDialog.closeChildrens(element);
			
			self._cleanup();
			if ( o.cache ) {
				$.data( a, "cache.tabs", true );
			}
			self._trigger( "load", null, self._ui( self.anchors[ index ], self.panels[ index ] ) );

			//enbale close button for popups
			var _dialog = elem.parents('.ui-dialog-content['+$.oLayout.relationAttrString+']');
			if(_dialog.length > 0)
			{
				_dialog = $.oRegistry('get',{'method':_dialog.eq(0).attr($.oLayout.relationAttrString),'type':'odialog'});
				_dialog.activateButtons.call(_dialog);
			}

			try {
				if(typeof o.json_args.oldSuccess === 'function')
					o.json_args.oldSuccess( r, s );
			}
			catch ( e ) {}
		};
		this.xhr = $.oJSON(o.json_args);
		// last, so that load event is fired before show...
		self.element.dequeue( "tabs" );
//		this.xhr = ojson.call();
		return this;
	};

	myTabs.oldLoad = myTabs.load;

	myTabs.load = function(index)
	{
		var Index = this._getIndex( index );
		var self = this,
			o = this.options,
			a = this.anchors.eq( Index )[ 0 ],
			url = $.data( a, "load.tabs" );
		if(url == '_')
			return this.newLoad(index);
		else
			return this.oldLoad(index);
	};

	$.widget( "ui.tabs", myTabs);

	delete myTabs;

	$.widget('ui.myTabs',
	{
		options : {
			tagName : 'ul',
			cssClass : {
				basic : 'ui-tabs',
				validated : 'validated'
			}
		},

		wrapper_options : {
			idPrefix: 'ui-tabs-container-',
			panelTemplate: '<div/>',
			spinner: '<em>Loading&#8230;</em>',
			fx: { "opacity": 'toggle' },
			json_args : {
				namespace:['project','servlets','tabs'],
				'class':'TabsAction',
				method:'_runEvent',
				args:''
			}
		},

		_create : function(){this.init(true);},

		_init : function(){this.init(false);},
		//setters
		setTabs : function()
		{
			var self = this,
				options = this.options;
				wrapper_options = this.wrapper_options;
			if(typeof wrapper_options.select != 'function')
			{
				if(this.element.hasClass(options.cssClass.validated))
				{
					wrapper_options.select = function(event,ui)
					{
						//find and reset current form avoid tooltips issue after a validation
						var current_code = $(ui.panel);
						var current_form = current_code.find('form');
						var isValid = true,
							options = $(ui.tab).attr('options');
						if(typeof options != 'undefined' && options.length > 0)
						{
							options = $.evalJSON(options);
							$.each(options['ref'],function(i,v){
								var valid = (v > 0);
								if(!valid)
									isValid = valid;
							});
						}
						if(isValid && current_form.length)
							current_form.myValidator('resetForm');
						return isValid;
					}
				}
			}
			this.tabs = this.element.tabs(wrapper_options);
		},
		//getters
		getTabs : function(){return this.tabs;},
		//basic implementation
		init : function(_init)
		{
			var self = this,
				options = this.options,
				wrapper_options = this.wrapper_options;

			if(! (this.element.is('div.'+options.cssClass.basic) && this.element.find(options.tagName).length > 0) )
			{
				return $.each(this.element.find('div.'+options.cssClass.basic+':has('+options.tagName+')'),
					function(i,v){return $(this).myTabs(options);}
				);
			}
			if(_init)
			{
				this.ul = this.element.find(options.tagName);
				$.each(this.ul,function(i,v){self.setTabs();});
			}
			return this;
		},

		destroy :function(){},

	});

	$.extend( $.ui.myTabs, {
		version: "1.0",
		class_name : 'oTabs'
	});

})(jQuery);
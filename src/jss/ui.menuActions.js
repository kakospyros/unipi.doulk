(function($,undefined)
{
	$.widget('ui.menuActions',{
		options :{
			tagName : 'ul',
			eventOn : 'li',
			cssClass : {
				basic : 'menu-event',
				single_open : 'single-open'
			}
		},

		_ceate : function(){},
		
		_init : function(){this.init(true);},

		init : function(_init)
		{
			var self = this,
				options = this.options;
			
			if(! (this.element.is(options.tagName)
					&& this.element.hasClass(options.cssClass.basic)
					&& this.element.find(options.eventOn).length > 0) )
			{
				return $.each(this.element.find(options.tagName+'.'+options.cssClass.basic+':has('+options.eventOn+')'),
					function(i,v){return $(this).menuActions(options);});
			}
			
			if(_init)
			{
				$.each(this.element.find(options.eventOn),
					function(i,v)	{self.createEvent(this);}
				);
			}
			return this;
		},

		createEvent : function(elem)
		{
			var self = this,
				element = $(elem);
				method = 'MenuActions',
				args = {},
				json_arguments = {};
			var archon = element.find('a[name][title]:first').eq(0);
			var action = archon.attr('name') || null;
			element.unbind('click');
			element.bind(
			{
				'click' : function(event)
				{
					event.preventDefault();
					if(action === null)
						return false;
					if(self.element.hasClass(self.options.cssClass.single_open))
					{
						var registry = $.oRegistry('check',{method:method,action:action,type:'actions'},true);
						if(registry)
							return false;
					}
					args = {
						action:[['project','servlets'],method,'_runEvent'],
						inner:'#maincontainer',
						args:{
							action:action,
							text:archon.attr('title')
						}
					};
					$.oLayout(args).sendCall(json_arguments);
				}
			});
		},
		destroy :function(){}
	});

	$.extend( $.ui.menuActions, {
		version: "1.0",
		class_name : 'oMenuActions'
	});

})(jQuery);
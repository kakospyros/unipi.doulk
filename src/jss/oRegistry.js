(function($)
{
	var _oRegistry = {
		//attributes
		class_name : 'oRegistry',
		register : {},

		//methods
		check : function(conf, add)
		{
			conf = $.extend(true,{'method':null,'action':null,'type':'odialog'},conf);
			add = !(typeof add === 'undefined');
			var method = conf.method,
				action = conf.action,
				type = conf.type;
			
			if(typeof this.register[type] != 'undefined' && typeof this.register[type][method] != 'undefined')
			{
				var i = 0,
					cont = null,
					loop = this.register[type][method];

				for(i in loop)
				{
					if(i == action)
						return true;
				}
				if(add === true)
				{
					if(type === 'actions')
						this.register[type][method][action] = {};
					else if(type === 'odialog')
						this.register[type][method] = action;
				}
			}
			else
			{
				if(add === true)
				{
					this.register[type] = $.extend(true,{},this.register[type]);
					if(type === 'actions')
					{
						if(typeof this.register[type][method] === 'undefined')
							this.register[type][method] = {};
						this.register[type][method][action] = {};
					}
					else if(type === 'odialog')
						this.register[type][method] = action;
				}
			}
			return false;
		},

		clear : function(conf)
		{
			conf = $.extend(true,{'method':null,'action':null,'type':'odialog'},conf);
			var method = conf.method,
				action = conf.action,
				type = conf.type;

			if(typeof this.register[type][method] != 'undefiend')
			{
				if(action == null)
					delete this.register[type][method];
				else
				{
					if(typeof this.register[type][method][action] === 'undefined')
						return false;
					delete this.register[type][method][action];
				}
				return true;
			}
			return false;
		},

		get : function(conf)
		{
			conf = $.extend(true,{'method':null,'type':'odialog'},conf);
			var method = conf.method,
				type = conf.type;

			if(typeof this.register[type] === 'undefined' || (method != null && typeof this.register[type][method] === 'undefined'))
				return false;
			if(method == null && type == null)
				return this.register;
			if(method == null)
				return this.register[type];
			return this.register[type][method];
		}

	};

	$.oRegistry = function()
	{
		var fargs = arguments;
		if( _oRegistry[fargs[0]] && typeof _oRegistry[fargs[0]] === 'function')
			return _oRegistry[fargs[0]].apply(_oRegistry,Array.prototype.slice.call( fargs, 1 ));
		else
			$.error( 'Method ' +  method + ' does not exist on _oRegistry' );
	};

})(jQuery);
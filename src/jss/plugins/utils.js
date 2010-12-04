(function($)
{
	$.fn.func_num_args = function()
	{
		if (!arguments.callee.caller)
			return false;
		return arguments.callee.caller.arguments.length;
	};
})(jQuery);

(function($)
{
	$.fn.getFormData = function(element)
	{
		if(typeof element === 'undefined')
			element = this;
		else
			element = $(element);

		var data = decodeURIComponent(element.serialize());
		if(data)
		{
			data = $.parseQuery(data);
			data = element.getFormNumberData(data);
		}
		return data;
	};
})(jQuery);

(function($)
{
	$.fn.getFormNumberData = function(data,element)
	{
		if(typeof element === 'undefined')
			element = this;
		else
			element = $(element);

		var _data = {};
		var numbers = element.find('input.number');
		$.each(numbers,function(i,v){
			_data[$(this).attr('name')] = $(this).triggerHandler('getNumber');
		});
		return $.extend(true,data,_data);
	};
})(jQuery);

(function($)
{
	$.filterArguments = function(argument)
	{
		var values = {},
			options = {},
			hasValues = false,
			hasOptions = false;
		if(!(argument == null))
		{
			hasValues = (typeof argument['_tagValue'] != 'undefined');
			if(hasValues)
				values = $.extend(true,{},argument._tagValue);
			hasOptions = (typeof argument['_selectOptions'] != 'undefined');
			if(hasOptions)
				options = $.extend(true,{},argument._selectOptions);
		}
		return {
			'hasValues':hasValues,
			'values':values,
			'hasOptions':hasOptions,
			'options':options
			};
	};
})(jQuery);

(function($)
{
	$.getRelation = function(element, parentClass)
	{
		if(typeof element === 'undefined')
			return false;
		
		var dOptions = element.attr('options');
		parentClass = parentClass || '['+$.oLayout.relationAttrString+']';
		
		if(dOptions === '' || typeof dOptions === 'undefined' || !element.is('div'))
		{
			var parentBox = element.closest('div['+$.oLayout.relationAttrString+']').eq(0);
			if(parentBox.length < 1)
				return false;
		}
		else
			var parentBox = element;
		var parentOptions = parentBox.attr('options');

		if(typeof parentOptions === 'undefined')
			return false;

		var parentOptionsObj = $.evalJSON(parentOptions),
			link = null,
			isParent = false,
			val = null,
			relation = {
				'parent' : [],
				child : [],
				current : null,
				isParent : false,
				isChild : false
			};

		if(typeof parentOptionsObj['parent'] != 'undefined')
		{
			relation.isParent = true;
			link = parentOptionsObj['parent'];
		}
		else if(typeof parentOptionsObj.child != 'undefined')
		{
			relation.isChild = true;
			link = parentOptionsObj.child;
		}
		if(link === null)
			return false;

		relation.current = parentBox.attr($.oLayout.relationAttrString);

		if(relation.isParent)
		{
			$('div[options]['+$.oLayout.relationAttrString+']').filter(function(i,v){
				var foptions = $(this).attr('options');
				if(typeof foptions != 'undefined')
				{
					foptions = $.evalJSON(foptions);
					if(typeof foptions['child'] != 'undefined' && foptions['child'] == link)
					{
						val = $(this).attr($.oLayout.relationAttrString);
						if(typeof val != 'undefined')
							relation['child'].push(val);
					}
				}
			});
		}
		else
		{
			$('div[options]['+$.oLayout.relationAttrString+']').filter(function(i,v){
				var foptions = $(this).attr('options');
				if(typeof foptions != 'undefined')
				{
					foptions = $.evalJSON(foptions);
					if(typeof foptions['parent'] != 'undefined' && foptions['parent'] == link)
					{
						val = $(this).attr($.oLayout.relationAttrString);
						if(typeof val != 'undefined')
							relation['parent'].push(val);
					}
				}
			});
		}
		return relation;
	};
})(jQuery);

(function($)
{
	$.oClone = function(obj)
	{
		var target = obj.constructor();
		for (var key in target)
			delete target[key];
		return $.extend(true,target,obj);
	};
})(jQuery);

(function($)
{
	$.populate = function(code,argument,elem_callback)
	{
		var args = $.filterArguments(argument);
			args.values = args.values || {};
			args.options = args.options || {},
			elem_values = false,
			elem_option = false;
		if(argument && argument._defaults && argument._defaults.numberFormat)
			args.numberFormat = argument._defaults.numberFormat;
		else
			args.numberFormat = $.oLayout._defaults.numberFormat;

		if(typeof elem_callback === 'function')
		{
			var _isCallback = true;
			_callback = function(){elem_callback(arguments[0]);};
		}
		else
		{
			var _isCallback = false;
			_callback = function(){};
		}

		var elements = code.find('*').filter(function(){
			var _name = $(this).attr('name');
			if((typeof _name != 'undefined') && ((typeof args.values[_name] != 'undefined' || typeof args.options[_name] != 'undefined')) )
				return true;
			return false;
		});

		if(args.hasOptions || args.hasValues)
		{
			$.each(elements,function(pos,elem){
				var _name = $(this).attr('name'),
					events = $(this).data('events'),
					elemOptions = $(this).attr('options'),
					element = $(this);
				elem_value = ((typeof args.values[_name]) != 'undefined');
				elem_option = ((typeof args.options[_name]) != 'undefined');

				if(elem_option || elem_value)
				{
					switch(this.tagName.toLowerCase())
					{
						case "input":
							if(this.type === 'radio')
							{
								if(elem_value)
								{
									if( element.val() == args.values[_name] )
									{
										if(element.hasClass('readonly_disabled'))
										{
											element.unbind('click');
											element.trigger('click');
											element.myEvents();
										}
										else
											element.trigger('click');
									}
								}
							}
							else if(this.type === 'checkbox')
							{
								if(elem_value)
								{
									if( ( element.val() == args.values[_name] ) || ( args.values[_name] == null && element.is(':checked')) )
									{
										if(element.hasClass('readonly_disabled'))
										{
											element.unbind('click');
											element.trigger('click');
											element.myEvents();
										}
										else
											element.trigger('click');
									}
								}
							}
							else
							{
								element.val(args.values[_name]);
							}
							break;
						case "select":
							if(typeof args.options[_name] === 'object')
							{
								element.find('option').remove();
								var tag = this;
								$.each(args.options[_name],function(opt_pos,opt_val)
								{
									$(tag).append($('<option/>').attr('value',opt_pos).text(opt_val));
								});
							}
							if(typeof args.values[_name] === 'object' && $(this).find('option').length)
							{
								var tag = this;
								$.each(args.values[_name],function(opt_pos,opt_val){
									$(tag).find('option[value='+opt_val+']').attr("selected",true);
								});
							}
							else
								element.find("option[value="+args.values[_name]+"]").attr("selected",true);
							break;
						case "p":
						case "label":
						case "span":
							element.html(args.values[_name]);
							break;
						default:
							element.val(args.values[_name]);
							break;
					}
				}
				_callback(this);
			});
		}
		else if(_isCallback)
			$.each(elements,function(pos){_callback(this);});
		code.find('input.number[type=text]').numberFormat(true,args.numberFormat);
		return elements;
	};
})(jQuery);

(function($)
{
	$.fn.numberFormat = function(setEvent,format)
	{
		var element = $(this);

		if(element.hasClass('number'))
		{
			if(typeof setEvent === 'undefined' || !(setEvent === true) )
				setEvent = false;

			var elemOptions = $(this).attr('options');
			if( (typeof format != 'object'))
				format = {format:"#,##0.00",locale:"gr"};
			else
				format = $.extend(true,{format:"#,##0.00",locale:"gr"},format)

			if(typeof elemOptions != 'undefined')
			{
				elemOptions = $.evalJSON(elemOptions);
				if(elemOptions.number)
				var format = $.extend(true,format,elemOptions.number);
			}
			element.formatNumber(format);

			if(setEvent)
			{
				element.blur(function(){
					$(this).parseNumber(format);
					$(this).formatNumber(format);
				});
				element.bind({
					'getNumber' : function(event){
						var number = $(this).val();
						return $.parseNumber(number,format);
					}
				});
			}
		}
	}

})(jQuery);
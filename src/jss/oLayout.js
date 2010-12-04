(function($)
{
	$.oLayout = function()
	{
		var fargs = arguments,
			_oLayout = {

				//attributes
				class_name : 'oLayout',
				ajax_options :
				{
					namespace:null,
					'class':null,
					method:null,
					args:[]
				},

				options :
				{
					action:null,
					args:null,
					build:true,
					'close':null,
					field_options : {},
					inner:null,
					isParent : false,
					newElement: null,
					remote:true,
					replace:false
				},

				mainBoxes : ['page'],

				//methods
				init : function(options)
				{
					this.options = $.extend(true,this.options,options);
					return this;
				},

				build : function(element,html_code,args)
				{
					var layout = null;
					if(this.options.newElement == true)
						layout = element;
					else
						layout = $(element);

					if(layout.length)
					{
						if(typeof html_code === 'undefined' && this.options.newElement == true)
							html_code = null;
							
						if(args._tplCssOptions && args._tplCssOptions.isParent === true)
						{
							var parentDiv = $('<div/>');
							$.oLayout.setAsParent(parentDiv,args._tplCssOptions);
							parentDiv.html(html_code);
							layout.empty();
							layout.append(parentDiv);
						}
						else
						{
							layout.html(html_code);
						}
					}
					return layout;
				},

				buildTemplate : function(responseText,element)
				{
					var self = this,
						check = false,
						options = this.options;

					if(element == null)
						element = options.inner;
					if(element == null)
					{
						$.each(this.mainBoxes,function(key){
							if(typeof responseText[key] === 'object')
							{
								self.buildTemplate(responseText[key],key);
								check = true;
							}
						});
						if(!check)
						{
							var inner = $('#'+responseText.inner);
							if(inner.length)
							{
								self.buildTemplate(responseText.source,inner);
								check = true;
							}
						}
						if(check)
							return;
					}

					var html = responseText.template,
						templateArguments = responseText._htmlTag || {},
						token = responseText.form_token,
						layer = responseText['Layer'],
						popupArgs = responseText['_popupBox'];

					if(element == null)
					{
						if(options.replace === true)
							console.log('under construction');
						if(popupArgs)
							currentTemplate = this.createBox(html,popupArgs);
						else if(!layer || layer === 'undefined')
							currentTemplate = this.build("#page",html,templateArguments);
					}
					else if(options.replace === false)
					{
						currentTemplate = this.build(element,html,templateArguments);
					}
					else if(options.replace === true)
						currentTemplate = this.replace(element,html);

					this.fixGUI(currentTemplate,templateArguments);
				},

				createBox : function(html,args)
				{
					var args = args || {},
						box = $('<div/>');
					var options = args.options,
						wrapper_options = args.wrapper_options;
					wrapper_options.html = html;
					var _dialog = $.myDialog(options,wrapper_options);
					_dialog.show();
					return _dialog.getBox();
				},

				fixGUI : function(html,argument)
				{
					if(!html)
						return false;
					var forms = html.find('form');

					html.menuActions();
					html.myToolTips();
					html.myTabs();
					forms.formActions();

					var elements = $.populate(html,argument);

					forms.myValidator();
					html.myDatePicker();
					html.loginForm();
					html.myEvents();

				},

				onComplete : function(XMLHttpRequest, textStatus)
				{
					if((typeof this.complete) === 'function')
						this.complete(XMLHttpRequest,textStatus);
				},

				onSuccess : function(data, textStatus, XMLHttpRequest)
				{
					if(data.exception)
					{
						if(typeof data.oRegistry != 'undefined' &&  !(data.oRegistry.method == null))
							var registry = $.oRegistry('clear',{'method':data.oRegistry.method,'action':data.oRegistry.action,'type':'actions'});
						$.myDialog('ErrorBox',{'html' : data.message});
					}
					else
					{
						if(this.options.build === true)
							this.buildTemplate(data);
						if((typeof this.success) === 'function')
							this.success(data, textStatus, XMLHttpRequest);
					}
				},

				replace : function(element,html)
				{
					var el = $(element);
					if(el.length)
						el.replaceWith(html);
					return el;
				},

				sendCall : function(ajax_options)
				{
					if(!this.options.remote)
						return false;

					var self = this,
						method = this.options.action,
						namespace = null,
						_class = null,
						_method = null;

					if(method.length == 3)
					{
						namespace = method.shift();
						_class = method.shift();
						_method = method.shift();
						if(!(typeof namespace === 'object'))
							namespace = [namespace];
					}
					else if(method.length == 2)
					{
						namespace = null;
						_class = method.shift();
						_method = method.shift();
					}
					else if(method.length < 2 || _method.length < 1)
						return false;

					var json_options = {
														namespace:namespace,
														'class':_class,
														method:_method,
														args:this.options.args
													};

					this.ajax_options = $.extend(true,json_options,ajax_options);

					this.success = this.ajax_options.success;
					this.complete = this.ajax_options.complete;

					this.ajax_options.success = function(data, textStatus, XMLHttpRequest){self.onSuccess(data, textStatus, XMLHttpRequest);};
					this.ajax_options.complete = function(XMLHttpRequest,textStatus){self.onComplete(XMLHttpRequest,textStatus);};

					this.json = $.oJSON(this.ajax_options);

					return this;
				}
			};
			
		if( _oLayout[fargs[0]] && typeof _oLayout[fargs[0]] === 'function')
			return _oLayout[fargs[0]].apply(this,Array.prototype.slice.call( fargs, 1 ));
		else if (typeof fargs[0] === 'object' || ! fargs[0])
		{
			var options = fargs[0] || {},
				ajax_options = fargs[1] || {};
			return _oLayout.init(options,ajax_options);
		}
		else
			$.error( 'Method ' +  method + ' does not exist on oLayout' );
	};

	$.extend($.oLayout, {

		relationUID : 0,
		relationAttrString : 'aria-relation-Id',
		relationValueString : 'layout-relation-',

		getRelationAttr : function($el)
		{
			var id = $el.attr('id');
			if(!id)
			{
				this.relationUID +=1;
				id = this.relationUID;
			}
			return this.relationValueString + id;
		},

		setAsChild : function(element,options)
		{
			var field_options = element.attr('options');
			if(typeof field_options != 'undefined')
				field_options = $.evalJSON(field_options);
			field_options = $.extend(true,field_options,options.field_options);
			field_options = $.toJSON(field_options);
			var relationID = $.oLayout.getRelationAttr(element),
				attrs = {};
			attrs['options'] = field_options;
			attrs[this.relationAttrString] = relationID;
			element.attr(attrs);
		},

		setAsParent : function(element,options)
		{
			var field_options = element.attr('options');
			if(typeof field_options != 'undefined')
				field_options = $.evalJSON(field_options);
			field_options = $.extend(true,field_options,options.field_options);
			field_options = $.toJSON(field_options);
			var relationID = $.oLayout.getRelationAttr(element),
				attrs = {};
			attrs['options'] = field_options;
			attrs[this.relationAttrString] = relationID;
			element.attr(attrs);
		}
	});

	$.extend($.oLayout, {
		_defaults : {},
	});

})(jQuery);

var t = $.oLayout(
				{
					action:[['project','servlets'],'Authenticated','_getFormTpl'],
					args:{'getLang':true,'getDefaults':true}
				}
				).sendCall({
					success:function(data, textStatus, XMLHttpRequest)
					{
						$.oLanguage().setLang(data.js_lang);
						$.oLayout._defaults = data._defaults || {};
					}
				});
(function($)
{
	$.oDialog = {};
})(jQuery);

(function($)
{
	$.myDialog = function()
	{
		var fargs = arguments,
			_myDialog = {
				//attributes
				class_name : 'myDialog',
				options : {
					appendOn: 'body',
					oRegistry : {
						method : null,
						action : null
					},
					isChild : false,
					isParent : false,
					field_options : {}
				},
		
				wrapper_options : {
					dialogClass : 'popup-dialog',
					title : null,
					html : null,
					minHeight : 100,
					minWidth : 100,
					resizable : false,
					position : 'center',
					closeOnEscape : true
				},
				//fucntions
				//setter
				setBox : function(){this.box = $('<div/>');},
		
				setContainer : function()
				{
					this.boxContainer = $('<div/>');
					this.boxContainer.html(this.wrapper_options['html']);
					this.getBox().append(this.boxContainer);
				},
				setDialog : function()
				{
					var box = this.getBox(),
						wrapper_options = this.wrapper_options;
					box.dialog(wrapper_options);
					this._dialog = box.dialog('widget');
				},
				//getter
				getBox : function(){return this.box;},
		
				getBoxContainer : function(){return this.boxContainer;},
		
				getDialog : function(){return this.box.dialog('widget');},
		
				getRelation : function(element)
				{
					if(typeof element === 'undefined')
						var _dialog = this.getBox();
					else
						var _dialog = element;
					return $.getRelation(_dialog);
				},
				//basic implementation
				init : function(options,wrapper_options)
				{
					var self = this;
					this.options = $.extend(true,this.options,options),
					this.wrapper_options = $.extend(true,this.wrapper_options,wrapper_options);
					this.wrapper_options['close'] = function(event,ui){self.onClose(event,ui);};
					return this;
				},

				activateButtons : function()
				{
					var self = this,
						buttons = this.getBoxContainer().find('button[name=close]');
		
					$.each(buttons,function(i,v)
					{
						$(this).unbind('click');
						$(this).bind(
							{
								'click' : function(event,ui)
								{
									event.preventDefault();
									self.wrapper_options['close'].call(this,event,ui);
								}
							}
						);
					});
				},

				destroy : function(){
					var box = this.getBox();
					box.dialog('destroy').remove();
				},

				onClose : function(event,ui)
				{
					var self = this,
						options = this.options,
						container = this.getBoxContainer();
					if(! (options.oRegistry.method == null) )
						var clear = $.oRegistry('clear',{'method':options.oRegistry.method,'action':options.oRegistry.action,'type':'actions'});

					container.find('form').myValidator('resetForm');

					if(typeof options['close'] === 'function')
						options['close'].call(this,event,ui);

					if(options.isParent === true)
						$.myDialog.closeChildrens(this.getBox());

					$.oRegistry('clear',{'method':this.getBox().attr($.oLayout.relationAttrString),'type':'odialog'});
					this.destroy();
				},

				setAsChild : function()
				{
					var options = this.options,
						box = this.getBox();
					$.oLayout.setAsChild(box,options);
				},

				setAsParent : function()
				{
					var options = this.options,
						box = this.getBox();
					$.oLayout.setAsParent(box,options);
				},

				show : function()
				{
					var self = this,
						options = this.options,
						wrapper_options = this.wrapper_options;

					this.setBox();
					var box = this.getBox();

					this.setContainer();
					$(options.appendOn).append(box);
					this.activateButtons();
					box.dialog(wrapper_options);
					this.setDialog();
					if(options.isParent == true)
						this.setAsParent();
					if(options.isChild == true)
						this.setAsChild();
					$.oRegistry('check',{'method':box.attr($.oLayout.relationAttrString),'action':self,'type':'odialog'},true);
				}
			};
		
		_myDialog = $.extend(true,_myDialog,$.oDialog);

		if( _myDialog[fargs[0]] && typeof _myDialog[fargs[0]] === 'function')
			return _myDialog[fargs[0]].apply(this,Array.prototype.slice.call( fargs, 1 ));
		else if (typeof fargs[0] === 'object' || ! fargs[0])
		{
			var options = fargs[0] || {},
				wrapper_options = fargs[1] || {};
			return _myDialog.init(options,wrapper_options);
		}
		else
		{
			$.error( 'Method ' +  method + ' does not exist on myDialog' );
		}
	};

	$.extend($.myDialog, {

		closeChildrens : function(element)
		{
			var relations = $.getRelation($(element));
			if(typeof relations['child'] == 'undefined')
				return false;
			$.each(relations['child'],function(i,v){
				var __dialog = new $.oRegistry('get',{'method':this,'type':'odialog'});
				if(__dialog != false && __dialog.onClose)
					__dialog.onClose.call(__dialog,'close');
			});
		},

		closeCurrent : function(element)
		{
			var relations = $.getRelation($(element));
			if(relations['current'] && relations['current'] != null)
			{
				var __dialog = new $.oRegistry('get',{'method':relations['current'],'type':'odialog'});
				__dialog.onClose.call(__dialog,'close');
			}
		}

	});

})(jQuery);

(function($)
{
	var extend_obj = {
		'ErrorBox' : function(wrapper_options,options)
		{
			options = $.extend(true,{},options);
			wrapper_options = $.extend(true,{
				modal : true,
				dialogClass : 'error-dialog',
				posititon : 'center',
				title : $.oLanguage().getLang().oGeneric.ui.error.title,
				html : $.oLanguage().getLang().oGeneric.ui.error.message
				},wrapper_options);
			wrapper_options['html'] = $('<p/>').append(wrapper_options['html']);
			wrapper_options.buttons = $.extend(true,{},wrapper_options.buttons);
			wrapper_options.buttons['ok'] = function(event,ui){_dialog.onClose(event,ui);};

			var _dialog = $.myDialog(options,wrapper_options);
			_dialog.show();
		}
	};
	$.oDialog = $.extend(true,$.oDialog,extend_obj);
})(jQuery);

(function($)
{
	var extend_obj = {
		'WarningBox' : function(wrapper_options,options)
		{
			options = $.extend(true,{},options);
			wrapper_options = $.extend(true,{
				modal : true,
				dialogClass : 'warning-dialog',
				posititon : 'center',
				title : $.oLanguage().getLang().oGeneric.ui.warning.title,
				html : $.oLanguage().getLang().oGeneric.ui.warning.message
				},wrapper_options);
			wrapper_options['html'] = $('<p/>').append(wrapper_options['html']);
			wrapper_options.buttons = $.extend(true,{},wrapper_options.buttons);
			wrapper_options.buttons['ok'] = function(event,ui){_dialog.onClose(event,ui);};

			var _dialog = $.myDialog(options,wrapper_options);
			_dialog.show();
		}
	};
	$.oDialog = $.extend(true,$.oDialog,extend_obj);
})(jQuery);

(function($)
{
	var extend_obj = {
		'ConfirmationBox' : function(callback,wrapper_options,options)
		{
			options = $.extend(true,{},options);
			wrapper_options = $.extend(true,{
				modal : true,
				dialogClass : 'confirm-dialog',
				posititon : 'center',
				title : $.oLanguage().getLang().oGeneric.ui.confirmation.title,
				html : $.oLanguage().getLang().oGeneric.ui.confirmation.message
				},wrapper_options);
			wrapper_options['html'] = $('<p/>').append(wrapper_options['html']);
			wrapper_options.buttons = $.extend(true,{},wrapper_options.buttons);
			wrapper_options.buttons['yes'] = function(event,ui)
			{
				_dialog.onClose(event,ui);
				if(typeof callback.method === 'function')
					callback.method.call(this,callback.data);
			};
			wrapper_options.buttons['no'] = function(event,ui){_dialog.onClose(event,ui);};

			var _dialog = $.myDialog(options,wrapper_options);
			_dialog.show();
		}
	};
	$.oDialog = $.extend(true,$.oDialog,extend_obj);
})(jQuery);
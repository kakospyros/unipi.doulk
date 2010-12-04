(function($)
{
	$.widget('ui.loginForm',{
		options : {
			loginButtonID : 'loginButton',
			logoutButtonID : 'logoutButton',
			dashButtonID : 'dashButton',
			formID : 'loginFRM',
			createEvents:true,
			focusOn:'username'
		},

		_init : function(){this.init()},

		init : function()
		{
			try
			{
				var self = this,
					options = this.options;
					id = this.element.attr('id');

				if(typeof id === 'undefined' || ! (id == options.loginButtonID || id == options.logoutButtonID || id == options.dashButtonID) )
				{
					return $.each(this.element.find('[id='+options.loginButtonID+'],[id='+options.logoutButtonID+'],[id='+options.dashButtonID+']'),
						function(i,v){return $(this).loginForm(options);}
					);
				}
				if(options.createEvents === true)
					this.createEvent();
				return this;
			}
			catch(e){return false;}
		},

		createEvent : function()
		{
			var self = this,
				options = this.options,
				id = this.element.attr('id');
			this.element.unbind('click');

			if(id == options.loginButtonID)
			{
				this.form = this.element.closest('form[id='+options.formID+']');
				if(this.form.length == 0)
					$.error('login form has not been exist');
				this.element.bind(
				{
					'click' : function(event)
					{
						event.preventDefault();
						var data = self.form.triggerHandler('submit');
						if(data == false)
							return false;
						var args = {
							'action':[['project','servlets'],'Authenticated','_login'],
							'args':data
						};
						$.oLayout(args).sendCall();
					}
				});
				this.form.find('[name='+options.focusOn+']').focus();
			}
			else if(id == options.logoutButtonID)
			{
				this.element.bind(
				{
					'click' : function(event)
					{
						event.preventDefault();
						var args = {
							'action':[['project','servlets'],'Authenticated','_logout']
						};
						var json_args = {
							'beforeSend':function(){
								var openBox = $('div['+$.oLayout.relationAttrString+']');
								$.each(openBox,function(i,v){
									var attr = $(this).attr($.oLayout.relationAttrString);
									var box = $.oRegistry('get',{'method':attr,'type':'odialog'});
									if(box)
										box.onClose.call(box);
								});
							}
						};
						$.oLayout(args).sendCall(json_args);
					}
				});
			}
		}
	});

	$.extend( $.ui.loginForm, {
		version: "1.0",
		class_name : 'oLoginForm'
	});

})(jQuery);
(function($)
{
	$.widget('ui.myEvents',
	{
		options : {
			eventsClass : {
				fetchURL : 'fetchURL',

				getInfoForm : 'getInfoForm',
				getSelectInfoForm : 'getSelectInfoForm',
				triggerTarget : 'triggerTarget',
				setDisabled : 'readonly_disabled'
			}
		},

		_create : function(){/*this.init(true);*/},
		
		_init : function(){this.init(true);},

		init : function(_init){
			var self = this,
				options = this.options,
				className = this.element.attr('class'),
				selector = null;
			if(className.indexOf(options.eventsClass.fetchURL+':') == -1){
				if(className.indexOf(options.eventsClass.getInfoForm+':') == -1){
					if(className.indexOf(options.eventsClass.getSelectInfoForm+':') == -1){
						if(className.indexOf(options.eventsClass.triggerTarget+':') == -1){
							if(className.indexOf(options.eventsClass.setDisabled) == -1){
								return $.each(this.element.find('[class*='+options.eventsClass.fetchURL+
									'\:],[class*='+options.eventsClass.getInfoForm+
									'\:],[class*='+options.eventsClass.getSelectInfoForm+
									'\:],[class*='+options.eventsClass.triggerTarget+
									'\:],[class*='+options.eventsClass.setDisabled+
									']'),
									function(i,v){$(this).myEvents(options);}
								);
							}
						}
					}
				}
			}
			if(_init)
				this.createEvent();
			return this;
		},

		createEvent : function(){
			var self = this,
				options = this.options,
				className = this.element.attr('class');
			//fetch url
			if(className.indexOf(options.eventsClass.fetchURL+':') != -1){
				this.element.unbind('blur');
				this.element.bind({
					'blur' : function(event){
						event.preventDefault();
						var elementClass = className.split(' ').filter(function(i,v){
							var _class = i.split(':');
							if(_class[0] === self.options.eventsClass.fetchURL && typeof _class[1] === 'string')
								return true;
							return false;
						});

						if(!elementClass.length)
							return false;

						var c = elementClass[0].split(':'),
							val = self.element.val(),
							reg = new RegExp("^((http(s)?)://)?[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+$");

						if(val.length < 3 || !reg.test(val))
							return false;

						var args = {
							action:[['project','servlets'],'Utils','fetchURL'],
							args:{
								url:val,
								request:['title']
							}
						};
						json_args = {
							'success':function(data){
								var el = $('input[name='+c[1]+']:first');
								el.val(data.response);
							},
							'async':true
						};
						$.oLayout(args).sendCall(json_args);
					}
				});
			}
			//get info form
			else if(className.indexOf(options.eventsClass.getInfoForm+':') != -1){
				this.element.unbind('change');
				this.element.bind({
					'change' : function(event){
						event.preventDefault();
						var elementClass = className.split(' ').filter(function(i,v){
							var _class = i.split(':');
							if(_class[0] === self.options.eventsClass.getInfoForm && typeof _class[1] === 'string')
								return true;
							return false;
						});

						if(!elementClass.length)
							return false;

						var c = elementClass[0].split(':'),
							val = self.element.val();

						if(c[1].length < 3 )
							return false;
						
						var args = {
							action:[['project','servlets'],'Utils','getInfoForm'],
							args:{
								val:val,
								object:c[1]
							}
						};
						json_args = {
							'success':function(data){
								var form = self.element.parents('form:first');
								if(form.length > 0){
									$.populate(form,data);
								}
							}
						};
						$.oLayout(args).sendCall(json_args);
					}
				});
			}
			//select info form
			else if(className.indexOf(options.eventsClass.getSelectInfoForm+':') != -1){
				this.element.unbind('change');
				this.element.bind({
					'change' : function(event){
						event.preventDefault();
						var elementClass = className.split(' ').filter(function(i,v){
							var _class = i.split(':');
							if(_class[0] === self.options.eventsClass.getSelectInfoForm && typeof _class[1] === 'string')
								return true;
							return false;
						});

						if(!elementClass.length)
							return false;

						var c = elementClass[0].split(':'),
							val = self.element.val();
							
						if(c[1].length < 3 || $('select[name=' + c[2] +']').length == 0)
							return false;
						
						var args = {
							action : [['project','servlets'],'Utils','getSelectInfoForm'],
							args : {
								val : val,
								object : c[1],
								displayOn : c[2]
							}
						};
						var json_args = {
							'success' : function(data){
								var subs = $('select[name=' + c[2] +']');
								$.each(subs,function(i,v){
									var combo = $(this),
										old_val = $(this).val();
									combo.find('option').remove();
									$.each(data.options.opt,function(_i,_v){
										combo.append($('<option/>').attr('value',_i).text(_v));
									});
									combo.val(old_val);
								});
							}
						};
						$.oLayout(args).sendCall(json_args);
					}
				});
			}
			//trigger Target
			else if(className.indexOf(options.eventsClass.triggerTarget+':') != -1){
				this.element.bind({
					'click' : function(event){
						var elementClass = className.split(' ').filter(function(i,v){
							var _class = i.split(':');
							if(_class[0] === self.options.eventsClass.triggerTarget && typeof _class[1] === 'string')
								return true;
							return false;
						});

						if(!elementClass.length)
							return false;

						var c = elementClass[0].split(':');
						if(c.length != 2)
							return false;

						var val = self.element.val(),
							target = $('.'+c[1]),
							els = $('.'+c[1]).find('input[type=text]');

						if(val == 1){
							els.removeAttr('readonly');
			//				els.removeAttr('disabled');
							target.removeClass('readonly');
						}
						else{
							els.attr('readonly',true);
			//				els.attr('disabled',true);
							target.addClass('readonly');
							var el_price = els.filter('[name=price]');
							if(typeof el_price != 'undefined')
							{
								el_price.val(0);
								$.each(el_price,function(){
									if($(this).hasClass('number'))
										$(this).trigger('blur');
								})
							}
						}
					}
				});
			}
			//set disabled
			else if(className.indexOf(options.eventsClass.setDisabled) != -1){
				this.element.unbind('click');
				this.element.unbind('change');
				this.element.bind({
					'click' : function(event){
						event.preventDefault();
					},
					'change' : function(event){
						event.preventDefault();
					}
				});
			}
		}

	});

	$.extend( $.ui.menuActions, {
		version: "1.0",
		class_name : 'myEvents'
	});
	
})(jQuery);

//(function($)
//{
//	$.widget('ui.myEvents',
//	{
//		options : {
//			eventsClass : {
//				fetchURL : 'fetchURL',
//				fetchSubCategories : 'fetchSubCategories',
//				fetchDoctor : 'fetchDoctor',
//				togglingAjax : 'togglingAjax',
//				getInfo : 'getInfo',
//			}
//		},
//
//		_create : function(){/*this.init(true);*/},
//		
//		_init : function(){this.init(true);},
//
//		init : function(_init)
//		{
//			var self = this,
//				options = this.options,
//				className = this.element.attr('class'),
//				selector = null;
//			if(className.indexOf(options.eventsClass.fetchURL+':') == -1)
//			{
//				if(className.indexOf(options.eventsClass.getInfo+':') == -1)
//				{
//					if(className.indexOf(options.eventsClass.fetchSubCategories) == -1)
//					{
//						if(className.indexOf(options.eventsClass.fetchDoctor) == -1)
//						{
//							if(className.indexOf(options.eventsClass.togglingAjax) == -1)
//							{
//								return $.each(this.element.find('[class*='+options.eventsClass.fetchURL+
//									'\:],[class*='+options.eventsClass.getInfo+
//									'\:],[class*='+options.eventsClass.fetchSubCategories+
//									'],[class*='+options.eventsClass.fetchDoctor+
//									'],[class*='+options.eventsClass.togglingAjax+
//									']'),
//									function(i,v){
//										$(this).myEvents(options);
//									}
//								);
//							}
//						}
//					}
//				}
//			}
//			if(_init)
//				this.createEvent();
//			return this;
//		},
//
//		createEvent : function()
//		{
//			var self = this,
//				options = this.options,
//				className = this.element.attr('class');
//
//			if(className.indexOf(options.eventsClass.fetchURL+':') != -1)
//			{
//				this.element.unbind('blur');
//				this.element.bind({
//					'blur' : function(event)
//					{
//						event.preventDefault();
//						self.eventFetchURL(this,event);
//					}
//				});
//			}
//
//			else if(className.indexOf(options.eventsClass.getInfo+':') != -1)
//			{
//				this.element.unbind('change');
//				this.element.bind({
//					'change' : function(event){
//						event.preventDefault();
//						self.eventGetInfo(this,event);
//					}
//				});
//			}
//
//			else if(className.indexOf(options.eventsClass.fetchSubCategories) != -1)
//			{
//				var form = $(this.element).closest('form');
//				var subs = form.find('select.printSubCategories:first');
//				if(subs.length == 0)
//					return false;
//				this.element.unbind('change');
//				this.element.bind({
//					'change' : function(event)
//					{
//						event.preventDefault();
//						self.eventFetchSubCategories(this,subs,event);
//					}
//				});
//			}
//
//			else if(className.indexOf(options.eventsClass.fetchDoctor) != -1)
//			{
//				var form = $(this.element).closest('form');
//				var subs = form.find('select.fetchDoctor'),
//					subsTarget = form.find('select.printDoctor:first');
//				if(subs.length == 0)
//					return false;
//				this.element.unbind('change');
//				this.element.bind({
//					'change' : function(event)
//					{
//						event.preventDefault();
//						self.eventFetchDoctor(this,subs,subsTarget,event);
//					}
//				});
//			}
//
//			else if(className.indexOf(options.eventsClass.togglingAjax) != -1)
//			{
//				this.element.unbind('change');
//				this.element.bind({
//					'change' : function(event)
//					{
//						event.preventDefault();
//						self.eventToggleAjax(this,event);
//					}
//				});
//			}
//		},
//
//		destroy :function(){},
//
//		//fetchURL info
//		eventFetchURL : function(elem,event)
//		{
//			var self = this,
//				element = $(elem);
//
//			var el_class = element.attr('class').split(" ").filter(function(pos,elem){
//				var c = pos.split(':');
//				if(c[0] === self.options.eventsClass.fetchURL && typeof c[1] === 'string')
//					return true;
//				return false;
//			});
//
//			if(!el_class.length)
//				return;
//
//			var c = el_class[0].split(':');
//			var url = element.val();
//			var reg =new RegExp("^((http(s)?)://)?[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+$");
//			if(url.length < 3 || !reg.test(url) )
//				return;
//
//			var args = {
//				'action':[['project','servlets'],'Utils','fetchURL'],
//				'args':{
//					'url':url,
//					'request':['title']
//				}
//			};
//			json_args = {
//				'success':function(data)
//				{
//					$('[name='+c[1]+']').eq(0).val(data.response);
//				},
//				'async':true
//			};
//			$.oLayout(args).sendCall(json_args);
//		},
//
//		//getinfo
//		eventGetInfo : function(elem,event)
//		{
//			var self = this,
//				element = $(elem);
//
//			var el_class = element.attr('class').split(" ").filter(function(pos,elem){
//				var c = pos.split(':');
//				if(c[0] === self.options.eventsClass.getInfo && typeof c[1] === 'string')
//					return true;
//				return false;
//			});
//			if(! (el_class.length > 0))
//				return;
//
//			var c = el_class[0].split(':');
//			var url = element.val();
//			if(c[1].length < 3 )
//				return;
//
//			var args = {
//				action:[['project','servlets'],'Utils','getInfo'],
//				args:{
//					val:url,
//					object:c[1]
//				}
//			};
//			json_args = {
//				'success':function(data){
//					var form = element.parents('form:first');
//					if(form.length > 0){
//						$.populate(form,data);
//					}
//				}
//			};
//			$.oLayout(args).sendCall(json_args);
//		},
//		//fetch subCategories
//		eventFetchSubCategories : function(elem,subs,event)
//		{
//			var self = this,
//				element = $(elem);
//				old_val = subs.val();
//			var args = {
//					'action' : [['project','servlets'],'Utils','fetchSubCategories'],
//					'args' : {'categories' : element.val()}
//				},
//				json_args = {
//					'success' : function(data)
//					{
//						subs.find('option').remove();
//						$.each(data.options.subCategory,function(i,v)
//						{
//							subs.append($('<option/>').attr('value',i).text(v));
//						});
//						subs.val(old_val);
//					}
//				};
//			$.oLayout(args).sendCall(json_args);
//		},
//
//		//fetch Doctor
//		eventFetchDoctor : function(elem,subs,subsTarget,event)
//		{
//			var self = this,
//				element = $(elem);
//				old_val = subsTarget.val();
//				formData = {};
//
//			$.each(subs,function(i,v){
//				formData[$(this).attr('name')] = $(this).val();
//			});
//			var args = {
//					'action' : [['project','servlets'],'Utils','fetchDoctor'],
//					'args' : formData
//				},
//				json_args = {
//					'success' : function(data)
//					{
//						subsTarget.find('option').remove();
//						$.each(data.options.fetchDoctor,function(i,v)
//						{
//							subsTarget.append($('<option/>').attr('value',i).text(v));
//						});
//					}
//				};
//			$.oLayout(args).sendCall(json_args);
//		},
//
//		//toggling
//		eventToggleAjax : function(elem,event)
//		{
//			var form = $(this.element).closest('form');
//			var self = this,
//				element = $(elem),
//				form = $(elem).closest('form'),
//				method = $(elem).closest('form').attr('name') || 'FormActions';
//			var toggle = form.find('div.toggle_'+element.attr('name')).eq(0);
//			var args = {
//					'action' : [['project','servlets','forms'],method,'_toggle'],
//					'args' : {'id':element.attr('id'),'val' : element.val()},
//					'newElement': true,
//					'inner':toggle
//				};
//			$.oLayout(args).sendCall();
//		},
//
//	});
//
//	$.extend( $.ui.menuActions, {
//		version: "1.0",
//		class_name : 'myEvents'
//	});
//	
//})(jQuery);
(function($)
{
	$.widget('ui.formActions',{
		options : {
			dataTable_extented : true,
			buttons : 'button[name][type=button]',
			disableClass : 'ui-state-default ui-state-disabled',
			rowSelector : {
				tagName : ':checkbox',
				selected : ':checkbox:checked',
				notselected : ':checkbox:not(:checked)'
			}
		},

		_jsonClass : 'FormActionsExpand',

		_create : function(){this.init(true);},
		
		_init : function(){this.init(false);},
		//setter
		setDataTable : function()
		{
			if(this.options.dataTable_extented === true)
			{
				this.dt = this.element.tableActions();
			}
		},

		//getter
		getDataTable : function(){return this.dt;},

		//basic implementation
		init : function(_init)
		{
			var self = this,
				options = this.options;
			
			if(! this.element.is('form'))
			{
				return $.each(this.element.find('form'),
					function(i,v){return $(this).formActions(options);}
				);
			}
			if(this.element.hasClass('singlePage'))
				return this.element.formActionsSingle(options);

			if(_init)
			{
				this.setDataTable();
				this.buttons = $.each(this.element.find(options.buttons),
					function(i,v){self.createEvent(this);}
				);
			}
			return this;
		},

		createEvent : function(elem)
		{
			var self = this,
				element = $(elem),
				options = this.options,
				method = this.element.attr('name') || this._jsonClass,
				action = $(elem).attr('name');
			element.unbind('click');
			element.bind(
			{
				'click' : function(event)
				{
					try
					{
						event.preventDefault();
						if(element.hasClass(options.disableClass))
							return false;
						//form validation check
						if(action === 'add-row' || action === 'update-row' )
						{
							var data = self.element.triggerHandler('submit');
							if(data == false)
								return false;
						}
						var registry = $.oRegistry('check',{'method':method,'action':action,'type':'actions'},true);
						if(registry)
							return false;
						//delete-multi
						if(action === 'delete-multi'){self.eventDeleteMulti(elem);}
						//delete-row
						else if(action === 'delete-row'){self.eventDeleteRow(elem);}
						//other actions
						else{self.eventOthers(elem,data);}
					}
					catch(e){return false;}
				}
			});
		},

		deleteRecord : function(arg,callback)
		{
			var self = arg['self'],
				options = arg['self'].options,
				c_tr = arg['self'].findParentFromRunningTR('current');
			arg.data = arg.data || {};
			arg.data._record = 0;

			var tr = self.findParentFromRunningCheckTR('next',c_tr);
			if(! (tr.length > 0) )
				tr = self.findParentFromRunningCheckTR('prev',c_tr);
			if(! (tr.length > 0) )
				arg.data._record = 0;
			else
			{
				var requested = tr.find(self.options.rowSelector.tagName+'[id]:last');
				if(requested.length)
					arg.data._record = requested.attr('id');
			}

			var args = {
					'action':[['project','servlets','forms'],arg.method,'_runEvent'],
					'args':{
						'action':arg.action,
						'data' : arg.data
					}
				};
				var json_args = {
					'success':function(data, textStatus, XMLHttpRequest)
					{
						if(data['close'] === true)
						{
							$.myDialog.closeCurrent(self.element);
							var updateData = data['updateDataTable'];
							if(typeof updateData != 'undefined' && typeof updateData.selector != 'undefined')
							{
								var elements = $(updateData['selector']);
								if(elements.length)
								{
									$.each(elements,function(){
										var dt = $(this).tableActions('getDataTable');
										dt.fnClearTable();
									});
								}
							}
							return true;
						}

						//reset form
						if(typeof data['reset'] != 'undefined' && data['reset'] === true)
							self.element.trigger('reset');
						//populate form
						if(typeof data['populate'] != 'undefined' && data['populate'] === true && typeof data['_htmlTag'] != 'undefined')
							$.populate(self.element,data['_htmlTag'])

						//update datatable records
						var updateData = data['updateDataTable'];
						if(typeof updateData != 'undefined' )
						{
							if(typeof updateData['selector'] != 'undefined')
							{
								var uElement = $(updateData['selector']);
								if(uElement.length)
								{
									$.each(uElement,function()
									{
										var element = $(this);
										var dt = element.tableActions('getDataTable');
										dt.fnClearTable();
										element.tableActions('addMultiRow',updateData['records'],arg.data._record);
									});
								}
							}

							//show summary notification
							if(typeof updateData.notification != 'undefined')
								$.jGrowl(updateData.notification.message,{'header':'<img src="/src/img/ok.png" class="ok"/>'+updateData.notification['header']});
						}

						var tr = self.findParentFromRunningTR('current');
						
						if(tr.length > 0)
						{
							tr.siblings().removeClass('gradeX');
							tr.addClass('gradeX');
							var prevTR = self.findParentFromRunningCheckTR('prev',tr);
							var nextTR = self.findParentFromRunningCheckTR('next',tr);
						}
						else
						{
							var prevTR = [];
							var nextTR = [];
						}
						var prevBTN = self.element.find(options.buttons+'[name=prev-row]');
						var nextBTN = self.element.find(options.buttons+'[name=next-row]');

						if(! (prevTR.length>0) )
							prevBTN.addClass(options.disableClass);
						else
							prevBTN.removeClass(options.disableClass);
						if(! (nextTR.length>0) )
							nextBTN.addClass(options.disableClass);
						else
							nextBTN.removeClass(options.disableClass);

						if(typeof callback === 'function')
							callback();
					}
				};
			$.oLayout(args).sendCall(json_args);
		},
		
		deleteRecords : function(arg,callback)
		{
			var args = {
					'action':[['project','servlets','forms'],arg.method,'_runEvent'],
					'args':{
						'action':arg.action,
						'data' : arg.data
					}
				},
				json_args = {
					'success':function(data, textStatus, XMLHttpRequest)
					{
						//check and close child popup if selected record is in deleted
						if(data['close'] === true)
							$.myDialog('closeChildrens',arg.self.element);

						//update datatable records
						var updateData = data['updateDataTable'];
						if(typeof updateData != 'undefined')
						{
							if(typeof updateData['selector'] != 'undefined')
							{
								var uElement = $(updateData['selector']);
								if(uElement.length)
								{
									$.each(uElement,function()
									{
										var element = $(this);
										var dt = element.tableActions('getDataTable');
										dt.fnClearTable();
										element.tableActions('addMultiRow',updateData['records'],updateData['rowID']);
									});
								}
							}

							//show summary notification
							if(typeof updateData.notification != 'undefined')
								$.jGrowl(updateData.notification.message,{'header':'<img src="/src/img/ok.png" class="ok"/>'+updateData.notification['header']});
						}
					}
				};
			$.oLayout(args).sendCall(json_args);
		},

		eventDeleteMulti : function(elem)
		{
			var self = this,
				element = $(elem),
				options = this.options,
				method = this.element.attr('name') || this._jsonClass,
				action = $(elem).attr('name');
			
			var toDelete = self.element.find(options.rowSelector.selected);
			//0 records have been selected
			if(toDelete.length == 0)
			{
				self.element.find('span:has('+options.rowSelector.notselected+')')
					.stop()
					.animate({opacity: 0}, 1000)
					.animate({opacity: 1}, 1000);
				$.myDialog('ErrorBox',{
					html : $.oLanguage().getLang().oGeneric.ui.error.noRecordSelected,
				},
				{
					'close' : function()
					{
						$.oRegistry('clear',{'method':method,'action':action,'type':'actions'});
					}
				});
			}
			//at least one record has been selected
			else
			{
				var deleteArgs = [];
				$.each(toDelete,function(i,v)
				{
					deleteArgs.push($(this).attr('id'));
				});
				$.myDialog('ConfirmationBox',
					{
						html : $.oLanguage().getLang().oGeneric.ui.confirmation.deleteConfirm,
						method:self.deleteRecords,
						data:{'method':method,'data':deleteArgs,'action':action,'self':self}
					},
					{},
					{'close':function(){$.oRegistry('clear',{'method':method,'action':action,'type':'actions'});}}
				);
			}
		},

		eventDeleteRow : function(elem)
		{
			var self = this,
				method = this.element.attr('name') || this._jsonClass,
				action = $(elem).attr('name');

			var data = {};
			$.myDialog('ConfirmationBox',
					{
						html : $.oLanguage().getLang().oGeneric.ui.confirmation.deleteConfirm,
						'method':self.deleteRecord,
						data:{'method':method,'data':data,'action':action,'self':self}
					},
					{},
					{'close':function(){$.oRegistry('clear',{'method':method,'action':action,'type':'actions'});}}
				);
		},
		
		eventOthers : function(elem,data)
		{
			var self = this,
				element = $(elem),
				options = this.options,
				method = this.element.attr('name') || this._jsonClass,
				action = $(elem).attr('name');

			data = data || {};
			//next - prev section
			if(action === 'next-row' || action === 'prev-row')
			{
				data._record = 0;
				data._perm = 0;
				if(action === 'prev-row')
					var tr = self.findParentFromRunningTR('prev');
				else
					var tr = self.findParentFromRunningTR('next');

				if(tr.length)
				{
					var requested = tr.find(options.rowSelector.tagName+':first')
					if(requested.length)
						data._record = requested.attr('id');
				}
				//need it to fix next/prev appearence after a navigation on tab menus
				var prevTR = self.findParentFromRunningCheckTR('prev',tr);
				var nextTR = self.findParentFromRunningCheckTR('next',tr);
				if( (prevTR.length>0) )
					data._perm += 2;
				if( (nextTR.length>0) )
					data._perm += 1;
			}
			//update row section
			else if(action === 'update-row')
				var tr = self.findParentFromRunningTR('current');

			var args =
				{
					action : [['project','servlets','forms'],method,'_runEvent'],
					args:{
						action : action,
						data : data
					}
				};
				var json_args = {};
				//clear previous child opening dialog
				if(action == 'new-form')
				{
					json_args.beforeSend = function(){
						$.myDialog.closeChildrens(self.element);
					};
				}
				json_args.success = function(){
					var data = arguments[0];
					//reset form
					if(typeof data['reset'] != 'undefined' && data['reset'] === true)
						self.element.trigger('reset');
					//populate form
					if(typeof data['populate'] != 'undefined' && data['populate'] === true && typeof data['_htmlTag'] != 'undefined')
						$.populate(self.element,data['_htmlTag']);

					//update datatable records
					var updateData = data['updateDataTable'];
					if(typeof updateData != 'undefined')
					{
						if(typeof updateData['selector'] != 'undefined')
						{
							var uElement = $(updateData['selector']);
							if(uElement.length)
							{
								$.each(uElement,function()
								{
									var element = $(this);
									var dt = element.tableActions('getDataTable');
									dt.fnClearTable();
									element.tableActions('addMultiRow',updateData['records'],updateData['rowID']);
								});
							}
						}
						//show summary notification
						if(typeof updateData.notification != 'undefined')
							$.jGrowl(updateData.notification.message,{'header':'<img src="/src/img/ok.png" class="ok"/>'+updateData.notification['header']});
					}

					if(! (typeof updateData != 'undefined' && typeof updateData.selector != 'undefined') )
					{
						if(action === 'next-row' || action === 'prev-row')
						{
							var tr = self.findParentFromRunningCheckTRByID(updateData['rowID']);
						}
					}

					if(action === 'update-row')
						var tr = self.findParentFromRunningTR('current');
					if(typeof tr === 'undefined')
						var tr = [];

					if(tr.length)
					{
						tr.siblings().removeClass('gradeX');
						tr.addClass('gradeX');
						var prevTR = self.findParentFromRunningCheckTR('prev');
						var nextTR = self.findParentFromRunningCheckTR('next');
					}
					else
					{
						var prevTR = [];
						var nextTR = [];
					}
					var prevBTN = self.element.find(options.buttons+'[name=prev-row]');
					var nextBTN = self.element.find(options.buttons+'[name=next-row]');
					if(! (prevTR.length>0) )
						prevBTN.addClass(options.disableClass);
					else
						prevBTN.removeClass(options.disableClass);
					if(! (nextTR.length>0) )
						nextBTN.addClass(options.disableClass);
					else
						nextBTN.removeClass(options.disableClass);

					if(typeof data.oRegistry != 'undefined' &&  !(data.oRegistry.method == null))
						var registry = $.oRegistry('clear',{'method':data.oRegistry.method,'action':data.oRegistry.action,'type':'actions'});
				};

			if(!(action === 'new-form' || action === 'update-form'))
				args.build = false;
			else
				args.args.data._openMethod = 1;

			$.oLayout(args).sendCall(json_args);
		},




		findParentFromRunningTR : function(selected,tr)
		{
			var getRelation = $.myDialog('getRelation',this.element);
			parentForm = $('['+$.oLayout.relationAttrString+'='+getRelation['parent'][0]+']:first');
//			parentForm = getRelation['parent'][0];
			var dtTable = parentForm.find('table.tablesorter:first').tableActions('getDataTable','t');
			if(typeof dtTable === 'undefined')
				jQuery.error('table issue');

			dtTable = parentForm.find('table.tablesorter:first').tableActions('getDataTable');

			if(selected === 'current' || selected === '')
			{
				if(typeof tr === 'undefined')
					tr = parentForm.eq(0).find('.gradeX');
				else
					tr = tr[0];
			}
			else if(selected === 'first')
			{
				if(typeof tr === 'undefined')
					tr = parentForm.eq(0).find('tr:last')[0];
				else
					tr = tr[0];
			}
			else if(selected === 'last')
			{
				if(typeof tr === 'undefined')
					tr = parentForm.eq(0).find('tr:last')[0];
				else
					tr = tr[0];
			}
			else if(selected === 'next')
			{
				if(typeof tr === 'undefined')
					tr = parentForm.eq(0).find('.gradeX')[0];
				else
					tr = tr[0];
				tr = dtTable.fnGetAdjacentTr(tr);

				if($(tr).length > 0 && !(parentForm.eq(0).find('.gradeX').next().length > 0) )
					dtTable.fnPageChange( 'next');
				else if(!($(tr).length > 0))
					return -1;
			}
			else if(selected === 'prev')
			{
				if(typeof tr === 'undefined')
					tr = parentForm.eq(0).find('.gradeX')[0];
				else
					tr = tr[0];
				tr = dtTable.fnGetAdjacentTr(tr,false);
				if($(tr).length > 0 && !(parentForm.eq(0).find('.gradeX').prev().length > 0) )
					dtTable.fnPageChange( 'previous');
				else if(!($(tr).length > 0))
					dtTable.tr_effects(tr);
			}
			return $(tr);
		},

		findParentFromRunningCheckTR : function(selected, tr)
		{
			var getRelation = $.myDialog('getRelation',this.element);
			parentForm = $('['+$.oLayout.relationAttrString+'='+getRelation['parent'][0]+']:first');
//			parentForm = getRelation['parent'][0];
			var dtTable = parentForm.find('table.tablesorter:first').tableActions('getDataTable','t');
			if(typeof dtTable === 'undefined')
				jQuery.error('table issue');

			if(selected === 'current' || selected === '')
			{
				if(typeof tr === 'undefined')
					tr = parentForm.eq(0).find('.gradeX')[0];
				else
					tr = tr[0];
			}
			else if(selected === 'last')
			{
				dtTable.fnPageChange( 'last');
				if(typeof tr === 'undefined')
					tr = parentForm.eq(0).find('tr:last')[0];
				else
					tr = tr[0];
			}
			else if(selected === 'next')
			{
				if(typeof tr === 'undefined')
					tr = parentForm.eq(0).find('.gradeX')[0];
				else
					tr = tr[0];
				tr = dtTable.fnGetAdjacentTr(tr);
			}
			else if(selected === 'prev')
			{
				if(typeof tr === 'undefined')
					tr = parentForm.eq(0).find('.gradeX')[0];
				else
					tr = tr[0];
				tr = dtTable.fnGetAdjacentTr(tr,false);
			}
			return $(tr);
			
		},

		findParentFromRunningCheckTRByID : function(id)
		{
			var getRelation = $.myDialog('getRelation',this.element);
			parentForm = $('['+$.oLayout.relationAttrString+'='+getRelation['parent'][0]+']:first');
			tr = parentForm.find('tr').filter(function(i,v){
						return ($(this).find('[id='+id+']').length > 0);
					});
			return $(tr);
		}

	});

	$.extend( $.ui.formActions, {
		version: "1.0",
		class_name : 'oFormActions'
	});

})(jQuery);

(function($)
{
	$.widget('ui.formActionsSingle', $.extend({},$.ui.formActions.prototype,{

		_jsonClass : 'FormActionsExpandSinglePage',

		//basic implementation
		init : function(_init)
		{
			var self = this,
				options = this.options;
			
			if(! this.element.is('form.singlePage'))
			{
				return $.each(this.element.find('form.singlePage'),
					function(i,v){return $(this).formActionsSingle(options);}
				);
			}
			if(_init) 
			{
				this.setDataTable();
				this.buttons = $.each(this.element.find(options.buttons),
					function(i,v){self.createEvent(this);}
				);
			}
			return this;
		},
		
		deleteRecord : function(arg,callback)
		{
			var self = arg['self'],
				options = arg['self'].options,
				c_tr = arg['self'].findParentFromRunningTR('current');
			arg.data = arg.data || {};
			arg.data._record = 0;

			var tr = self.findParentFromRunningCheckTR('next',c_tr);
			if(! (tr.length > 0) )
				tr = self.findParentFromRunningCheckTR('prev',c_tr);
			if(! (tr.length > 0) )
				arg.data._record = 0;
			else
			{
				var requested = tr.find(self.options.rowSelector.tagName+'[id]:last');
				if(requested.length)
					arg.data._record = requested.attr('id');
			}

			var args = {
					'action':[['project','servlets','forms'],arg.method,'_runEvent'],
					'args':{
						'action':arg.action,
						'data' : arg.data
					}
				};
				var json_args = {
					'success':function(data, textStatus, XMLHttpRequest)
					{
						if(data['close'] === true)
						{
							var updateData = data['updateDataTable'];
							if(typeof updateData != 'undefined' && typeof updateData.selector != 'undefined')
							{
								var elements = $(updateData['selector']);
								if(elements.length)
								{
									$.each(elements,function(){
										var dt = $(this).tableActions('getDataTable');
										dt.fnClearTable();
									});
								}
							}
							//reset form
							if(typeof data['reset'] != 'undefined' && data['reset'] === true)
								self.element.trigger('reset');
							return true;
						}

						//reset form
						if(typeof data['reset'] != 'undefined' && data['reset'] === true)
							self.element.trigger('reset');
						//populate form
						if(typeof data['populate'] != 'undefined' && data['populate'] === true && typeof data['_htmlTag'] != 'undefined')
							$.populate(self.element,data['_htmlTag'])

						//update datatable records
						var updateData = data['updateDataTable'];
						if(typeof updateData != 'undefined')
						{
							if(typeof updateData['selector'] != 'undefined')
							{
								var uElement = $(updateData['selector']);
								if(uElement.length)
								{
									$.each(uElement,function()
									{
										var element = $(this);
										var dt = element.tableActions('getDataTable');
										dt.fnClearTable();
										element.tableActions('addMultiRow',updateData['records'],arg.data._record);
									});
								}
							}

							//show summary notification
							if(typeof updateData.notification != 'undefined')
								$.jGrowl(updateData.notification.message,{'header':'<img src="/src/img/ok.png" class="ok"/>'+updateData.notification['header']});
						}

						var tr = self.findParentFromRunningTR('current');
						
						if(tr.length > 0)
						{
							tr.siblings().removeClass('gradeX');
							tr.addClass('gradeX');
							var prevTR = self.findParentFromRunningCheckTR('prev',tr);
							var nextTR = self.findParentFromRunningCheckTR('next',tr);
						}
						else
						{
							var prevTR = [];
							var nextTR = [];
						}
						var prevBTN = self.element.find(options.buttons+'[name=prev-row]');
						var nextBTN = self.element.find(options.buttons+'[name=next-row]');

						if(! (prevTR.length>0) )
							prevBTN.addClass(options.disableClass);
						else
							prevBTN.removeClass(options.disableClass);
						if(! (nextTR.length>0) )
							nextBTN.addClass(options.disableClass);
						else
							nextBTN.removeClass(options.disableClass);

						if(typeof callback === 'function')
							callback();
					}
				};
			$.oLayout(args).sendCall(json_args);
		},

		deleteRecords : function(arg,callback)
		{
			var args = {
					'action':[['project','servlets','forms'],arg.method,'_runEvent'],
					'args':{
						'action':arg.action,
						'data' : arg.data
					}
				},
				json_args = {
					'success':function(data, textStatus, XMLHttpRequest)
					{
						//check and close child popup if selected record is in deleted
						if(data['close'] === true)
							arg.self.element.trigger('reset');

						//update datatable records
						var updateData = data['updateDataTable'];
						if(typeof updateData != 'undefined')
						{
							if(typeof updateData['selector'] != 'undefined')
							{
								var uElement = $(updateData['selector']);
								if(uElement.length)
								{
									$.each(uElement,function()
									{
										var element = $(this);
										var dt = element.tableActions('getDataTable');
										dt.fnClearTable();
										element.tableActions('addMultiRow',updateData['records'],updateData['rowID']);
									});
								}
							}

							//show summary notification
							if(typeof updateData.notification != 'undefined')
								$.jGrowl(updateData.notification.message,{'header':'<img src="/src/img/ok.png" class="ok"/>'+updateData.notification['header']});
						}
					}
				};
			$.oLayout(args).sendCall(json_args);
		},

		findParentFromRunningTR : function(selected,tr)
		{
			var dtTable = this.element.find('table.tablesorter:first').tableActions('getDataTable','t');
			if(typeof dtTable === 'undefined')
				jQuery.error('table issue');
			dtTable =  this.element.find('table.tablesorter:first').tableActions('getDataTable');

			if(selected === 'current' || selected === '')
			{
				if(typeof tr === 'undefined')
					tr = this.element.find('.gradeX');
				else
					tr = tr[0];
			}
			else if(selected === 'first')
			{
				if(typeof tr === 'undefined')
					tr = this.element.find('tr:last')[0];
				else
					tr = tr[0];
			}
			else if(selected === 'last')
			{
				if(typeof tr === 'undefined')
					tr = this.element.find('tr:last')[0];
				else
					tr = tr[0];
			}
			else if(selected === 'next')
			{
				if(typeof tr === 'undefined')
					tr = this.element.find('.gradeX')[0];
				else
					tr = tr[0];
				tr = dtTable.fnGetAdjacentTr(tr);

				if($(tr).length > 0 && !(this.element.find('.gradeX').next().length > 0) )
					dtTable.fnPageChange( 'next');
				else if(!($(tr).length > 0))
					return -1;
			}
			else if(selected === 'prev')
			{
				if(typeof tr === 'undefined')
					tr = this.element.find('.gradeX')[0];
				else
					tr = tr[0];
				tr = dtTable.fnGetAdjacentTr(tr,false);
				if($(tr).length > 0 && !(this.element.find('.gradeX').prev().length > 0) )
					dtTable.fnPageChange( 'previous');
				else if(!($(tr).length > 0))
					dtTable.tr_effects(tr);
			}
			return $(tr);
		},

		findParentFromRunningCheckTR : function(selected, tr)
		{
			var dtTable = this.element.find('table.tablesorter:first').tableActions('getDataTable','t');
			if(typeof dtTable === 'undefined')
				jQuery.error('table issue');

			if(selected === 'current' || selected === '')
			{
				if(typeof tr === 'undefined')
					tr = this.element.find('.gradeX')[0];
				else
					tr = tr[0];
			}
			else if(selected === 'last')
			{
				dtTable.fnPageChange( 'last');
				if(typeof tr === 'undefined')
					tr = this.element.find('tr:last')[0];
				else
					tr = tr[0];
			}
			else if(selected === 'next')
			{
				if(typeof tr === 'undefined')
					tr = this.element.find('.gradeX')[0];
				else
					tr = tr[0];
				tr = dtTable.fnGetAdjacentTr(tr);
			}
			else if(selected === 'prev')
			{
				if(typeof tr === 'undefined')
					tr = this.element.find('.gradeX')[0];
				else
					tr = tr[0];
				tr = dtTable.fnGetAdjacentTr(tr,false);
			}
			return $(tr);
			
		},

		findParentFromRunningCheckTRByID : function(id)
		{
			tr = this.element.find('tr').filter(function(i,v){
						return ($(this).find('[id='+id+']').length > 0);
					});
			return $(tr);
		}
	})	);

	$.extend( $.ui.formActionsSingle, {
		version: "1.0",
		class_name : 'FormActionsSameLayer'
	});

})(jQuery);
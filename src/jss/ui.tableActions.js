(function($,undefined)
{
	$.widget('ui.tableActions',{
		options : {
			"new" : true,
			first_field : '<input type="checkbox" />',
			edit_field : '<span class="editme" />',
			hover_class : 'gradeA',
			tableClass : {
				_default : 'tablesorter',
				_tabsEdit : 'tabViewer',
				_minimalView : 'minimal'
			},
			trClass : {
				_default : ['gradeX','gradeC','gradeA','gradeU']
			},
			tdClass :{
				edit : 'edit-event',
				view : 'view-event'
			} 
		},

		wrapper_options :{
			bJQueryUI: true,
			bProcessing:true,
			aaSorting:[[1,'asc']],
			sDom: '<"H"lfr><"fixed_height"t><"F"ip>',
			sSearch: "", "bRegex": true, "bSmart": true,
			sPaginationType: "full_numbers",
			iDisplayLength:10,
			aLengthMenu: [[5, 10, 20], [5, 10, 20]],
			aoColumnDefs: [
												{
													sClass:'center',
													aTargets:[0]
												},
												{
													bSortable:false,
													aTargets:[0]
												},
												{
													bSearchable:false,
													aTargets:[0]
												}
											]
		},

		_create : function(){this.init(true);},

		_init : function(){this.init(false);},

		// setter
		setDataTable : function(wrapper_options)
		{
			var self = this,
				options = this.options,
				wrapper_options = $.extend({},this.wrapper_options);

			if(this.element.hasClass(options.tableClass._minimalView))
			{
				wrapper_options = $.extend(true,wrapper_options,{
					sDom : '<"fixed_height"t><"F"ip>',
					iDisplayLength : 5,
					bProcessing : false,
				})
			}
			this.dataTable = this.element.dataTable(wrapper_options);
		},

		// getter
		getDataTable : function(){return this.dataTable;},

		getFormClassStatus : function()
		{
			var tableClass = this.options.tableClass;
			if(this.element.hasClass(tableClass._tabsEdit))
				return tableClass._tabsEdit;
			else
				return tableClass._default;
		},

		// basic implementation
		init : function(_init)
		{
			var self = this,
				options = this.options,
				wrapper_options = this.wrapper_options;

			this.form = this.element.closest('form[name]');
			if(! (this.element.is('table') && this.element.hasClass(options.tableClass._default)) )
			{
				return $.each(this.element.find('table.'+options.tableClass._default),
					function(i,v){return $(this).tableActions(options,wrapper_options);}
				);
			}

			if(_init)
			{
				var searchedClass = this.getFormClassStatus(),
					th = this.element.find('th'),
					td = this.element.find('td');
			
				wrapper_options.fnDrawCallback = function()
				{
					var nTD = self.element.find('td');
					if(self.options.edit_field != null && nTD.length == 1)
						nTD.eq(0).attr('colspan',self.element.find('th').length);
				};

				if(options.edit_field != null)
				{
					wrapper_options.fnHeaderCallback = function( nHead, aasData, iStart, iEnd, aiDisplay)
					{
						var _th = $(nHead).find('th');
						var secondRun = _th.filter(function(i,v){
							return ($(this).hasClass(searchedClass))
						});
						if(secondRun.length == 0)
						{
							_th.eq(0).empty();
							var new_th = _th.eq(0).clone().addClass(searchedClass);
							$(nHead).append(new_th);
						}
					};

					wrapper_options.fnRowCallback = function(nRow, aData, iDisplayIndex,iDisplayIndexFull)
					{
						var row_td = $(nRow).find('td');
						var first_td = row_td.eq(0),
							last_td = row_td.last();
						var first_val = first_td.html(),
							last_value = last_td.html();
						if(first_val != null && first_val.match(/<.*?>/g) == null)
						{
							// td contents
							first_td.empty();
							var tag = $(options.first_field).attr('id',first_val);
							first_td.append(tag);
							// td events
							self.createEvent(nRow);
							self.createEvent(tag);
							if(! last_td.hasClass(searchedClass))
							{
								var tag = $('<td/>'),
									edit_tag = $(options.edit_field);
								tag.append(edit_tag).addClass(searchedClass);
								$(nRow).append(tag);
								self.createEvent(tag,first_val);
							}
						}
						return nRow;
					};
				}
				this.setDataTable(wrapper_options);
			}
			return this;
		},

		addRow : function(nData,display)
		{
			if(display === true)
				var args = this.getDataTable().fnAddDataAndDisplay(nData);
			else
				var args = this.getDataTable().fnAddData(nData,false);

			return args;
		},

		addMultiRow : function(nData,selected)
		{
			var self = this,
				selected = selected || 0,
				skipped = null;
			
			$.each(nData,function(i,v)
			{
				if(selected != v[0])
					self.addRow(v,false);
				else
					skipped = v;
			});

			if(skipped != null)
			{
				var args = this.addRow(skipped,true);
				this.tr_effects($(args.nTr),'selected');
			}
			else
				this.getDataTable().fnDraw()
		},
		
		createEvent : function(elem,val)
		{
			var self = this,
				element = $(elem),
				options = this.options;
				searchedClass = self.getFormClassStatus();

			if(this.form.length == 0)
				return false;
			if(element.is('tr') || element.is('td') || element.is(':checkbox') )
			{
				if(element.is('tr'))
				{
					element.unbind('mouseover');
					element.unbind('mouseout');
					element.bind(
					{
						'mouseover' : function(event){
							element.addClass(options.hover_class);
						},
						'mouseout' : function(event){
							element.removeClass(options.hover_class);
						}
					});
				}
				else if(element.is('td'))
				{
					element.unbind('click');
					var data = {'id':val,'_perm':0};
					if(searchedClass === options.tableClass._tabsEdit)
						data._openMethod = 1;

					var action = 'update-form';
					element.bind(
					{
						'click' : function(event)
						{
							var method = self.form.attr('name'),
								tr = element.parents('tr:eq(0)');
							var registry = $.oRegistry('check',{'method':method,'action':action,'type':'actions'},true);
							if(registry)
								return false;
							self.tr_effects(tr,'selected');
							/*  
							 * check the position of selected row and send
							 * request code for previous and next button if are
							 * needed code = 0 (none) code = 1 (prev) code = 2
							 * (next) code = 3 (next & prev)
							 */ 
							 if(action === 'update-form')
							 {
								data._perm = 0;
							 	var next = $(self.dataTable.fnGetAdjacentTr(tr[0]));
								var prev = $(self.dataTable.fnGetAdjacentTr(tr[0],false));
								if(next != null && next.length)
									data._perm += 1;
								if(prev != null && prev.length)
									data._perm += 2;
							 }
							 var args = {
									'action':[['project','servlets','forms'],method,'_runEvent'],
									'args':{
										'action':action,
										'data' : data
									}
								},
								//clear previous opening dialog
								json_args = {
									'beforeSend' : function()
									{
										$.myDialog.closeChildrens(self.form);
									}
								};
							if(self.form.hasClass('singlePage'))
							{
								json_args.success = function(){
									var data = arguments[0];
									//reset form
									if(typeof data['reset'] != 'undefined' && data['reset'] === true)
										self.form.trigger('reset');
									//populate form
									if(typeof data['populate'] != 'undefined' && data['populate'] === true && typeof data['_htmlTag'] != 'undefined')
										$.populate(self.form,data['_htmlTag']);
									if(typeof data.oRegistry != 'undefined' &&  !(data.oRegistry.method == null))
										var registry = $.oRegistry('clear',{'method':data.oRegistry.method,'action':data.oRegistry.action,'type':'actions'});
								};
							}
							$.oLayout(args).sendCall(json_args);
						}
					});
				}
				else if(element.is(':checkbox'))
				{
					element.unbind('click');
					element.bind(
					{
						'click' : function(event)
						{
							var tr = element.closest('tr');
							if($(this).is(':checked'))
								self.tr_effects(tr,'multi-selected');
							else
								self.tr_effects(tr,'clear');
							
						}
					});
				}
			}
		},

		tr_effects : function(tr,action)
		{
			var self = this,
				options = this.options;
			if(action === 'selected')
			{
				var tbody = tr.parents('tbody');
				tbody.children('tr.'+options.trClass._default[0]).removeClass(options.trClass._default[0]);
				tr.addClass(options.trClass._default[0]);
			}
			else if(action === 'multi-selected')
			{
				tr.addClass(options.trClass._default[0]);
			}
			else if(action === 'clear')
			{
				if(tr.hasClass(options.trClass._default[0]))
					tr.removeClass(options.trClass._default[0]);
				tr.contents().find(':checkbox').removeAttr('checked');
			}
		},

		
		//actions
		deleteRowByCheckbox : function(checkId)
		{
			var self = this,
				options = this.options;
			if(options.first_field != null)
			{
				var tag = $(options.first_field),
					checkboxes = this.element.find('tbody :checkbox[id='+checkId+']');
				$.each(checkboxes,function(i,v){
					self.dataTable.fnDeleteRow(i);
				});
			}
		},

		updateRowData : function(checkId, rowData)
		{
			var self = this,
				options = this.options;
			if(options.first_field != null)
			{
				var tag = $(options.first_field),
					checkboxes = this.element.find('tbody :checkbox[id='+checkId+']');
				$.each(checkboxes,function(i,v){
					self.dataTable.fnRowUpdate(rowData,i);
				});
			}
		}
		
	});

	$.extend( $.ui.tableActions, {
		version: "1.0",
		class_name : 'oTableAction'
	});

})(jQuery);
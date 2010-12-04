(function($)
{
	$.oJSON = function(options)
	{
		options = $.extend(true,
		{
			namespace:[],
			'class':null,
			method:null,
			args:{}
		}
		,options);

		ajax_options = $.extend(true,
			{
				url:'/request_handler.php',
				type: "POST",
				dataType: 'json',
				contentType: "application/json; charset=utf-8",
				success:null,
				complete:null
			},
			options);
		delete ajax_options['namespace'];
		delete ajax_options['class'];
		delete ajax_options['method'];
		delete ajax_options['args'];

		var data =
		{
			'json':
			{
				namespace:options.namespace.join('\\\\'),
				'class':options['class'],
				method:options.method,
				args:options.args
			}
		};

		ajax_options.data = $.toJSON(data);

		//before send function
		ajax_options.beforeSend = function(XMLHttpRequest)
		{
			if((typeof options.beforeSend) === 'function')
				options.beforeSend(XMLHttpRequest);
		},
		//on success function
		ajax_options.success = function(data, textStatus, XMLHttpRequest)
		{
			if(!(data === null))
			{
//				if(data.exception)
//					$.myDialog('ErrorBox',{'html' : data.message});
//				else if((typeof options.success) === 'function')
				if((typeof options.success) === 'function')
					options.success(data, textStatus, XMLHttpRequest);
			}
		},
		//on error function
		ajax_options['error'] = function(XMLHttpRequest)
		{
			if((typeof options['error']) === 'function')
				options['error'](XMLHttpRequest);
		},
		//on complete function
		ajax_options.complete = function(XMLHttpRequest,textStatus)
		{
			if(!(data === null))
			{
				if(data.exception)
					alert(data.message);
				else if((typeof options.complete) === 'function')
				{
					options.complete(data, textStatus, XMLHttpRequest);
				}
			}
		};

		return $.ajax(ajax_options);

	};
})(jQuery);
(function($)
{
	var _oLang = {

				class_name : 'oLanguage',
				init : function(){return this;},
				getLang : function(){return this.object;},
				setLang : function(object){this.object = $.extend(true,{},object);},

		};

	$.oLanguage = function()
	{
		var fargs = arguments;

		if( _oLang[fargs[0]] && typeof _oLang[fargs[0]] === 'function')
			return _oLang[fargs[0]].apply(this,Array.prototype.slice.call( fargs, 1 ));
		else if (typeof fargs[0] === 'object' || ! fargs[0])
			return _oLang.init();
		else
			$.error( 'Method ' +  method + ' does not exist on oLanguage' );
	};
	
})(jQuery);
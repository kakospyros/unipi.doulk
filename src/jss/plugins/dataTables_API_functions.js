	$.fn.dataTableExt.oApi.fnAddDataAndDisplay = function ( oSettings, aData )
	{
		/* Add the data */
		var iAdded = this.oApi._fnAddData( oSettings, aData );
		var nAdded = oSettings.aoData[ iAdded ].nTr;
		
		/* Need to re-filter and re-sort the table to get positioning correct, not perfect
		 * as this will actually redraw the table on screen, but the update should be so fast (and
		 * possibly not alter what is already on display) that the user will not notice
		 */
		this.oApi._fnReDraw( oSettings );
		
		/* Find it's position in the table */
		var iPos = -1;
		for( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ )
		{
			if( oSettings.aoData[ oSettings.aiDisplay[i] ].nTr == nAdded )
			{
				iPos = i;
				break;
			}
		}
		
		/* Get starting point, taking account of paging */
		if( iPos >= 0 )
		{
			oSettings._iDisplayStart = ( Math.floor(i / oSettings._iDisplayLength) ) * oSettings._iDisplayLength;
			this.oApi._fnCalculateEnd( oSettings );
		}
		
		this.oApi._fnDraw( oSettings );
		return {
			"nTr": nAdded,
			"iPos": iAdded
		};
	};

	$.fn.dataTableExt.oApi.fnDataUpdate = function ( oSettings, nRowObject, iRowIndex )
	{
		var dataRow = oSettings.aoData[iRowIndex]._aData;
		$(nRowObject).find("TD").each( function(i) {
			dataRow[i] = $(this).html();
		} );
	};

	$.fn.dataTableExt.oApi.fnRowUpdate = function ( oSettings, dataObject, iRowIndex )
	{
		var nRow = oSettings.aoData[iRowIndex].nTr;
		var nData = oSettings.aoData[iRowIndex]._aData;
		$.each($(nRow).find('td'),function(i){
			$(this).html(dataObject[i]);
			nData[i] = dataObject[i];
		});
		this.oApi._fnReDraw(oSettings );
	};

	$.fn.dataTableExt.oApi.fnGetAdjacentTr  = function ( oSettings, nTr, bNext )
	{
		/* Find the node's position in the aoData store */
		var iCurrent = oSettings.oApi._fnNodeToDataIndex( oSettings, nTr );
		
		/* Convert that to a position in the display array */
		var iDisplayIndex = $.inArray( iCurrent, oSettings.aiDisplay );
		if ( iDisplayIndex == -1 )
		{
			/* Not in the current display */
			return null;
		}
		
		/* Move along the display array as needed */
		iDisplayIndex += (typeof bNext=='undefined' || bNext) ? 1 : -1;
		
		/* Check that it within bounds */
		if ( iDisplayIndex < 0 || iDisplayIndex >= oSettings.aiDisplay.length )
		{
			/* There is no next/previous element */
			return null;
		}
		
		/* Return the target node from the aoData store */
		return oSettings.aoData[ oSettings.aiDisplay[ iDisplayIndex ] ].nTr;
	};

	$.fn.dataTableExt.oApi.fnStandingRedraw = function(oSettings) {
		//redraw to account for filtering and sorting
		// concept here is that (for client side) there is a row got inserted at the end (for an add) 
		// or when a record was modified it could be in the middle of the table
		// that is probably not supposed to be there - due to filtering / sorting
		// so we need to re process filtering and sorting
		// BUT - if it is server side - then this should be handled by the server - so skip this step
		if(oSettings.oFeatures.bServerSide === false){
			var before = oSettings._iDisplayStart;
			oSettings.oApi._fnReDraw(oSettings);
			//iDisplayStart has been reset to zero - so lets change it back
			oSettings._iDisplayStart = before;
			oSettings.oApi._fnCalculateEnd(oSettings);
		}
		
		//draw the 'current' page
		oSettings.oApi._fnDraw(oSettings);
	};

	$.fn.dataTableExt.oApi.fnDisplayRow = function ( oSettings, nRow )
	{
		var iPos = -1;
		for( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ )
		{
			if( oSettings.aoData[ oSettings.aiDisplay[i] ].nTr == nRow )
			{
				iPos = i;
				break;
			}
		}
		
		if( iPos >= 0 )
		{
			oSettings._iDisplayStart = ( Math.floor(i / oSettings._iDisplayLength) ) * oSettings._iDisplayLength;
			this.oApi._fnCalculateEnd( oSettings );
		}
		
		this.oApi._fnDraw( oSettings );
	};

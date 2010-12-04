<form name="User_Address" class="validate singlePage" action="POST">
	<div class="referenceFrame">
		<div class="referenceLabel">
			<h1>Customer Accounts</h1>
			<h2>please enter the required customer account information</h2>
		</div>
		<p class="referenceValue population" name="name">&nbsp;</p>
	</div>
	<div class="inputFrame inputFrameShadow popupFrame">
		<h1>Customer Address</h1>
		<h2>customer address information</h2>
		<div class="hr"></div>
		<fieldset>
			<label class="g100" for="name">Street:<br /><span class="sublabel">street name</span></label>
			<input type="text" name="street" class="wptext g100" required="required" minlength="3" />
			<label class="g100" for="number">Number:<br /><span class="sublabel">street number</span></label>
			<input type="text" name="number" class="wptext g100" required="required" minlength="1" />
		</fieldset>
		<fieldset>
			<label class="g100" for="area">City:<br /><span class="sublabel">city</span></label>
			<input type="text"name="area" class="wptext g100" required="required" />
			<label class="g100" for="post_code">Post Code:<br /><span class="sublabel">post code</span></label>
			<input type="text" name="post_code" class="wptext g100" required="required" />
		</fieldset>
	</div>
	<?= $oRecords; ?>
	<?= $_commands; ?>
</form>
<form name="User_Contact" class="validate singlePage" action="POST">
	<div class="referenceFrame">
		<div class="referenceLabel">
			<h1>Customer Accounts</h1>
			<h2>customer account information</h2>
		</div>
		<p class="referenceValue population" name="name">&nbsp;</p>
	</div>
	<div class="inputFrame inputFrameShadow popupFrame">
		<h1>Customer Contact</h1>
		<h2>please enter the required customer contact information</h2>
		<div class="hr"></div>
		<fieldset>
			<label class="g100" for="cname">Name:<br /><span class="sublabel">contact name</span></label>
			<input type="text" name="cname" class="wptext g100" required="required" minlength="3" options='{"position":["top","center"]}' />
			<label class="g100" for="position">Position:<br /><span class="sublabel">contact title</span></label>
			<input type="text" name="position" class="wptext g100" required="required" minlength="1" />
		</fieldset>
		<fieldset>
			<label class="g100" for="telephone">Telephone:<br /><span class="sublabel">telephone</span></label>
			<input type="text"name="telephone" class="wptext g100" required="required" options='{"position":["top","center"]}'/>
			<label class="g100" for="fax">Fax:<br /><span class="sublabel">fax</span></label>
			<input type="text" name="fax" class="wptext g100" />
		</fieldset>
		<fieldset>
			<label class="g100" for="mobile">Mobile:<br /><span class="sublabel">mobile</span></label>
			<input type="text" name="mobile" class="wptext g100" required="required" />
			<label class="g100" for="email">E-mail:<br /><span class="sublabel">email</span></label>
			<input type="text" name="email" class="wptext g100" required="required" />
		</fieldset>
	</div>
	<?= $oRecords; ?>
	<?= $_commands; ?>
</form>
<form name="Users" class="validate" action="POST">
	<div class="inputFrame inputFrameShadow popupFrame">
		<h1>User Accounts</h1>
		<h2>please enter the required user account information</h2>
		<div class="hr"></div>
		<fieldset>
			<label class="g100" for="name">S.S.N:<br /><span class="sublabel">user social security number</span></label>
			<input type="text" name="amka" class="wptext g244" required="required" minlength="11" />
		</fieldset>
		<fieldset>
			<label class="g100" for="name">Surname:<br /><span class="sublabel">user surname</span></label>
			<input type="text" name="surname" class="wptext g244" required="required" minlength="3" /><div class="vr"></div>
			<label class="g100" for="name">Given Name:<br /><span class="sublabel">user given-name</span></label>
			<input type="text" name="given_name" class="wptext g244" required="required" minlength="3" />
		</fieldset>
		<fieldset>
			<label class="g100" for="passwd">Password:<br /><span class="sublabel">user password</span></label>
			<input type="password" name="passwd" class="wptext g244" required="required" minlength="6" /><div class="vr"></div>
			<label class="g100" for="passwd_confirm">Confirm:<br /><span class="sublabel">cornfirm user password</span></label>
			<input type="password" name="passwd_confirm" class="wptext g244" required="required" minlength="6" data-equals="passwd" />
		</fieldset>
<?php if(!$noCategory){ ?>
		<fieldset>
			<label class="g100" for="category">Category:<br /><span class="sublabel">select category</span></label>
			<select class="wpselect g254 togglingAjax" name="category"></select>
		</fieldset>
<?php } ?>
	</div>
	<div class="toggle_category"><?= $doctor_info; ?></div>
	<?= $_commands; ?>
</form>
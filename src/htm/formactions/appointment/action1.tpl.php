<form name="Appointment" action="POST">
	<div class="referenceFrame">
		<div class="referenceLabel">
			<h1>Reference Number</h1>
			<h2>automatically generated</h2>
		</div>
		<p class="referenceValue population" name="reference">&nbsp;</p>
	</div>
	<div class="inputFrame inputFrameShadow popupFrame">
		<h1>Appointment</h1>
		<h2>Information for appointment</h2>
		<div class="hr"></div>
		<fieldset>
			<label class="g100" for="patient">Patient:<br /><span class="sublabel">patient name</span></label>
			<input type="text" class="wptextInfo g244" name="patient" readonly="readonly"/>
		</fieldset>
		<fieldset>
			<label class="g100" for="schedule_time">Date:<br /><span class="sublabel">appoitment date</span></label>
			<input type="text" class="wptextInfo g244" name="schedule_time" readonly="readonly"/>
		</fieldset>
	</div>
	<?= $_commands; ?>
</form>
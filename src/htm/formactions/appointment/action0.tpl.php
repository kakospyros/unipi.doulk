<form name="Appointment" class="validate" action="POST">
	<div class="referenceFrame">
		<div class="referenceLabel">
			<h1>Reference Number</h1>
			<h2>automatically generated</h2>
		</div>
		<p class="referenceValue population" name="reference">&nbsp;</p>
	</div>
	<div class="inputFrame inputFrameShadow popupFrame">
		<h1>Appointment</h1>
		<h2>please enter the required information for appointment</h2>
		<div class="hr"></div>
		<fieldset>
			<label class="g100" for="specialty">Specialty:<br /><span class="sublabel">select doctor specialty</span></label>
			<select class="wpselect g254 fetchDoctor" name="specialty" required="required" ></select>
		</fieldset>
		<fieldset>
			<label class="g100" for="hours">Hours:<br /><span class="sublabel">select doctor visiting hour</span></label>
			<select class="wpselect g254 fetchDoctor" name="hours" required="required" ></select>
		</fieldset>
		<fieldset>
			<label class="g100" for="parent_id">Doctor List:<br /><span class="sublabel">select doctor</span></label>
			<select class="wpselect g254 printDoctor" name="parent_id" required="required" ></select>
		</fieldset>
		<fieldset>
			<label class="g100" for="schedule_time">Date:<br /><span class="sublabel">select appoitment date</span></label>
			<input type="text" class="wptext g244 datePicker doctorSchedule" id="schedule_time" name="schedule_time" required="required" />
		</fieldset>
	</div>
	<?= $_commands; ?>
</form>
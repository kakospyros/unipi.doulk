<?php
use phpJar\utils as _utils;
use project\scheme as _pscheme;
?>
<table cellpadding="0" cellspacing="0" border="0" class="Appointment:Records display tablesorter">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>Reference</th>
			<th>Doctor</th>
			<th>Schedule Time</th>
			<th>Modify at</th>
		</tr>
	</thead>

	<tbody>
<?php
	if(!empty($_oLoop)):
		$oUser = new _pscheme\Users();
		foreach ($_oLoop as $_loop):
		$oDoctor = $oUser->_selectFilterRecordsingle(sprintf(' AND t.id = %d',$_loop->parent_id));
?>
		<tr class="<?= $class; ?>">
			<td><?= $_loop->id; ?></td>
			<td><?= $_loop->reference; ?></td>
			<td><?= sprintf('%s %s',$oDoctor->surname,$oDoctor->given_name); ?></td>
			<td><?= _utils\DT::_getLocalString($_loop->schedule_time); ?></td>
			<td><?= _utils\DT::_getLocalString($_loop->modify_time); ?></td>
		</tr>
<?php
		endforeach;//master foreach
	endif;//if empty
?>
	</tbody>
</table>
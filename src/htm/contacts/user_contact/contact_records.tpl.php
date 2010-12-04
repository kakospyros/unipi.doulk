<?php
use phpJar\utils as _utils;
?>
<table cellpadding="0" cellspacing="0" border="0" class="User_Contact:Records display tablesorter minimal">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>Name</th>
			<th>Position</th>
			<th>Telephone</th>
			<th>Fax</th>
			<th>Mobile</th>
			<th>E-mail</th>
			<th>Modify at</th>
		</tr>
	</thead>

	<tbody>
<?php
	if(!empty($_oLoop)):
		foreach ($_oLoop as $_loop):
?>
		<tr class="<?= $class; ?>">
			<td><?= $_loop->id; ?></td>
			<td><?= $_loop->name; ?></td>
			<td><?= $_loop->position; ?></td>
			<td><?= $_loop->telephone; ?></td>
			<td><?= $_loop->fax; ?></td>
			<td><?= $_loop->mobile; ?></td>
			<td><?= $_loop->email; ?></td>
			<td><?= _utils\DT::_getLocalString($_loop->modify_time); ?></td>
		</tr>
<?php
		endforeach;//master foreach
	endif;//if empty
?>
	</tbody>
</table>
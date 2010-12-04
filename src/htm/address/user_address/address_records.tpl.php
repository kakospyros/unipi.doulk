<?php
use phpJar\utils as _utils;
use project\scheme as _pscheme;
?>
<table cellpadding="0" cellspacing="0" border="0" class="User_Address:Records display tablesorter minimal">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>Street</th>
			<th>Area</th>
			<th>Postal Code</th>
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
			<td><?php printf("%s (%s)",$_loop->street,$_loop->number); ?></td>
			<td><?= $_loop->area; ?></td>
			<td><?= $_loop->post_code; ?></td>
			<td><?= _utils\DT::_getLocalString($_loop->modify_time); ?></td>
		</tr>
<?php
		endforeach;//master foreach
	endif;//if empty
?>
	</tbody>
</table>
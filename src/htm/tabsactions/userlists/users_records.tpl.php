<?php
use phpJar\utils as _utils;
?>
<table cellpadding="0" cellspacing="0" border="0" class="Users:Records display tablesorter tabViewer">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>Surname</th>
			<th>Given Name</th>
			<th>S.S.N</th>
			<th>Discription</th>
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
			<td><?= $_loop->surname; ?></td>
			<td><?= $_loop->given_name; ?></td>
			<td><?= $_loop->amka; ?></td>
			<td><?= $_loop->description; ?></td>
			<td><?= _utils\DT::_getLocalString($_loop->modify_time); ?></td>
		</tr>
<?php
		endforeach;//master foreach
	endif;//if empty
?>
	</tbody>
</table>
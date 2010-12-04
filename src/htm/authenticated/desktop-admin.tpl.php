<?php
	use project\servlets as _pservlets;
	$user_info = _pservlets\Authenticated::_getLoginInfo();
	$project_info = _pservlets\Project_Info::_getInfo();
?>
<div id="topspacer"></div>
<div id="eprescribe"><img src="/src/img/logo1.png" width="159" height="64" alt="e-prescribing icon" /></div>
<div id="topbar">
	<span class="info"><?= sprintf('%s %s: %s',$project_info['name'],$language_common->titles->version,$project_info['version']); ?></span>
	<span class="info"><?= date('l, d F Y'); ?></span>
</div>
<div id="bottomspacer">
	<a id="logoutButton" class="logout" href="#"></a>
	<p class="userinfo"><?= sprintf('%s %s',$user_info['surname'],$user_info['given_name']); ?><br /><?= sprintf('%s: %s',$language->fields->amka,$user_info['amka']); ?></p>
	<img class="user" src="/src/img/user.png" width="22" height="32" alt="user icon" />
</div>
<div id="menu">
	<ul class="menu-event">
		<li class="desktop-menu"><a name="account" class="link ca" href="#" title="<?= $language->menu->titles->account; ?>"><?= $language->menu->fields->user; ?><em class="blue"><?= $language->menu->fields->accounts; ?></em><span class="subtitle"><?= $language->menu->text->account; ?></span></a></li>
		<li class="desktop-menu"><a name="admin" class="link as" href="#" title="<?= $language->menu->titles->admin; ?>"><?= $language->menu->fields->admin; ?><em><?= $language->menu->fields->setup; ?></em><span class="subtitle"><?= $language->menu->text->admin; ?></span></a></li>
	</ul>
</div>
<div id="maincontainer">&nbsp;</div>
<div id="taskbar"><span class="info">&copy;&nbsp;http://www.techmind.gr&nbsp;all rights reserved</span></div>
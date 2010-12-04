<?php
/**
 *
 * @var unknown_type
 */
$_Language->commons = new \stdClass();
$_Language->commons->titles = new \stdClass();
$_Language->commons->titles->aa = 'A/A';
$_Language->commons->titles->active = 'Active';
$_Language->commons->titles->emailAddress = 'e-mail';
$_Language->commons->titles->lastLogin = 'Last login';
$_Language->commons->titles->memberOf = 'Member of';
$_Language->commons->titles->username = 'Username';
$_Language->commons->ZebraCaption = array(
	0 => array('value'=>'Refresh','title'=>'refresh'),
);
/**
 *
 * @var unknown_type
 */
$_Language->oGeneric = new \stdClass();
//generic buttons
$_Language->oGeneric->button = new \stdClass();
$_Language->oGeneric->button->cancel = 'cancel';
$_Language->oGeneric->button->no = 'no';
$_Language->oGeneric->button->yes = 'yes';
//generic error
$_Language->oGeneric->error = new \stdClass();
$_Language->oGeneric->error->class = new \stdClass();
$_Language->oGeneric->error->class->property = "Class '%s' does not have property '%s'";
$_Language->oGeneric->error->class->method = "Class '%s' does not have method '%s'";
$_Language->oGeneric->error->db = new \stdClass();
$_Language->oGeneric->error->db->connect = "Could not connect to '%s' server";
$_Language->oGeneric->error->db->driver_missing = "Database Driver missing for: %s";
$_Language->oGeneric->error->db->driver_method = "Database Driver does not have method: %s";
$_Language->oGeneric->error->db->tableNotExists = "table %s does not exists on database";
$_Language->oGeneric->error->dt->date = "Invalid date time value '%s'";
$_Language->oGeneric->error->dt->dateFormat = "Validation Format Date type";
$_Language->oGeneric->error->dt->dateType = "Validation Date type";
$_Language->oGeneric->error->form->token = "Form Token";
$_Language->oGeneric->error->generic = new \stdClass();
$_Language->oGeneric->error->generic = "An error has been occured !";
$_Language->oGeneric->error->property = new \stdClass();
$_Language->oGeneric->error->language->property = 'Language Property \'%s\' does not exists';
$_Language->oGeneric->error->registry = new \stdClass();
$_Language->oGeneric->error->registry->offSet = "'%s' is not been stored in Registry";
$_Language->oGeneric->error->scheme = new \stdClass();
$_Language->oGeneric->error->scheme->date = "invalid date : %s For field => %s on table => %";
$_Language->oGeneric->error->scheme->propertyNotExists = "class '%s' has no member '%s'";
$_Language->oGeneric->error->scheme->propertyTable = 'this attribute does not exists on this table';
$_Language->oGeneric->error->scheme->timestamp = "invalid timestamp : %s For field => %s, on table => %s";
$_Language->oGeneric->error->scheme->query = "Query [%s]: %s";
//ui
$_Language->oGeneric->ui = new \stdClass();
//ui-error
$_Language->oGeneric->ui->error = new \stdClass();
$_Language->oGeneric->ui->error->title = 'System Error';
$_Language->oGeneric->ui->error->message = 'An error has occured !';
$_Language->oGeneric->ui->error->noRecordSelected = 'You have not selected any record for delete !';
//ui-confirmation
$_Language->oGeneric->ui->confirmation = new \stdClass();
$_Language->oGeneric->ui->confirmation->deleteConfirm = 'Are you sure, you want to delete selected record(s) ?';
$_Language->oGeneric->ui->confirmation->title = 'Please Confirm';
$_Language->oGeneric->ui->confirmation->message = 'Are you sure, you want to proceed with this action ?';
//ui-warning
$_Language->oGeneric->ui->warning = new \stdClass();
$_Language->oGeneric->ui->warning->title = 'Attention';
$_Language->oGeneric->ui->warning->message = 'Attention';
//ui-notification
$_Language->oGeneric->ui->notification = new \stdClass();
$_Language->oGeneric->ui->notification->success = new \stdClass();
$_Language->oGeneric->ui->notification->success->title = 'Success';
$_Language->oGeneric->ui->notification->success->delete = 'You have successfully delete a Record !';
$_Language->oGeneric->ui->notification->success->delete_multi = 'You have successfully delete %d Record(s) !';
$_Language->oGeneric->ui->notification->success->new = 'You have successfully add a new Record !';
$_Language->oGeneric->ui->notification->success->update = 'You have successfully update a Record !';

$_Language->oGeneric->popup = new \stdClass();
$_Language->oGeneric->popup->title = 'Title';

/**
 *
 * Enter description here ...
 * @var unknown_type
 */
$_Language->desktop = new \stdClass();
$_Language->desktop->fields = new \stdClass();
$_Language->desktop->menu = new \stdClass();
$_Language->desktop->menu->fields = new \stdClass();
$_Language->desktop->menu->fields->accounts = 'Accounts';
$_Language->desktop->menu->fields->admin = 'Admin';
$_Language->desktop->menu->fields->billing = 'Billing';
$_Language->desktop->menu->fields->campaign = 'Campaign';
$_Language->desktop->menu->fields->channel = 'Channel';
$_Language->desktop->menu->fields->customer = 'Customer';
$_Language->desktop->menu->fields->control = 'Control';
$_Language->desktop->menu->fields->configuration = 'Configuration';
$_Language->desktop->menu->fields->planner = 'Planner';
$_Language->desktop->menu->fields->dictionery = 'Dictionary';
$_Language->desktop->menu->fields->setup = 'Setup';
$_Language->desktop->menu->fields->statistics = 'Statistics';
$_Language->desktop->menu->fields->system = 'System';
$_Language->desktop->menu->text = new \stdClass();
$_Language->desktop->menu->text->account = 'edit customer account information';
$_Language->desktop->menu->text->admin = 'full system administration';
$_Language->desktop->menu->text->billing = 'billing plan and configuration';
$_Language->desktop->menu->text->campaign = 'campaign planning tools';
$_Language->desktop->menu->text->configuration = 'search site lists administration';
$_Language->desktop->menu->text->dictionery = 'word pool configuration';
$_Language->desktop->menu->text->system = 'dashboard display of system vitals';
$_Language->desktop->menu->titles = new \stdClass();
$_Language->desktop->menu->titles->account = 'Customer Accounts';
$_Language->desktop->menu->titles->admin = 'Admin Setup';
$_Language->desktop->menu->titles->billing = 'Billing Control';
$_Language->desktop->menu->titles->campaign = 'Campaign Planner';
$_Language->desktop->menu->titles->configuration = 'Channel Configuration';
$_Language->desktop->menu->titles->dictionery = 'Dictionary Setup';
$_Language->desktop->menu->titles->system = 'System Statistics';
/**
 *
 * Enter description here ...
 * @var unknown_type
 */
$_Language->login = new \stdClass();
$_Language->login->error = new \stdClass();
$_Language->login->error->loginAuth = 'Login Error';
$_Language->login->error->passwdAuth = 'Password mismatch';

?>
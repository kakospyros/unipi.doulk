<?php
use phpJar\database as _database;
/**
 *
 * @var unknown_type
 */
$_Language->Appointment = new \stdClass();
$_Language->Appointment->error = new \stdClass();
$_Language->Appointment->error->generic = 'An unknown error has been occured !';
//db
$_Language->Appointment->error->reference[ _database\DB_PDO::_ERROR_DUBLICATE] = 'An appointment with reference equal to \'%s\' already exists on the system !';
//form
$_Language->Appointment->error->validation = new \stdClass();
?>
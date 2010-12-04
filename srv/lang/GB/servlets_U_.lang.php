<?php
use phpJar\database as _database;
/**
 *
 * @var unknown_type
 */
$_Language->Users = new \stdClass();
$_Language->Users->error = new \stdClass();
$_Language->Users->error->generic = 'An unknown error has been occured !';
//db
$_Language->Users->error->name[ _database\DB_PDO::_ERROR_DUBLICATE] = 'The user \'%s\' already exists on the system !';
$_Language->Users->error->reference[ _database\DB_PDO::_ERROR_DUBLICATE] = 'A user with reference equal to \'%s\' already exists on the system !';
//form
$_Language->Users->error->validation = new \stdClass();
?>
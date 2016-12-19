<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(

    //database
    //website
    'website_db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=qlcbd_website;host='.getenv('OPENSHIFT_MYSQL_DB_HOST').';',
        'username'       => getenv('OPENSHIFT_MYSQL_DB_USERNAME'),
        'password'       => getenv('OPENSHIFT_MYSQL_DB_PASSWORD'),
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\' ',
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ),
    ),
    //he thong quan ly
    'qlcbd_db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=qlcbd;host='.getenv('OPENSHIFT_MYSQL_DB_HOST').';',
        'username'       => getenv('OPENSHIFT_MYSQL_DB_USERNAME'),
        'password'       => getenv('OPENSHIFT_MYSQL_DB_PASSWORD'),
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\' ',
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ),
    ),

    //service
    'service_manager' => array(
        //Adaptor
        'factories' => array(
            'Zend\Db\Adapter\Adapter'
            => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),


    )

);

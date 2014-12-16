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
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'      => '10.129.149.127',
                    'port'      => 3306,
                    'user'      => 'myunhcr',
                    'password'  => 'R2DpiGsQudED3z',
                    'dbname'    => 'myunhcr',
                    'driverOptions' => array('1002' => 'SET NAMES utf8')//UTF-8 Support
                )
            )
        )
    ),
);

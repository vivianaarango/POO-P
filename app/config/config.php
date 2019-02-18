<?php


return new \Phalcon\Config(array(
    'database' => array(
         'schema'   => 'public',
         'adapter'  => 'Postgresql',
         'host'     => 'localhost',
         'username' => '',
         'password' => '*',
         'dbname'   => '',
    ),

    'application' => array(
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'modelsDir'      => __DIR__ . '/../../app/models/',
        'modelsCms'      => __DIR__ . '/../../app/models-cms/',
        'pluginsDir'     => __DIR__ . '/../../app/plugins/',
        'libraryDir'     => __DIR__ . '/../../app/library/',
        'constantsDir'   => __DIR__ . '/../../app/library/constants',
        'enumsDir'       => __DIR__ . '/../../app/library/constants/enums',
        'tasksDir'       => __DIR__ . '/../../app/tasks',
        'baseUri' => '/api_devel/',

    ),

));

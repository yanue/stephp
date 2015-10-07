<?php
# ==== database ====
return array(
    'db.defaultSqlDriver' => 'mysql',
    'mysql' => array(
        'host' => '112.124.106.166', //host address
        'port' => 3306, // db server port
        'user' => '', // user name for dbms
        'pass' => '', // pass word for dbms
        'name' => 'test', // default selected db name
        'driver' => 'mysql'
    ),
    'sqlite' => array(
        'file' => WEB_ROOT . '/data/sqlite.db',
        'driver' => 'sqlite3'
    ),
);
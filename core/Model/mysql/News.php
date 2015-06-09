<?php
namespace Brevis\Model\mysql;

use xPDO\xPDO;

class News extends \Brevis\Model\News
{

    public static $metaMap = array (
        'package' => 'Brevis\\Model',
        'version' => '3.0',
        'table' => 'news',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' => 
        array (
            'pagetitle' => '',
            'longtitle' => '',
            'text' => '',
            'alias' => '',
        ),
        'fieldMeta' => 
        array (
            'pagetitle' => 
            array (
                'dbtype' => 'varchar',
                'phptype' => 'string',
                'precision' => '100',
                'null' => true,
                'default' => '',
            ),
            'longtitle' => 
            array (
                'dbtype' => 'varchar',
                'phptype' => 'string',
                'precision' => '255',
                'null' => true,
                'default' => '',
            ),
            'text' => 
            array (
                'dbtype' => 'longtext',
                'phptype' => 'string',
                'null' => true,
                'default' => '',
            ),
            'alias' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => true,
                'default' => '',
            ),
        ),
        'indexes' => 
        array (
            'alias' => 
            array (
                'alias' => 'alias',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'alias' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
        ),
    );
}

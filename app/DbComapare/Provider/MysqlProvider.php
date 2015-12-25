<?php

    namespace App\DbCompare\Provider;


    class MysqlProvider extends AbstractProvider
    {

        public function getTable($db) {
            return 'SELECT cl.TABLE_NAME tables, cl.COLUMN_NAME columns, cl.COLUMN_TYPE dtype, cl.COLUMN_KEY dkey FROM information_schema.columns cl, information_schema.TABLES ss WHERE cl.TABLE_NAME = ss.TABLE_NAME AND cl.TABLE_SCHEMA = "' . $db . '" AND ss.TABLE_TYPE = "BASE TABLE" ORDER BY cl.table_name';
        }
    }
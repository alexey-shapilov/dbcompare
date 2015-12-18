<?php
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller;
    use DB;

    class DbCompareController extends Controller
    {
        public function __construct() {
            $this->middleware('dbcompareconnect');
        }

        public function listTables() {
            $db1 = DB::connection('connection1');
            $db2 = DB::connection('connection2');

            $db1Tables = $db1->select('SHOW TABLES');
            $db2Tables = $db2->select('SHOW TABLES');

            $tables = array();
            foreach ($db1Tables as $table) {
                $tables['db11'][] = $table->{'Tables_in_' . $db1->getDatabaseName()};
            }
            foreach ($db2Tables as $table) {
                $tables['db2'][] = $table->{'Tables_in_' . $db2->getDatabaseName()};
            }

            return $tables;
        }
    }
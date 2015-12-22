<?php
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller;
    use DB;

    class DbCompareController extends Controller
    {
        private $currentCompare;
        private $tables;

        public function __construct() {
            $this->middleware('dbcompareconnect');
        }

        private function unPrefix($str, $prefix) {
            if (!empty($prefix) && strpos($str, $prefix) === 0) {
                $str = substr($str, strlen($prefix));
            }
            return $str;
        }

        private function compare_table($a, $b) {
            $result = 0;
            if ($a == $b) {
                if (count($this->tables[$this->currentCompare[0]][$a]) != count($this->tables[$this->currentCompare[1]][$b])) {
                    $result = 1;
                }
            } elseif ($a > $b) {
                $result = 1;
            } else {
                $result = -1;
            }

            return $result;
        }

        public function listTables() {
            $db1 = DB::connection('connection1');
            $db2 = DB::connection('connection2');

            $db1Name = $db1->getDatabaseName();
            $db2Name = $db2->getDatabaseName();

            $db1Prefix = $db1->getTablePrefix();
            $db2Prefix = $db2->getTablePrefix();

            $db1Tables = $db1->select('SELECT cl.TABLE_NAME tables, cl.COLUMN_NAME columns, cl.COLUMN_TYPE dtype FROM information_schema.columns cl, information_schema.TABLES ss WHERE cl.TABLE_NAME = ss.TABLE_NAME AND cl.TABLE_SCHEMA = "' . $db1Name . '" AND ss.TABLE_TYPE = "BASE TABLE" ORDER BY cl.table_name');
            $db2Tables = $db1->select('SELECT cl.TABLE_NAME tables, cl.COLUMN_NAME columns, cl.COLUMN_TYPE dtype FROM information_schema.columns cl, information_schema.TABLES ss WHERE cl.TABLE_NAME = ss.TABLE_NAME AND cl.TABLE_SCHEMA = "' . $db2Name . '" AND ss.TABLE_TYPE = "BASE TABLE" ORDER BY cl.table_name');

            foreach ($db1Tables as $table) {
                $tName = $this->unPrefix($table->tables, $db1Prefix);
                $this->tables[$db1Name][$tName]['fields'][$table->columns]['type'] = $table->dtype;
            }

            foreach ($db2Tables as $table) {
                $tName = $this->unPrefix($table->tables, $db2Prefix);
                $this->tables[$db2Name][$tName]['fields'][$table->columns]['type'] = $table->dtype;
            }

            $allTables = array_unique(array_merge(array_keys($this->tables[$db1Name]), array_keys($this->tables[$db2Name])));

            $db1Tables = &$this->tables[$db1Name];
            $db2Tables = &$this->tables[$db2Name];

            foreach ($allTables as $table) {
                if (isset($db1Tables[$table]['fields']) && isset($db2Tables[$table]['fields'])) {
                    $allFields = array_unique(array_merge(array_keys($db1Tables[$table]['fields']), array_keys($db2Tables[$table]['fields'])));
                    foreach ($allFields as $field) {
                        if (isset($db1Tables[$table]['fields'][$field]) && isset($db2Tables[$table]['fields'][$field])) {
                            if ($db1Tables[$table]['fields'][$field]['type'] !== $db2Tables[$table]['fields'][$field]['type']) {
                                $db1Tables[$table]['fields'][$field]['status'] = 'change';
                                $db2Tables[$table]['fields'][$field]['status'] = 'change';
                            } else {
                                $db1Tables[$table]['fields'][$field]['status'] = 'equal';
                                $db2Tables[$table]['fields'][$field]['status'] = 'equal';
                            }
                        } else if (!isset($db1Tables[$table]['fields'][$field])) {
                            $db1Tables[$table]['fields'][$field] = $db2Tables[$table]['fields'][$field];
                            $db1Tables[$table]['fields'][$field]['status'] = 'new';
                        } else {
                            $db2Tables[$table]['fields'][$field] = $db1Tables[$table]['fields'][$field];
                            $db2Tables[$table]['fields'][$field]['status'] = 'new';
                        }
                    }
                    ksort($db1Tables[$table]['fields']);
                    ksort($db2Tables[$table]['fields']);
                } else if (!isset($db1Tables[$table])) {
                    $db1Tables[$table] = array(
                        'fields' => $db2Tables[$table]['fields'],
                        'status' => 'new'
                    );
                } else {
                    $db2Tables[$table] = array(
                        'fields' => $db1Tables[$table]['fields'],
                        'status' => 'new'
                    );
                }
            }

            ksort($db1Tables);
            ksort($db2Tables);

            return view('listtables', array('tables' => $this->tables));
        }
    }
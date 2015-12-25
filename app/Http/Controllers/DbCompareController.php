<?php
    namespace App\Http\Controllers;

    use Illuminate\Routing\Controller;
    use DB;
    use DbCompare;

    class DbCompareController extends Controller
    {
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

        public function listTables() {
            $db1 = DB::connection('connection1');
            $db2 = DB::connection('connection2');

            $db1Name = $db1->getDatabaseName();
            $db2Name = $db2->getDatabaseName();

            $db1Tables = $db1->select(DbCompare::driver($db1->getDriverName())->getTable($db1Name));
            $db2Tables = $db2->select(DbCompare::driver($db2->getDriverName())->getTable($db2Name));

            foreach ($db1Tables as $table) {
                $tName = $this->unPrefix($table->tables, $db1->getTablePrefix());
                $this->tables[$db1Name]['tables'][$tName]['fields'][$table->columns]['type'] = $table->dtype;
                $this->tables[$db1Name]['tables'][$tName]['fields'][$table->columns]['key'] = $table->dkey;
            }

            foreach ($db2Tables as $table) {
                $tName = $this->unPrefix($table->tables, $db2->getTablePrefix());
                $this->tables[$db2Name]['tables'][$tName]['fields'][$table->columns]['type'] = $table->dtype;
                $this->tables[$db2Name]['tables'][$tName]['fields'][$table->columns]['key'] = $table->dkey;
            }

            $allTables = array_unique(array_merge(array_keys($this->tables[$db1Name]['tables']), array_keys($this->tables[$db2Name]['tables'])));

            $db1Tables = &$this->tables[$db1Name]['tables'];
            $db2Tables = &$this->tables[$db2Name]['tables'];

            $db1All = &$this->tables[$db1Name];
            $db2All = &$this->tables[$db2Name];

            $countTableChange = 0;
            $count1TableNew = 0;
            $count2TableNew = 0;

            foreach ($allTables as $table) {
                if (isset($db1Tables[$table]['fields']) && isset($db2Tables[$table]['fields'])) {
                    $allFields = array_unique(array_merge(array_keys($db1Tables[$table]['fields']), array_keys($db2Tables[$table]['fields'])));
                    $tableEqual = true;
                    foreach ($allFields as $field) {
                        if (isset($db1Tables[$table]['fields'][$field]) && isset($db2Tables[$table]['fields'][$field])) {
                            if ($db1Tables[$table]['fields'][$field]['type'] !== $db2Tables[$table]['fields'][$field]['type']) {
                                $db1Tables[$table]['fields'][$field]['status'] = 'change';
                                $db2Tables[$table]['fields'][$field]['status'] = 'change';
                                $db1Tables[$table]['status'] = 'tableChange';
                                $db2Tables[$table]['status'] = 'tableChange';
                                $tableEqual = false;
                            } else {
                                $db1Tables[$table]['fields'][$field]['status'] = 'equal';
                                $db2Tables[$table]['fields'][$field]['status'] = 'equal';
                            }
                        } else if (!isset($db1Tables[$table]['fields'][$field])) {
                            $db1Tables[$table]['fields'][$field] = $db2Tables[$table]['fields'][$field];
                            $db1Tables[$table]['fields'][$field]['status'] = 'new';
                            $db1Tables[$table]['status'] = 'tableChange';
                            $tableEqual = false;
                        } else {
                            $db2Tables[$table]['fields'][$field] = $db1Tables[$table]['fields'][$field];
                            $db2Tables[$table]['fields'][$field]['status'] = 'new';
                            $db2Tables[$table]['status'] = 'tableChange';
                            $tableEqual = false;
                        }
                    }
                    if ($tableEqual) {
                        $db1Tables[$table]['status'] = 'tableEqual';
                        $db2Tables[$table]['status'] = 'tableEqual';
                    } else {
                        $countTableChange++;
                    }
                    ksort($db1Tables[$table]['fields']);
                    ksort($db2Tables[$table]['fields']);
                } else if (!isset($db1Tables[$table])) {
                    $db1Tables[$table] = array(
                        'fields' => $db2Tables[$table]['fields'],
                        'status' => 'tableNew'
                    );
                    $count1TableNew++;
                } else {
                    $db2Tables[$table] = array(
                        'fields' => $db1Tables[$table]['fields'],
                        'status' => 'tableNew'
                    );
                    $count2TableNew++;
                }
            }

            $db1All['countChange'] = $countTableChange;
            $db1All['countNew'] = $count1TableNew;
            $db2All['countChange'] = $countTableChange;
            $db2All['countNew'] = $count2TableNew;

            ksort($db1Tables);
            ksort($db2Tables);

            return view('listtables', array('tables' => $this->tables));
        }
    }
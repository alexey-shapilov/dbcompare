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

            $db1Tables = $db1->select('SHOW TABLES');
            $db2Tables = $db2->select('SHOW TABLES');

            $db1Name = $db1->getDatabaseName();
            $db2Name = $db2->getDatabaseName();

            $db1Prefix = $db1->getTablePrefix();
            $db2Prefix = $db2->getTablePrefix();

            $this->tables = array(
                $db1Name => array(
                    'prefix' => $db1Prefix
                ),
                $db2Name => array(
                    'prefix' => $db2Prefix
                )
            );
            foreach ($db1Tables as $table) {
                $tName = $this->unPrefix($table->{'Tables_in_' . $db1Name}, $db1Prefix);
                $this->tables[$db1Name]['tables'][] = $tName;
                $this->tables[$db1Name][$tName] = $db1->select('SHOW COLUMNS FROM `' . $table->{'Tables_in_' . $db1Name} . '`');
            }
            foreach ($db2Tables as $table) {
                $tName = $this->unPrefix($table->{'Tables_in_' . $db2Name}, $db2Prefix);
                $this->tables[$db2Name]['tables'][] = $tName;
                $this->tables[$db2Name][$tName] = $db2->select('SHOW COLUMNS FROM `' . $table->{'Tables_in_' . $db2Name} . '`');
            }

            $this->currentCompare = array($db1Name, $db2Name);
            $tablesDiff = array('tables' => array_udiff($this->tables[$db1Name]['tables'], $this->tables[$db2Name]['tables'], array($this, 'compare_table')));

            foreach ($tablesDiff['tables'] as $table) {
                $tablesDiff[$table] = $this->tables[$db1Name][$table];

//                foreach ($tablesDiff[$table] as $columnMain) {

                if (isset($this->tables[$db2Name][$table])) {
                    foreach ($this->tables[$db2Name][$table] as $columnCompare) {
                        $equal = -1;
                        foreach ($tablesDiff[$table] as $columnMain) {
                            if ($columnMain->Field == $columnCompare->Field) {
                                $equal = 0;
                                $columnMain->status = 'equal';
                                if ($columnMain->Type != $columnCompare->Type) {
                                    $columnMain->status = 'change';
                                    $equal = 1;
                                }
                                break;
                            }
                        }
                        if ($equal === -1) {
                            $column = $columnCompare;
                            $column->status = 'new';
                            $tablesDiff[$table][] = $column;
                        }
                    }
                }

//                }
            }

//            $this->currentCompare = array($db2Name, $db1Name);
//            $tablesDiff[$db2Name] = array('prefix' => $db2Prefix, 'tables' => array_udiff($this->tables[$db2Name]['tables'], $this->tables[$db1Name]['tables'], array($this, 'compare_table')));
//            foreach ($tablesDiff[$db2Name]['tables'] as $table) {
//                $tablesDiff[$db2Name][$table] = $this->tables[$db2Name][$table];
//            }

            return view('listtables', array('tables' => $this->tables, 'tablesDiff' => $tablesDiff));
        }
    }
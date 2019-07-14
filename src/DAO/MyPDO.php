<?php

    namespace src\DAO {

        use src\Util\Bind;
        
        class MyPDO extends PDO {

            protected $sql;
            protected $statement;
            protected $bindings;

            /**
             * Constructor
             *
             * @param string $dsn - the PDO Data Source Name
             * @param string $user - database user
             * @param string $password - database password
             * @param array $options - associative array of connection options
             */
            public function __construct()
            {
                require_once 'global/ErrorHandler.php';
                ErrorHandler::defineServerEnv();

                // set default options
                $defaults = array(
                    PDO::ATTR_PERSISTENT => true, // persistent connection
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // throw exceptions
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "UTF8"', // character encoding
                    PDO::MYSQL_ATTR_FOUND_ROWS => true, // count rows matched for updates even if no changes made
                );

                try {
                    parent::__construct(Bind::get('host'), Bind::get('db_user'), Bind::get('db_senha'), $defaults);
                    if ($this && !empty($options) && is_array($options)) {
                        foreach ($options as $key => $value) {
                            $this->setAttribute($key, $value);
                        }
                    }
                } catch (PDOException $e) {
                    return false;
                }
            }


            public function prepare($sql, $options = array())
            {
                // cleanup
                $this->sql = trim($sql);

                try {
                    // prepare the statement
                    $this->statement = NULL;
                    if ($this->statement = parent::prepare($this->sql, $options)) {
                        return $this->statement;
                    }
                } catch (PDOException $e) {
                    return false;
                }
            }


            public function execute($bindings)
            {

                $this->bindings = (empty($bindings)) ? NULL : $bindings;

                if (!empty($this->statement)) {
                    try {
                        return $this->statement->execute($bindings);
                    } catch (PDOException $e) {
                        return false;
                    }
                }
            }

            public function select($sql, $bindings = array(), $fetch_style = '', $fetch_argument = '')
            {
                // prepare the statement
                if ($this->prepare($sql)) {
                    // bind and execute
                    if ($this->execute($bindings)) {
                        // set default fetch mode
                        $fetch_style = (empty($fetch_style)) ? PDO::FETCH_ASSOC : $fetch_style;
                        // return the results
                        if (!empty($fetch_argument)) {
                            return $this->statement->fetchAll($fetch_style, $fetch_argument);
                        }
                        return $this->statement->fetchAll($fetch_style);
                    }
                    return false;
                }
                return false;
            }

            public function selectCell($sql, $bindings = array())
            {

                if ($this->prepare($sql)) {
                    // bind and execute
                    if ($this->execute($bindings)) {
                        // return the value
                        return $this->statement->fetch(PDO::FETCH_COLUMN);
                    }
                    return false;
                }
                return false;
            }

            public function run($sql, $bindings = array())
            {

                if ($this->prepare($sql)) {

                    try {
                        if (preg_match('/delete/i', $this->sql) && !preg_match('/where/i', $this->sql)) {
                            throw new PDOException('Missing WHERE clause for DELETE statement');
                        }
                    } catch (PDOException $e) {
                        $this->debug($e);
                        return false;
                    }

                    try {
                        if (!preg_match('/(select|describe|delete|insert|update|create|alter)+/i', $this->sql)) {
                            throw new PDOException('Unsupported SQL command');
                        }
                    } catch (PDOException $e) {
                        return false;
                    }


                    if ($success = $this->execute($bindings)) {

                        if (preg_match('/(delete|insert|update)/i', $this->sql)) {
                            return $this->statement->rowCount();
                        } else if (preg_match('/(select|describe)/i', $this->sql)) {
                            return $this->statement->fetchAll(PDO::FETCH_ASSOC);
                        } else if (preg_match('/(create|alter)/i', $this->sql)) {
                            return $success;
                        }
                    }
                    return false;
                }
                return false;
            }

            public function delete($sql, $bindings = array())
            {
                return $this->run($sql, $bindings);
            }


            public function filter($values, $table)
            {

                try {
                    $this->sql = 'SHOW COLUMNS FROM ' . $table;
                    $sth = $this->query($this->sql);
                    $info = $sth->fetchAll();
                } catch (PDOException $e) {
                    return false;
                }


                $ai_fields = array();
                $columns = array();
                foreach ($info as $item) {
                    $columns[] = $item['Field'];
                    if (isset($item['Extra']) && $item['Extra'] == 'auto_increment') {
                        $ai_fields[] = $item['Field'];
                    }
                }

                foreach ($values as $name => $value) {
                    if (!in_array($name, $columns)) {
                        unset($values[$name]);
                    }
                }

                if (!empty($ai_fields)) {
                    foreach ($ai_fields as $item) {
                        unset($values[$item]);
                    }
                }

                return $values;
            }


            public function insert($table, $values, $bindings = array())
            {

                $values = $this->filter($values, $table);


                $sql = 'INSERT INTO '.$table.' (';

                $i = 0;
                foreach ($values as $column => $value) {
                    $sql .= ($i == 0) ? $column : ', ' . $column;
                    $i++;
                }
                $sql .= ') VALUES (';

                $i = 0;
                if (empty($bindings)) {
                    $bindings = array_values($values);
                    foreach ($values as $value) {
                        $sql .= ($i == 0) ? '?' : ', ?';
                        $i++;
                    }
                } else {
                    foreach ($values as $value) {
                        $sql .= ($i == 0) ? $value : ', '.$value;
                        $i++;
                    }
                }
                $sql .= ')';

                return $this->run($sql, $bindings);
            }

            public function update($table, $values, $where, $bindings = array())
            {

                $values = $this->filter($values, $table);


                    $sql = 'UPDATE '.$table.' SET ';

                $final_bindings = array();
                $i = 0;
                foreach ($values as $column => $value) {
                    $marker = $bound_value = NULL;
                    if (preg_match('/(:\w+|\?)/', $value, $matches)) {
                        if (strpos(':', $matches[1]) !== false) {
                            $marker = $matches[1];
                            $bound_value = $bindings[$matches[1]];
                        } else {
                            $marker = ':'.$column;
                            $bound_value = array_shift($bindings);
                        }
                    } else {
                        $marker = ':'.$column;
                        $bound_value = $value;
                    }
                    $final_bindings[$marker] = $bound_value;

                    $sql .= ($i == 0) ? $column.' = '.$marker : ', '.$column.' = '.$marker;
                    $i++;
                }

                if (!empty($where)) {

                    if (!is_array($where)) {
                        $where = preg_split('/\b(where|and)\b/i', $where, NULL, PREG_SPLIT_NO_EMPTY);
                        $where = array_map('trim', $where);
                    }


                    foreach ($where as $i => $condition) {
                        $marker = $bound_value = NULL;

                        preg_match('/(\w+)\s*(=|<|>|!)+\s*(.+)/i', $condition, $parts);
                        if (!empty($parts)) {

                            list( , $column, $operator, $value) = $parts;

                            if (preg_match('/(:\w+|\?)/', $value, $matches)) {
                                if (strpos(':', $matches[1]) !== false) {
                                    $marker = $matches[1];
                                    $bound_value = $bindings[$matches[1]];
                                } else {
                                    $marker = ':where_'.$column;
                                    $bound_value = array_shift($bindings);
                                }
                            } else {
                                $marker = ':where_'.$column;
                                $bound_value = $value;
                            }
                            $final_bindings[$marker] = $bound_value;
                            $where[$i] = substr_replace($condition, $marker, strpos($condition, $value));
                        }
                    }

                    foreach ($where as $i => $condition) {
                        $sql .= ($i == 0) ? ' WHERE '.$condition : ' AND '.$condition;
                    }
                }

                return $this->run($sql, $final_bindings);
            }
        }
    }

<?php
namespace Library\Core;

use Library\Fluent\FluentPDO;
use Library\Fluent\SelectQuery;


class PDOModel extends FluentPDO
{

    public $table = '';
    public $tableQuantity = 1;
    public $lastInsertId = -1;
    public $trueTable = '';
    public $splitColumn = '';
    public $isExists = NULL;
    public static $errmsg = '';
    public static $errcode = '';

    public function init()
    {
        if (!self::$pdo instanceof \PDO) {
            $dbType = Config::getSite('database', 'db.defaultSqlDriver');
            if (empty($dbType)) {
                $dbType = 'mysql';
            }
            $config = Config::getSite('database', 'db.drivers.' . $dbType);

            $db_port = isset($config['port']) ? $config['port'] : '3306';
            $db_host = $config['host'];
            $db_name = isset($this->database) && !empty($this->database) ? $this->database : $config['name'];
            $db_user = $config['user'];
            $db_pass = $config['pass'];

            $options = array(
// 	            \PDO::MYSQL_ATTR_INIT_COMMAND    => 'set names \'utf8\'',
                \PDO::ATTR_PERSISTENT => false, # ?
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, #err model, ERRMODE_SILENT 0,ERRMODE_EXCEPTION 2
            );
            // Create a new PDO instanace
            try {
                $dsn = $dbType . ':dbname=' . $db_name . ';host=' . $db_host . ';port=' . $db_port;
                self::$pdo = new \PDO($dsn, $db_user, $db_pass, $options);
                self::$pdo->exec('set names \'utf8\'');
            } catch (\PDOException $e) { // Catch any errors
                self::$pdo = null;
                $this->errmsg = $e->getMessage();
                $this->errcode = $e->getCode();
                Debug::log($e->getFile() . ' line ' . $e->getLine() . ":" . $e->getMessage() . "\n\t\tTrace:" . $e->getTraceAsString(), 'ERROR');
            }
        }

        if (empty($this->table)) {
            $reflection = new \ReflectionClass($this);
            $className = $reflection->getShortName();
            $reflection = new \ReflectionClass($this);
            $modelName = $reflection->getShortName();
            $table = substr($modelName, 0, -5);
            preg_match_all('/((?:^|[A-Z])[a-z]+)/', $table, $matches);
            $this->table = strtolower(implode('_', $matches[0]));
            if ($this->tableQuantity <= 1) {
                $this->trueTable = $this->table;
            }
            unset($modelName);
            unset($reflection);
            unset($table);
            unset($matches);
        }

        if ($this->tableQuantity > 0 && empty($this->splitColumn)) {
            $this->splitColumn = $this->primaryKey;
        }
    }

    public function __set($key, $val)
    {
        $this->setAttribute($key, $val);
        return $this;
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function __unset($key)
    {
        return $this->removeAttribute($key);
    }

    /**
     * 插入到数据库
     * @return bool
     */
    public function insert()
    {
        $query = $this->insertInto($this->getTable(), $this->getAttributes());
        try {
            $sql = $query->getQuery();
            $result = $query->execute();

            if (is_numeric($result)) {
                $this->lastInsertId = $result;
                $this->setAttribute($this->primaryKey, $result);
            } else if (false === $result) {
                return false;
            } else {
                $this->lastInsertId = isset($this->{$this->primaryKey}) ? $this->{$this->primaryKey} : -1;
                $this->isExists = true;
            }
            return true;
        } catch (\PDOException $e) {
            Debug::log($e->getFile() . ' line ' . $e->getLine() . ":" . $e->getMessage() . "\n\t\tSQL:" . $sql . "\n\t\tTrace:" . $e->getTraceAsString());
            $this->lastInsertId = -1;
            return false;
        }
    }

    /**
     * 保存到服务器，如果存在则更新，否则插入
     */
    public function save()
    {
        if ($this->exists()) {
            return $this->renewal();
        } else {
            return $this->insert();
        }
    }

    /**
     *
     * @return boolean
     */
    public function remove()
    {
        if ($this->exists()) {
            $query = $this->delete($this->getTable());
            $attributes = $this->getAttributes();
            $primaryKeyVal = $attributes[$this->primaryKey];
            if (empty($primaryKeyVal)) {
                return false;
            }
            $query->where(array($this->primaryKey => $primaryKeyVal));
            $sql = $query->getQuery();
            try {
                return (bool)$query->execute();
            } catch (\Exception $e) {
                Debug::log($e->getFile() . ' line ' . $e->getLine() . ":" . $e->getMessage() . "\n\t\tSQL:" . $sql . "\n\t\tTrace:" . $e->getTraceAsString());
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * 更新数据，防止覆盖父类的update
     * @param $data
     * @return boolean
     */
    public function renewal(array $data = array())
    {
        $attributes = $this->getAttributes();

        if (empty($data) && empty($attributes)) {
            Debug::log(get_called_class() . ':' . 'does not set any attribute for the model class either the updated attributes');
            return false;
        }

        $primaryKeyVal = array_key_exists($this->primaryKey, $attributes) ? $attributes[$this->primaryKey] : false;
        $where = array();
        if (isset($primaryKeyVal) && !is_null($primaryKeyVal)) {
            $where = array($this->primaryKey => $primaryKeyVal);
            if (empty($data)) {
                unset($attributes[$this->primaryKey]);
                $data = $attributes;
            }
        } else {
            if (!empty($data)) {
                foreach ($data as $key => $val) {
                    if (array_key_exists($key, $attributes)) {
                        unset($attributes[$key]);
                    }
                }
                $where = $attributes;
            } else {
                return false;
            }
        }
        $query = parent::update($this->getTable(), $data, $where);
        $sql = $query->getQuery() . '  ' . implode(' , ', $query->getParameters());
        try {
            $result = $query->execute();
            return true;
        } catch (\PDOException $e) {
            Debug::log($e->getFile() . ' line ' . $e->getLine() . ":" . $e->getMessage());
            Debug::log(" SQL:" . $sql);
            Debug::log($e->getTraceAsString());
            return false;
        }
    }

    /**
     * @return number
     */
    public function getLastInsertId()
    {
        return $this->lastInsertId;
    }

    /**
     * @return Ambigous <NULL, boolean>
     */
    public function exists()
    {
        if (!is_bool($this->isExists)) {
            $attributes = $this->getAttributes();
            if (empty($attributes)) {
                return false;
            }
            $query = $this->from($this->getTable())
                ->where($attributes)->limit(1);
            $sql = $query->getQuery();
            try {
                $result = $query->fetch();
            } catch (\Exception $e) {
                Debug::log($e->getFile() . ' line ' . $e->getLine() . ":" . $e->getMessage() . " SQL:" . $sql);
                Debug::log($e->getTraceAsString());
                $result = false;
            }
            if (is_array($result) && array_key_exists($this->primaryKey, $result)) {
                foreach ($result as $key => $val) {
                    $this->setAttribute($key, $val);
                }
                $this->lastInsertId = $this->getAttribute($this->primaryKey);
                $this->isExists = true;
            } else {
                $this->isExists = false;
            }
        }
        return $this->isExists;
    }


    /**
     * @param SelectQuery $query
     * @param array $field
     * @param Page $page
     * @return NULL|unknown
     */
    function executeQueryByPage(SelectQuery $query, $field, Page $page = null)
    {

        $totalCount = $query->select(null)->select('count(*) as ct')->fetch('ct');
        $page->setTotalCount($totalCount);
        $res = array('page' => $page);
        if ($totalCount <= 0) {
            return $res;
        }

        $query = $query->offset($page->getFirstResult())->limit($page->getPageSize())->select(null);

        foreach ($field as $fi) {
            $query = $query->select($fi);
        }


        $res = $query->fetchAll();
        $res['page'] = $page;

        return $res;
    }

    /**
     * 根据hash获取分表表名
     * @param string|int $primaryKeyVal
     * @return string
     */
    public function getHashedTableName($splitVal)
    {
        $hash = abs(crc32($splitVal));
        $number = $hash % intval($this->tableQuantity);
        return $this->table . "_" . $number;
    }

    /**
     * 获取表明
     * @return string
     */
    public function getTable()
    {
        if ($this->tableQuantity <= 1) {
            return isset($this->database) && !empty($this->database) ? $this->database . '.' . $this->table : $this->table;
        } else {
            if (empty($this->trueTable)) {
                $splitVal = $this->getAttribute($this->splitColumn);
                if (false === $splitVal) {
                    throw new \Exception("You should set a value to the split-column before you get the splited table name.");
                }
                $this->trueTable = $this->getHashedTableName($splitVal);
            }
            return isset($this->database) && !empty($this->database) ? $this->database . '.' . $this->trueTable : $this->trueTable;
        }
    }

    /**
     * @param $where string
     * @return boolean
     */
    public function findFirst($where = null)
    {
        $q = $this->from($this->getTable())->where($where);
        return $q->fetch();
    }

    /**
     * find by sql
     * -- easy to use a single sql statement
     *
     * @param $sql An SQL string
     * @param array $bindVals Paramters to bind
     * @author yanue
     * @return array
     */
    public function findAll($sql, $bindVals = array())
    {
        $sth = self::$pdo->prepare($sql);
        foreach ($bindVals as $key => $value) {
            $sth->bindValue("$key", $value);
        }
        try {
            $sth->execute();
            $res = $sth->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            Debug::log($sql);
            Debug::log($e->getFile() . ":" . $e->getTraceAsString());
            $res = array();
        }
        unset($sth);
        return $res;
    }

    /**
     * find by sql
     * -- easy to use a single sql statement
     * -- return one row
     *
     * @param $sql An SQL string
     * @param array $bindVals Paramters to bind
     * @author yanue
     * @return array
     */
    public function findOne($sql, $bindVals = array())
    {
        $sth = self::$pdo->prepare($sql);
        foreach ($bindVals as $key => $value) {
            $sth->bindValue("$key", $value);
        }
        $sth->execute();
        $res = $sth->fetch(\PDO::FETCH_ASSOC);
        unset($sth);
        return $res;
    }

    /**
     * @param $where
     * @param null $limit
     * @param $sort
     * @return array
     */
    public function find($where = null, $limit = null, $sort = null)
    {
        $q = $this->from($this->getTable())->where($where);
        if ($limit) {
            $q = $q->limit($limit);
        }
        if ($sort) {
            $q = $q->orderBy($sort);
        }

        return $q->fetchAll();
    }


    /**
     * 将对象的属性及其值返回array，只返回数据库映射的属性
     * @return array
     */
    public function toArray()
    {
        return $this->getStructure()->getAttributes();
    }

    /**
     * 清除该类绑定的属性及其值，回到new XXXXModel()的状态
     */
    public function clearSelf()
    {
        $this->getStructure()->clearAttributes();
        $this->isExists = NULL;
        return $this;
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getList($offset = 0, $limit = NULL, $select = NULL, $condition = null, $order = NULL)
    {
        $query = $this->from($this->getTable());
        if (!empty($select)) {
            $query->select(null)->select($select);
        }
        if (!empty($condition)) {
            $query->where($condition);
        } else {
            $query->where($this->getAttributes());
        }
        if (!empty($order)) {
            $query->orderBy($order);
        }
        if (empty($offset) || !is_numeric($offset)) {
            $offset = 0;
        }
        $query->offset($offset);
        if (!empty($limit) && is_numeric($limit) && $limit > 0) {
            $query->limit($limit);
        }
        $sql = $query->getQuery();
        try {
            $result = $query->fetchAll();
        } catch (\Exception $e) {
            $result = array();
            Debug::log($e->getFile() . ":" . $e->getMessage() . " SQL:" . $sql);
            Debug::log($e->getTraceAsString());
        }
        return $result;
    }

    public function count($condition = NULL)
    {
        $query = $this->from($this->getTable());
        $query->select(null)->select('count(' . $this->primaryKey . ') as total');
        if (!empty($condition)) {
            $query->where($condition);
        } else {
            $query->where($this->getAttributes());
        }
        $sql = $query->getQuery();
        try {
            $result = $query->fetch('total');
        } catch (\Exception $e) {
            Debug::log($e->getFile() . ' line ' . $e->getLine() . ":" . $e->getMessage() . "\n\t\tSQL:" . $sql . "\n\t\tTrace:" . $e->getTraceAsString());
            $result = 0;
        }
        return (int)$result;
    }

    /**
     * 快速设置属性
     * @param array $data
     */
    public function setAttributes(array $data)
    {
        $this->getStructure()->setAttributes($data);
        return $this;
    }

    public function setAttribute($key, $val)
    {
        $this->getStructure()->setAttribute($key, $val);
        return $this;
    }

    public function getAttribute($key)
    {
        return $this->getStructure()->getAttribute($key);
    }

    public function getAttributes()
    {
        return $this->getStructure()->getAttributes();
    }

    public function removeAttribute($key)
    {
        return $this->getStructure()->removeAttribute($key);
    }

    # Transaction by sql queue
    public function transBySqlQueue($sqlQueue = '')
    {
        //$this->connection();
        if (count($sqlQueue) > 0) {
            /*
             * Manual says:
             * If you do not fetch all of the data in a result set before issuing your next call to PDO::query(), your call may fail. Call PDOStatement::closeCursor() to release the database resources associated with the PDOStatement object before issuing your next call to PDO::query().
             * */
            //self::$pdo->closeCursor();
            //关闭自动提交
            self::$pdo->setAttribute(\PDO::ATTR_AUTOCOMMIT, 0);
            try {

                self::$pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
                self::$pdo->beginTransaction();
                foreach ($sqlQueue as $sql) {
                    $res = self::$pdo->exec($sql);
                    if (!$res) {
                        throw new \PDOException ();
                    }
                }
                return self::$pdo->commit();
            } catch (\PDOException $e) {
                self::$pdo->rollBack();
                $this->errmsg = $e->getMessage();
                $this->errcode = $e->getCode();
                Debug::log($e->getCode() . ":" . $e->getMessage());
                return false;
            }
            // 重新开启自动提交
            self::$pdo->setAttribute(\PDO::ATTR_AUTOCOMMIT, 1);
        }
        return false;
    }

    /**
     * transaction by closure function
     *
     * ---- in closure function you must throw the error
     * @param $closure
     * @return bool
     * @throws \Exception
     */
    public static function transaction($closure)
    {
        try {
            self::$pdo->beginTransaction();

            if ($closure() === false) {
                self::$pdo->rollback();
                return false;
            } else {
                self::$pdo->commit();
            }
        } catch (\PDOException $e) {
            self::$pdo->rollback();
            self::$errmsg = $e->getMessage();
            self::$errcode = $e->getCode();
            Debug::log($e->getCode() . ":" . $e->getMessage());
            return false;
        }
        return true;
    }

    public function detailForList($id = NULL)
    {
        return $this->toArray();
    }


    public function getById($id, $field = '')
    {
        $q = $this->from($this->getTable())->where(array('id' => $id));
        return $q->fetch($field);
    }

    public function del($where)
    {
        if (!$where) {
            return false;
        }
        $query = $this->deleteFrom($this->getTable())->where($where);
        return $query->execute();
    }

    public function up($data, $where)
    {
        if (!$where) return false;
        $q = $this->update($this->getTable())->set($data)->where($where);
        return $q->execute();
    }

    public function add($data)
    {
        $q = $this->insertInto($this->getTable(), $data)->ignore();
        return $q->execute();
    }
}


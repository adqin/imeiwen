<?php

/**
 * 数据库操作封装.
 * 
 * @author aic <41262633@qq.com>
 */

/**
 * Class Db.
 */
class Db {

    private static $instance = null; // 数据库类单实例.
    private static $conn = null; // 数据库链接.
    private $res = null;

    /**
     * 单例模式.
     * 
     * @return \Db
     */
    public static function instance() {
        // 如果未数据库未连接.
        if (!static::$conn) {
            static::$conn = new mysqli(\Config\Db::$database['host'], \Config\Db::$database['user'], \Config\Db::$database['passwd'], \Config\Db::$database['dbname']);
            if (mysqli_connect_errno()) {
                printf("数据库连接失败: %s\n", mysqli_connect_error());
                exit;
            }

            if (!static::$conn->set_charset(\Config\Db::$database['charset'])) {
                printf("数据库转换编码失败: %s\n", static::$conn->error);
                exit;
            }
        }

        // 如果数据库对象未实例化.
        if (!static::$instance) {
            static::$instance = new \Db();
        }

        return static::$instance;
    }

    /**
     * 查询一条记录.
     * 
     * @param string $sql 查询的sql.
     * 
     * @return array.
     */
    public function getRow($sql) {
        $this->query($sql);
        $rs = $this->res->fetch_array(MYSQLI_ASSOC);
        return $rs ? $rs : array();
    }

    /**
     * 查询一条记录一个字段的值.
     * 
     * @param string $sql 查询的sql.
     * 
     * @return mixed.
     */
    public function getSimple($sql) {
        $row = $this->getRow($sql);
        if (!$row) {
            return null;
        }

        return array_values($row)[0];
    }

    /**
     * 查询记录条数.
     * 
     * @param string $sql 查询的sql.
     * 
     * @return integer.
     */
    public function count($sql) {
        $rs = $this->getSimple($sql);
        return is_null($rs) ? 0 : $rs;
    }

    /**
     * 查询记录是否存在.
     * 
     * @param string $sql 查询的sql语句.
     * 
     * @return boolean.
     */
    public function exists($sql) {
        $rs = $this->getRow($sql);
        return $rs ? true : false;
    }

    /**
     * 查询列.
     * 
     * @param string $sql 查询的sql.
     * 
     * @return array.
     */
    public function getColumn($sql) {
        $this->query($sql);
        $arr = array();
        while ($row = $this->res->fetch_row()) {
            $arr[] = $row[0];
        }
        return $arr;
    }

    /**
     * 查询返回多条记录.
     * 
     * @param string $sql 查询的sql.
     * @param string $key 返回数组索引.
     * 
     * @return array.
     */
    public function getList($sql, $key = '') {
        $this->query($sql);
        $arr = array();
        while ($row = $this->res->fetch_array(MYSQLI_ASSOC)) {
            $arr[] = $row;
        }

        // 如果指定以某键值返回.
        if ($key && $arr) {
            return \Common::getArrByKey($arr, $key);
        }

        return $arr;
    }

    /**
     * 通过主键id更新Db.
     * 
     * @param string $table 更新表名.
     * @param array $param 更新参数.
     * @param array $id 更新的主键id.
     * 
     * @return boolean.
     */
    public function updateById($table = '', $param = array(), $id = 0) {
        if (empty($table) || empty($param) || !is_array($param) || !$id) {
            \Common::ajaxReturnFalse('update db, 参数不符合要求');
        }

        $where = "`id` = '$id'";
        $sql = "update `{$table}` set ";
        $upItem = [];
        foreach ($param as $k => $v) {
            $upItem[] = "`$k` = '$v'";
        }
        $upItemString = implode(',', $upItem);

        $sql .= "$upItemString where $where";
        //echo $sql;

        return $this->execute($sql);
    }

    /**
     * 通过指定条件更新表记录.
     * 
     * @param string $table 更新表.
     * @param array $param 更新参数.
     * @param string $where 更新条件.
     * 
     * @return boolean.
     */
    public function updateByWhere($table = '', $param = array(), $where = '') {
        if (empty($table) || empty($param) || !is_array($param) || !$where || !is_string($where)) {
            \Common::ajaxReturnFalse('update db, 参数不符合要求');
        }
        
        $sql = "update `{$table}` set ";
        $upItem = [];
        foreach ($param as $k => $v) {
            $upItem[] = "`$k` = '$v'";
        }
        $upItemString = implode(',', $upItem);

        $sql .= "$upItemString where $where";
        //echo $sql;

        return $this->execute($sql);
    }

    /**
     * 新写入记录.
     * 
     * @param string $table 更新表.
     * @param array $param 更新参数.
     * 
     * @return integer.
     */
    public function insert($table = '', $param = array()) {
        if (!$table || !is_array($param) || !$param) {
            \Common::ajaxReturnFalse('insert db, 更新参数有误');
        }

        $sql = "insert `$table`";

        $keys = array_keys($param);
        $sql .= "(`" . implode("`,`", $keys) . "`) values('" . implode("','", $param) . "')";
        //echo $sql;

        if ($this->execute($sql)) {
            return static::$conn->insert_id;
        } else {
            return 0;
        }
    }

    /**
     * 开始事务, 并且不自动提交.
     * 
     * @return boolean.
     */
    public function startTransactions() {
        return static::$conn->autocommit(false);
    }

    /**
     * 提交事务.
     * 
     * @return boolean.
     */
    public function commit() {
        return static::$conn->commit();
    }

    /**
     * 回滚事务.
     * 
     * @return boolean.
     */
    public function rollback() {
        return static::$conn->rollback();
    }

    /**
     * 用于插入或更新操作.
     * 
     * @param string $sql 更新的sql.
     * 
     * @return boolean.
     */
    public function execute($sql) {
        $this->query($sql);
        if ($this->res === false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 执行sql的错误记录.
     * 
     * @return string.
     */
    public function getError() {
        return static::$conn->error;
    }

    /**
     * 执行sql.
     * 
     * @param string $sql 操作的sql语句.
     * 
     * @return void
     */
    private function query($sql) {
        $this->res = static::$conn->query($sql);
    }

    /**
     * 释放sql执行资源.
     */
    public function __destruct() {
        $this->res = null;
    }

}

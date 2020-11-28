<?php /** @noinspection PhpDeprecationInspection */
/** @noinspection HtmlDeprecatedTag */

/** @noinspection PhpOptionalBeforeRequiredParametersInspection */

class MySql
{
    public $query;
    public $hostName;
    public $userName;
    public $passWord;
    public $dataBaseName;
    public $info = array();
    public $insertId = "";
    public $mysqlConncet = false;
    public $pageNum = 1;
    public $pageSize = 30;
    public $pageStr = "";
    public $resultCount = 0;
    public $fileName = "";
    public $pageCount = 1;
    public $affectedNum = 0;

    /*****************************************************************************
     * 函数名称：MySql($hostName = "localhost:3306", $userName = "root", $passWord = "", $dataBaseName = "database")
     * 函数作用：构造函数,初始化类数据
     * 传入参数：$hostName      主机名和端口
     * $userName      连接数据库用户名
     * $passWord      连接数据库密码
     * $dataBaseName  数据库名
     * 返 回 值：无
     * 调用示列：$MySql = new MySql( $localhost_sql ,$user_sql , $pass_sql , $database_sql);初始化一个类
     * 作    者: kinhong
     * 创建日期：2007-09-27
     ****************************************************************************
     * @param string $hostName
     * @param string $userName
     * @param string $passWord
     * @param string $dataBaseName
     */

    public function __construct($hostName = "", $userName = "", $passWord = "", $dataBaseName = "")
    {
        $this->hostName = $hostName;
        $this->userName = $userName;
        $this->passWord = $passWord;
        $this->dataBaseName = $dataBaseName;
    }

    /*****************************************************************************
     * 函数名称：connectMySql()
     * 函数作用：连接数据库(只在本类中使用)
     * 传入参数：无
     * 返 回 值：无
     * 调用示列：$this -> connectMySql()
     * 作    者: kinhong
     * 创建日期：2007-09-27
     *****************************************************************************/

    public function connectMySql()
    {
        if ($this->mysqlConncet == false) {
            $this->mysqlConncet = mysql_connect($this->hostName, $this->userName, $this->passWord);
            mysql_query("set names utf8");
            mysql_select_db($this->dataBaseName, $this->mysqlConncet);
        }
    }


    /*****************************************************************************
     * 函数名称：selectInfo($sqlQuery, $typemysql = "MYSQL_ASSOC")
     * 函数作用：执行SQL查询语句
     * 传入参数：$sqlQuery SQL语句
     * $typemysql = "MYSQL_ASSOC" 返回值数组类型控制  "MYSQL_ASSOC"将返回表字段为数组索引
     * 返 回 值：true / false
     * 调用示列：$isTrue   = $this ->  selectInfo($sqlQuery);
     * 作    者: kinhong
     * 创建日期：2007-09-27
     ****************************************************************************
     * @param $sqlQuery
     * @param string $typemysql
     * @return bool
     */

    public function selectInfo($sqlQuery, $typemysql = "MYSQL_ASSOC")
    {
        $this->connectMySql();

        $GLOBALS['charset'] = "utf-8";

        if ($this->mysqlInfo() > '4.1' && $GLOBALS['charset']) {

            mysql_query("SET character_set_connection=" . $GLOBALS['charset'] . ", character_set_results=" . $GLOBALS['charset'] . ", character_set_client=binary");
        }

        if ($this->mysqlInfo() > '5.0') {
            mysql_query("SET sql_mode=''");
        }

        $this->query = mysql_query($sqlQuery, $this->mysqlConncet);

        $result = array();

        if ($typemysql == "MYSQL_NUM") {
            while ($row = mysql_fetch_array($this->query, MYSQL_NUM)) //MYSQL_NUM
            {
                $result[] = $row;
            }

        } else if (stristr($sqlQuery, 'insert into')) {   //取得上一次进行插入后的ID
            $result = mysql_insert_id();
        } else if (stristr($sqlQuery, 'update ')) {
            $result = mysql_affected_rows();
        } else {
            while ($row = mysql_fetch_array($this->query, MYSQL_ASSOC)) {
                $result[] = $row;
            }

        }

        $this->info = $result;

        return true;
    }


    /*****************************************************************************
     * 函数名称：countInfo($tableName, $whereClause)
     * 函数作用：统计记录总数（常用于分页）
     * 传入参数：$tableName 表名
     * $whereClause SQL条件
     * 返 回 值：无
     * 调用示列：$this -> countInfo($tableName, $whereClause);
     * 作    者: kinhong
     * 创建日期：2007-09-27
     ****************************************************************************
     * @param $tableName
     * @param $whereClause
     */

    public function countInfo($tableName, $whereClause)
    {

        $countSql = ' select count(1) from ' . $tableName;
        if ($whereClause != '') $countSql .= ' where ' . $whereClause;

        $this->connectMySql();
        $this->query = mysql_query($countSql, $this->mysqlConncet);//or die ( mysql_error());
        $this->resultCount = mysql_result($this->query, 0);//or die ( mysql_error());

    }

    /*****************************************************************************
     * 函数名称：getPageInfo($selectClause = " * ", $tableName , $whereClause = '', $groupClause = '' , $orderClause = '', $limitClause = '', $offertClause = 0 , $pageNum = 1 , $pageSize = 30 )
     * 函数作用：获取单一页信息内容(分页程序)
     * 传入参数：$selectClause  SQL语句中要查询的相关字段
     * $tableName     SQL查询表名
     * $whereClause   SQL条件语句
     * $groupClause   SQL排序方式(group by ""后语句)
     * $orderClause   SQL排序方式
     * $offertClause = 0 SQL的offert后的语句
     * $pageNum       当前页码
     * $pageSize      每页显示记录数
     *
     * 返 回 值：true / false
     * 调用示列：$isTrue = $MySql -> getPageInfo($selectClause , $tableName , $whereClause , $groupClause = '' , $orderClause = '',  $offertClause = 0 , $pageNum  , $pageSize );
     * 作    者: kinhong
     * 创建日期：2007-09-27
     ****************************************************************************
     * @param string $selectClause
     * @param $tableName
     * @param string $whereClause
     * @param string $groupClause
     * @param string $orderClause
     * @param int $offertClause
     * @param int $pageNum
     * @param int $pageSize
     * @return bool
     */

    public function getPageInfo($selectClause = " * ", $tableName, $whereClause = '', $groupClause = '', $orderClause = '', $offertClause = 0, $pageNum = 1, $pageSize = 30)
    {

        $numBegin = ($pageNum - 1) * $pageSize;
        $limitClause = $numBegin . ' , ' . $pageSize;

        $sqlQuery = $this->buildSelect($selectClause, $tableName, $whereClause, $groupClause, $orderClause, $limitClause, $offertClause);

        $isTrue = $this->selectInfo($sqlQuery);

        $this->countInfo($tableName, $whereClause);
        $this->buildPageStr($pageNum, $pageSize);

        return $isTrue;

    }

    /*****************************************************************************
     * 函数名称：buildPageStr( $pageNum = 1, $pageSize = 30 )
     * 函数作用：输入分页相关字符串(对应本类中分页显示)
     * 传入参数：$pageNum   当前页码
     * $pageSize  每页显示记录数
     *
     * 返 回 值：无
     * 调用示列：$this ->  buildPageStr( $pageNum , $pageSize );
     * 作    者: kinhong
     * 创建日期：2007-09-27
     ****************************************************************************
     * @param int $pageNum
     * @param int $pageSize
     */

    public function buildPageStr($pageNum = 1, $pageSize = 30)
    {

        $this->pageCount = ceil($this->resultCount / $pageSize);
        $numBegin = ($pageNum - 1) * $pageSize + 1;
        if (($pageNum * $pageSize) < ($this->resultCount)) $numEnd = $pageNum * $pageSize;
        else $numEnd = $this->resultCount;

        $this->pageStr = " <div align='right'> 第 <font color='#FF0000'>" . $numBegin . "</font> 至 <font color='#FF0000'>" . $numEnd . "</font> 条记录   ";
        $this->pageStr .= "共 [<font color='#FF0000'>" . ($this->resultCount) . "</font>] 记录   [ ";

        if ($pageNum == 1) $this->pageStr .= "<font color='#999999'>首页  | 上一页</font>";
        else $this->pageStr .= "<a href='javascript:getToPage(1)'>首页</a>  |  <a href='javascript:getToPage(" . ($pageNum - 1) . ")'>上一页</a> ";

        if ($pageNum == ($this->pageCount) or ($this->pageCount) <= 0) $this->pageStr .= " |<font color='#999999'> 下一页 | 尾页</font> ] ";
        else $this->pageStr .= " | <a href='javascript:getToPage(" . ($pageNum + 1) . ")'>下一页</a>  |  <a href='javascript:getToPage(" . ($this->pageCount) . ")'>尾页</a> ] ";


        /** @noinspection JSUnresolvedFunction */
        $this->pageStr .= "第 <select onchange = 'getToPage(this.value)'>";

        for ($i = 1; $i <= $this->pageCount; $i++) {

            if ($pageNum == $i) {
                $this->pageStr .= "<option value='" . $i . "' selected >" . $i . "</option>";
            } else {
                $this->pageStr .= "<option value='" . $i . "'>" . $i . "</option>";
            }
        }

        $this->pageStr .= "</select>页&nbsp;&nbsp;</div>";
        //}
    }

    /*****************************************************************************
     * 函数名称：buildSelect($selectClause = '*', $tableName, $whereClause = '', $groupClause = '' , $orderClause = '', $limitClause = '', $offertClause = 0)
     * 函数作用：生成查询SQL语句
     * 传入参数：$selectClause 查询字段
     * $tableName    数据表名
     * $whereClause  查询条件
     * $groupClause  GROUP BY字段
     * $orderClause  排序字段
     * $limitClause  记录数
     * $offertClause 起始记录（偏移量）
     * 返回值：  $sql (返回select查询SQL语句)
     * 调用示列：$sqlQuery = $this ->  buildSelect($selectClause , $tableName, $whereClause , $groupClause  , $orderClause , $limitClause , $offertClause );
     * 作    者: Kinhon
     * 创建日期：2006-09-08
     ****************************************************************************
     * @param string $selectClause
     * @param $tableName
     * @param string $whereClause
     * @param string $groupClause
     * @param string $orderClause
     * @param string $limitClause
     * @param int $offertClause
     * @return string
     */

    public function buildSelect($selectClause = '*', $tableName, $whereClause = '', $groupClause = '', $orderClause = '', $limitClause = '', $offertClause = 0)
    {
        $sql = '';
        if ($tableName != '') {
            $sql = 'SELECT ' . $selectClause . ' FROM ' . $tableName;
        }

        if ($whereClause != '') $sql .= ' WHERE ' . $whereClause;
        if ($groupClause != '') $sql .= ' GROUP BY ' . $groupClause;
        if ($orderClause != '') $sql .= ' ORDER BY ' . $orderClause;
        if ($limitClause != '') $sql .= ' LIMIT  ' . $limitClause;
        if ($offertClause != 0) $sql .= ' OFFSET ' . $offertClause;

        return $sql;
    }


    /*****************************************************************************
     * 函数名称：querySql($sqlQuery)
     * 函数作用：执行SQL语句
     * 传入参数：$sqlQuery 要执行的SQL语句
     * 返回值：  true / false
     * 调用示列：$this -> querySql($sqlQuery);
     * 作    者: Kinhon
     * 创建日期：2006-09-08
     ****************************************************************************
     * @param $sqlQuery
     * @return resource
     */

    public function querySql($sqlQuery)
    {
        $this->connectMySql();
        $this->query = mysql_query($sqlQuery, $this->mysqlConncet);
        $this->affectedNum = @mysql_affected_rows();
        return $this->query;
    }

    /*****************************************************************************
     * 函数名称：insertSql($sqlQuery)
     * 函数作用：添加一条记录（可以获得刚添加记录的ID）
     * 传入参数：$sqlQuery 要执行的SQL语句
     * 返回值：  true / false
     * 调用示列：$this -> insertSql($sqlQuery);
     * 作    者: Kinhon
     * 创建日期：2006-09-08
     ****************************************************************************
     * @param $sqlQuery
     * @return int|string
     */

    public function insertSql($sqlQuery)
    {
        $this->connectMySql();
        $this->query = mysql_query($sqlQuery, $this->mysqlConncet);
        $this->insertId = mysql_insert_id();
        return $this->insertId;
    }

    /*****************************************************************************
     * 函数名称：updateSql($sqlQuery)
     * 函数作用：更新一条记录（可以获得刚添加记录的ID）
     * 传入参数：$sqlQuery 要执行的SQL语句
     * 调用示列：$this -> 函数名称：updateSql($sqlQuery);
     * 作    者: Vence
     * 创建日期：2014-03-26
     ****************************************************************************
     * @param $sqlQuery
     */

    public function updateSql($sqlQuery)
    {
        $this->connectMySql();
        $this->query = mysql_query($sqlQuery, $this->mysqlConncet);
    }

    /*****************************************************************************
     * 函数名称：closeMySql()
     * 函数作用：关闭数据库连接
     * 传入参数：无
     * 返回值：  无
     * 调用示列：$MySql->closeMySql();
     * 作    者: Kinhon
     * 创建日期：2006-09-08
     *****************************************************************************/

    public function closeMySql()
    {
        if ($this->mysqlConncet != false) {
            mysql_close();
        }
    }

    public function mysqlInfo()
    {
        return mysql_get_server_info();
    }

}


require_once __DIR__ . '/../../bootstrap.php';

if (!function_exists('selectMysqlConnect')) {
    function selectMysqlConnect($t = '')
    {
        $t = $t ? $t : '0';
        $Connect = array(
            //平台主库
            '0' => array(
                'DB_HOST' => $_ENV['DB_HOST_34WAN_SITE'],
                'DB_USER' => $_ENV['DB_USERNAME_34WAN_SITE'],
                'DB_PWD' => $_ENV['DB_PASSWORD_34WAN_SITE'],
                'DB_NAME' => $_ENV['DB_DATABASE_34WAN_SITE'],
            ),
            //平台从库
            '1' => array(
                'DB_HOST' => $_ENV['DB_HOST_34WAN_SITE'],
                'DB_USER' => $_ENV['DB_USERNAME_34WAN_SITE'],
                'DB_PWD' => $_ENV['DB_PASSWORD_34WAN_SITE'],
                'DB_NAME' => $_ENV['DB_DATABASE_34WAN_SITE'],
            ),
            'purse' => array(
                'DB_HOST' => $_ENV['DB_HOST_34WAN_SITE'],
                'DB_USER' => $_ENV['DB_USERNAME_34WAN_SITE'],
                'DB_PWD' => $_ENV['DB_PASSWORD_34WAN_SITE'],
                'DB_NAME' => $_ENV['DB_DATABASE_34WAN_SITE'],
            ),
            'new_sdk_log' => array(
                'DB_HOST' => $_ENV['DB_HOST_34WAN_SITE'],
                'DB_USER' => $_ENV['DB_USERNAME_34WAN_SITE'],
                'DB_PWD' => $_ENV['DB_PASSWORD_34WAN_SITE'],
                'DB_NAME' => $_ENV['DB_DATABASE_34WAN_SITE'],
            ),
            'union_channel' => array(
                'DB_HOST' => $_ENV['DB_HOST_UNION_CHANNEL'],
                'DB_USER' => $_ENV['DB_USERNAME_UNION_CHANNEL'],
                'DB_PWD' => $_ENV['DB_PASSWORD_UNION_CHANNEL'],
                'DB_NAME' => $_ENV['DB_DATABASE_UNION_CHANNEL'],
            ),

        );
        return new MySql($Connect[$t]['DB_HOST'], $Connect[$t]['DB_USER'], $Connect[$t]['DB_PWD'], $Connect[$t]['DB_NAME']);
    }
}

if (!function_exists('needInfo')) {
    function needInfo($orderSql, $j = '')
    {
        $data = array();
        $obj = selectMysqlConnect($j);
        if ($orderSql != '') {
            $result = $obj->selectInfo($orderSql);
            if ($result) {
                $data = $obj->info;
            }
        }
        return $data;
    }
}

if (!function_exists('QueryInfo')) {
    function QueryInfo($orderSql, $j = '')
    {
        $result = false;
        $obj = selectMysqlConnect($j);
        if ($orderSql) {
            $result = $obj->querySql($orderSql);
        }
        return $result;
    }
}
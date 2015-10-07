### 数据库CURD

#### 相关方法
定义好数据库模型后，可以直接使用相关方法进行数据库操作
下面是部分方法：

    /**
     * 获取一条数据
     *
     * @param null $where
     * @param null $columns
     * @param null $sort
     * @return mixed
     */
    final public static function findFirst($where = null, $columns = null, $sort = null)
    
    /**
     * 获取所有数据
     *
     * @param null $where
     * @param null $columns
     * @param null $sort
     * @return array
     */
    final public static function all($where = null, $columns = null, $sort = null, $group = null)
    
    /**
     * 获取所有数据
     *
     * @param null $where
     * @param null $columns
     * @param null $sort
     * @param int $page
     * @param int $limit
     * @return array
     */
    final public static function find($where = null, $columns = null, $sort = null, $page = null, $limit = null)
    
    /**
     * sql查询
     *
     * @param $sql
     * @return mixed
     */
    final public static function fetchOne($sql)
    
    /**
     * sql批量查询
     *
     * @param $sql
     * @return array
     */
    final public static function fetchAll($sql)
            
    /**
      * 获取一条数据某一字段的值
      *
      * @param $where
      * @param $column
      * @param string $default
      * @return string
      */
    final public static function getByColumn($where, $column, $default = '')
 

    /**
      * 获取多条数据某一字段的值
      * @param $where
      * @param $column
      * @param string $index_key
      * @param null $group
      * @param null $sort
      * @return array
      */
    final public static function getByColumnArr($where, $column, $index_key = '', $group = null, $sort = null)
    
    /**
     * 删除数据
     *
     * @param $where
     * @return bool|PDOStatement
     */
    final public static function del($where)
    
    /**
     * 更新数据
     *
     * @param $data
     * @param $where
     * @return bool|PDOStatement
     * @throws Exception
     */
    final public static function update($data, $where)
        
    /**
     * 插入新数据
     *
     * @param $data
     * @return bool|int
     */
    final public static function create($data)
    
  有了上面的方法，即可很简单实现数据操作了
  
#### 简单实例

1. 查询
        
        // 查id为1的用户
        $where = 'id=1'
        //或 $where = array('id'=>1);
        UserModel::findFirst($where);
        // 查询所有数据
        $where='';
        UserModel::all($where);
        // 分页查询
        $curpage=0;
        $limit=10;
        $where='';
        $count = DriverModel::count($where);
        $res = DriverModel::find($where, '', "created desc", $curpage, $limit);
        
        
2. 更新

        // 更新id为1的用户
        $where = 'id=1'
        //或 $where = array('id'=>1);
        $data=array('name'=>'stephp');
        UserModel::update($data,$where);
        
        
3. 删除        
        
        // 删除id为1的用户
        $where = 'id=1'
        //或 $where = array('id'=>1);
        UserModel::del($data,$where);
      
4. 新增 

        $data=array('name'=>'stephp');
        UserModel::create($data,$where);

#### 其他

1. 直接使用通过流式数据库操作模式

        $aa = $this->db->from('user')->where('id', 2)->fetch();

2. 直接使用sql方法 

        UserModel::fetchOne($sql);

3. 事务处理 
  
        $this->db->begin();
        try{
            // 处理相关页面
            $this->db->commit();
        } catch (Exception $e) {
            Debug::log('task fail' . $e->getMessage());
            $this->db->rollback();
            Ajax::outError(Ajax::CUSTOM_ERROR_MSG, $e->getMessage());
        }
        
- 上一章： [数据库模型](model.md)
- 下一章： [插件式模块业务服务](service.md)
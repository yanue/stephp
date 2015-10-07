### 数据库模型

#### 简单实例
下面我们来结合数据库进行一些数据处理，首先需要了解一下数据库模型
    <?php
    
    namespace Model;
    
    use Library\Core\Model;
    
    class UserModel extends Model
    {
    }
将上面的文件存储为model/UserModel.php文件，就完成了一个用户表的数据库模型

#### 相关说明

- 所有数据库模型约定放在model目录（可以在加子目录，需要注意namespace）


- 一个表对应一个类，命名规则，默认将首字母大写，采用骆驼峰形式(即将表名的_替换为下一个字母大写) 并加上Model,如:
  
        表名为user则类名为UserModel
        表名为user_profile则类名为UserProfileModel
        
- 数据库模型支持多库操作

  因此数据库模型有个缺省的属性$database来指定数据库，如：
  
        class UserModel extends Model
        {
          public $database = 'sqlite';
        }
      
  这时，该模型指定了数据库配置文件的 **sqlite** 配置项
        
        return array(
            'db.defaultSqlDriver' => 'mysql',
            'mysql' => array(
                'host' => '112.124.106.166', //host address
                'port' => 3306, // db server port
                'user' => '', // user name for dbms
                'pass' => '', // pass word for dbms
                'name' => 'test', // default selected db name
                'driver' => 'mysql'
            ),
            'sqlite' => array(
                'file' => WEB_ROOT . '/data/sqlite.db',
                'driver' => 'sqlite3'
            ),
        );  
  

- 数据库模型目前支持mysql,sqlite,sql server，因此可以通过配置文件来指定数据库类型，如上面sqlite配置项

- 数据库模型的操作基于[fluentPdo](http://lichtner.github.io/fluentpdo/),使用简单,这里已经整合，具体看下一章


### mongo模型
    mongodb的模型和上面一样，只需要继承Mongo类即可
        
- 上一章： [视图](view.md)
- 下一章： [数据库基础CURD](curd.md)
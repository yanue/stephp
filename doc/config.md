## 配置文件
    #### 基础配置：config/config.php
        
        # ==== php配置 ====
        $config['timezone'] = "PRC";
        $config['display_errors'] = true;
        $config['debug'] = true;
        $config['timezone'] = "PRC";
        $config['display_errors'] = true;
        $config['debug'] = true;
        # ==== 默认应用配置 =====
        $config['module'] = "home"; #默认模块
        $config['controller'] = "index";
        $config['action'] = "index";
        $config['suffix'] = ".html"; # 请不要保护那个'.'
        
        return $config;
    
    
    #### 数据库配置：config/database.php
       
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
    
- 上一章： [介绍](intro.md)
- 下一章： [URL分发及路由](dispatcher.md)
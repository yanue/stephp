### 插件式模块业务

#### 相关方法
由于很多模块业务逻辑会复用，因此这里抽出一个基于插件Plugin模式的类似Dao的服务层
设计上的低耦合带来了很大的方便

#### 简单实例

比如，用户登陆状态处理：

    <?php
    /**
     * Created by PhpStorm.
     * User: yanue
     * Date: 7/3/15
     * Time: 10:35 AM
     */
    
    namespace Service;
    
    
    use library\cache\Cache;
    use Library\Core\Plugin;
    use Model\UserModel;
    
    class UserStatus extends Plugin
    {
        const prefix = 'user_';
        const driver = 'redis';
    
        public function __construct()
        {
            parent::__construct();
        }
    
        public static function exists($uid)
        {
            return Cache::getInstance(self::driver)->exists(self::prefix . $uid);
        }
    
        public static function get($uid)
        {
            return Cache::getInstance(self::driver)->get(self::prefix . $uid);
        }
        
        public static function getFromDb($uid)
        {
            return UserModel::findfirst('id='.$uid);
        }
                
        public static function save($uid, $sess)
        {
            return Cache::getInstance(self::driver)->save(self::prefix . $uid, $sess);
        }
    
        public static function remove($uid)
        {
            return Cache::getInstance(self::driver)->delete(self::prefix . $uid);
        }
    
        public static function getUid()
        {
            return isset($_REQUEST['uid']) ? intval($_REQUEST['uid']) : 0;
        }
    
        public static function getUserName()
        {
            $cache = Cache::getInstance(self::driver)->get(self::prefix . self::getUid());
            return $cache ? $cache['user_name'] : '';
        }
    
        public static function getAvatar($uid = 0)
        {
            $uid = $uid ? $uid : UserStatus::getUid();
            $folder = substr($uid, -2);
            return '/avatar/' . $folder . '/' . $uid . '.png';
        }
    
    }

如上面示例：这里面进行了缓存相关操作，数据库的操作等，放在这里使用都是十分方便的。
因此有类似于这样的需求的，都要以这种模式去实现
这里面涉及到一个很重要的继承Plugin

#### Plugin类说明

    <?php
    /**
     * Created by PhpStorm.
     * User: yanue
     * Date: 10/25/14
     * Time: 9:31 AM
     */
    
    namespace Library\Core;
    
    
    use Library\Di\DI;
    use Library\Di\Injectable;
    
    class Plugin extends Injectable
    {
        /**
         * @var string
         */
        protected $controller = null;
    
        /**
         * @var string
         */
        protected $action = null;
    
        /**
         * @var string
         */
        protected $module = null;
    
        /**
         * @var string
         */
        protected $api = null;
    
        /**
         * @var string
         */
        protected $mvc_uri = null;
    
    
        public function __construct()
        {
            $this->setDI(new DI());
            $this->module = $this->uri->getModule();
            $this->controller = $this->uri->getController();
            $this->action = $this->uri->getAction();
            $this->api = $this->uri->getApi();
        }
    
        public function getCurrentMvcUri($separation = '/')
        {
            if ($this->api) {
                $this->mvc_uri = $this->module . $separation . $this->api . $separation . $this->controller . $separation . $this->action;
            } else {
                $this->mvc_uri = $this->module . $separation . $this->controller . $separation . $this->action;
            }
            return $this->mvc_uri;
        }
    } 

这里面做了很少的操作，因为在网站运行时，就将很多依赖的类自动引入了，这就是di层带来的便利

另外如果继承了Plugin并且需要使用构造函数的话，那么必须在里面加入下面这行代码：
                           
       parent::__construct();
       
否则依赖注入的类将无法引入。

网站运行时默认会引入的类库如下：
    
        /**
         * @var \Library\Core\Uri
         */
        protected $uri;
        
        /**
         * @var \Library\Core\Request
         */
        protected $request;
        
        /**
         * @var \Library\Core\View
         */
        protected $view;
        
        /**
         * @var \Library\Util\Session
         */
        protected $session;
        
        /**
         * @var \Library\Core\Response
         */
        protected $response;
        
        /**
         * @var \Library\Db\Fluent\FluentPDO
         */
        protected $db;
        
        /**
         * @var \Library\Cache\Cache
         */
        protected $cache;
        
- 上一章： [数据库操作CURD](curd.md)
- 下一章： [常见类库](class.md)
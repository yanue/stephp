### 常见类库使用说明

由上一章所说，由于网站运行时，已经依赖注入了几个重要的类库，因此我们可以直接通过继承Plugin就可以使用相应的类库

另外在控制器内和模板里也是可以直接使用，很方便

下面讲述一些常用的类及相关方法

#### Request类

位于library/core/Uri

这个很重要，里面实现了$_GET $_POST $_REQUEST的获取和url参数获取

常用方法：

    /**
     * get key data
     *
     * @param $key
     * @param string $type
     * @param string $default
     * @return bool|float|int|null
     */
    $this->request->get($key,$type='',$default='')

该类默认引入，通过以上方式直接使用（继承了Plugin的同理）

#### Uri类

位于library/core/Uri

这个类实现了很多url的处理，功能很强大

常用方法：

     * 获取url中匹配key的值
     * --说明: 可以通过key获取包含path部分和?后面的query部分以key=>val结构的val
     *  -例http://localhost/mvc/index/index/te/ed/test/a/p/2/a.html?c=d 通过te可以获取ed,c获取d
     *
     * @param $key
     * @param null $default
     * @return null
     */
    $this->uri->getParam($key, $default = null)
    
    /**
     * 获取url中path部分的除去mvc结构的第n个参数
     * --说明:
     *  -例/index/index/a/b, 如果默认module是home,那么控制器就是index,方法是index,因此后面的为/a/b,取1时为a,以此类推
     *  -例/home/index/index/c/d 默认module为home,那么mvc结构部分为/home/index/index,其他同上
     *  -例/admin/index/index/e/f/g/h 默认module为home,那么mvc结构部分为/admin/index/index,其他同上
     *
     * @param int $n 除去mvc结构的第n个参数(以0开始)
     * @return string
     */
    $this->uri->getUriSegment($n)
    
    /**
     * 获取url中最后一个path部分最后一个参数
     * --说明:当path部分的数量为单数,则以key=val匹配后剩下的最后一个path参数key
     *
     * @return string
     */
    $this->uri->getLastParam()
    

    $this->uri->getMvcUri()
    

    /**
     * 获取url中?后面query部分(a=b&c=d)的值
     * --说明:当怕怕path部分的数量为单数,则以key=val匹配后剩下的最后一个path参数key
     *
     * @param string $key 例:(a=b&c=d)中=前面参数a
     * @return string 匹配的值
     */
    $this->uri->getQuery($key)
    
    /**
     * 返回当前页面完整url
     *
     * @return string
     */
    $this->uri->getFullUrl()
    

    /**
     * 获取uri部分
     *
     * @return string
     */
    $this->uri->getUriString()
    

    /**
     * 获取uri中?后面query部分
     *
     * @return string
     */
    $this->uri->getQueryString()
    

    /**
     * 获取uri中path部分
     *
     * @return string
     */
    $this->uri->getPathString()
    

    /**
     * 当前页面url构造(用于分页地址构造等)
     *
     * @param string $uri array(key=>val)或者/a/b?a=b 更新或添加到url中目录结构path部分
     * @param array $del_arr array(key) 删除原有path中的匹配key部分
     * @return string 新构造url
     */
    $this->uri->setUrl($uri = '', $del_arr = array())
    
    /**
     * baseUrl
     *
     * @param string $uri
     * @param bool $setSuffix
     * @return string
     */
    $this->uri->baseUrl($uri = '', $setSuffix = true)
    
    /**
    * 获取当前模块的url
    *
    * @param string $uri
    * @param bool $setSuffix
    * @return string
    */
    $this->uri->getModuleUrl($uri = '', $setSuffix = true)
    
    
    /**
    * 获取当前控制器的url
    *
    * @param string $uri
    * @param bool $setSuffix
    * @return string
    */
    $this->uri->getControllerUrl($uri = '', $setSuffix = true)
    
    
    /**
    * 获取当前方法的url
    *
    * @param string $uri
    * @param bool $setSuffix
    * @return string
    */
    $this->uri->getActionUrl($uri = '', $setSuffix = true)


如上面所列方法，可以给开发带来很大便捷
下面讲解一下几个方法

##### setUrl方法
  - 作用：改变当前页面url路径及参数
  - 应用场景：分页，多级筛选
  - 使用参数：    
  
            * @param string $uri array(key=>val)或者/a/b?a=b 更新或添加到url中目录结构path部分
            * @param array $del_arr array(key) 删除原有path中的匹配key部分
            
  - 实例说明：
    如当前页面url:  （搜索stephp用户并处于第一页）
    
        /user/list/p/1?key=stephp
    
    下一页的时候，需要改变为：/user/list/p/2?key=stephp，因此：
    
        $this->uri->setUrl(array('p'=>2));
    
    如果同时需要改变key值：/user/list/p/3?key=change，可以：
    
        $this->uri->setUrl('/p/3?key=change');
    
    然后需要清除掉搜索关键字是：/user/list/p/3，可以
    
        $this->uri->setUrl('','key');
        
    总之，可以根据参数说明改变url，但这个只局限于当前页面

##### getControllerUrl方法
  - 作用：改变当前页面url为当前控制器下
  - 应用场景：模板页面内链接
  - 使用参数：    
         
          * @param string $uri
          * @param bool $setSuffix
         
  - 实例说明：
    如当前页面 user/list 模板内有个链接地址需要跳转到 个人资料 user/profile
    链接地址可以手动填写，但是很容易忘记前面的模块（或者更换默认模块时就必须加上module模块名）
    因此可以通过这种方法解决这样的问题,如下：
    
        $this->uri->getControllerUrl('profile?uid='.$uid,'key');
    url起始位置就是当前controller，因此只需加上profile就可以跳转了
    
    还有其他类似这样的参数
    
#### Pagination类

位于library/util/Pagination

功能：处理分页模板数据渲染

##### 实例：

- 第一步： 在控制器内

        $page = $this->request->get('p');
        $curpage = $page <= 0 ? 0 : $page - 1;
        $limit = 12;

        $count = DriverModel::count($where);
        $res = DriverModel::find($where, '', "created desc", $curpage, $limit);
        Pagination::instance()->showPage($page, $count, $limit);

- 第二步： 在模板内
        
    <?php \Library\Util\Pagination::instance()->display(); ?>


#### Upload类

位于library/util/Upload

功能：用于上传文件

##### 实例：
    
      <?php
      /**
       * Created by PhpStorm.
       * User: yanue
       * Date: 4/8/14
       * Time: 11:37 AM
       */
      
      namespace App\Admin\Api;
      
      use Library\Core\Config;
      use Library\Util\Ajax;
      
      class UploadController extends ApiBase
      {
          /**
           * upload img
           */
          public function imgAction()
          {
              // 初始化配置
              $conf = Config::getSite('upload', 'pic');
              $upload = new Upload();
              $upload->upExt = $conf['ext'];
              $upload->maxAttachSize = $conf['maxAttachSize'];
              $buff = $upload->upOne();
              $this->checkFile($buff['md5']);
      
              $file = $upload->saveFile($buff);
              $fileUrl = rtrim(Config::getSite('upload', 'baseUrl'), '/') . '/' . $file['url'];
              $url = $file['url'];
      
             // $id = $this->syncDb('img', array('md5' => $buff['md5'], 'ext' => $buff['ext'], 'url' => $url, 'size' => $buff['size'], 'name' => $buff['name']));
      
              Ajax::outRight(array('url' => $url, 'id' => $id, 'name' => $buff['name'], 'ext' => $buff['ext']));
          }
      }
      
#### Ajax类

位于library/util/Ajax

功能：Ajax格式及错误信息输出

##### 实例：      
    
        /**
         * del
         */
        public function delAction() #删除集装箱信息#
        {
            // get params
            $data = $this->request->get('data');
            if (!$data) {
                Ajax::outError(Ajax::INVALID_PARAM);
            }
            if (!is_array($data)) {
                Ajax::outError(Ajax::INVALID_PARAM);
            }
    
            $status = UserModel::update(array('is_deleted' => 1), 'id in ( ' . implode(',', $data) . ' ) ');
    
            if (!$status) {
                Ajax::outError(Ajax::ERROR_NOTHING_HAS_CHANGED, '删除失败！');
            }
    
            $this->admin_log->add(array('id' => $data, 'status' => $status));
            // out right info
            Ajax::outRight();
        }


#### 其他

还有很多其他方法就不在赘述了
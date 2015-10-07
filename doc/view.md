### 视图渲染
如前面实例，我们需要渲染user/profile页面

	<?php
    namespace App\Home\Controller;
	// HomeBase.php文件
    class HomeBase extends Controller {
    
           public function __construct()
           {
                parent::__construct();
                // 处理公用逻辑
           }
    }
    ?>
    
	<?php
    namespace App\Home\Controller;
	// UserController.php文件继承HomeBase
    class UserController extends HomeBase {
    
           public function profileAction()
           {
                // 处理自身
           }
    }
    ?>
首先，可以直接在控制器里面直接输出（如echo，print_r等），但是我们需要渲染模板文件
这里有几个重要函数：（可以通过控制器方法$this->view->方法名进行使用）
	
	 /**
     * set layout
     *
     * @param string $layout : 需要使用的布局模块名称
     * @param string $content : 当前action在layout内引用的内容模板
     * @return void;
     */
    public function setLayout($layout = 'layout', $content = '')
    
	/**
     * set layout content
     *
     * @param string $content : 当前action在layout内引用的内容模板
     * @return void
     */
    public function setContent($content = '')
    
    /**
     * 禁用layout
     *
     */
    public function disableLayout()
    
    /**设置变量
     * @param $key
     * @param $value
     */
    public function setVar($key, $value)
    
    /**
     * render  -- to include template
     *
     * @param string $name : 当前模块视图下相对路径模块名称.
     * @param string $var 模板变量
     * @return void
     */
    public function render($name, $var = null)
    
    /**
     * include layout content
     *
     * 说明 : 加载layout布局下的当前action的内容模板,于layout模板内使用
     */
    public function content()
    
    /**
     * 模板显示功能
     *
     */
    public function display()


1. 视图相关说明 
   - 模板直接使用php渲染，需要用到smart等模板时需要自行扩展
   - 模板文件默认以.phtml为后缀的php文件，是为区分其他php文件
   - 模板文件规则，以user/profile为例：
     模板处于app/home/view为主的目录下（以下都省略）
     
     默认的动作名对应的模板文件user/profile.phtml
     即：一个控制器对应一个目录，一个动作名对应一个phtml文件并位于相应的控制器目录
     
2. layout布局

	layout是将常用的布局结构整合在一切公用,如下定义一个layout.phtml文件位于app/home/view下
        
        <!doctype html>
        <html lang="en-US">
        <head>
            <meta charset="UTF-8">
            <title>hello world - stephp</title>
        </head>
        <body>
        <?php
        $this->render('header');
        echo '<hr>';
        $this->content();
        echo '<hr>';
        $this->render('footer');
        ?>
        </body>
        </html>
        
	**说明**：

    所有模板都是一个View对象(可以在模板内打印$this查看)，这里面用到了render和content方法，
    render方法是引用一个其他的模板文件
	  	
3. 变量传递
   - 控制器需要传递参数给模板，可以通过方法：
   
       $this->view->setVar(参数名,参数内容)
       
    设置后可在引用进去的任意模板文件内直接通过 **$参数名** 使用
    
    **注意**：这里是通过controller直接传递参数
    
4. 局部引用视图
 -  模板有很多公用的地方，可以提取出来，放到另外文件进行引用，可以在控制器内或模板内直接使用
       
       // 控制器
       $this->view->render(文件名)
       
       // 模板内
       $this->render(文件名,传递参数对应key=>val)
       // 由于模板之间变量是不能直接访问的，必要时可以通过传递参数形式实现

    **注意**：这里是传递参数，是用于模板内容

5. 引用用方法
   由于采用低耦合和依赖注入的形式，模板内还可以直接使用调用类库和一些默认基础载入的
   如：渲染分页
   
        <?php 
            echo  \Library\Util\Pagination::instance()->display();
        ?>
   如：设置链接
   
        $this->uri->setUrl('a=b');
        $this->uri->getModuleUrl('a=b');
        
- 上一章： [控制器](dispatcher.md)
- 下一章： [数据库模型](model.md)
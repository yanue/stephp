### 控制器
和其他mvc相似，控制器位于模块下的controller或api目录（暂不支持控制器子目录）

#### 简单实例
假设这个 URI:

	example.com/user/profile

我们先要定义一个模块（默认home，位于app/home）,加入我们需要用户相关信息：

	<?php
	namespace App\Home\Controller;
 
    class UserController extends Controller {
    
           public function indexAction()
           {
                // 个人中心
           }
           
           public function profileAction()
           {
                // 个人资料
                echo 'profile';
           }
           
    }
    ?>
##### 注意：
* 控制器文件必须加上对应的namespace，基于项目跟目录开始即App\Home\Controller
* 控制器必须加上Controller 即：User+Controller=UserController
* 动作名必须加上Action 即：profile+Action=profileAction

如果要在你的任意控制器中使用构造函数的话，那么必须在里面加入下面这行代码：

    parent::__construct();

这行代码的必要性在于，你此处的构造函数会覆盖掉这个父控制器类中的构造函数，所以我们要手动调用它。

    <?php
    class UserController extends Controller {
    
           public function __construct()
           {
                parent::__construct();
           }
    }
    ?>
如果你需要设定某些默认的值或是在实例化类的时候运行一个默认的程序，那么构造函数在这方面就非常有用了。
构造函数并不能返回值，但是可以用来设置一些默认的功能。

### 特殊方法
	actionBefore 如果Contoller存在actionBefore方法，将会在执行当前action方法之前执行
	actionAfter 如果Contoller存在actionAfter方法，将会在执行当前action方法之后执行
	
### 继承处理
控制器可以继承控制器，有时需要统一处理一些逻辑，可以通过继承来实现

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
	
- 上一章： [URL分发及路由](dispatcher.md)
- 下一章： [视图处理](view.md)
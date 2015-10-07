# url分发及路由
## 基于uri段
1. stephp 使用基于段的方法:
 >example.com/module/controller/action
 >example.com/module/api/controller/action(针对api特别处理)

2. 也可以通过URL的query模式
 
> example.com/index.php?m=home&c=test&a=debug

 但这种对浏览器不友好，应该转换为基于段的模式，转换后如下：（Apache使用.htaccess，nginx见配置）
 >example.com/home/test/debug

说明：
- 对于默认的模块，可以在url直接去掉。如

> example.com/home/article/detail 可以直接简写为 example.com/article/detail

- 动作名为默认的index也可以直接去掉（url上有其他段参数时不能去掉）。如

>    example.com/home/article/index 可以直接简写为 example.com/article/
>    example.com/home/article/index/p/2(不能去掉动作名)
 
- 最后，只有动作名和控制器名都为默认index时，可以直接去掉

>  example.com/home/index/index 可以直接简写为 example.com/home 最后简化为：example.com

## uri路由
上面那种默认的url匹配模式，有些时候估计不是很美观或其他原因需要更改，可以通过uri路由实现：
>（主要参考ci框架的思路：[http://codeigniter.org.cn/userguide2/general/routing.html](http://codeigniter.org.cn/userguide2/general/routing.html)）

 路由规则定义在config/routes.php 文件中. 在此文件中，你可以看到一个名为 $route的数组，它可以让你定义你自己的路由规则。

 定义可以用两种方式： 通配符(wildcards) 或者 正则表达式(Regular Expressions)

一个典型的通配符路由看起来是这样的：

> $route['product/(:num)'] = "catalog/product_lookup";

在一个路由中,数组的键包含着被匹配的URI,而数组的值包含着路由将被重定向的目的地.在上面的例子中,如果单词“product”出现在URL的第一个部分中，而且数字(:num)出现在URI的第二个部分中,"catalog"类和"product_lookup"方法将被替代使用(即将被重定向).

你可以匹配文字的值或者使用以下两种通配符类型:

> :num 将匹配一个只包含有数字的segment(段).
:any 将匹配任何字符(可以是多个segment段).可以匹配多个值，如：
$route['product/(:any)'] = "catalog/product_lookup/$1/$2/$3/$4/$5";        //将整条url上的每一个参数全部传递给catalog控制器下的 product_lookup方法。

注意: 路由将会按照定义的顺序来运行.高层的路由总是优先于低层的路由.

下面是一些简单的例子:

> $route['journals'] = "blogs";

如果URL的第一个分段（类名）是关键字"journals"，那么将会重定向到"blogs"类中处理.

> $route['blog/joe'] = "blogs/users/34";

如果URL的前两个分段是"blog"和"joe"，那么将会重定向到"blogs"类的"users"方法中处理.并且将ID"34"设为参数.

> $route['product/(:any)'] = "catalog/product_lookup";

当"product"作为URL中第一个分段时, 无论第二分段是什么都将被重定向到"catalog"类的"product_lookup"方法.

> $route['product/(:num)'] = "catalog/product_lookup_by_id/$1";

当“product”作为 URL 中第一个分段时，如果第二分段是数字，则将被重定向到“catalog”类，并传递所匹配的内容到“product_lookup_by_id”方法中。
如果你喜欢可以使用正则表达式来自定义你的路由规则. 任何有效的正则表达式都是被允许的, 甚至逆向引用.

注意:  如果你使用逆向引用请将双反斜线语法替换为美元符语法（\\1 替换为 $1).
一个典型的正则表达式看起来像下面的样子:

> $route['products/([a-z]+)/(\d+)'] = "$1/id_$2";

上例中, 类似于 products/shirts/123 的URI 将换成调用 shirts 控制器类的 id_123 方法.

你也可以混合使用通配符与正则表达式.


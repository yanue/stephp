stephp
======
A Simple Tiny Easy PHP mvc Framework
------------------------------------
### 查看文档
1.[查看文档](http://stephp.yanue.net)<br />

### --命名规则:

-遵循骆驼峰命名规则,类名需要首字母大写

-控制器: 控制器名称+Controller.php 控制器类名和文件名相同 例: testController.php,控制器类名:testController
-控制器方法: 方法名+action 例: testAction();

-控制器文件位于当前模块下的controllers目录
-模型文件位于当前模块下的models目录
-视图文件位于当前模块下的views目录

### --目录结构
    project # 项目根目录
    ├─app   # 应用目录
    │  └─default        # 模块
    │     ├─controllers # 控制器
    │     ├─models      # 模型
    │     └─views       # 试图
    │        ├─index    # 对应于IndexController->indexAction.
    │        └─layout.php # layout
    ├─assets        # 该目录可以自定义,默认前端采用seajs模块化开发结构
    │  ├─images     # 图片
    │  ├─scripts    # 脚本
    │  └─styles     # 样式
    ├─configs 配置文件
    │  └─application.ini    # 应用配置
    ├─data      # 数据存放
    ├─library   # 系统类库
    │  ├─core   # 系统初始化核心处理
    │  ├─db     # 数据库操作
    │  ├─func   # 基础公用函数
    │  └─util   # 基础工具类
    ├─.htaccess # 去除url上index.php
    ├─index.php # 入口文件
    └─README.md # 简单说明文档

### --简单执行流程
打开url->加载类库->解析url->检查是否经过路由->分陪mvc->执行控制器方法->数据处理->视图输出



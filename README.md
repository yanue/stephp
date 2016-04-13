## stephp框架介绍

### 框架特点
* 体积小，简单易用
* 基于namespace自动加载类库
* 低耦合，依赖注入，易扩展
* 强大url及路由处理能力
* 强大缓存处理方式
* 灵活的配置

### 目录结构
    project # 项目根目录
        ├─app   # 应用目录
        │  └─home           # 模块
        │     ├─controller # 控制器
        │     ├─model      # 模型
        │     └─view       # 试图
        │        ├─index    # 对应于IndexController->indexAction.
        │        └─layout.php # layout
        ├─assets        # 该目录可以自定义,默认前端采用seajs模块化开发结构
        │  ├─images     # 图片
        │  ├─js         # 脚本
        │  └─css        # 样式
        ├─cache         # 缓存目录及日志默认
        ├─config 配置文件
        │  ├─config.php   # 应用配置
        │  ├─database.php # 数据库配置
        │  └─router.php   # 路由配置
        ├─data      # 数据存放
        ├─library   # 系统类库
        │  ├─cache  # 缓存处理
        │  ├─core   # 系统初始化核心处理
        │  ├─di     # 依赖注入
        │  ├─db     # 数据库操作
        │  └─util   # 基础工具类
        ├─model     # 数据库模型
        ├─service   # 业务逻辑处理
        ├─.htaccess # 去除url上index.php
        ├─index.php # 入口文件
        └─README.md # 简单说明文档

### 命名规则
- 遵循骆驼峰命名规则,类名需要首字母大写

- 控制器: 控制器名称+Controller.php 控制器类名和文件名相同 例: TestController.php,控制器类名:TestController
- 控制器方法: 方法名+action 例: testAction();

- 控制器文件位于当前模块下的controller目录
- 模型文件位于当前模块下的model目录
- 视图文件位于当前模块下的view目录

### 特别注意
 项目是基于namespace自动引入类库，因此类名所在目录命名必须为小写，文件名必须和类名一致，类文件必须加上命名空间（以项目跟目录为起始，可将目录首字母大写，如：Library/Core即library/core目录）
 
### 执行流程
 打开url->加载类库->解析url->检查是否经过路由->分陪mvc->执行控制器方法->数据处理->视图输出


### 已有案例
* [优递莱思集装箱监控调度平台](http://utlz.cn/)
* [创客港](http://bzsns.cn/)
* [创富港](http://webwework.com/)
* [黔东南州水利民生工程](http://www.ygsl.gov.cn/)
* [estt官网](http://estt.com.cn/)
* [捷明实业官网](http://www.fu5.com.cn/)
* [爱玩儿网](http://aiwaner.net/)
* [yanue.net](http://yanue.net)
* ...


查看文档 

[查看文档](doc/index.md)



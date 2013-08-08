<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>hello world - stephp with seajs</title>
    <meta name='author' content='yanue' />
    <meta name='copyright' content='Looklo Team' />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrl(); ?>assets/styles/base.css" />
    <script type="text/javascript" src="<?php echo $this->baseUrl(); ?>assets/scripts/seajs/sea.js"></script>
    <script>
        seajs.config({
            charset: 'utf-8',
            alias: {
                'jquery': 'jquery/jquery.last',
                'seajs-log':'seajs/plugins/seajs-log',
                'seajs-debug':'seajs/plugins/seajs-log'
            },
            debug:true,
            preload: ["jquery",'seajs-log']
        });
        seajs.use('app/main',function(m){
            m.test();
        });
    </script>
</head>
<body>
<!-- 头部区域 -->
<div id="content">
    <!-- 内容区域 -->
    <p>
        Stephp - A Simple Tiny Easy PHP MVC Framework
    </p>
    <dl>
        <dt>see the docs:</dt>
        <dd>
            <a href="http://yanue.github.io/stephp" target="_blank">http://yanue.github.io/stephp</a>
        </dd>
        <dd>
            <a href="http://stephp.yanue.net/" target="_blank">http://stephp.yanue.net/docs/</a>
        </dd>
    </dl>
    <hr>
    <p>可以使用seajs进行js模块化开发</p>
    <p>seajs 文档 <a href="http://seajs.org/docs">http://seajs.org/</a></p>
    <hr>
</div>
</body>
</html>
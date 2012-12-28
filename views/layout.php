<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>后台管理中心 - 空间说</title>
    <meta name="robots" content="noindex, nofollow" />
    <meta name='author' content='yanue' />
    <meta name='copyright' content='Looklo Team' />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrl('public/css/yanue.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrl('public/css/content.css'); ?>" />
    <script type="text/javascript" src="<?php echo $this->baseUrl('public/js/seajs/sea.js'); ?>"></script>
    <script>
        seajs.config({
            base: '<?php echo $this->baseUrl('public/js/'); ?>',
            charset: 'utf-8',
            timeout: 200000,
            alias: {
                'jquery': "<?php echo $this->baseUrl('public/js/jquery.last.js'); ?>"
            },
            preload: ["jquery"]
        });

        // 将 jQuery 暴露到全局
        seajs.modify('jquery', function(require, exports){
            window.jQuery = window.$ = exports;
        });


        seajs.use('yanue.init', function(y){
            y.autoheight();//自适应高度
            y.menu();//加载左边导航
        });

		var Looklo = Looklo || {};
		Looklo.site_url = '<?php echo $this->baseUrl(); ?>';
        Looklo._CUID = '<?php echo 1; ?>';
        Looklo._CUSER = '<?php echo 'yanue';?>';
    </script>
</head>
<body>
<!-- 头部区域 -->
<?php

$this->render('header');
?>
<!-- 内容区域 -->
<div id="main">
    <?php
    $this->render('sidebar');
    $this->render('showpath');
    ?>
    <div id="content">
        <?php  $this->content();  ?>
    </div>
    <div class="clear">
    </div>
</div>
<div class="clear">
</div>
<!-- 底部区域 -->
<?php
$this->render('footer');
$this->render('ajaxStatus');
?>
</body>
</html>

<h1 class="ctitle">欢迎进入后台管理中心</h1>
<p>你还没有登录，请先登录！</p>
<script type="text/javascript">
	seajs.use('looklo.action',function(a){
		a.login('#loginDo');
	});
</script>
<form action="logindo" id="loginForm">
	<p><label for="">用户名：</label><input type="text" class="txt" id="user" value=""> <span class="status"></span></p>
    <p><label for="">密 码：</label><input type="password" class="txt" id="passwd"> <span class="status"></span></p>
	<p><label for=""></label><input type="button" value="登陆" id='loginDo' class="deep_btn"></p>
</form>
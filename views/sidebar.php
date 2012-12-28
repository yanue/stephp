<div id="sidebar">
	<div id="user">
		<?php
			if(isset($_SESSION['_CUID']) && $_SESSION['_CUID']>0){
		?>
			<p>
				欢迎您！<span style="color: #f00;">普通管理员</span>
			</p>
			<p>
				用户: <a href=""><?php echo isset($_SESSION['_CUSR']) ? $_SESSION['_CUSR'] : '请先登陆' ;?></a>
			</p>
			<p>
				[ <a href="<?php echo $this->baseUrl('login/loginout');?>">退出</a>
				] [ <a href="">修改信息</a>
				]
			</p>
		<?php }else{ ?>
			<p style="text-align:center;padding:10px 0 0 0;line-height:24px;font-size:14px;font-family:'Microsoft YaHei';">您还没有登录！</p>
			<p style="text-align:center;"><a href="<?php echo $this->baseUrl('login');?>">请先登录</a></p>
		<?php  } ?>
	</div>

   	<div id="sideMenu">
           <ul id="leftIndex">
			  	<?php
			   		require_once 'configs/menu.php';
			   		foreach($navs as $key => $val){
			   	?>
               		<li class="topIndex <?php echo 'nav'.$this->curNav == $key ? 'selected' : '' ;?>" for="<?php echo $key; ?>"><a href="javascript:;"><?php echo $val; ?></a></li>
				<?php } ?>
           </ul>
           <div id="menu">
			   	<?php
					foreach ($menus as $navKey=>$navVals){
				?>
				<div class="<?php echo $navKey; ?>" style="<?php echo 'nav'.$this->curNav==$navKey ? 'display:block' :'display:none';?>">
					<?php foreach ($navVals as $menuKey => $menuVal) {
						$isCur =  $navKey.'_'.$menuKey;
						$cur = 'nav'.$this->curNav.'_menu'.$this->curMenu;
						?>
					<dl>
						<dt class="menuTitle"><?php echo $menuVal; ?></dt>
						<dd class="menuList" style="display: <?php echo $isCur==$cur ?'block':'none';?>">
							<?php
								foreach($subMenus as $subKey=>$subVal){
									if($subKey == $isCur){
										foreach($subVal as $subMenuKey => $subMenuVal){
							?>
							<p><a href="<?php echo $subMenuVal[1]; ?>" class="<?php echo $cur.'_subMenu'.$this->curSubMenu == $isCur.'_'.$subMenuKey ? 'current':'';?>"><?php echo $subMenuVal[0];?></a></p>
							<?php }}} ?>
						</dd>
					</dl>
				<?php } ?>
                </div>
				<?php } ?>
           </div>
		   <div class="clear"></div>
   	</div>
</div>

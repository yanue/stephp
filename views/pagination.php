<?php
if ($this->pageCount) {
	if (@$this->keywords) {
		$keyword = "?keywords=" . $this->keywords;
	} else {
		$keyword = null;
	}
	?>
<?php if($this->total){?><span style="float: right;">总<?php echo $this->pageCount;?>条记录,每页<?php echo $this->perpage;?>条,当前<?php echo $this->total;?>/<?php echo $this->page;?>页</span><?php }?>
<!-- First page link -->
<?php if (isset($this->previous)){?>
<a
	href="<?php echo $this->url(array('page' => $this->first)).$keyword; ?>">
	首页 </a>
|
<?php }else{ ?>
<span class="disabled">首页</span>
|
<?php }; ?>

<!-- Previous page link -->
<?php if (isset($this->previous)){?>
<a
	href="<?php echo $this->url(array('page' => $this->previous)).$keyword; ?>">
	&lt; 上一页 </a>
|
<?php }else{ ?>
<span class="disabled">&lt; 上一页</span>
|
<?php }; ?>

<!-- Numbered page links -->
<?php foreach ($this->pagesInRange as $page){?> 
  <?php if ($page != $this->current){?>
<a href="<?php echo $this->url(array('page' => $page)).$keyword; ?>"><?= $page; ?></a>
| 
  <?php }else{ ?>
    <?= $page; ?> | 
  <?php }; ?>
<?php }; ?>

<!-- Next page link -->
<?php if (isset($this->next)){?>
<a
	href="<?php echo $this->url(array('page' => $this->next)).$keyword; ?>">
	下一页 &gt; </a>
|
<?php }else{ ?>
<span class="disabled">下一页 &gt;</span>
|
<?php }; ?>

<!-- Last page link -->
<?php if (isset($this->next)){?>
<a
	href="<?php echo $this->url(array('page' => $this->last)).$keyword; ?>">
	尾页 </a>
<?php }else{ ?>
<span class="disabled">尾页</span>
<?php }; ?>
<?php }; ?>


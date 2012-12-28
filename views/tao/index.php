<h1 class='ctitle'>商品管理 <a href='' class='gary_btn'>删除</a></h1>

<table cellspacing="1" cellpadding="1" width='100%' class='list' >
    <tr class="head">
        <th width='24px'><input type="checkbox" id='selectAll' class="chk"></th>
        <th width='60px'>产品ID</th>
        <th width='380px'>标题</th>
        <th width='100px'>价格</th>
        <th width='90px'>淘客商品ID</th>
        <th width='120px'>最近更新</th>
        <th width=''>更新</th>
    </tr>
	<?php foreach($this->products as $product){?>
	<tr>
		<td><input type="checkbox" name="selectAll" class="chk"></td>
        <th class='name'><?php echo $product['uid'];?></th>
        <td><?php
			$brief_and_url = json_decode($product['brief_and_url']);
				echo '<a href="'.$brief_and_url->click_url.'" target="_blank">'.$brief_and_url->brief.'</a>';
			?></td>
		<td>￥ <?php echo $product['price']/100;?></td>
		<td><?php echo $product['tao_id'];?></td>
		<td><?php echo date('Y-m-d H:i',$product['modify_time']); ?></td>
		<td><a href="">更新</a></td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan="7">
			<div class='pagination'><?php $this->render('pagination');?></div>
		</td>
	</tr>
</table>
<h1 class='ctitle'>专业人士评价管理</h1>
<div class="search">
	<p><b>评价搜索</b></p>
	<p>
		<select name="" id="" class="selc">
			<option value="">评价ID</option>
			<option value="">评价人UID</option>
			<option value="">被评价人UID</option>
			<option value="">标题/描述</option>
		</select>
		<input type="text" name="" class="txt" id="">
		<input type="text" name="" class="txt" id="">
		<input type="button" value="搜索" class="deep_btn">
	</p>

</div>
<?php
	$isPublish = array('未审核','通过','未通过');
?>
<script type="text/javascript">
seajs.use('looklo.common',function(c){
	c.selectAll('#selectAll','.list .chk');
});
</script>

<p class="action">
    <input type="button" class="deep_btn" value="审核通过">
    <input type="button" class="gray_btn" value="审核不通过">
</p>
<table cellspacing="1" cellpadding="1" width='100%' class='list' >
    <tr class="head">
        <th width='24px'><input type="checkbox" id='selectAll' class="chk"></th>
        <th width='60px'>评价ID</th>
        <th width='380px'>描述</th>
        <th width='100px'>来自用户</th>
        <th width='100px'>被评用户</th>
        <th width='90px'>评分</th>
        <th width='90px'>包含图片</th>
        <th width='120px'>评价时间</th>
        <th width='120px'>状态</th>
        <th width=''>操作</th>
    </tr>
	<tbody class="listData">
		<?php foreach($this->reviews as $review){?>
		<tr class="row">
			<td><input type="checkbox" name="selectAll" class="chk"></td>
			<th class='name'><?php echo $review['id'];?></th>
			<td><a href="<?php echo $this->baseUrl('review/view/'.$review['id']); ?>"><?php echo mb_substr( $review['brief'],0,30,'utf-8');?>...</a></td>
			<td><a href="<?php echo $this->baseUrl('review/from/'.$review['from_uid']);?>"><?php echo $review['from_user'];?></a></td>
			<td><a href="<?php echo $this->baseUrl('review/to/'.$review['to_uid']);?>"><?php echo $review['to_user'];?></a></td>
			<td><?php echo $review['score'];?></td>
			<td><?php echo $review['photo'] ? '是':'否' ;?></td>
			<td><?php echo date('Y-m-d H:i',$review['create_time']); ?></td>
			<td><?php echo $isPublish[$review['published']];?></td>
			<td> <span>通过</span></td>
		</tr>
		<?php } ?>
    </tbody>
    <tr>
        <td colspan="7">
            <div class='pagination'><?php $this->render('pagination');?></div>
        </td>
    </tr>
</table>
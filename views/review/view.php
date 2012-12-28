<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yansueh
 * Date: 12-12-20
 * Time: 下午5:52
 * To change this template use File | Settings | File Templates.
 */
$isPublish = array('未审核','通过','不通过');
$review = $this->review;
?>
<h1 class='ctitle'>专业人士评价管理</h1>

<script>
	seajs.use('looklo.action',function(a){
		a.manageReview();
	})
</script>
<div class="content">
    <p><span class="label">评价ID：</span><?php echo $review['id'];?></p>
    <p><span class="label">评价时间：</span><?php echo date('Y-m-d H:i:s',$review['create_time']); ?></p>
    <p><span class="label">来自用户：</span><?php echo $review['from_uid'];?></p>
    <p><span class="label">被评用户：</span><?php echo $review['to_uid'];?></p>
    <p><span class="label">评分：</span><?php echo $review['score'];?></p>
	<p><span class="label">评价内容：</span></p>
	<p style="margin:0 0 0 88px;"><?php echo $review['brief'];?></p>
    <p style="margin:0 0 0 88px;">
		<?php
			foreach(json_decode($review['photo']) as $pic){
				echo '<img src="'.IMAGE_SERVER.'120/120/'.$pic[0].'" height="120px"/>';
			}
		?>
	</p>
	<p>		<b>审核：</b>
    </p>
    <div class="do">
		<?php if($review['published']==0){?>

        	说明：<textarea class="texta" name="" id="reason"></textarea><br />
			<input type="button" class="deep_btn" id='passed' value="通过" publish="<?php echo $review['published']; ?>" rid=<?php echo $review['id']; ?> >
			<input type="button" class="gray_btn" id='unpassed' value="不通过" publish="<?php echo $review['published']; ?>" rid=<?php echo $review['id']; ?> >


		<?php }else{
			$param = json_decode($review['param'],true);
			echo '该评论由 <em class="red">'.$param['user'].'</em> 于 <em>'.date('Y-m-d H:i:s',$param['time']).'</em> 审核 <em class="red">'.$isPublish[$review['published']] .'</em>';
		?>
		<p><em>理由：</em><?php echo $param['desc'];?></p>
		<?php } ?>
	</div>
</div>

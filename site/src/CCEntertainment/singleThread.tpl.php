<?php $nr=1 ?>
<div style="margin:5px; background-color:#FFF;">
<h3><?=esc($news['title'])." #".$nr++?></h3>
<hr>
<p><?=filter_data($news['data'], $news['filter'])?></p>
<p class='smaller-text silent'>
<p class='smaller-text'><em>Posted on <?=$news['created']?> by <?=$news['owner']?></em></p>
<!--a href='<?=create_url("content/edit/{$news['id']}")?>'>edit</a-->
</p>
</div>

<?php foreach($comments as $val):?>
<?php if($val['responseTo']==$news['id']): ?>
		<div style='background-color:#FFF;border:2px solid #ccc;margin-bottom:1em; padding-left:20;padding:1em;'>
			<h4><?php echo $val['author']." #".$nr++;?></h4>
			<hr>
			<p><?=filter_data($val['response'], "plain")?></p>
			  <p>At: <?=$val['created']." by ".$val['author']?></p>
		</div>
<?php endif ?> 
<?php endforeach;?>
<?=$commentsForm->GetHTML()?>

<h1><?=esc($news['title'])?></h1>
    
<p class='smaller-text'><em>Posted on <?=$news['created']?> by <?=$news['owner']?></em></p>

<img src="<?=create_url("site/data/img/".$news['img'])?>" alt="picture" />

<p><?=filter_data($news['data'], $news['filter'])?></p>
<p class='smaller-text silent'>
<!--a href='<?=create_url("content/edit/{$news['id']}")?>'>edit</a-->
</p>
</hr>  
<h2>Comments:</h2>
<?=$commentsForm->GetHTML()?>
<?php foreach($comments as $val):?>
<?php if($val['responseTo']==$news['id']): ?>
		<div style='background-color:#ABC;border:1px solid #ccc;margin-bottom:1em; margin-left:200;padding:1em;'>
		<h4 style="background-color:#ABB;"><?=$val['author']?></h4>
		
		<p><?=filter_data($val['response'], "plain")?></p>
		  <p>At: <?=$val['created']." by ".$val['author']?></p>
		</div>
<?php endif ?> 
<?php endforeach;?>

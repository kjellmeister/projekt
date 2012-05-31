<h2 style="text-align:center"><?=$heading?></h2>
<?php if($isAuthenticated != null):?>
  <p><a href="<?=create_url("entertainment/edit")?>">Write article</a></p>
<?php endif;?>

<?php if($contents != null):?>
  <?php foreach($contents as $val):?>
    <h1>
    	<a href="<?= create_url("entertainment/show/".$val['id']."/".$val['tag']) ?>"> <?=esc($val['title'])?> </a>
    </h1>
    <p class='smaller-text'><em>Posted on <?=$val['created']?> by <?=$val['owner']?></em></p>
    <img src="<?=create_url("site/data/img/".$val['img'])?>" alt="picture" />
    <p><?=filter_data($val['data'], $val['filter'])?></p>
   
    <p class='smaller-text silent'>
    <a href='<?=create_url("entertainment/edit/news/{$val['id']}/{$val['tag']}")?>'>edit</a>
    </p>
  <?php endforeach; ?>
<?php else:?>
  <p>No posts exists.</p>
  
<?php endif;?>

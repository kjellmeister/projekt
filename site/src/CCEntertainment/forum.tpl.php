<?php if($isAuthenticated != null):?>
  <p><a href="<?=create_url("entertainment/create/forum")?>">Create thread</a></p>
<?php endif;?>

<?php if($entertainment != null && !empty($entertainment)):?>
<h2>The entertainment forum:</h2>
  <?php foreach($entertainment as $val):?>
    <ul>
    	<li>
    		<a href="<?= create_url("entertainment/forum/entertainment/".$val['id']) ?>"> <?=esc($val['title'])?> </a>
    		<p class='smaller-text'><em>Posted on <?=$val['created']?> by <?=$val['owner']?></em></p>
    	</li>
    </ul>
    
   
    <!--p class='smaller-text silent'>
    <a href='<?=create_url("content/edit/{$val['id']}/{$val['tag']}")?>'>edit</a>
    </p-->
  <?php endforeach; ?>
  <hr>
<?php endif;?>



<?php if($sport != null && !empty($sport)):?>
<h2>The sport forum:</h2>
  <?php foreach($sport as $val):?>
    <ul>
    	<li>
    		<a href="<?= create_url("entertainment/forum/sport/".$val['id']) ?>"> <?=esc($val['title'])?> </a>
    		<p class='smaller-text'><em>Posted on <?=$val['created']?> by <?=$val['owner']?></em></p>
    	</li>
    </ul>
    
   
    <!--p class='smaller-text silent'>
    <a href='<?=create_url("content/edit/{$val['id']}/{$val['tag']}")?>'>edit</a>
    </p-->
  <?php endforeach; ?>
  <hr>
<?php endif;?>


<?php if($local != null && !empty($local)):?>
<h2>The forum for the locals:</h2>
  <?php foreach($local as $val):?>
    <ul>
    	<li>
    		<a href="<?= create_url("entertainment/forum/local/".$val['id']) ?>"> <?=esc($val['title'])?> </a>
    		<p class='smaller-text'><em>Posted on <?=$val['created']?> by <?=$val['owner']?></em></p>
    	</li>
    </ul>
    
   
    <!--p class='smaller-text silent'>
    <a href='<?=create_url("content/edit/{$val['id']}/{$val['tag']}")?>'>edit</a>
    </p-->
  <?php endforeach; ?>
  <hr>
<?php endif;?>


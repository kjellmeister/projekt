<h6 style="background-color:#DDD">
	<?=$rubrik?>
	<img style="width:60px; height:50px;" src="<?=create_url("site/data/img/rss2.png");?>" alt="rss"/>
</h6>

<hr>
<?php
foreach($news->channel->item as $article)
{
	echo "<h3><a href='".$article->link."'>".$article->title."</a></h3></br>";
	echo "<p>$article->description</p><hr>";
}

<div style="background-color:#DDD">
<h6 >
"Other threads:"
</h6>

<hr>
<?php
foreach($content as $val)
{
	echo "<h3>";
	echo "<a href='".create_url('entertainment/forum/'.$val['tag']."/".$val['id'])."'>".$val['title']."</a>";
	echo"</h3></br>";
}
?>
</div>

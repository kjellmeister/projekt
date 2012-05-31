<h6 style="background-color:#DDD">
Relaterade artiklar:
</h6>

<hr>
<?php
foreach($related as $val)
{
	echo "<h3><a href=".create_url("entertainment/show/".$val['id']."/".$val['tag']).">".esc($val['title'])."</a></h3></br>";
}

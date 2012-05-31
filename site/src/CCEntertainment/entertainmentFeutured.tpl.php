<div>

<h4>Underhållning:</h4>
<hr>
<h5>
	<?php
	if(isset($news[0]['id']))
	{
		for($i=0 ; $i<sizeof($news) && $i<3 ; $i++)
		{
			echo "<a name='white' class='white 'href=". create_url("entertainment/show/".$news[$i]['id']) .">- ". esc($news[$i]['title'])."</a></br>";
		}
	}
	?>
</h5>
</div>

<?php KirbyCacheWidget\notify_and_redirect(); ?>
<ul class="nav nav-list sidebar-list">
<?php
	//  data passed as an array in raw1 and/or raw2; simply dump the key value pairs
	if (isset($raw1))
	{
		foreach($raw1 as $key => $value):
			echo '<li>'.$key.'<span class="marginalia">'.$value.'</span></li>';
		endforeach;
	}
	if (isset($raw2))
	{
		foreach($raw2 as $key => $value):
			echo '<li>'.$key.'<span class="marginalia">'.$value.'</span></li>';
		endforeach;
	}
?>
</ul>
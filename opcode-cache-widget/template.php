<?php KirbyCacheWidget\notify_and_redirect(); ?>
<ul class="nav nav-list sidebar-list">
<?php
if ($isenabled)
{
	if ($terse)
	{
?>
	<!-- terse report -->
	<li>Capacity:<span class="marginalia"><?=$memory[3]?></span></li>
	<li>Effectiveness:<span class="marginalia"><?php echo number_format($hm[2],2) . '%'; ?> </span></li>
<?php
	}
	else
	{
?>
	<!-- normal report  -->
	<li style="margin-bottom: 6px;"><?=$version?></li>
	<!--   memory -->
	<li>Used memory:<span class="marginalia"><?=$memory[0]?></span></li>
	<li>Free memory:<span class="marginalia"><?=$memory[1]?></span></li>
	<li>Lost memory:<span class="marginalia"><?=$memory[2]?></span></li>
	<li style="margin-bottom: 6px;">Capacity:<span class="marginalia"><?=$memory[3]?></span></li>
	<!--   numbers -->
	<li>Number cached scripts:<span class="marginalia"><?=$numbers[0]?></span></li>
	<li style="margin-bottom: 6px;">Number cached keys:<span class="marginalia"><?=$numbers[1]?></span></li>
	<!--   hits and misses -->
	<li>Cache hits:<span class="marginalia"><?=$hm[0]?></span></li>
	<li>Cache misses:<span class="marginalia"><?=$hm[1]?></span></li>
	<li>Effectiveness:<span class="marginalia"><?php echo number_format($hm[2],2) . '%'; ?> </span></li>
<?php
	}
}
else
{
?>
	<!-- opcache is disabled, so can only report version and that its not enabled -->
<?php if (!$minimal) { ?>
	<li><?=$version?></li>
<?php } ?>
	<li>opcache_enabled:<span class="marginalia">No</span></li>
<?php 
}
?>
</ul>

<?php KirbyCacheWidget\notify_and_redirect(); ?>
<ul class="nav nav-list sidebar-list">
<?php if ($terse) { ?>
	<li>Memory Capacity:<span class="marginalia"><?php echo number_format(100*($info['seg_size']-$info['avail_mem'])/($info['avail_mem']+$info['seg_size']),2) . '%'; ?></span></li>
	<li>Slot Capacity:<span class="marginalia"><?php echo number_format(100*$stats['num_entries']/($stats['num_entries']+$stats['num_slots']),2) . '%'; ?></span></li>
	<li style="margin-bottom: 3px;">Hit Effectiveness: <span class="marginalia"><?php if ($stats['num_hits']+$stats['num_misses']) echo number_format(100*$stats['num_hits']/($stats['num_hits']+$stats['num_misses']),2) . '%'; else echo 'n/a'; ?></span></li>
<?php } else { ?>
	<li>Segment Size:<span class="marginalia"><?php KirbyCacheWidget\echo_bytes_for_humans($info['seg_size']); ?></span></li>
	<li>Available memory:<span class="marginalia"><?php KirbyCacheWidget\echo_bytes_for_humans($info['avail_mem']); ?></span></li>
	<li style="margin-bottom: 3px;">Capacity:<span class="marginalia"><?php echo number_format(100*($info['seg_size']-$info['avail_mem'])/($info['avail_mem']+$info['seg_size']),2) . '%'; ?></span></li>
	<!-- - -->
	<li>Number of slots:<span class="marginalia"><?=$stats['num_slots']?></span></li>
	<li>Number of entries:<span class="marginalia"><?=$stats['num_entries']?></span></li>
	<li style="margin-bottom: 3px;">Capacity:<span class="marginalia"><?php echo number_format(100*$stats['num_entries']/($stats['num_entries']+$stats['num_slots']),2) . '%'; ?></span></li>
	<!-- - -->
	<li>Hits: <span class="marginalia"><?=$stats['num_hits']?></span></li>
	<li>Misses: <span class="marginalia"><?=$stats['num_misses']?></span></li>
	<li style="margin-bottom: 3px;">Effectiveness: <span class="marginalia"><?php if ($stats['num_hits']+$stats['num_misses']) echo number_format(100*$stats['num_hits']/($stats['num_hits']+$stats['num_misses']),2) . '%'; else echo 'n/a'; ?></span></li>
</ul>
<?php } ?>

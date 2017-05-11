<?php KirbyCacheWidget\notify_and_redirect(); ?>
<ul class="nav nav-list sidebar-list">
<?php if ($terse) { ?>
<li>Capacity: <span class="marginalia"><?php echo number_format(100*$stats['bytes']/($stats['bytes']+$stats['limit_maxbytes']),2) . '%'; ?></span></li>
<li>Effectiveness: <span class="marginalia"><?php echo number_format(100*$stats['get_hits']/($stats['get_hits']+$stats['get_misses']),2) . '%'; ?></span></li>
<?php } else { ?>
	<li style="margin-bottom: 6px;">memcached <?=$stats['version']?> + libevent <?=$stats['libevent']?></li>
<!--	<li style="margin-bottom: 6px;"><?php if (isset($options))
	echo $options['host'].':'.$options['port'];
else
	echo 'default (localhost:11211)'; ?></li>-->

	<li>Total Memory: <span class="marginalia"><?php KirbyCacheWidget\echo_bytes_for_humans($stats['limit_maxbytes']); ?></span></li>

	<li>Memory Used: <span class="marginalia"><?php KirbyCacheWidget\echo_bytes_for_humans($stats['bytes']); ?></span></li>

	<li style="margin-bottom: 3px;">Capacity: <span class="marginalia"><?php echo number_format(100*$stats['bytes']/($stats['bytes']+$stats['limit_maxbytes']),2) . '%'; ?></span></li>

	<li>Current Items: <span class="marginalia"><?=$stats['curr_items']?></span></li>
	<li style="margin-bottom: 3px;">Total Items: <span class="marginalia"><?=$stats['total_items']?></span></li>

	<li>Gets: <span class="marginalia"><?=$stats['cmd_get']?></span></li>
	<li>Sets: <span class="marginalia"><?=$stats['cmd_set']?></span></li>
	<li style="margin-bottom: 3px;">Flushes: <span class="marginalia"><?=$stats['cmd_flush']?></span></li>

	<li>Get Hits: <span class="marginalia"><?=$stats['get_hits']?></span></li>
	<li>Get Misses: <span class="marginalia"><?=$stats['get_misses']?></span></li>
	<li style="margin-bottom: 3px;">Effectiveness: <span class="marginalia"><?php echo number_format(100*$stats['get_hits']/($stats['get_hits']+$stats['get_misses']),2) . '%'; ?></span></li>
</ul>
<?php } ?>

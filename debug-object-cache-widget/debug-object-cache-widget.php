<?php

namespace KirbyCacheWidget;

use c;
use tpl;

// This is a bit of an experimental diagnostic dump. It is not normally used.

return
    array(
    'title' => 'object cache [diagnostics]',
    'compressed' => true,
    'html' => function () {

        $a = array('c::cache' => c::get('cache'));
        $a += array('c::cache.driver' => c::get('cache.driver'));
        $a += array('c::cache.autoupdate' => c::get('cache.autoupdate'));
        $a += array('driver' => get_class(kirby()->cache));
        $a += array('user can flush' => canflush());
        $a += array('verbose' => c::get('plugin.cachewidget.verbose'));
        $a += array('terse' => c::get('plugin.cachewidget.terse'));

        $b = array('memcached loaded' => extension_loaded('memcached'));
        $b += array('OPcache loaded' => extension_loaded('Zend OPcache'));
        $b += array('apc loaded' => extension_loaded('apc'));
        $b += array('apcu loaded' => extension_loaded('apcu'));

        return tpl::load(hereRawTemplate(), array('raw1' => $a, 'raw2' => $b));
    }
    );

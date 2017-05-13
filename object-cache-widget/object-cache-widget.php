<?php

namespace KirbyCacheWidget;

use c;
use tpl;

// is the cache enabled in the kirby config?
if (!kirby()->options['cache']) {
    return array
    (
        'title' => 'object cache',
        'compressed' => true,
        'html' => function () {
            switch (panel()->user()->language()) {
                case 'de':
                    $res = 'Nicht aktiviert';
                    break;
                case 'fr':
                    $res = 'Non activé';
                    break;
                case 'nl':
                    $res = 'Niet geactiveerd';
                    break;
                default: /* EN */
                    $res = 'Not activated';
            }
            return $res;
        }
    );
}

$driver = kirby()->options['cache.driver'];

$title = 'object cache (' . $driver . ')';

// for memcached and apc, check if the appropriate php driver is loaded.
$has_php_extension = true;
switch (c::get('cache.driver')) {
    case 'memcached':
        $has_php_extension = extension_loaded('memcached');
        break;
    case 'apc':
        $has_php_extension = extension_loaded('apcu'); // apc needs apcu
        break;
}
// and guard
if (!$has_php_extension) {
    return array(
        'title' => $title,
        'compressed' => true,
        'html' => function () {
            return 'Required php extension not loaded';
        }
    );
}

// set up the options... only one (flush), if user is allowed
$options = false;

if (canflush()) {
    $options = array
    (
        array
        (
            'text' => txt_flush(),
            'icon' => 'trash-o',
            'link' => u('/plugin-cache/flush-obj'),
            'target' => '_top',
        )
    );
}

return
    array(
        'title' => $title,
        'compressed' => true,
        'options' => $options,
        'html' => function () {
            switch (kirby()->options['cache.driver']) {
                case 'file':
                    // the only possible thing to show when file is used
                    // is the configuration options

                    if (c::get('plugin.cachewidget.verbose', false)) {
//          $n=getFileCount( kirby()->options['cache.options']['root']);
                        $p = kirby()->roots()->cache();
                        $n = getFileCount($p);
                        $res = tpl::load(hereRawTemplate(), array('raw1' => array('dir' => $p, 'count' => $n)));
                    } else
                        $res = txt_activated();
                    break;

                case 'memcached':
                    // The simple way would be:
                    //   $stats=kirby()->cache->connection->getStats()
                    // but connection is protected, so, instead:

                    $defaults = array(
                        'host' => 'localhost',
                        'port' => 11211
                    );

                    $ops = array_merge($defaults, c::get('cache.options', array()));

                    $m = new \Memcached();
                    $m->addServer($ops['host'], $ops['port']);

                    $stats = $m->getStats()[$ops['host'] . ':' . $ops['port']];

                    if (count($stats)) {
                        if (c::get('plugin.cachewidget.verbose', false))
                            $res = tpl::load(hereRawTemplate(), array('raw1' => $stats));
                        else {
                            // set $t to language-specific template, if available.
                            $lang = panel()->user()->language();
                            $t = __DIR__ . DS . 'template-memcached-' . $lang . '.php';
                            if (!file_exists($t))
                                $t = __DIR__ . DS . 'template-memcached.php';

                            $res = tpl::load($t,
                                array(
                                    'terse' => c::get('plugin.cachewidget.terse', false),
                                    'stats' => $stats)
                            );
                        }
                    } else {
                        // if stats cannot be read, the daemon isn't running
                        switch (panel()->user->language()) {
                            case 'de':
                                $res = 'memcached läuft nicht';
                                break;
                            case 'fr':
                                $res = 'memcached ne fonctionne pas';
                                break;
                            case 'nl':
                                $res = 'memcached wordt niet uitgevoerd';
                                break;
                            default: /* EN */
                                $res = 'memcached not running?! ';
                        }
                    }
                    break;

                case 'apc':
                    $res = apc_response('user');
                    break;

                default:
                    $res = 'Oops. Driver not supported by this widget';
            }

            return $res;
        }
    );

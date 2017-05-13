<?php

require_once __DIR__ . DS . 'lib' . DS . 'utils.php';
require_once __DIR__ . DS . 'lib' . DS . 'apc.php';
require_once __DIR__ . DS . 'lib' . DS . 'routes.php';

$kirby->set('widget', 'opcode-cache-widget', __DIR__ . '/opcode-cache-widget');
$kirby->set('widget', 'object-cache-widget', __DIR__ . '/object-cache-widget');

if (c::get('plugin.cachewidget.showdiagnostics'))
    $kirby->set('widget', 'debug-object-cache-widget', __DIR__ . '/debug-object-cache-widget');

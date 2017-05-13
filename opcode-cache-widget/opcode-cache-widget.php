<?php

namespace KirbyCacheWidget;

use tpl;
use c;

class OpCacheData
{
    private $_configuration;
    private $_status;

    public function __construct()
    {
        $this->_configuration = opcache_get_configuration();
        $this->_status = opcache_get_status();
    }

    public function getIsEnabled()
    {
        return $this->_status['opcache_enabled'];
    }

    public function getVersion()
    {
        return 'PHP ' . phpversion() . " + OpCache {$this->_configuration['version']['version']}";
    }

    public function getMemory()
    {
        return array
        (
            $this->_status['memory_usage']['used_memory'],
            $this->_status['memory_usage']['free_memory'],
            $this->_status['memory_usage']['wasted_memory'],
        );
    }

    public function getMemoryForHumans()
    {
        if (!$this->_status['opcache_enabled'])
            return null;
        else
            return array
            (
                $this->_for_humans($this->_status['memory_usage']['used_memory']),
                $this->_for_humans($this->_status['memory_usage']['free_memory']),
                $this->_for_humans($this->_status['memory_usage']['wasted_memory']),
                number_format(100 * ($this->_status['memory_usage']['used_memory'] + $this->_status['memory_usage']['wasted_memory']) / ($this->_status['memory_usage']['used_memory'] + $this->_status['memory_usage']['wasted_memory'] + $this->_status['memory_usage']['free_memory']), 2) . '%'
            );
    }

    public function getRawMemoryUsage()
    {
        return $this->_status['memory_usage'];
    }

    public function getRawStatistics()
    {
        return $this->_status['opcache_statistics'];
    }

    private function _for_humans($bytes)
    {
        if ($bytes > 1048576) {
            return sprintf('%.2f&nbsp;MB', $bytes / 1048576);
        } else {
            if ($bytes > 1024) {
                return sprintf('%.2f&nbsp;kB', $bytes / 1024);
            } else {
                return sprintf('%d&nbsp;bytes', $bytes);
            }
        }
    }

    public function getHitsMisses()
    {
        return array
        (
            $this->_status['opcache_statistics']['hits'],
            $this->_status['opcache_statistics']['misses'],
            $this->_status['opcache_statistics']['opcache_hit_rate']
        );
    }

    public function getNumbers()
    {
        return array
        (
            $this->_status['opcache_statistics']['num_cached_scripts'],
            $this->_status['opcache_statistics']['num_cached_keys'],
            $this->_status['opcache_statistics']['max_cached_keys']
        );
    }
}

// guard
if (!extension_loaded('Zend OPcache')) {
    // OPcache not loaded...
    // is APC available?
    if (extension_loaded('apc'))
        return array
        (
            'title' => 'opcode cache (apc)',
            'compressed' => true,
            'html' => function () {
                return apc_response('system');
            }
        );
    else
        return array
        (
            'title' => 'opcode cache',
            'compressed' => true,
            'html' => function () {
                return 'Neither Zend OPcache nor apc loaded';
            }
        );
}

if (canflush()) {
    $options = array
    (
        array
        (
            'text' => txt_flush(),
            'icon' => 'trash-o',
            'link' => u('/plugin-cache/flush-php'),
            'target' => '_top'
        )
    );
} else
    $options = false;

return array
(
    'title' => 'opcode cache (Zend)',
    'target' => '_blank',
    'compressed' => true,
    'options' => $options,
    'html' => function () {
        $opdata = new OpCacheData();

        if (c::get('plugin.cachewidget.verbose', false)) {
            return tpl::load(hereRawTemplate(),
                array
                (
                    'raw1' => $opdata->getRawMemoryUsage(),
                    'raw2' => $opdata->getRawStatistics()
                )
            );
        } else {
            $totemplate = array
            (
                'isenabled' => $opdata->getIsEnabled(),
                'terse' => c::get('plugin.cachewidget.terse', false),
                'version' => $opdata->getVersion(),
                'memory' => $opdata->getMemoryForHumans(),
                'numbers' => $opdata->getNumbers(),
                'hm' => $opdata->getHitsMisses()
            );
            // Use language-specific template file (if available)
            $lang = panel()->user()->language();
            if (file_exists(__DIR__ . DS . 'template-' . $lang . '.php'))
                return tpl::load(__DIR__ . DS . 'template-' . $lang . '.php', $totemplate);
            else
                return tpl::load(__DIR__ . DS . 'template.php', $totemplate);
        }
    }
);

<?php

// guard against being called by a non-logged in user.
if (!site()->user())
{
  kirby()->routes
  (
    array(
      array
      (
          'pattern' => 'plugin-cache/(:any)',
          'action'  => function()
          {
            // If somebody is calling these routes and they are not a kirby user,
            // they are more than likely up to nefarious purposes...
            // so return a random (and pithy) message and a forbidden response.            header::forbidden();
            $a=['Computer Says No.','Nice try, muggle','Das ist streng verboten!','Shall we play a game?'];
            die($a[mt_rand(0, count($a) - 1)]);
          }
      )
    )
  );
}
else
{
  // if the user is allowed to flush, enable the routes
  if(KirbyCacheWidget\canFlush()) {
    kirby()->routes(array(
      array(
        'pattern' => 'plugin-cache/flush-obj',
        'action'  => function()
        {
          // flush the object cache
          if(kirby()->cache->flush())
          {
            go(kirby()->urls()->index() . '/' . c::get('plugin.cachewidget.panelpath', 'panel') . '/?cache=obj-flushed');
          }
        }
      ),
      array(
        'pattern' => 'plugin-cache/flush-php',
        'action'  => function() {
          // guard
          if (!extension_loaded('Zend OPcache'))
            return false;
          // flush the op cache
          if (opcache_reset())
          {
            go(kirby()->urls()->index() . '/' . c::get('plugin.cachewidget.panelpath', 'panel') . '/?cache=php-flushed');
          }
        }
      )
    ));
  }
}

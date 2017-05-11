<?php
namespace KirbyCacheWidget;
use c;
use tpl;

function apc_response($where)
{
//  if ($where =='system')
//     $stats=apc_cache_info( 'filehits', true);
//  else
     $stats=apc_cache_info( $where, true );

  $info=apc_sma_info( true );

  if ( $stats && $info)
  {
    if (c::get('plugin.cachewidget.verbose', false))
      $res=tpl::load(__DIR__ . DS . 'template-raw.php', array( 'raw1' => $info, 'raw2' => $stats) );
    else
      $res=tpl::load(__DIR__ . DS . 'template-apc.php', array( 'terse' => c::get('plugin.cachewidget.terse', false), 'info' => $info, 'stats' => $stats) );
  }
  else
    $res='Stats not available; Sorry.';
  return $res;
}

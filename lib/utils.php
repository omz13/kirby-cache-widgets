<?php
namespace KirbyCacheWidget;
use c;
use tpl;
use Memcached;
use Kirby;

function txt_flush()
{
  switch ( panel()->user()->language() )
  {
  case 'de':
    $txt = 'Löschen';
    break;
  case 'fr':
    $txt = 'Effacer';
    break;
  case 'nl':
    $txt = 'Spoel';
    break;
  default: /* EN */
    $txt = 'Flush';
  }
  return $txt;
}

function txt_activated()
{
  switch ( panel()->user()->language() )
  {
  case 'de':
    $res = 'Aktiviert';
    break;
  case 'fr':
    $res = 'Activé';
  break;
  case 'nl':
    $res = 'Geactiveerd';
     break;
  default: /* EN */
     $res = 'Activated';
  }        
  return $res;
}

function echo_bytes_for_humans($bytes)
{
  $s=null;

  if ($bytes > 1048576)
  {
      $s=sprintf('%.2f&nbsp;MB', $bytes / 1048576);
  }
  else
  {
    if ($bytes > 1024)
    {
      $s=sprintf('%.2f&nbsp;kB', $bytes / 1024);
    }
    else
    {
        $s=sprintf('%d&nbsp;bytes', $bytes);
    }
  }
  echo $s;
}

function notify_and_redirect()
{
  switch(get('cache'))
  {
    case 'php-flushed' :
      panel()->notify('php opcode cache flushed');
      panel()->redirect('/');
      break;

case 'obj-flushed' :
      panel()->notify('object cache flushed');
      panel()->redirect('/');
      break;
  }  
}

function getFileCount($path) {
    $size = 0;
    $ignore = array('.','..','.DS_Store');
    $files = scandir($path);
    foreach($files as $t) {
        if(in_array($t, $ignore)) continue;
        if (is_dir(rtrim($path, '/') . '/' . $t)) {
            $size += getFileCount(rtrim($path, '/') . '/' . $t);
        } else {
            $size++;
        }   
    }
    return $size;
}

function canFlush()
{
  // guard
  if (!site()->user())
   return false;

  // by default, an admin user can flush
  if ( c::get('plugin.cachewidget.admincanflush', true ) )
  {
    if ( site()->user()->isAdmin() )
      return true;
  }

  // if not an admin user, see if they are in the list of allowed users
  if( site()->user() )
  {
    $username = c::get('plugin.cachewidget.usercanflush');

    if(is_string($username))
    {
      if(site()->user()->username() == $username)
        return true;
    }
    elseif(is_array($username))
    {
      if(in_array(site()->user()->username(), $username))
        return true;
    }
  }
}

function hereRawTemplate()
{
  return __DIR__ . DS . 'template-raw.php';
}
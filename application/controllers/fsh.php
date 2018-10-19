<?php

function av($array, $key, $default=null)
{
  return (isset($array[$key]))?$array[$key]:$default;
}

class fsh
{
  protected $sleepTime;
  protected $config;
  protected $tempPath;
  protected $referer = null;
  protected $userAgent = 'MiPropioRastreador/0.2';
  protected $cookieJar = array();
  protected $lastFetch = 0;
  protected $filters = array();

  function __construct($sleepTime, array $options=array())
  {
    $this->sleepTime = $sleepTime;
    $this->tempPath = av($options, 'tempPath', 'tmp/');
    if (av($options, 'debug', true))
      define('FETCH_DEBUG', 1);
  }

  public function addFilter($function, $argument=null)
  {
    if (!is_callable($function))
      throw new Exception ('Filter function is not callable');

    if (!$argument)
      $argument = array();
    else if(!is_array($argument))
      $argument = array($argument);

    $this->filters[] = array('f' => $function, 'a'=>$argument);
  }

  protected function extractCookies($headers)
  {
    $lines = explode("\n", $headers);
    foreach ($lines as $l)
      {
    $dp = strpos($l, ':');
    if ($dp === false)
      continue;
    $part1 = trim(substr($l, 0, $dp));
    $part2 = trim(substr($l, $dp+1));
    if ($part1 == 'Set-Cookie')
      {
        $eq = strpos($part2, '=');
        if ($eq === false)
          {
        $this->cookieJar[] = $part2;
        continue;
          }

        $key = trim(substr($part2, 0, $eq));
        $val = trim(substr($part2, $eq+1));
        if (!$key)
          $this->cookieJar[] = $val;
        else
          $this->cookieJar[$key] = $val;
      }
      }
  }

  protected function getCookies()
  {
    $resv = array();

    foreach ($this->cookieJar as $key => $val)
      {
    $eval = explode(';', $val);
    if (is_numeric($key))
      $resv[] = $eval[0];
    else
      $resv[] = $key.'='.$eval[0];
      }

    if ( (defined('FETCH_DEBUG')) && (FETCH_DEBUG) )
      echo "\nCOOKIES: ".implode('; ', $resv)."\n";

    return implode('; ', $resv);
  }

  public function fetchUrl($url, $curlData = array())
  {
    $headers = array();
    $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
    $headers[] = "Connection: Keep-Alive";
    $unid = uniqid().'.html';

    if ( (defined('FETCH_DEBUG')) && (FETCH_DEBUG) )
      echo "Entrando en ".$url."... (".$unid.").";
    else
      echo "Entrando en ".$url."... ";

    $sleepSecs = $this->sleepTime / 1000;
    if (microtime(true)-$this->lastFetch < $sleepSecs)
      usleep(( $sleepSecs - (microtime(true)-$this->lastFetch) ) * 1000000);
    $try=0;
    do
      {
    if ($try>0)
      {
        echo "[reintento]";
        usleep(100000);
      }
    $curlBase = array(CURLOPT_COOKIE => $this->getCookies(),
              /* CURLOPT_COOKIEFILE => av($this->config, 'cookiejar'), */
              /* CURLOPT_COOKIEJAR => av($this->config, 'cookiejar'), */
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_USERAGENT => $this->userAgent,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_MAXREDIRS => 5,
              CURLOPT_VERBOSE => 0,
              CURLOPT_HEADER => 1,
              CURLOPT_HTTPHEADER => $headers,
              CURLOPT_HEADER, 0
              );
    if ($this->referer)
      $curlBase[CURLOPT_REFERER] = $this->referer;

    if ( (defined('FETCH_DEBUG')) && (FETCH_DEBUG) && av($curlBase, CURLOPT_REFERER))
      echo "Referer: ".$curlBase[CURLOPT_REFERER]."\n";

    $ch = curl_init();
    $curlopts = $curlBase + array(CURLOPT_URL => $url) + $curlData;

    curl_setopt_array($ch, $curlopts);
    $res = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($res, 0, $header_size);
    $res = substr($res, $header_size);
    $this->extractCookies($headers);
    $this->referer = $url;

    if (curl_getinfo($ch, CURLINFO_HTTP_CODE)!=200)
      {
        $try++;
        continue;
      }
    if ( (defined('FETCH_DEBUG')) && (FETCH_DEBUG) )
      file_put_contents($this->tempPath.'/'.$unid, $res);

    echo "OK\n";
    $ok = true;
    curl_close($ch);
      } while ( (!$ok) ||  ($try>10) );
    if (!$ok)
      {
    echo "ERROR\n";
    file_put_contents($this->tempPath.'/error.html', $res);
    throw new Exception("La página " . $url . " devolvió un error ".curl_getinfo($ch, CURLINFO_HTTP_CODE));
      }

    $this->lastFetch = microtime(true);
    if (count($this->filters)>0)
      {
    foreach ($this->filters as $filter)
      {
        $res = call_user_func_array(av($filter, 'f'), array_merge(array($res), av($filter, 'a')));
      }
      }
    return $res;
  }

};

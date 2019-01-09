<?php
namespace App\Lib;

use App\Lib\HttpClient;
use Log;

/**
 * 
 */
class SljSync
{
  public function __construct(){
  }

  # => string, eg: "2017-12-11 09:10:05"
  public static function get_lastest_modtime($table, $type = 'mod_time'){
    $params = [
      'table' => $table,
      'type' => $type,
    ];

    $time = self::post('?c=core&a=call&_m=slj.getLastestTime', $params);
    $env_fromtime = env('SLJ_SYNC_FROMTIME', date('Y-m-d H:i:s', 0));

    if($time === false)
      throw new \Exception("fail to get lastest_modtime", 1);
    elseif($time){
      return max(date('Y-m-d H:i:s', $time), $env_fromtime);
    }

    return $env_fromtime;
  }

  // 由表名获取水利局数据库的表名
  # => string
  public static function toSljTable($table){
    switch ($table) {
      case 'rain':
        return 'stu.ST_PPTN_R';
        break;
      case 'river':
        return 'stu.ST_RIVER_R';
        break;
      case 'shuiku':
        return 'stu.ST_RSVR_R';
        break;
      case 'chaoxi':
        return 'stu.ST_TIDE_R';
        break;
      case 'station':
        return 'stu.ST_STBPRP_B';
        break;
      case 'river_fh':
        return 'stu.ST_RVFCCH_B';
        break;
      case 'shuiku_fh':
        return 'stu.ST_RSVRFCCH_B';
        break;
      case 'shuiku_xx':
        return 'stu.ST_RSVRFSR_B';
        break;
      
      default:
        throw new \Exception("未定义的table: $table", 1);
        break;
    }
  }

  # => true | false
  public static function sync_data($table, array $data){
    $now = time();

    $params = [
      'table' => $table,
      'time' => $now,
      'token' => self::gen_token($now),
      'data' => json_encode($data),
    ];

    if(self::post('?c=core&a=call&_m=slj.putData', $params) === false)
      return false;

    return true;
  }

  public static function gen_token($time){
    $ticket = env('SLJ_TICKET');
    return md5($time . $ticket);
  }

  public static function post(string $api, array $params){
    $url = sprintf('%s/%s', env('SLJ_SYNC_URL'), $api);

    $res = HttpClient::post($url, $params);

    if (!$res)
      return false;

    $data = json_decode($res, true);
    if (!isset($data['code'])) {
      Log::info(__METHOD__, ['error' => $res]);
      return false;
    }

    if ($data['code'] != 1){
      Log::info(__METHOD__, $data);
      return false;
    }

    return $data['args'];
  }
}
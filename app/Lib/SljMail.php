<?php
namespace App\Lib;

use Mail;
use Cache;
use DB;

/**
 * 
 */
class SljMail
{
  protected static $content;

  public function __construct(){
  }

  public static function checkTableTimeAndAddMailContent($sljTable, $timeCol = 'tm'){
    $datetime = DB::table($sljTable)->max($timeCol);
    if(!$datetime)
      return;

    // $datetime = '2019-01-01 00:00:00';

    $now = time();
    $intvlHour = 4;
    if($now - strtotime($datetime) > $intvlHour*3600){
      self::$content .= sprintf('dmz库%s表已经超过%s小时没有同步数据了！最新数据的时间是%s；', 
        $sljTable, $intvlHour, $datetime) . PHP_EOL;
    }
  }

  public static function notice(){
    $lastNoticeTime = self::getLastNoticeTime();
    $now = time();

    # 一个钟最多通知一次
    if($now - $lastNoticeTime <= 3600)
      return -1;

    # 不需要通知
    if(!self::$content)
      return -2;

    $toMails = explode(',', env('SLJ_NOTICE_MAILS'));
    self::send($toMails, '南方网水利局数据同步', self::$content);

    self::setLastNoticeTime($now);

    return true;
  }

  # => int
  protected static function getLastNoticeTime(){
    return Cache::get('__last_mail_notice_time__', 0);
  }

  protected static function setLastNoticeTime(int $time){
    Cache::forever('__last_mail_notice_time__', $time);
  }

  # $toMails :: $email | [$email]
  protected static function send($toMails, $title, $content){
    Mail::raw($content, function ($message) use($toMails, $title) { 
      $message->to($toMails)
        ->subject($title);
    });
  }
}





<?php
namespace App\Lib;

use Mail;

/**
 * 
 */
class SljMail
{
  public function __construct(){
  }

  # $toMails :: $email | [$email]
  public static function send($toMails, $title, $content){
    Mail::raw($content, function ($message) use($toMails, $title) { 
      $message->to($toMails)
        ->subject($title);
    });
  }
}
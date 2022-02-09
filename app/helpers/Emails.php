<?php
/**
 * =======================================================================================
 *                           GemFramework (c) GemPixel                                     
 * ---------------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework as such distribution
 *  or modification of this framework is not allowed before prior consent from
 *  GemPixel. If you find that this framework is packaged in a software not distributed 
 *  by GemPixel or authorized parties, you must not use this software and contact gempixel
 *  at https://gempixel.com/contact to inform them of this misuse.
 * =======================================================================================
 *
 * @package GemPixel\Premium-URL-Shortener
 * @author GemPixel (https://gempixel.com) 
 * @license https://gempixel.com/licenses
 * @link https://gempixel.com  
 */

namespace Helpers;

use Core\DB;
use Core\Helper;
use Core\Email;
use Core\View;

final class Emails {
    
    /**
     * Setup Email
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @return void
     */
    public static function setup(){

        if(config('smtp')->host){
            $mailer = Email::factory('smtp', [
                'username' => config('smtp')->user,
                'password' => config('smtp')->pass,
                'host' => config('smtp')->host,
                'port' => config('smtp')->port
            ]);
        } else {
            $mailer = Email::factory();
        }

        $mailer->from([config('email'), config('title')])
               ->template(View::$path.'/email.php');
        
        return $mailer;
    }
    /**
     * Send an email to validate new email
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param [type] $user
     * @return void
     */
    public static function renewEmail($user){       
    
        $mailer = self::setup();

        $activate = route('activate', [$user->uniquetoken]);

        $message = str_replace("{site.title}", config("title"), config("email.activation"));
        $message = str_replace("{site.link}", config("url"), $message);
        $message = str_replace("{user.username}", "", $message);
        $message = str_replace("{user.activation}", $activate, $message);
        $message = str_replace("http://http", "http", $message);
        $message = str_replace("{user.email}", $user->email, $message);
        $message = str_replace("{user.date}", date("d-m-Y"), $message);	

        $mailer->to($user->email)
                ->send([
                    'subject' => '['.config("title").'] '.e('Please verify your email'),
                    'message' => function($template, $data) use ($message) {
                        if(config('logo')){
                            $title = '<img align="center" alt="'.config('title').'" border="0" class="center autowidth" src="'.uploads(config('logo')).'" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 166px; display: block;" title="'.config('title').'" width="166"/>';
                        } else {
                            $title = '<h3>'.config('title').'</h3>';
                        }
                        return Email::parse($template, ['content' => $message, 'brand' => '<a href="'.config('url').'">'.$title.'</a>']);
                    }
                ]);
    }
    /**
     * Send an email to new registered user
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param [type] $user
     * @return void
     */
    public static function registered($user){
        
        $mailer = self::setup();

        $message = str_replace("{site.title}", config("title"), config("email.registration"));
        $message = str_replace("{site.link}", config("url"), $message);
        $message = str_replace("{user.username}", "", $message);
        $message = str_replace("{user.activation}", "", $message);
        $message = str_replace("http://http", "http", $message);
        $message = str_replace("{user.email}", $user->email, $message);
        $message = str_replace("{user.date}", date("d-m-Y"), $message);	

        $mailer->to($user->email)
                ->send([
                    'subject' => '['.config("title").'] '.e('Registration has been successful'),
                    'message' => function($template, $data) use ($message) {
                        if(config('logo')){
                            $title = '<img align="center" alt="'.config('title').'" border="0" class="center autowidth" src="'.uploads(config('logo')).'" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 166px; display: block;" title="'.config('title').'" width="166"/>';
                        } else {
                            $title = '<h3>'.config('title').'</h3>';
                        }
                        return Email::parse($template, ['content' => $message, 'brand' => '<a href="'.config('url').'">'.$title.'</a>']);
                    }
                ]);
    }
    /**
     * Send a reset password email
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param [type] $user
     * @return void
     */
    public static function reset($user){
        
        $mailer = self::setup();
    
        $code = $user->uniquetoken.'-'.md5(AuthToken.": Expires on".strtotime(date('Y-m-d')));

        $message = str_replace("{site.title}", config("title"), config("email.reset"));
        $message = str_replace("{site.link}", config("url"), $message);
        $message = str_replace("{user.username}", "", $message);
        $message = str_replace("{user.activation}",  route('reset', [$code]) , $message);
        $message = str_replace("http://http", "http", $message);
        $message = str_replace("{user.email}", $user->email, $message);
        $message = str_replace("{user.date}", date("d-m-Y"), $message);	

        $mailer->to($user->email)
                ->send([
                    'subject' => '['.config("title").'] '.e('Password Reset Instructions'),
                    'message' => function($template, $data) use ($message) {
                        if(config('logo')){
                            $title = '<img align="center" alt="'.config('title').'" border="0" class="center autowidth" src="'.uploads(config('logo')).'" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 166px; display: block;" title="'.config('title').'" width="166"/>';
                        } else {
                            $title = '<h3>'.config('title').'</h3>';
                        }
                        return Email::parse($template, ['content' => $message, 'brand' => '<a href="'.config('url').'">'.$title.'</a>']);
                    }
                ]);
    }
    /**
     * Activate account
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param [type] $user
     * @return void
     */
    public static function activate($user){
        
        $mailer = self::setup();
    
        $message = str_replace("{site.title}", config("title"), config("email.activated"));
        $message = str_replace("{site.link}", config("url"), $message);
        $message = str_replace("{user.username}", "", $message);
        $message = str_replace("{user.activation}",  "", $message);
        $message = str_replace("http://http", "http", $message);
        $message = str_replace("{user.email}", $user->email, $message);
        $message = str_replace("{user.date}", date("d-m-Y"), $message);	

        $mailer->to($user->email)
                ->send([
                    'subject' => '['.config("title").'] '.e('Your email has been verified'),
                    'message' => function($template, $data) use ($message) {
                        if(config('logo')){
                            $title = '<img align="center" alt="'.config('title').'" border="0" class="center autowidth" src="'.uploads(config('logo')).'" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 166px; display: block;" title="'.config('title').'" width="166"/>';
                        } else {
                            $title = '<h3>'.config('title').'</h3>';
                        }
                        return Email::parse($template, ['content' => $message, 'brand' => '<a href="'.config('url').'">'.$title.'</a>']);
                    }
                ]);
    }
    /**
     * Change Password Email
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param [type] $user
     * @return void
     */
    public static function passwordChanged($user){
        
        $mailer = self::setup();
    
        $mailer->to($user->email)
                ->send([
                    'subject' => '['.config("title").'] '.e('Your password was changed.'),
                    'message' => function($template, $data) {
                        if(config('logo')){
                            $title = '<img align="center" alt="'.config('title').'" border="0" class="center autowidth" src="'.uploads(config('logo')).'" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 166px; display: block;" title="'.config('title').'" width="166"/>';
                        } else {
                            $title = '<h3>'.config('title').'</h3>';
                        }
                        return Email::parse($template, ['content' => e('Your password was changed. If you did not change your password, please contact us as soon as possible.'), 'brand' => '<a href="'.config('url').'">'.$title.'</a>']);
                    }
                ]);
    }
    /**
     * Send Payment Notification
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param [type] $user
     * @return void
     */
    public static function affiliatePayment($user, $amount){
        
        $mailer = self::setup();

        $mailer->to($user->email)
                ->send([
                    'subject' => '['.config("title").'] '.e('You just got paid!'),
                    'message' => function($template, $data) use ($amount) {
                        if(config('logo')){
                            $title = '<img align="center" alt="'.config('title').'" border="0" class="center autowidth" src="'.uploads(config('logo')).'" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 166px; display: block;" title="'.config('title').'" width="166"/>';
                        } else {
                            $title = '<h3>'.config('title').'</h3>';
                        }
                        return Email::parse($template, ['content' => e('You just got paid {amount} via PayPal for being an awesome affiliate!', null, ['amount' => $amount]), 'brand' => '<a href="'.config('url').'">'.$title.'</a>']);
                    }
                ]);
    }
    /**
     * Invite User
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param [type] $user
     * @return void
     */
    public static function invite($user){
                
        $mailer = self::setup();
        
        $message = str_replace("{site.title}", config("title"), config("email.invitation"));
        $message = str_replace("{site.link}", config("url"), $message);
        $message = str_replace("{user.username}", "", $message);
        $message = str_replace("{user.invite}",  route('invited', $user->uniquetoken), $message);
        $message = str_replace("http://http", "http", $message);
        $message = str_replace("{user.email}", $user->email, $message);
        $message = str_replace("{user.date}", date("d-m-Y"), $message);	

        $mailer->to($user->email)
                ->send([
                    'subject' => '['.config("title").'] '.e('You have been invited to join our team'),
                    'message' => function($template, $data) use ($message) {
                        if(config('logo')){
                            $title = '<img align="center" alt="'.config('title').'" border="0" class="center autowidth" src="'.uploads(config('logo')).'" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 166px; display: block;" title="'.config('title').'" width="166"/>';
                        } else {
                            $title = '<h3>'.config('title').'</h3>';
                        }
                        return Email::parse($template, ['content' => $message, 'brand' => '<a href="'.config('url').'">'.$title.'</a>']);
                    }
                ]);
    }
    /**
     * Canceled
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param [type] $user
     * @return void
     */
    public static function canceled($user){
        
        $mailer = self::setup();

        $mailer->from([config('email'), config('title')])
               ->template(View::$path.'/email.php');

        $message = '<p>Your subscription has been canceled because we have not received any payments on the due date. This might be because your credit card was declined or there is an issue with your account.</p><p>If you would like to reactivate your subscription, please contact us.</p>';

        $mailer->to($user->email)
                ->send([
                    'subject' => e('Your subscription has been canceled'),
                    'message' => function($template, $data) use ($message) {
                        if(config('logo')){
                            $title = '<img align="center" alt="'.config('title').'" border="0" class="center autowidth" src="'.uploads(config('logo')).'" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 166px; display: block;" title="'.config('title').'" width="166"/>';
                        } else {
                            $title = '<h3>'.config('title').'</h3>';
                        }
                        return Email::parse($template, ['content' => $message, 'brand' => $title]);
                    }
                ]);         
    }
    /**
     * Remind user
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.2.1
     * @param [type] $user
     * @return void
     */
    public static function remind($user){
        
        $mailer = self::setup();

        $mailer->from([config('email'), config('title')])
               ->template(View::$path.'/email.php');

        $message = '<p>Hey '.$user['username'].'</p>
                    <p>This is a simple reminder that your trial will end on '.date('d M Y', strtotime($user['expiration'])).'.</p>
                    <p>Please <a href="'.route('pricing', ['utm_source'=> 'email', 'utm_medium' => 'email', 'utm_campaign' => 'reminder']).'">renew</a> it if you wish to continue using all the amazing tools we provide you.</p>';

        $mailer->to($user['email'])
                ->send([
                    'subject' => e('Your trial will end soon!'),
                    'message' => function($template, $data) use ($message) {
                        if(config('logo')){
                            $title = '<img align="center" alt="'.config('title').'" border="0" class="center autowidth" src="'.uploads(config('logo')).'" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 166px; display: block;" title="'.config('title').'" width="166"/>';
                        } else {
                            $title = '<h3>'.config('title').'</h3>';
                        }
                        return Email::parse($template, ['content' => $message, 'brand' => $title]);
                    }
                ]);         
    }
}
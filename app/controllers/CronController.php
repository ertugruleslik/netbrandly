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

use Core\View;
use Core\Helper;
use Core\Request;
use Core\Response;
use Core\DB;
use Models\User;
use Helpers\Emails;

class Cron {
    use Traits\Links;

    /**
     * Check User Cron Jobs
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.2.1.1
     * @param string $token
     * @return void
     */
    public function user(string $token){
        
        if($token != md5('user'.AuthToken)) return null;    

        if(!\Helpers\App::isExtended() || !config('pro')) return null;

        $i = 0;
        foreach(User::where('admin', 0)->where('pro', '1')->findMany() as $user){
            
			if($user->pro && strtotime($user->expiration) < time() || ($user->trial && strtotime('now') > strtotime($user->expiration))) {
                $user->pro = 0;
                $user->planid = null;
                $user->trial = 0;
                $user->save();
                if($user->email){
                    Emails::canceled($user);
                }                                       
                $i++;
			}
        }
        GemError::channel('Cron.users');
        GemError::toChannel('Cron.users', $i > 0 ? "{$i} users were downgraded.": "Nothing to report.");

    }
    /**
     * Remove Data
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.2.1.1
     * @param string $token
     * @return void
     */
    public function data(string $token){

        if($token != md5('data'.AuthToken)) return null;    

        if(!config('pro')) return null;

        $ids = null;
        
        foreach(User::select('id')->select('planid')->where('admin', 0)->whereNotNull('planid')->whereNull('teamid')->findArray() as $user){

            if(!$plan = DB::plans()->where('id', $user['planid'])->first()) continue;

            $retention = $plan->retention;
            
            if($retention == 0) continue;
            
            DB::stats()->where('urluserid', $user['id'])->whereRaw('DATE(date) < \''.date("Y-m-d 00:00:00", strtotime("-{$retention} days")).'\'')->deleteMany();
            $ids .= "#{$user['id']},";
        }

        GemError::channel('Cron.data');
        GemError::toChannel('Cron.data', $ids ? "Data for users {$ids} were removed.": "Nothing to report.");

    }
    /**
     * Check URLs
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.2.1.1
     * @param string $token
     * @return void
     */
    public function urls(string $token){
        
        if($token != md5('url'.AuthToken)) return null;        

        $i = 0;
        
        foreach(DB::url()->whereNull('qrid')->whereNull('profileid')->where('status', 1)->orderByExpr('RAND()')->limit(500)->findMany() as $url){
            
            $detected = false;
            // Check blacklist domain
            if(!$url->qrid && !$url->profileid && ($this->domainBlacklisted($url->url) || $this->wordBlacklisted($url->url))){
                $detected = true;
            }

            // Check with Google Web Risk
            if(!$url->qrid && !$url->profileid && !$this->safe($url->url)) {
                $detected = true;
            }

            // Check with Phish
            if(!$url->qrid && !$url->profileid && $this->phish($url->url)) {
               $detected = true;
            }
            
            // Check with VirusTotal
            if(!$url->qrid && !$url->profileid && $this->virus($url->url)) {
                $detected = true;
            }

            if($detected){
                $url->status = 0;
                $url->save();
                
                if(DB::reports()->where('url', $url->url)->first()) continue;
        
                $report = DB::reports()->create();
                $report->url = \Helpers\App::shortRoute($url->domain, $url->alias.$url->custom);
                $report->type = "Disabled by cron";
                $report->email = "Cron Job";
                $report->bannedlink = $url->url;
                $report->status = 1;
                $report->ip = null;
                $report->date = Helper::dtime();
                $report->save();
                $i++;
            }
        }

        GemError::channel('Cron.urls');
        GemError::toChannel('Cron.urls', $i > 0 ? "{$i} urls were blocked.": "Nothing to report.");
    }
    /**
     * Remind Users
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.2.1
     * @param string $days
     * @param string $token
     * @return void
     */
    public function remind(string $days, string $token){
        
        if($token != md5('remind'.AuthToken)) return null;        

        $i = 0;
        
        foreach(User::where('admin', 0)->where('trial', 1)->findArray() as $user){

            if(date('d-m-Y') == date('d-m-Y', strtotime("-{$days} days", strtotime($user['expiration'])))){
                Emails::remind($user);
                $i++;
            }
        }

        GemError::channel('Cron.reminded');
        GemError::toChannel('Cron.reminded', $i > 0 ? "{$i} users were reminded.": "Nothing to report.");
    }
}


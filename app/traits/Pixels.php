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

namespace Traits;

use Core\Request;
use Core\DB;
use Core\Helper;
use User\Overlay;

trait Pixels {
    /**
     * Pixels
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.8
     * @param [type] $type
     * @param [type] $action
     * @return void
     */
	public static function pixels($type = null, $action = null){
        $list = [
            "gtmpixel" => [
                'name' => 'Google Tag Manager',
                'icon' => 'gtm.svg',                
                'validate' => [self::class, 'gtmRule'],
                'display' => [self::class, 'gtmpixel']
            ],
            "gapixel" => [
                'name' => 'Google Analytics',
                'icon' => 'ga.svg',                
                'validate' => [self::class, 'gaRule'],
                'display' => [self::class, 'gapixel']
            ],
            "fbpixel" => [
                'name' => 'Facebook',
                'icon' => 'facebook.svg',                
                'validate' => [self::class, 'fbRule'],
                'display' => [self::class, 'fbpixel']
            ],
            "adwordspixel" => [
                'name' => 'Google Ads',
                'icon' => 'adwords.svg',                
                'validate' => [self::class, 'adwordsRule'],
                'display' => [self::class, 'adwordspixel']
            ],
            "linkedinpixel" => [
                'name' => 'LinkedIn',
                'icon' => 'linkedin.svg',                
                'validate' => [self::class, 'linkedinRule'],
                'display' => [self::class, 'linkedinpixel']
            ],
            "twitterpixel" => [
                'name' => 'Twitter',
                'icon' => 'twitter.svg',                
                'validate' => [self::class, 'twitterRule'],
                'display' => [self::class, 'twitterpixel']
            ],
            "adrollpixel" => [
                'name' => 'AdRoll',
                'icon' => 'adroll.svg',                
                'validate' => [self::class, 'adrollRule'],
                'display' => [self::class, 'adrollpixel']
            ],
            "quorapixel" => [
                'name' => 'Quora',
                'icon' => 'quora.svg',                
                'validate' => [self::class, 'quoraRule'],
                'display' => [self::class, 'quorapixel']
            ],
            "pinterest" => [
                'name' => 'Pinterest',
                'icon' => 'pinterest.svg',                
                'validate' => [self::class, 'pinterestRule'],
                'display' => [self::class, 'pinterest']
            ],
            "bing" => [
                'name' => 'Bing',
                'icon' => 'bing.svg',                
                'validate' => [self::class, 'bingRule'],
                'display' => [self::class, 'bing']
            ],
            "snapchat" => [
                'name' => 'Snapchat',
                'icon' => 'snapchat.svg',                
                'validate' => [self::class, 'snapchatRule'],
                'display' => [self::class, 'snapchat']
            ],
            "reddit" => [
                'name' => 'Reddit',
                'icon' => 'reddit.svg',                
                'validate' => [self::class, 'redditRule'],
                'display' => [self::class, 'reddit']
            ],
        ];    
        
        if($extended = \Core\Plugin::dispatch('pixels.extend')){
			foreach($extended as $fn){
				$list = array_merge($list, $fn);
			}
		}

		if($type && $action && isset($list[$type][$action])) return $list[$type][$action];

		if(isset($list[$type])) return $list[$type];

		return $list;
    }
    /**
     * Rule for facebook pixel
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.8
     * @return void
     */
    public static function fbRule($tag){    
        if(!is_numeric($tag) || (strlen($tag) > 20)) throw new \Exception(("Facebook pixel ID is not correct. Please double check."));
        return true;
    }
    /**
     * Rule for adwords pixel
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.8
     * @return void
     */
    public static function adwordsRule($tag){    
        if(strlen($tag) > 40) throw new \Exception(e("Google Ads pixel ID is not correct. Please double check."));
        return true;
    }
    /**
     * Rule for linkedin pixel
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.8
     * @return void
     */
    public static function linkedinRule($tag){    
        if(strlen($tag) > 10) throw new \Exception(e("LinkedIn ID is not correct. Please double check."));
        return true;
    }
    /**
     * Rule for twitter pixel
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.8
     * @return void
     */
    public static function twitterRule($tag){    
        if(strlen($tag) > 15) throw new \Exception(e("Twitter ID is not correct. Please double check."));
        return true;
    }
    /**
     * Rule for adroll pixel
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.8
     * @return void
     */
    public static function adrollRule($tag){    
        if(strlen($tag) > 50) throw new \Exception(e("AdRoll ID is not correct. Please double check."));
        return true;
    }
    /**
     * Rule for quora pixel
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.8
     * @return void
     */
    public static function quoraRule($tag){    
        if(strlen($tag) < 30) throw new \Exception(e("Quora ID is not correct. Please double check."));
        return true;
    }
    /**
     * Rule for gtm pixel
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.8
     * @return void
     */
    public static function gtmRule($tag){            
        if(strlen($tag) < 5 || strpos($tag, "GTM") === false) throw new \Exception(e("GTM container ID is not correct. Please double check."));
        return true;
    }
    /**
     * Rule for ga pixel
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.8
     * @return void
     */
    public static function gaRule($tag){    
        if(strlen($tag) < 5) throw new \Exception(e("Google Analytics ID is not correct. Please double check."));
        return true;
    }
    /**
     * Rule for snapchat pixel
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.8
     * @param [type] $tag
     * @return void
     */
    public static function snapchatRule($tag){    
        if(strlen($tag) < 5) throw new \Exception(e("Snapchat ID is not correct. Please double check."));
        return true;
    }
    /**
     * Pixel rule
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.8
     * @param [type] $tag
     * @return void
     */    
    public static function pinterestRule($tag){    
        if(strlen($tag) < 5) throw new \Exception(e("Pinterest ID is not correct. Please double check."));
        return true;
    }
    /**
     * Pixel rule
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.8
     * @param [type] $tag
     * @return void
     */
    public static function redditRule($tag){    
        if(strlen($tag) < 5) throw new \Exception(e("Reddit ID is not correct. Please double check."));
        return true;
    }
    /**
     * Pixel rule
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.8
     * @param [type] $tag
     * @return void
     */
    public static function bingRule($tag){    
        if(strlen($tag) < 5) throw new \Exception(e("Bing ID is not correct. Please double check."));
        return true;
    }
    /**
     * Validate Pixel
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.8
     * @param [type] $type
     * @param [type] $tag
     * @return void
     */
    public static function validate($type, $tag){
        
        $pixels = self::pixels();

        if(isset($pixels[$type])){
            return call_user_func($pixels[$type]['validate'], $tag);
        }

        return false;
    }
    /**
     * Display Pixel Code
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.8
     * @param [type] $type
     * @param [type] $tag
     * @return void
     */
    public static function display($type, $tag){
        
        $pixels = self::pixels();
    
        if(isset($pixels[$type])){
            return \call_user_func($pixels[$type]['display'], $tag);
        }
    }

    /**
	 * Facebook Pixel
	 * @author KBRmedia <http://gempixel.com>
	 * @version 6.0
	 * @param  string $id
	 */
	public static function fbpixel($id){
		if(empty($id) || strlen($id) < 9) return;

		return "<!--fbpixel--><script type='text/javascript'>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init', '{$id}');fbq('track', 'PageView');		fbq('track', 'Lead');</script><noscript><img height='1' width='1' style='display:none'src='https://www.facebook.com/tr?id={$id}&ev=PageView&noscript=1'/></noscript>";
	}
	/**
	 * Adwords Pixel
	 * @author KBRmedia <http://gempixel.com>
	 * @version 1.0
	 * @param  string $id
	 */
	public static function adwordspixel($id){
		if(empty($id) || strlen($id) < 9) return;

		$Eid = explode("/", $id);

		return "<!--adwordspixel--><script async src='https://www.googletagmanager.com/gtag/js?id={$Eid[0]}'></script><script type='text/javascript'>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', '{$Eid[0]}');gtag('event', 'conversion', {'send_to': '{$id}'});</script>";
	}	
	/**
	 * Lnkedin Pixel
	 * @author KBRmedia <http://gempixel.com>
	 * @version 6.0
	 * @param  string $id
	 */
	public static function linkedinpixel($id){
		if(empty($id) || strlen($id) < 6) return;

		return '<!--linkedinpixel--><script type="text/javascript">_linkedin_data_partner_id = "'.$id.'";</script><script type="text/javascript">(function(){var s = document.getElementsByTagName("script")[0];var b = document.createElement("script");b.type = "text/javascript";b.async = true;b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";s.parentNode.insertBefore(b, s);})();</script>';
	}	
	/**
	 * Adroll Pixel
	 * @author KBRmedia <http://gempixel.com>
	 * @version 5.1
	 * @param  string $id
	 */
	public static function adrollpixel($id){

		if(empty($id) || strlen($id) < 9) return;

		$Eid = explode("/", $id);

		return '<!--adrollpixel--><script type="text/javascript">adroll_adv_id = "'.$Eid[0].'";adroll_pix_id = "'.$Eid[1].'";(function () {var _onload = function(){if (document.readyState && !/loaded|complete/.test(document.readyState)){setTimeout(_onload, 10);return}if (!window.__adroll_loaded){__adroll_loaded=true;setTimeout(_onload, 50);return}var scr = document.createElement("script");var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");scr.setAttribute(\'async\', \'true\');scr.type = "text/javascript";scr.src = host + "/j/roundtrip.js"; ((document.getElementsByTagName(\'head\') || [null])[0] || document.getElementsByTagName(\'script\')[0].parentNode).appendChild(scr);};if (window.addEventListener) {window.addEventListener(\'load\', _onload, false);}else {window.attachEvent(\'onload\', _onload)}}());</script>';

	}
	/**
	 * Twitter Pixel
	 * @author KBRmedia <http://gempixel.com>
	 * @version 5.1
	 * @param  string $id
	 */
	public static function twitterpixel($id){
		return "<!--twitterpixel--><script type='text/javascript'>!function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);},s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='//static.ads-twitter.com/uwt.js',a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');twq('init','$id');twq('track','PageView');</script>";
	}
	/**
	 * Quora Pixel
	 * @author KBRmedia <https://gempixel.com>
	 * @version 5.6.3
	 * @param  string $id
	 */
	public static function quorapixel($id){
		return "<!--quorapixel--><script>!function(q,e,v,n,t,s){if(q.qp) return; n=q.qp=function(){n.qp?n.qp.apply(n,arguments):n.queue.push(arguments);}; n.queue=[];t=document.createElement(e);t.async=!0;t.src=v; s=document.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t,s);}(window, 'script', 'https://a.quora.com/qevents.js');qp('init', '$id');qp('track', 'ViewContent');</script>";
	}	
    /**
     * Google Tag Manager
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param string $id
     * @return void
     */
    public static function gtmpixel($id){
        return "<!--gtmpixel--><script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{$id}');</script>";
    }
    /**
     * Google Analytics
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param [type] $id
     * @return void
     */
    public static function gapixel($id){
        return "<!--gapixel--><script async src='https://www.googletagmanager.com/gtag/js?id={$id}'></script><script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', '{$id}');</script>";
    }
    /**
     * Pinterest Pixel
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param [type] $id
     * @return void
     */
    public static function pinterest($id){
        return '<script>!function(e){if(!window.pintrk){window.pintrk = function () {window.pintrk.queue.push(Array.prototype.slice.call(arguments))};varn=window.pintrk;n.queue=[],n.version="3.0";vart=document.createElement("script");t.async=!0,t.src=e;varr=document.getElementsByTagName("script")[0];r.parentNode.insertBefore(t,r)}}("https://s.pinimg.com/ct/core.js");pintrk(\'load\', \''.$id.'\');pintrk(\'page\');pintrk(\'track\', \'pagevisit\');</script>';
    }
    /**
     * Snapchat pixels
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.6
     * @param [type] $id
     * @return void
     */
    public static function snapchat($id){
        return "<!-- Snap Pixel Code --><script type='text/javascript'>(function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function(){a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};a.queue=[];var s='script';r=t.createElement(s);r.async=!0;r.src=n;var u=t.getElementsByTagName(s)[0];u.parentNode.insertBefore(r,u);})(window,document,'https://sc-static.net/scevent.min.js');snaptr('init', '".$id."');snaptr('track', 'PAGE_VIEW');</script><!-- End Snap Pixel Code -->";
    }
    /**
     * Display pixel for reddit
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.8
     * @param [type] $id
     * @return void
     */
    public static function reddit($id){
        return '<!-- Reddit Pixel --><script>
        !function(w,d){if(!w.rdt){var p=w.rdt=function(){p.sendEvent?p.sendEvent.apply(p,arguments):p.callQueue.push(arguments)};p.callQueue=[];var t=d.createElement("script");t.src="https://www.redditstatic.com/ads/pixel.js",t.async=!0;var s=d.getElementsByTagName("script")[0];s.parentNode.insertBefore(t,s)}}(window,document);rdt(\'init\',\''.$id.'\');rdt(\'track\', \'PageVisit\');
        </script><!-- DO NOT MODIFY --><!-- End Reddit Pixel -->';
    }
    /**
     * Display pixel for bing
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.8
     * @param [type] $id
     * @return void
     */
    public static function bing($id){
        return '<!--Bing Pixel --><script>(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"'.$id.'"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");</script>';
    }
}
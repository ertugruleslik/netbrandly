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

namespace API;

use \Core\Helper;
use \Core\Request;
use \Core\Response;
use \Core\DB;
use \Core\Auth;
use \Models\User;

class QR {
    use \Traits\Links;

    /**
     * Check if is admin
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     */
    public function __construct(){

        if(!Auth::ApiUser()->has('qr')){
            die(Response::factory(['error' => 1, 'message' => 'You do not have permission to access this endpoint.'], 403)->json());
        }        
    }
    /**
     * List all plans
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param \Core\Request $request
     * @return void
     */
    public function get(Request $request){

        $qrs = [];

        $query = DB::qrs()->where('userid', Auth::ApiUser()->id);

        $page = (int) currentpage();

        $limit = 15;

        if( $request->limit && \is_numeric($request->limit) ){                    
            $limit = (int) $request->limit;
        } 

        $total = $query->count();

        $results = $query->limit($limit)->offset(($page-1)*$limit)->findMany();
        
        if(($total % $limit)<>0) {
            $max = floor($total/$limit)+1;
        } else {
            $max = floor($total/$limit);
        }  
    
        foreach($results as $qr){
            $scan = 0;
            if($url = DB::url()->select('click')->first($qr->urlid)){
                $scan = $url->click;
            }

            $qrs[] = [
                "id" => $qr->id,
                "name" => $qr->name,
                "link" => route('qr.generate', $qr->alias),
                "scans" => $scan,
                "date" => $qr->created_at
            ];
        }

        return Response::factory(['error' => 0, 'data' => ['result' => $total, 'perpage' => $limit, 'currentpage' => $page, 'nextpage' => $max == 0 || $page == $max ? null : $page+1, 'maxpage' => $max, 'qrs' => $qrs]])->json();

    }
    /**
     * Get a single QR
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param integer $id
     * @return void
     */
    public function single(int $id){

        if(!$qr = DB::qrs()->where('id', $id)->where('userid', Auth::ApiUser()->id)->first()){
            return Response::factory(['error' => 1, 'message' => 'QR does not exist.'])->json(); 
        }

        if($qr->urlid && $url = DB::url()->first($qr->urlid)){

            // Countries
            $countries = DB::stats()
                            ->select('country', 'country')
                            ->selectExpr('COUNT(country)', 'count')
                            ->where('urlid', $url->id)
                            ->groupBy('country')
                            ->orderByDesc('count')
                            ->limit(10)
                            ->findMany();
        
            $i = 0;
            $top_country = [];
        
            foreach ($countries as $country) {
                if(empty($country->country)) $country->country = "Unknown";
                $top_country[ucwords($country->country)] = $country->count;
            }

            arsort($top_country);
        
            // referrers
            $top_referrers = [];
        
            $referrers = DB::stats()
                            ->select('domain', 'domain')
                            ->selectExpr('COUNT(domain)', 'count')
                            ->where('urlid', $url->id)
                            ->groupBy('domain')
                            ->limit(10)
                            ->orderByDesc('count')
                            ->findMany();
                
            $browsers = DB::stats()
                            ->select('browser', 'browser')
                            ->selectExpr('COUNT(browser)', 'count')
                            ->where('urlid', $url->id)
                            ->groupBy('browser')
                            ->limit(10)
                            ->orderByDesc('count')
                            ->findMany();
        
            $os = DB::stats()
                        ->select('os', 'os')
                        ->selectExpr('COUNT(os)', 'count')
                        ->where('urlid', $url->id)
                        ->groupBy('os')
                        ->limit(10)
                        ->orderByDesc('count')
                        ->findMany();
            
            $facebook = DB::stats()
                        ->where('urlid', $url->id)
                        ->whereRaw("(domain LIKE '%facebook.%' OR domain LIKE '%fb.%')")
                        ->count();

            $twitter = DB::stats()
                        ->where('urlid', $url->id)
                        ->whereRaw("(domain LIKE '%twitter.%' OR domain LIKE '%t.co%')")
                        ->count();

            $instagram = DB::stats()
                        ->where('urlid', $url->id)
                        ->whereRaw("(domain LIKE '%instagram.%')")
                        ->count();
            $linkedin = DB::stats()
                    ->where('urlid', $url->id)
                    ->whereRaw("(domain LIKE '%linkedin.%')")
                    ->count();
        
            foreach ($referrers as $referrer) {
                
                if(empty($referrer->domain)) $referrer->domain = e("Direct, email and other");

                if(!preg_match("~facebook.~", $referrer->domain) && !preg_match("~fb.~", $referrer->domain) && !preg_match("~t.co~", $referrer->domain) && !preg_match("~twitter.~", $referrer->domain)){
                    $top_referrers[$referrer->domain] = $referrer->count;
                }
            }  
        
            $top_browsers = [];
            foreach ($browsers as $browser) {
                $top_browsers[ucwords($browser->browser)] = $browser->count;
            }

            $top_os = [];
            foreach ($os as $o) {
                $top_os[ucwords($o->os)] = $o->count;
            }
        
            arsort($top_referrers); 
            arsort($top_browsers); 
            arsort($top_os); 
        }

        $response = [
            "error" => 0,
            "details" => [
                    "id" => $qr->id,
                    "name" => $qr->name,
                    "link" => route('qr.generate', $qr->alias),
                    "scans" => (isset($url) && $url) ? $url->click : 0,
                    "data" => $qr->created_at
            ],            
        ];

        if(isset($url) && $url){
            $response['data'] = [
                                "clicks"  	    => (int) $url->click,
                                "uniqueClicks" 	=> (int) $url->uniqueclick,
                                "topCountries" 	=> $top_country,
                                "topReferrers" 	=> $top_referrers,
                                "topBrowsers"	=> $top_browsers,
                                "topOs"			=> $top_os,
                                "socialCount"	=> [
                                            "facebook" => (int) $facebook,
                                            "twitter"  => (int) $twitter,
                                            "instagram"   => (int) $instagram,
                                            "linkedin"   => (int) $linkedin
                                ]
                            ];
        }
        
        return Response::factory($response)->json();
    }
    /**
     * Create QR Code
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param \Core\Request $body
     * @return void
     */
    public function create(Request $body){
        
        $user = Auth::ApiUser();
    
        $count = DB::qrs()->where('userid', $user->rID())->count();

        $total = $user->hasLimit('qr');

        if($total != 0 && $count > $total){
            return Response::factory(['error' => 1, 'message' => 'You have reached your limit.'])->json();
        }

        $request = $body->getJSON();

        if(!isset($request->type) || !isset($request->data)) return Response::factory(['error' => 1, 'message' => 'Invalid QR format or missing data.'])->json(); 

        if(!\Helpers\QR::typeExists($request->type)) return Response::factory(['error' => 1, 'message' => 'Invalid QR format or missing data.'])->json();

        $input = $request->data;

        $data = call_user_func([\Helpers\QR::class, 'type'.ucfirst($request->type)], clean($input));

        $qrdata = [];

        $qrdata['type'] = clean($request->type);

        $qrdata['data'] = $input;

        if(isset($request->background)){
            $qrdata['color']['bg'] = clean($request->background);
        }
        if(isset($request->foreground)){
            $qrdata['color']['fg'] = clean($request->foreground);
        }

        if(isset($request->logo)){
            
            if(!in_array(Helper::extension($request->logo), ['jpg', 'png'])) return Response::factory(['error' => 1, 'message' =>'Logo must be either a PNG or a JPEG (Max 500kb).'])->json();


            $filename = "qr_logo".Helper::rand(6).md5($request->logo).Helper::extension($request->logo);

            copy($request->logo, appConfig('app.storage')['qr']['path'].'/'.$filename);

            $qrdata['custom'] = $filename;
        }

        $url = null;
        $alias = \substr(md5($user->rID().Helper::rand(32).$data), 0, 6);

        if(!in_array($request->type, ['text', 'vcard'])){
            $url = DB::url()->create();
            $url->userid = $user->rID();
            $url->url = $data;
            $url->alias = $alias;
            $url->date = Helper::dtime();
            $url->save();
        }

        $qr = DB::qrs()->create();        
        $qr->userid = $user->rID();
        $qr->alias = $alias;
        $qr->urlid = $url ? $url->id : null;
        $qr->name = isset($request->name) ? clean($request->name) : 'Via API';
        $qr->data = json_encode($qrdata);
        $qr->status = 1;
        $qr->created_at = Helper::dtime();
        $qr->save();

        if($url){
            $url->qrid = $qr->id;
            $url->save();
        }

        return Response::factory(['error' => 0, 'id' => $qr->id, 'link' => route('qr.generate', $qr->alias)])->json();
    }
    /**
     * Update QR
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param integer $id
     * @return void
     */
    public function update(Request $body, int $id){
        
        $user = Auth::ApiUser();

        if(!$qr = DB::qrs()->where('id', $id)->where('userid', $user->id)->first()){
            return Response::factory(['error' => 1, 'message' => 'QR does not exist.'])->json();
        }

        $request = $body->getJSON();
        $qr->data = json_decode($qr->data);
        
        if(isset($request->data)){
            $input = $request->data;
            $data = call_user_func([\Helpers\QR::class, 'type'.ucfirst($qr->data->type)], clean($input));
            
            $qr->data->data = $input;

            if($qr->urlid && $url = DB::url()->where('id', $qr->urlid)->first()){
                $url->url = $data;
                $url->save();
            }            
        }

        if(isset($request->background)){
            $qr->data->color->bg = clean($request->background);
        }
        if(isset($request->foreground)){
            $qr->data->color->fg = clean($request->foreground);            
        }

        if(isset($request->logo)){
            
            if(!in_array(Helper::extension($request->logo), ['jpg', 'png'])) return Response::factory(['error' => 1, 'message' =>'Logo must be either a PNG or a JPEG (Max 500kb).'])->json();

            if(isset($qr->data->custom) && !empty($qr->data->custom)){
                unlink( appConfig('app.storage')['qr']['path'].'/'.$qr->data->custom);
            }


            $filename = "qr_logo".Helper::rand(6).md5($request->logo).Helper::extension($request->logo);

            copy($request->logo, appConfig('app.storage')['qr']['path'].'/'.$filename);

            $qr->data->custom = $filename;
        }

        $qr->data = json_encode($qr->data);

        $qr->save();     

        unlink( appConfig('app.storage')['qr']['path'].'/'.$qr->filename);
        
        return Response::factory(['error' => 0, 'message' => 'QR has been updated successfully.'])->json();
    }
    /**
     * Delete QR
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param integer $id
     * @return void
     */
    public function delete(int $id){
        if(!$qr = DB::qrs()->where('id', $id)->where('userid', Auth::ApiUser()->id)->first()){
            return Response::factory(['error' => 1, 'message' => 'QR does not exist.'])->json(); 
        }

        $qr->delete();

        if($url = DB::url()->where('qrid', $id)->where('userid', $qr->userid)->first()){
            $this->deleteLink($url->id);
        }

        return Response::factory(['error' => 0, 'message' => 'QR has been deleted successfully.'])->json(); 
    }
}
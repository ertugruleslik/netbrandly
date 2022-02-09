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

use Core\Helper;
use Core\Request;
use Core\Response;
use Core\DB;
use Core\Auth;
use Models\User;

class Domains {

    /**
     * Check if is admin
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     */
    public function __construct(){

        if(!Auth::ApiUser()->has('domain')){
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

        $domains = [];

        $query = DB::domains()->where('userid', Auth::ApiUser()->id);

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
    
        foreach($results as $domain){

            $domains[] = [
                "id" => $domain->id,
                "domain" => $domain->domain,
                "redirectroot" => $domain->redirect,
                "redirect404" => $domain->redirect404
            ];
        }

        return Response::factory(['error' => 0, 'data' => ['result' => $total, 'perpage' => $limit, 'currentpage' => $page, 'nextpage' => $max == 0 || $page == $max ? null : $page+1, 'maxpage' => $max, 'domains' => $domains]])->json();

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
    
        $count = DB::domains()->where('userid', $user->rID())->count();

        $total = $user->hasLimit('domain');

        if($total != 0 && $count > $total){
            return Response::factory(['error' => 1, 'message' => 'You have reached your limit.'])->json();
        }

        $request = $body->getJSON();

        if(!isset($request->domain) || filter_var(idn_to_ascii($request->domain),FILTER_VALIDATE_URL) == false) return Response::factory(['error' => 1, 'message' => 'A valid domain name is required.']);

        $domain =  str_replace(['http://', 'https://'], '', clean($request->domain));
        
        if(DB::domains()->whereRaw('domain = ? OR domain = ?', ['http://'.$domain, 'https://'.$domain])->first()) return Response::factory(['error' => 1, 'message' => 'The domain has been already used.']);
        
        // if(\Helpers\App::checkDNS(config('url'), $request->domain) === false) {
        //     return Helper::redirect()->back()->with('danger', e('The domain name is not pointed to our server. DNS changes could take up to 36 hours.'));
        // }

        if(isset($request->redirectroot) && !filter_var($request->redirectroot, FILTER_VALIDATE_URL)) return Response::factory(['error' => 1, 'message' => 'A valid url is required for the root domain.']);
        if(isset($request->redirect404) && !filter_var($request->redirect404, FILTER_VALIDATE_URL)) return Response::factory(['error' => 1, 'message' => 'A valid url is required for the 404 page.']);

        $domain = DB::domains()->create();
        $domain->domain = Helper::clean($request->domain, 3, true);
        $domain->redirect = Helper::clean($request->redirectroot, 3, true);
        $domain->redirect404 = Helper::clean($request->redirect404, 3, true);
        $domain->status = 1;
        $domain->userid = $user->rID();

        $domain->save();

        return Response::factory(['error' => 0, 'id' => $domain->id])->json();
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

        $request = $body->getJSON();

        if(!$domain = DB::domains()->where('id', $id)->where('userid', $user->rID())->first()) return Response::factory(['error' => 1, 'message' => 'Domain not found. Please try again.']);

        if(!isset($request->redirectroot) && !isset($request->redirect404)) return Response::factory(['error' => 1, 'message' => 'No data sent.'])->json();
        
        if(isset($request->redirectroot)){
            if(!filter_var($request->redirectroot, FILTER_VALIDATE_URL)) return Response::factory(['error' => 1, 'message' => 'A valid url is required for the root domain.']);
            $domain->redirect = Helper::clean($request->redirectroot, 3, true);
        }
        
        if(isset($request->redirect404)){
            if(!filter_var($request->redirect404, FILTER_VALIDATE_URL)) return Response::factory(['error' => 1, 'message' => 'A valid url is required for the 404 page.']);
            $domain->redirect404 = Helper::clean($request->redirect404, 3, true);
        }

        $domain->save();
        
        return Response::factory(['error' => 0, 'message' => 'Domain has been updated successfully.'])->json();
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

        $user = Auth::ApiUser();

        if(!$domain = DB::domains()->where('userid', $user->rID())->where('id', $id)->first()){
            return Response::factory(['error' => 1, 'message' => 'Domain not found. Please try again.'])->json();
        }             

        $domain->delete();

        return Response::factory(['error' => 0, 'message' => 'Domain has been deleted successfully.'])->json(); 
    }
}
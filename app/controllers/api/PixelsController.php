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

class Pixels {

    use \Traits\Pixels;

    /**
     * Check if is admin
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     */
    public function __construct(){

        if(!Auth::ApiUser()->has('pixels')){
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

        $pixels = [];

        $query = DB::pixels()->where('userid', Auth::ApiUser()->id);

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
    
        foreach($results as $pixel){

            $pixels[] = [
                "id" => $pixel->id,
                "type" => $pixel->type,
                "name" => $pixel->name,
                "tag" => $pixel->tag,
                "date" => $pixel->created_at
            ];
        }

        return Response::factory(['error' => 0, 'data' => ['result' => $total, 'perpage' => $limit, 'currentpage' => $page, 'nextpage' => $max == 0 || $page == $max ? null : $page+1, 'maxpage' => $max, 'pixels' => $pixels]])->json();

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
    
        $count = DB::pixels()->where('userid', $user->rID())->count();

        $total = $user->hasLimit('pixels');

        if($total != 0 && $count > $total){
            return Response::factory(['error' => 1, 'message' => 'You have reached your limit.'])->json();
        }

        $request = $body->getJSON();

        $providers = self::pixels();

        if(!isset($request->type)) return Response::factory(['error' => 1, 'message' => 'Missing type parameter. Pixel provider is required.'])->json();

        if(!isset($request->name)) return Response::factory(['error' => 1, 'message' => 'Missing name parameter. Pixel name is required.'])->json();
        
        if(!isset($request->tag)) return Response::factory(['error' => 1, 'message' => 'Missing tag parameter. Pixel tag is required.'])->json();

        if(!isset($providers[$request->type])) return Response::factory(['error' => 1, 'message' => 'Pixel provider is currently not supported.'])->json();

        if(strlen($request->tag) < 3) {
            return Response::factory(['error' => 1, 'message' => 'Please enter valid id.'])->json();
        }

        if($pixel = DB::pixels()->where('userid', $user->rID())->where('type', $request->type)->where('tag', clean($request->tag))->first()){
            return Response::factory(['error' => 1, 'message' => 'A pixel with this provider and tag already exists.'])->json();
        }  
        
        try{
            self::validate($request->type, $request->tag);
        } catch(\Exception $e){
            return Response::factory(['error' => 1, 'message' => $e->getMessage()])->json();
        }
        
        $pixel = DB::pixels()->create();
        
        $pixel->userid = $user->rID();
        $pixel->type = clean($request->type);
        $pixel->name = clean($request->name);
        $pixel->tag = clean($request->tag);
        $pixel->created_at = Helper::dtime('now');
        $pixel->save();

        return Response::factory(['error' => 0, 'id' => $pixel->id])->json();
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

        if(!$pixel = DB::pixels()->where('userid', $user->rID())->where('id', $id)->first()){
            return Response::factory(['error' => 1, 'message' => 'Pixel not found. Please try again.']);
        }            

        $providers = self::pixels();
        
        if(!isset($request->tag)) return Response::factory(['error' => 1, 'message' => 'Missing tag parameter. Pixel tag is required.'])->json();

        if(!isset($providers[$pixel->type])) return Response::factory(['error' => 1, 'message' => 'Pixel provider is currently not supported.'])->json();

        if(strlen($request->tag) < 3) {
            return Response::factory(['error' => 1, 'message' => 'Please enter valid id.'])->json();
        }
        
        try{
            self::validate($pixel->type, $request->tag);
        } catch(\Exception $e){
            return Response::factory(['error' => 1, 'message' => $e->getMessage()])->json();
        }   
        if(isset($request->name) && !empty($request->name)){
            $pixel->name = clean($request->name);
        }

        $pixel->tag = clean($request->tag);

        $pixel->save();
        
        return Response::factory(['error' => 0, 'message' => 'Pixel has been updated successfully.'])->json();
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

        if(!$pixel = DB::pixels()->where('userid', $user->rID())->where('id', $id)->first()){
            return Response::factory(['error' => 1, 'message' => 'Pixel not found. Please try again.'])->json();
        }             

        $pixel->delete();

        return Response::factory(['error' => 0, 'message' => 'Pixel has been deleted successfully.'])->json(); 
    }
}
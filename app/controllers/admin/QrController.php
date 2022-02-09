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

namespace Admin;

use Core\DB;
use Core\View;
use Core\Request;
use Core\Helper;
Use Helpers\CDN;
use Models\User;

class Qr {
    
    use \Traits\Links;

    /**
     * Links
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @return void
     */
    public function index(Request $request){
        $query = DB::qrs();
        
        if($request->sort == "old") $query->orderByAsc('created_at');
        if(!$request->sort) $query->orderByDesc('created_at');

        $qrs = [];
        foreach($query->paginate(is_numeric($request->perpage) ? $request->perpage : 15) as $qr){
            if(!$qr->user = User::first($qr->userid)) continue;
            $qr->url = DB::url()->first($qr->urlid);
            $qr->data= json_decode($qr->data, true);
            $qr->source = '';
            if(is_array($qr->data['data'] )){
                foreach($qr->data['data'] as $key => $data){
                    $qr->source .= "{$key} = {$data}\n";
                }
            }  else {
                $qr->source = $qr->data['data'];
            }        
            $qrs[] = $qr;
        }

        View::push(assets('frontend/libs/clipboard/dist/clipboard.min.js'), 'js')->toFooter();

        View::set('title', e('QR Codes'));

        return View::with('admin.qr', compact('qrs'))->extend('admin.layouts.main');
    }

     /**
     * Delete qr
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param [type] $id
     * @return void
     */
    public function delete(int $id, string $nonce){

        \Gem::addMiddleware('DemoProtect');

        if(!Helper::validateNonce($nonce, 'qr.delete')){
            return Helper::redirect()->back()->with('danger', e('An unexpected error occurred. Please try again.'));
        }

        if(!$qr = DB::qrs()->where('id', $id)->first()){
            return back()->with('danger', 'QR does not exist.');
        }
        
        unlink( appConfig('app.storage')['qr']['path'].'/'.$qr->filename);

        $qr->delete();

        if($url = DB::url()->where('qrid', $id)->first()){
            $this->deleteLink($url->id);
        }
        
        return back()->with('success', 'QR has been successfully deleted.');
    }
}
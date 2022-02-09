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

class Tax {

    use \Traits\Payments;
    
    /**
     * Check License
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.2
     */
    public function __construct(){
        if(!\Helpers\App::isExtended()){
            return Helper::redirect()->to(route('admin.settings.config', ['payments']))->with('danger', 'Please enter your extended purchase code to unlock tax rates.');
        }
    }
    /**
     * Tax rates
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.2
     * @return void
     */
    public function index(){

        $rates = DB::taxrates()->orderByDesc('id')->paginate(15);

        CDN::load('datetimepicker');

        View::set('title', e('Tax Rates'));

        return View::with('admin.tax.index', compact('rates'))->extend('admin.layouts.main');
    }
    /**
     * Add Rate
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.2
     * @return void
     */
    public function new(){
        
        View::set('title', e('New Tax Rate'));

        return View::with('admin.tax.new')->extend('admin.layouts.main');
    }
    /**
     * Save coupon
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.2
     * @param \Core\Request $request
     * @return void
     */
    public function save(Request $request){
        
        \Gem::addMiddleware('DemoProtect');
        
        if(!$request->name || !$request->rate || !$request->countries) return Helper::redirect()->back()->with('danger', e('The name, the rate and countries are required.'));    
        
        $data = [];

        foreach($this->processor() as $name => $processor){
            if(!config($name) || !config($name)->enabled || !isset($processor['createtax']) || !$processor['createtax']) continue;
            $data[$name] = call_user_func($processor['createtax'], $request);
        }
        
        $rate = DB::taxrates()->create();
        $rate->name = Helper::clean($request->name, 3, true);
        $rate->rate = clean($request->rate);
        $request->countries = array_map('clean', $request->countries);
        $rate->countries = implode(',', $request->countries);
        $rate->status = clean($request->status);
        $rate->data = json_encode($data);
        $rate->save();

        \Core\Plugin::dispatch('admin.rate.add', ['id' => $rate->id]);

        return Helper::redirect()->to(route('admin.tax'))->with('success', e('Tax rate has been added successfully.'));
    }    
    /**
     * Edit Rate
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.2
     * @return void
     */
    public function edit($id){
        
        if(!$rate = DB::taxrates()->first($id)){
            return Helper::redirect()->to(route('admin.tax'))->with('danger', e('Tax rate does not exist.'));
        }

        $rate->countries = explode(',', $rate->countries);

        View::set('title', e('Edit Tax Rate'));

        return View::with('admin.tax.edit', compact('rate'))->extend('admin.layouts.main');
    }    
    /**
     * Update Tax rate
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.2
     * @param \Core\Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, int $id){
        
        \Gem::addMiddleware('DemoProtect');
        
        if(!$request->name) return Helper::redirect()->back()->with('danger', e('The name is required.'));
        
        if(!$rate = DB::taxrates()->where('id', $id)->first()){
            return Helper::redirect()->back()->with('danger', e('The tax rate does not exist.'));
        }

        $rate->name = Helper::clean($request->name, 3, true);
        $request->countries = array_map('clean', $request->countries);
        $rate->countries = implode(',', $request->countries);
        $rate->status = clean($request->status);
        $rate->save();

        return Helper::redirect()->back()->with('success', e('Tax rate has been updated successfully.'));
    }
    /**
     * Delete Tax rate
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.2
     * @param \Core\Request $request
     * @param integer $id
     * @param string $nonce
     * @return void
     */
    public function delete(Request $request, int $id, string $nonce){
        
        \Gem::addMiddleware('DemoProtect');

        if(!Helper::validateNonce($nonce, 'tax.delete')){
            return Helper::redirect()->back()->with('danger', e('An unexpected error occurred. Please try again.'));
        }

        if(!$rate = DB::taxrates()->where('id', $id)->first()){
            return Helper::redirect()->back()->with('danger', e('Tax rate not found. Please try again.'));
        }
        
        $rate->delete();
        return Helper::redirect()->back()->with('success', e('Tax rate has been deleted.'));
    }
}
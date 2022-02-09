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

use Core\Request;
use Core\View;
use Core\Helper;
use Core\DB;
use Helpers\Payments\Paypal;
use Helpers\Slack;

class Webhook {

    use Traits\Links, Traits\Payments;
	
	/**
	 * Handle Webhook
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.0
	 * @param [type] $provider
	 * @return void
	 */
	public function index(Request $request, $provider = null){
		
		if(!$provider) $provider = 'stripe';

		if($provider == 'paypal') $provider = 'paypalapi';

		\Core\Plugin::register('payment.success', function($data){
			
			if(!config('affiliate')->enabled) return;

			[$user, $planid, $payment] = $data;

			if($affiliate = DB::affiliates()->where('userid', $user->id)->whereNull('paid_on')->first()){
				if($payment = DB::payment()->where('id', $payment)->first()){
					$affiliate->amount = round((config('affiliate')->rate / 100) * $payment->amount, 2);
					$affiliate->paid_on = Helper::dtime();
					$affiliate->save();
				}
			}
		});

		if($provider && method_exists(__CLASS__, $provider)){
			return $this->{$provider}($request);
		}

		if($class = $this->processor($provider, 'webhook')){
			return call_user_func($class, $request);
		}

		die();
	}
	/**
	 * Slack Webhook
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.0
	 * @param \Core\Request $request
	 * @return void
	 */
    public function slack(Request $request){
		
		$start = microtime(true);

		if(Slack::validate(config("slacksigningsecret"))){

			$user_id = $request->user_id;
			$content = $request->text;
			$webhook = $request->response_url;

			preg_match_all('#\(([^)]+)\)[\s](.*)#i', $content, $matches);

			if(isset($matches[1][0]) && !empty($matches[1][0])){
				$custom     = $matches[1][0];
				$url 		= $matches[2][0];
			}else{
				$url    = $content;
				$custom = "";
			}

			if(!$user = \Models\User::where('slackid', $user_id)->first()){
				return print($url);
			}
			
            $data = new \stdClass;
			
			$data->custom = isset($custom) && !empty($custom) ? $custom : null;

			$data->url = clean($url);

			$data->pass =  null;

			$data->domain = null;
	
			$data->expiry = null;
	
			$data->type = null;
			$data->location = null;
			$data->device = null;
			$data->state = null;
			$data->paramname  = null;
			$data->paramvalue  = null;
			$data->metatitle = null;
			$data->metadescription = null;
			$data->metaimage = null;
			$data->description = null;
			$data->pixels = null;
	
            try	{

                $response = $this->createLink($data, $user);

            } catch (\Exception $e){

                $response = [];				

				\GemError::log('Slack Error: '.$e->getMessage());
            }	

			if((microtime(true) - $start) > 3){
                
                \Core\Http::url($webhook)->with('content-type', 'application/json')->body(["text" => isset($response['shorturl']) ? $response["shorturl"] : $url])->post();
                
				return true;
			}

			
			return print(isset($response["shorturl"]) ? $response["shorturl"] : $url);
		}
		return print("Error");
	}
	/**
	 * PayPal IPN
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.0
	 * @param \Core\Request $request
	 * @return void
	 */
	public function ipn(Request $request){
		return Paypal::webhook($request);
	}
}
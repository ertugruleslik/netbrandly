<?php
/**
 * ====================================================================================
 *                           GemFramework (c) GemPixel
 * ----------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework owned by GemPixel Inc as such
 *  distribution or modification of this framework is not allowed before prior consent
 *  from GemPixel administrators. If you find that this framework is packaged in a 
 *  software not distributed by GemPixel or authorized parties, you must not use this
 *  software and contact gempixel at https://gempixel.com/contact to inform them of this
 *  misuse otherwise you risk of being prosecuted in courts.
 * ====================================================================================
 *
 * @package AppConfig
 * @author GemPixel (http://gempixel.com)
 * @copyright 2020 GemPixel
 * @license http://gempixel.com/license
 * @link http://gempixel.com  
 * @since 1.0
 */

return [
  /**
   * Default Language
   * @var string
   */
  'language' => 'en',

  /**
   * Browser Based Language
   * @var boolean
   */
  'browserbasedlang' => false,

  /**
   * Allow users to shorten already-shortened link
   * @var boolean
   */
  'self_shortening' => false,

  /**
	 * Anti-Flood Time
	 * @var integer Minutes, Stats will not be updated when the same visitor clicks the same url for this amount of time
	 */	
	'antiflood' => 15,

  /**
   * Automatically redirect splash page when a timer is set. Set to true for this to happen
   * @var boolean
   */
  'redirectauto' => false,

  /**
   * List of Executables
   * @var array
   */
  'executables' => ["exe","dll","bin","dat","osx"],
  
  /**
   * Storage Paths Configuration
   * @var array
   */
  'storage' => [
      'public' => [
        'path' => PUB,
        'link' => config('url')
      ],
      'uploads'  => [
        'path' => PUB.'/content',
        'link' => config('url').'/content'
      ],
      'blog' => [
        'path' => PUB.'/content/blog',
        'link' => config('url').'/content/blog'
      ],
      'avatar' => [
        'path' => PUB.'/content/avatar',
        'link' => config('url').'/content/avatar'        
      ],
      'images' => [
        'path' => PUB.'/content/images',
        'link' => config('url').'/content/images'
      ],
      'qr' => [
        'path' => PUB.'/content/qr',
        'link' => config('url').'/content/qr'        
      ],
      'profile' => [
        'path' => PUB.'/content/profiles',
        'link' => config('url').'/content/profiles'        
      ],
    ],
    
    /**
     * Geo Driver: api | maxmind | custom
     * api: Path to api with with {IP} as placeholder
     * maxmind: Path to database
     * custom: Fully qualified name of a class
     */
    'geodriver' => 'maxmind',

    'geopath' => STORAGE.'/app/GeoLite2-City.mmdb',
    // 'geopath' => 'https://freegeoip.app/json/{IP}',  
    // 'geopath' => \Helpers\MyClass::class,

    /**
     * Mail Drivers
     * @var array
     */
    'maildrivers' => [
      'mailgun' => \Core\Support\Mailgun::class,
    ],
    /**
     * Path to cache folder
     * @var string
     */
    'cachepath' => STORAGE.'/cache',

    /**
     * Route to the API
     * @var string
     */
    'apiroute' => '/api/',

    /** 
     * Throttle API X per Y minutes
     * @var array
     * @example [3, 10] = 3 requests per 10 minutes
     */
    'throttle' => [30, 1],

    /**
     * Enable debugger
     * @var integer
     */
    'debug' => defined('DEBUG') ? DEBUG : 0,
    /**
     * Logs Path
     * @var string
     */
    'log' => LOGS.'/',

    /** 
     * Default Theme
     * @var string
     */
    'default_theme' => 'default'

  ];
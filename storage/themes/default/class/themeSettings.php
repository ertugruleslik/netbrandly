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

use Core\Plugin;
use Core\Helper;
use Core\DB;


class themeSettings {
    
    /**
     * Generate extra menu
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @return void
     */
    public static function menu(){
        
        $option = config("theme_config");

        if(!isset($option->homelinks)) return null;

        foreach(explode("\n", $option->homelinks) as $list){
            if(empty($list)) continue;
            [$title, $link] = array_map('trim', explode("|", $list));
            print('<li class="nav-item nav-item-spaced d-lg-block">
                    <a class="nav-link" href="'.$link.'">'.$title.'</a>
                </li>');            
        }
    }
    /**
     * Theme Settings
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @return void
     */
    public static function settings(){
        
        if(!$option = config("theme_config")){
            $option = new \stdClass;
        }

        if(!isset($option->hero)) $option->hero = "";
        if(!isset($option->homeheader)) $option->homeheader = "";
        if(!isset($option->homedescription)) $option->homedescription = "";
        if(!isset($option->homelinks)) $option->homelinks = "";
        if(!isset($option->homestyle)) $option->homestyle = "dark";
        if(!isset($option->homecolor)) {
            $option->homecolor = new \stdClass;
            $option->homecolor->type = 'default';
            $option->homecolor->c1 = '#DD7BFF';
            $option->homecolor->c2 = '#FF6C6C';
        }
                     
        \Helpers\CDN::load('simpleeditor');

        \Core\View::push("<script>
                            CKEDITOR.replace('homedescription');
                        </script>", "custom")->toFooter();  

        
        \Helpers\CDN::load("spectrum");
		
        \Core\View::push('<script type="text/javascript">																			    						    				    
                        $("#c1").spectrum({
                            color: "'.(isset($option->homecolor->c1) ? $option->homecolor->c1 : '#DD7BFF').'",
                            showInput: true,
                            preferredFormat: "hex"
                        });	
                        $("#c2").spectrum({
                            color: "'.(isset($option->homecolor->c1) ? $option->homecolor->c2 : '#FF6C6C').'",
                            showInput: true,
                            preferredFormat: "hex"
                        });
                    </script>', 'custom')->tofooter(); 

        $content = '<div class="row">
                        <div class="col-md-8">
                            <div class="card card-default">
                                <div class="card-body">
                                    <form action="'.route("admin.themes.update").'" method="post" enctype="multipart/form-data" id="setting-form">
                                        <div class="form-group">
                                            <label for="style" class="form-label mb-3">Theme Scheme</label><br>
                                            <div class="btn-group btn-group-toggle mb-4 border rounded" data-toggle="buttons">
                                                <label class="btn btn-dark text-light px-3 py-4">
                                                    <input type="radio" name="homestyle" value="darkmode" class="me-2" autocomplete="off" '.($option->homestyle == 'darkmode' ? 'checked' : '').'> Pure Dark
                                                </label>
                                                <label class="btn btn-primary text-light px-3 py-4">
                                                    <input type="radio" name="homestyle" value="dark" class="me-2" autocomplete="off" '.($option->homestyle == 'dark' ? 'checked' : '').'> Bluelit
                                                </label>
                                                <label class="btn btn-light text-dark px-3 py-4">
                                                    <input type="radio" name="homestyle" value="light" class="me-2" autocomplete="off" '.($option->homestyle == 'light' ? 'checked' : '').'> White Sky
                                                </label>
                                            </div>
                                            <p class="form-text">This option will change the color scheme for frontend.</p>
                                        </div>
                                        <div class="form-group">
                                            <label for="style" class="form-label mb-4 d-block">Main Header Background Color</label>

                                            <label>
                                                <input type="radio" name="homecolor[type]" value="default" class="me-2" autocomplete="off" '.(isset($option->homecolor->type) && $option->homecolor->type == 'default' ? 'checked' : '').'> Default
                                            </label>
                                            <label>
                                                <input type="radio" name="homecolor[type]" value="custom" class="ms-4 me-2" autocomplete="off" '.(isset($option->homecolor->type) && $option->homecolor->type ==  'custom' ? 'checked' : '').'> Custom                                        
                                            </label>
                                            <p class="form-text my-2">Use the default colors for the homepage background or custom colors defined below. If you want to set a single color, choose the same color for both otherwise you can create some cool gradients.</p>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="c1">'.e("Color 1").'</label><br>
                                                    <input type="text" name="homecolor[c1]" id="c1" value="'.(isset($option->homecolor->c1) && $option->homecolor->c1 ? $option->homecolor->c1 : '#DD7BFF').'">
                                                </div>
                                            </div>	
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="c2">'.e("Color 2").'</label><br>
                                                    <input type="text" name="homecolor[c2]" id="c2" value="'.(isset($option->homecolor->c2) && $option->homecolor->c2 ? $option->homecolor->c2 : '#FF6C6C').'">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group mt-3">
                                            '.(!empty($option->hero) ? '<a href="#" id="remove_logo" class="btn btn-info btn-xs pull-right">Remove Current Image</a>':"").'
                                            <label for="hero" class="form-label">Custom Home Page Image</label>
                                            <input type="file" class="form-control" name="hero" id="hero" value="'.$option->hero.'">
                                            <p class="form-text">This will replace the default hero image that comes shipped with the script. JPG or PNG. 500 kb max. Recommended size: 560x710</p>
                                        </div>
                                        <div class="form-group">
                                            <label for="homeheader" class="form-label">Home Main Header</label>
                                            <input type="text" class="form-control" name="homeheader" id="homeheader" value="'.$option->homeheader.'">
                                            <p class="form-text">This will replace the home main header right before the shortener form. If you leave it empty, the site title will be shown.</p>
                                        </div>	
                                        <div class="form-group">
                                            <label for="homedescription" class="form-label">Home Main Description</label>
                                            <textarea class="form-control" name="homedescription" id="homedescription">'.$option->homedescription.'</textarea>
                                            <p class="form-text">This will replace the home main description right before the shortener form. If you leave it empty, the site description will be shown.</p>
                                        </div>
                                        <div class="form-group">
                                            <label for="homelinks" class="form-label">Menu Links</label>
                                            <textarea class="form-control" name="homelinks" id="homelinks" rows="5" placeholder="e.g. Google|https://google.com">'.$option->homelinks.'</textarea>
                                            <p class="form-text">You can add custom links to the menu using the following format (one per line): TITLE|LINK</p>
                                        </div>
                                        '.csrf().'
                                        <button class="btn btn-primary">Save Settings</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-default">
                                <div class="card-header">Help</div>
                                <div class="card-body">	
                                    <p><strong>HTML Usage</strong></p>
                                    <p>You can use the following HTML elements: '.htmlentities("<b> <i> <s> <u> <strong> <span> <p> <br>").'</p>

                                    <p><strong>Translating Strings</strong></p>
                                    <p>If you add a new title or a new description, you can still translate them to any language by simply adding it via the language manager.</p>
                                </div>
                            </div>
                            <div class="card card-default">
                                <div class="card-header">Custom Home Page Color</div>
                                <div class="card-body">	
                                    <p>You can now create your own background color for the homepage and some other pages. You can either choose a single color and a cool gradient using the color selector. You can play with the Scheme and Color to create your background. For example if your background is too bright, choose the "White Sky" scheme to change the text to dark otherwise choose the "Bluelit".</p>

                                    <p><strong>Note</strong> The custom background does not apply to the Pure Dark scheme or Dark Mode.</p>

                                    <p><strong>Need gradient ideas?</strong> <a href="https://uigradients.com" rel="nofollow" target="blank">Check here</a></p>
                                </div>
                            </div>
                            <div class="card card-default">
                                <div class="card-header">Menu Link</div>
                                <div class="card-body">	
                                    <p>You can add custom links to the menu using the following format (one per line): TITLE|LINK</p>

                                    <p><strong>Example</strong></p>
                                    <pre>Support|https://support.gempixel.com<br>Blog|https://gempixel.com/blog</pre>

                                    <p>You can add as much as you want however you need to make sure it does not break the template</p>
                                </div>
                            </div>		     
                        </div>
                    </div>';
        return $content;        
    }
    /**
     * Update 
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @return void
     */
    public function update(){

        \Gem::addMiddleware('DemoProtect');

        $option = config("theme_config");

        $request = new \Core\Request;

        $data = [];

        $data['homeheader'] = Helper::clean($request->homeheader);
        $data['homedescription'] = $request->homedescription;
        $data['homestyle'] = $request->homestyle;
        $data['homelinks'] = $request->homelinks;

        $data['homecolor'] = $request->homecolor;

        if($image = $request->file('hero')){
            
            if(!$image->mimematch || !in_array($image->ext, ['jpg', 'png'])) return Helper::redirect()->back()->with('danger', e('The custom image is not valid. Only a JPG or PNG are accepted.'));

            if($image->sizekb > 500) return Helper::redirect()->back()->with('danger', e('Custom image must be either a PNG or a JPEG (Max 500kb).'));

            $filename = Helper::rand(6)."_hero_".$image->name;

            if(isset($option->hero) && !empty($option->hero) && file_exists(ROOT."/content/".$option->hero)){
				unlink(appConfig('app.storage')['uploads']['path'].$option->hero);
			}

            $request->move($image, appConfig('app.storage')['uploads']['path'], $filename);
            $data['hero'] = $filename;

        }

        if($request->homestyle == "darkmode"){
            $request->cookie('darkmode', 1);
        } else {
            $request->cookie('darkmode', 1, -3600);
        }
        
        $setting = DB::settings()->where('config', 'theme_config')->first();

        $setting->var = json_encode($data);
        $setting->save();
        return Helper::redirect()->back()->with('success', e('Settings are successfully saved.'));
    }
    /**
     * Theme Config
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.1.8
     * @param string $name
     * @return void
     */
    public static function config(string $name){

        $config = config("theme_config");

        if($name == 'title'){
            return (isset($config->homeheader) && $config->homeheader ? e($config->homeheader) :  config('title'));
        }

        if($name == 'description'){
            return (isset($config->homedescription) && $config->homedescription ? e($config->homedescription) : e(config('description')));
        }

        if($name == 'homecolor'){
            if(isset($config->homecolor) && $config->homecolor->type == 'custom' && $config->homestyle != 'darkmode' && !request()->cookie('darkmode')){
                return 'style="background: linear-gradient(220.55deg, '.$config->homecolor->c1.' 0%, '.$config->homecolor->c2.' 100%) !important;"';
            }
        }
    }
}
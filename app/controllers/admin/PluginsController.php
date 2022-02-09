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
use Core\Response;
use Core\Helper;
use Models\User;

class Plugins {	
    /**
     * Plugins Home
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @return void
     */
    public function index(Request $request){

        if($request->activated){            
            \Core\Plugin::dispatch('admin.plugin.activate', $request->activated);
        }

        $plugins = [];

        foreach (new \RecursiveDirectoryIterator(STORAGE."/plugins/") as $path){
            
            if($path->isDir() && $path->getFilename() !== "." && $path->getFilename() !== ".." && file_exists(STORAGE."/plugins/".$path->getFilename()."/config.json")){          

                $data = json_decode(file_get_contents(STORAGE."/plugins/".$path->getFilename()."/config.json"));

                $plugin = new \stdClass;
                
                $plugin->id = $path->getFilename();
                $plugin->name = isset($data->name) ? Helper::clean($data->name, 3) : "No Name";
                $plugin->author = isset($data->author) ? Helper::clean($data->author, 3) : "Unknown";
                $plugin->link = isset($data->link) ? Helper::clean($data->link, 3) : "#none";
                $plugin->version = isset($data->version) ? Helper::clean($data->version, 3) : "1.0";
                $plugin->description = isset($data->description) ? Helper::clean($data->description, 3) : "";

                $plugin->enabled = isset(config('plugins')->{$plugin->id}) ? true : false;

                $plugins[] = $plugin;
            }
        }  

        View::set('title', e('Plugins'));

        return View::with('admin.plugins', compact('plugins'))->extend('admin.layouts.main');
    }
    /**
     * Activate
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param [type] $id
     * @return void
     */
    public function activate($id){

        \Gem::addMiddleware('DemoProtect');

        if(!file_exists(STORAGE."/plugins/".$id."/config.json")){
            return back()->with('danger', e('Plugin does not exist.'));
        }

        $plugins = config('plugins');

        if(isset($plugins->{$id})) return back()->with('danger', e('Plugin is already active.')); 

        $plugins->$id = ['settings' => []];
        
        $settings = DB::settings()->where('config', 'plugins')->first();
        $settings->var = json_encode($plugins);
        $settings->save();

        return Helper::redirect()->to(route('admin.plugins', ['activated' => $id]))->with('success', e('Plugin was successfully activated.'));
    }

     /**
     * Disable
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param [type] $id
     * @return void
     */
    public function disable($id){

        \Gem::addMiddleware('DemoProtect');
        
        $plugins = config('plugins');

        if(!isset($plugins->{$id})) return back()->with('danger', e('Plugin is already disabled.')); 

        unset($plugins->{$id});            

        $settings = DB::settings()->where('config', 'plugins')->first();
        $settings->var = json_encode($plugins);
        $settings->save();

        \Core\Plugin::dispatch('admin.plugin.disable', $id);

        return back()->with('success', e('Plugin was successfully disabled.'));
    }

    /**
     * Upload Plugin
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param \Core\Request $request
     * @return void
     */
    public function upload(Request $request){
        
        \Gem::addMiddleware('DemoProtect');

        if($file = $request->file('file')){       

            if(!$file->mimematch || !in_array($file->ext, ['zip'])) return Helper::redirect()->to(route('admin.plugins'))->with('danger', e('The file is not valid. Only .zip files are accepted.'));    

            $name = str_replace('.'.$file->ext, '', $file->name);

            $exists = file_exists(PLUGIN.'/'.$name);

            $request->move($file, PLUGIN);

            $zip = new \ZipArchive();

            $f = $zip->open(PLUGIN.'/'.$file->name);
        
            if($f === true) {

                if(!$exists) mkdir(PLUGIN.'/'.$name);
              
                if(!$zip->extractTo(PLUGIN."/".$name."/")){
                    return Helper::redirect()->to(route('admin.plugins'))->with('danger', e('The file was downloaded but cannot be extracted due to permission.'));
                }
        
                $zip->close();

                if(!file_exists(PLUGIN.'/'.$name.'/config.json')){
                    \Helpers\App::deleteFolder(PLUGIN.'/'.$name);
                    unlink(PLUGIN.'/'.$file->name);
                    return Helper::redirect()->to(route('admin.plugins'))->with('danger', e('Invalid plugin. Please make sure the plugin is up to date and includes a config.json file.'));
                }
              
            } else {
                return Helper::redirect()->to(route('admin.plugins'))->with('danger', e('The file cannot be extracted. You can extract it manually.'));
            }

            if(file_exists(PLUGIN.'/'.$file->name)){
                unlink(PLUGIN.'/'.$file->name);
            }

            return Helper::redirect()->to(route('admin.plugins'))->with('success', $exists ? e('Plugin has been updated successfully.') : e('Plugin has been uploaded successfully.')); 
        }

        return Helper::redirect()->to(route('admin.plugins'))->with('danger', e('An unexpected error occurred. Please try again.'));
    }
    /**
     * Plugin Directory
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.2
     * @param \Core\Request $request
     * @return void
     */
    public function directory(Request $request){

        if(!config('purchasecode')){
            return Helper::redirect()->to(route('admin.update'))->with('danger', e('Please update your purchase code in the sidebar.'));
        }

        if($request->install){
            return $this->install($request);            
        }

        if($request->q){
            $http = \Core\Http::url('https://cdn.gempixel.com/plugins/')
                                ->with('X-Authorization', 'TOKEN '.config('purchasecode'))
                                ->body(clean($request->q))
                                ->post();
        } else {
            $http = \Core\Http::url('https://cdn.gempixel.com/plugins/')
                                ->with('X-Authorization', 'TOKEN '.config('purchasecode'))
                                ->post();
        }

        $plugins = [];    
                                        
        if($http->getBody() == 'Failed'){
            return Helper::redirect()->to(route('admin.update'))->with('danger', e('Please update your purchase code in the sidebar.'));
        } 

        $plugins = [];
        $allplugins = config('plugins');

        foreach($http->bodyObject() as $plugin){
            $plugin->installed = file_exists(PLUGIN.'/'.$plugin->tag.'/');

            if($plugin->installed){
                $config = json_decode(file_get_contents(PLUGIN.'/'.$plugin->tag.'/config.json'));
                $plugin->installedversion = $config->version;
            }

            $plugins[] = $plugin;
        }

        View::set('title', e('Plugin Directory'));

        return View::with('admin.plugins_dir', compact('plugins'))->extend('admin.layouts.main');
    }
    /**
     * Install Plugin
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.2
     * @param [type] $request
     * @return void
     */
    public function install($request){

        \Gem::addMiddleware('DemoProtect');

        $name = $request->install;
 
        $exists = file_exists(PLUGIN.'/'.$name);

        if(!copy("https://cdn.gempixel.com/plugins/{$name}.zip", PLUGIN."/{$name}.zip")){
            return Helper::redirect()->to(route('admin.plugins'))->with('danger', e('An error ocurred. Plugin was not downloaded.')); 
        }

        $zip = new \ZipArchive();

        $f = $zip->open(PLUGIN.'/'.$name.'.zip');
    
        if($f === true) {

            if(!$exists) mkdir(PLUGIN.'/'.$name);
            
            if(!$zip->extractTo(PLUGIN."/".$name."/")){
                return Helper::redirect()->to(route('admin.plugins'))->with('danger', e('The file was downloaded but cannot be extracted due to permission.'));
            }
    
            $zip->close();

            if(!file_exists(PLUGIN.'/'.$name.'/config.json')){
                \Helpers\App::deleteFolder(PLUGIN.'/'.$name);
                unlink(PLUGIN.'/'.$name.'.zip');
                return Helper::redirect()->to(route('admin.plugins'))->with('danger', e('Invalid plugin. Please make sure the plugin is up to date and includes a config.json file.'));
            }
            
        } else {
            return Helper::redirect()->to(route('admin.plugins'))->with('danger', e('The file cannot be extracted. You can extract it manually.'));
        }

        if(file_exists(PLUGIN.'/'.$name.'.zip')){
            unlink(PLUGIN.'/'.$name.'.zip');
        }

        return Helper::redirect()->to(route('admin.plugins'))->with('success', $exists ? e('Plugin has been installed & updated successfully.') : e('Plugin has been installed successfully.')); 

    }
}
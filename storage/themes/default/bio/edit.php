<div class="d-flex">
    <div>
        <h1 class="h4 mb-5"><?php ee('Update Bio') ?>  <?php echo $bio->name ?>  <?php echo (user()->defaultbio == $bio->id ? '<span class="badge bg-primary ms-2">'.e('Default').'</span>' : '') ?></h1>
    </div>
    <div class="ms-auto">
        <a href="<?php echo \Helpers\App::shortRoute($url->domain, $bio->alias) ?>" class="btn btn-primary" target="_blank"><i data-feather="eye"></i> <?php ee('View Bio') ?></a>
    </div>    
</div>
<form action="<?php echo route('bio.update', [$bio->id]) ?>" method="post">
    <?php echo csrf() ?>
    <div class="row">
        <div class="col-md-8" id="generator">
            <div class="card card-body">
                <div class="form-group">
                    <label class="form-label"><?php ee('Bio Page Name') ?></label>
                    <input type="text" class="form-control p-2" name="name" placeholder="e.g. For Instagram" value="<?php echo $bio->name ?>" data-required>
                </div>
                <div class="form-group mt-4">
                    <label class="form-label"><?php ee('Bio Page Alias') ?></label>
                    <div class="d-flex">                        
                        <div>
                            <input type="text" class="form-control p-2" name="custom" value="<?php echo $bio->alias ?>" placeholder="e.g. my-page">
                            <p class="form-text"><?php ee('Leave this field empty to generate a random alias.') ?></p>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="card card-body">
                <div class="form-group mb-4 d-flex align-items-center">
                    <div class="me-3">
                        <?php if(isset($bio->data->avatar)): ?>
                            <img src="<?php echo uploads($bio->data->avatar, 'profile') ?>" width="100" class="rounded" id="useravatar">
                        <?php else: ?>
                            <img src="<?php echo user()->avatar()?>" width="100" class="rounded" id="useravatar">
                        <?php endif ?>
                    </div>
                    <div>
                        <label for="avatar" class="form-label"><?php ee('Custom Avatar') ?></label>				    	
                        <input type="file" name="avatar" id="avatar" class="form-control mb-2">
                        <p class="form-text"><?php ee('We recommend you choose a square picture with a maximum size of 300x300 and 500kb.') ?></p>
                    </div>
                </div>
            </div>
            <div class="card card-body">
                <ul class="nav nav-pills">
                    <li class="nav-item mb-xs-3">
                        <a href="#links" class="nav-link active" data-bs-toggle="collapse" data-bs-parent="#generator"><?php ee('Content') ?></a>
                    </li>
                    <li class="nav-item mb-xs-3">
                        <a href="#social" class="nav-link" data-bs-toggle="collapse" data-bs-parent="#generator"><?php ee('Social Links') ?></a>
                    </li>
                    <li class="nav-item mb-xs-3">
                        <a href="#appearance" class="nav-link" data-bs-toggle="collapse" data-bs-parent="#generator"><?php ee('Appearance') ?></a>
                    </li>
                    <li class="nav-item mb-xs-3 ms-1">
                        <a href="#advanced" class="nav-link" data-bs-toggle="collapse" data-bs-parent="#generator"><?php ee('Advanced') ?></a>
                    </li>                      
                </ul>
            </div>
            <div class="collapse show" id="links">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title fw-bold"><i class="me-2" data-feather="layout"></i> <?php ee('Content') ?></h5>
                    </div>
                    <div class="card-body">
                        <div id="linkcontent">
                            
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#contentModal"><?php ee('Add Link or Content') ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card collapse" id="social">
                <div class="card-header">
                    <h5 class="card-title fw-bold"><i class="me-2" data-feather="link"></i> <?php ee('Social Links') ?></h5>
                </div>                
                <div class="card-body">
                    <div class="form-group mt-3">
                        <label class="form-label"><?php ee('Facebook') ?></label>
                        <input type="text" class="form-control p-2" name="social[facebook]" value="<?php echo $bio->data->social->facebook ?>" placeholder="https://" data-error="<?php ee('Please enter a valid link') ?>">                        
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label"><?php ee('Twitter') ?></label>
                        <input type="text" class="form-control p-2" name="social[twitter]" value="<?php echo $bio->data->social->twitter ?>" placeholder="https://" data-error="<?php ee('Please enter a valid link') ?>">                        
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label"><?php ee('Instagram') ?></label>
                        <input type="text" class="form-control p-2" name="social[instagram]" value="<?php echo $bio->data->social->instagram ?>" placeholder="https://" data-error="<?php ee('Please enter a valid link') ?>">                        
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label"><?php ee('Tiktok') ?></label>
                        <input type="text" class="form-control p-2" name="social[tiktok]" value="<?php echo $bio->data->social->tiktok ?>" placeholder="https://" data-error="<?php ee('Please enter a valid link') ?>">                        
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label"><?php ee('Linkedin') ?></label>
                        <input type="text" class="form-control p-2" name="social[linkedin]" value="<?php echo $bio->data->social->linkedin ?>" placeholder="https://" data-error="<?php ee('Please enter a valid link') ?>">                        
                    </div> 
                    <div class="form-group mt-3">
                        <label class="form-label"><?php ee('Youtube') ?></label>
                        <input type="text" class="form-control p-2" name="social[youtube]" placeholder="https://" value="<?php echo $bio->data->social->youtube ?? '' ?>" data-error="<?php ee('Please enter a valid link') ?>">                        
                    </div>  
                    <div class="form-group mt-3">
                        <label class="form-label"><?php ee('Telegram') ?></label>
                        <input type="text" class="form-control p-2" name="social[telegram]" placeholder="https://" value="<?php echo $bio->data->social->telegram ?? '' ?>" data-error="<?php ee('Please enter a valid link') ?>">                        
                        </div>                  
                </div>
            </div>
            <div class="card collapse" id="appearance">
				<div class="card-header mt-2">
					<h5 class="card-title fw-bold"><i data-feather="plus-circle" class="me-2"></i> <?php ee('Appearance') ?></h5>
				</div>				
				<div class="card-body">
                    <h5><?php ee('Templates') ?></h5>
                    <div class="row mt-3">
                        <div class="col-2">
                            <a href="#" class="d-block text-center border rounded p-3 d-flex align-self-stretch" style="height:50px;background: linear-gradient(90deg, #1CB5E0 0%, #000851 100%);" onclick="changeTheme('#1CB5E0', '#1CB5E0', '#000851', '#000851', '#ffffff', '#ffffff');">                               
                            </a>
                        </div>
                        <div class="col-2">
                            <a href="#" class="d-block text-center border rounded p-3 d-flex align-self-stretch" style="height:50px;background: linear-gradient(90deg, #FC466B 0%, #3F5EFB 100%);" onclick="changeTheme('#FC466B', '#3F5EFB', '#FC466B', '#ffffff', '#FC466B', '#ffffff');">                               
                            </a>
                        </div>
                        <div class="col-2">
                            <a href="#" class="d-block text-center border rounded p-3 d-flex align-self-stretch" style="height:50px;background: linear-gradient(90deg, #FDBB2D 0%, #22C1C3 100%);" onclick="changeTheme('#FDBB2D', '#22C1C3', '#FDBB2D', '#ffffff', '#FDBB2D', '#ffffff');">                               
                            </a>
                        </div>
                        <div class="col-2">
                            <a href="#" class="d-block text-center border rounded p-3 d-flex align-self-stretch" style="height:50px;background: linear-gradient(90deg, #00c6ff 0%, #0072ff 100%);" onclick="changeTheme('#00c6ff', '#0072ff', '#00c6ff', '#ffffff', '#00c6ff', '#ffffff');">                             
                            </a>
                        </div>                                       
                        <div class="col-2">
                            <a href="#" class="d-block text-center border rounded p-3 d-flex align-self-stretch" style="height:50px;background: linear-gradient(90deg, #d53369 0%, #daae51 100%);" onclick="changeTheme('#d53369', '#daae51', '#d53369', '#ffffff', '#d53369', '#ffffff');">                               
                            </a>
                        </div>                    
                        <div class="col-2">
                            <a href="#" class="d-block text-center border rounded p-3 d-flex align-self-stretch" style="height:50px;background: linear-gradient(90deg, #ED4264 0%, #FFEDBC 100%);" onclick="changeTheme('#ED4264', '#FFEDBC', '#ED4264', '#ffffff', '#ED4264', '#ffffff');">                               
                            </a>
                        </div>
                    </div>
                    <div class="row mt-3"> 
                        <div class="col-2">
                            <a href="#" class="d-block text-center border rounded p-3 d-flex align-self-stretch" style="height:50px;background: linear-gradient(90deg, #232526 0%, #414345 100%);" onclick="changeTheme('#232526', '#414345', '#232526', '#ffffff', '#232526', '#ffffff');">                               
                            </a>
                        </div>
                        <div class="col-2">
                            <a href="#" class="d-block text-center border rounded p-3 d-flex align-self-stretch" style="height:50px;background: linear-gradient(45deg, #fff 50%, #76b852 51%);" onclick="changeTheme('#ffffff', '#ffffff', '#ffffff', '#76b852', '#ffffff', '#000000');">                               
                            </a>
                        </div>
                        <div class="col-2">
                            <a href="#" class="d-block text-center border rounded p-3 d-flex align-self-stretch" style="height:50px;background: linear-gradient(45deg, #fff 50%, #f71e3e 51%);" onclick="changeTheme('#ffffff', '#ffffff', '#ffffff', '#f71e3e', '#ffffff', '#000000');">                               
                            </a>
                        </div>
                        <div class="col-2">
                            <a href="#" class="d-block text-center border rounded p-3 d-flex align-self-stretch" style="height:50px;background: linear-gradient(45deg, #fff 50%, #000000 51%);" onclick="changeTheme('#ffffff', '#ffffff', '#ffffff', '#000000', '#ffffff', '#000000');">                               
                            </a>
                        </div>
                        <div class="col-2">
                            <a href="#" class="d-block text-center border rounded p-3 d-flex align-self-stretch" style="height:50px;background: linear-gradient(45deg, #fff 50%, #1CB5E0 51%);" onclick="changeTheme('#ffffff', '#ffffff', '#ffffff', '#1CB5E0', '#ffffff', '#000000');">                               
                            </a>
                        </div>
                        <div class="col-2">
                            <a href="#" class="d-block text-center border rounded p-3 d-flex align-self-stretch" style="height:50px;background: linear-gradient(45deg, #fff 50%, #c7ae0a 51%);" onclick="changeTheme('#ffffff', '#ffffff', '#ffffff', '#c7ae0a', '#ffffff', '#000000');">                               
                            </a>
                        </div>
                    </div>
                    <hr>
                    <h5><?php ee('Background') ?></h5>
                    <div class="mb-3 mt-3">
                        <div class="btn-group">
                            <a href="#singlecolor" class="btn btn-primary btn-sm active text-white" data-bs-toggle="collapse" data-bs-parent="#appearance"><?php ee('Single Color') ?></a>
                            <a href="#gradient" class="btn btn-primary btn-sm text-white" data-bs-toggle="collapse" data-bs-parent="#appearance"><?php ee('Gradient Color') ?></a>
                            <a href="#image" class="btn btn-primary btn-sm text-white" data-bs-toggle="collapse" data-trigger="color" data-bs-parent="#appearance"><?php ee('Image') ?></a>
                        </div>                      
                    </div>
                    <div id="singlecolor" class="collapse show">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="bg"><?php ee("Background") ?></label><br>
                                    <input type="text" name="bg" id="bg" value="<?php echo $bio->data->style->bg ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="gradient" class="collapse">                        
                        <div class="row">                            
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="fg"><?php ee("Gradient Start") ?></label><br>
                                    <input type="text" name="gradient[start]" id="bgst" value="<?php echo $bio->data->style->gradient->start ?? '' ?>">
                                </div>	
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="fgs"><?php ee("Gradient Stop") ?></label><br>
                                    <input type="text" name="gradient[stop]" id="bgsp" value="<?php echo $bio->data->style->gradient->stop ?? '' ?>">
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <div id="image" class="collapse">
                        <input type="file" class="form-control mb-4" name="bgimage" id="bgimage">
                    </div>
                    <h5><?php ee('Text Color') ?></h5>
                    <div class="form-group mb-4">
                        <input type="text" name="textcolor" id="textcolor" value="<?php echo $bio->data->style->textcolor ?? '' ?>">
                    </div>                       
                    <h5><?php ee('Button Color') ?></h5>
                    <div class="form-group mb-4">
                        <input type="text" name="buttoncolor" id="buttoncolor" value="<?php echo $bio->data->style->buttoncolor ?? '' ?>">
                    </div>
                    <h5><?php ee('Button text Color') ?></h5>
                    <div class="form-group mb-4">
                        <input type="text" name="buttontextcolor" id="buttontextcolor" value="<?php echo $bio->data->style->buttontextcolor ?? '' ?>">
                    </div>
				</div>
			</div>
            <div class="card collapse" id="advanced">
                <div class="card-header">
                    <h5 class="card-title fw-bold"><i class="me-2" data-feather="settings"></i> <?php ee('Advanced') ?></h5>
                </div>               
                <div class="card-body">
                    <div class="form-group">
                        <label for="pass" class="form-label"><?php ee('Password Protection') ?></label>
                        <p class="form-text"><?php ee('By adding a password, you can restrict the access.') ?></p>
                        <div class="input-group">
                            <div class="input-group-text bg-white"><i data-feather="lock"></i></div>
                            <input type="text" class="form-control border-start-0 ps-0" name="pass" id="pass" value="<?php echo $url->pass ?>" placeholder="<?php echo e("Type your password here")?>" autocomplete="off">
                        </div>
                    </div>
                    <?php if(\Core\Auth::user()->has("pixels") !== false):?>
                    <div id="pixels" class="mt-4">
                        <label class="form-label"><?php echo e("Targeting Pixels")?></label>
                        <p class="form-text"><?php echo e('Add your targeting pixels below from the list. Please make sure to enable them in the pixels settings.')?></p>
                        <div class="input-group input-select">
                            <span class="input-group-text bg-white"><i data-feather="filter"></i></span>
                            <select name="pixels[]" data-placeholder="Your Pixels" multiple data-toggle="select">
                                <?php foreach(\Core\Auth::user()->pixels() as $type => $pixels): ?>     
                                    <optgroup label="<?php echo ucwords($type) ?>">                       
                                    <?php foreach($pixels as $pixel): ?>
                                        <option value="<?php echo $pixel->type ?>-<?php echo $pixel->id ?>" <?php echo in_array($pixel->type.'-'.$pixel->id, explode(',', $url->pixels)) ? 'selected': '' ?>><?php echo $pixel->name ?></option>                                
                                    <?php endforeach ?>
                                    </optgroup>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <?php endif ?>
                    <hr>
                    <div class="form-group">
                    <label for="customcss" class="form-label"><?php ee('Custom CSS') ?></label>
                        <textarea class="form-control" name="customcss" id="customcss" rows="5" placeholder="e.g. .btn { display: block }"><?php echo $bio->data->style->custom ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div id="preview">
                <div class="card">                    
                    <div class="text-center" style="max-height:600px;overflow-y:scroll">
                    <?php if(isset($bio->data->avatar)): ?>
                            <img src="<?php echo uploads($bio->data->avatar, 'profile') ?>" class="rounded-circle my-3 border" id="useravatar" height="120" width="120">
                        <?php else: ?>
                            <img src="<?php echo user()->avatar() ?>" class="rounded-circle my-3 border" id="useravatar" height="120" width="120">
                        <?php endif ?>
                        <h3><span><?php echo $bio->name ?></span></h3></em>
                        <div class="card-body">
                            <div id="social" class="text-center mt-2">
                            </div>
                            <div id="content" class="mt-5">
                            </div>                        
                        </div>
                    </div>
                </div>
            </div>
            <div class="card my-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <img src="<?php echo \Helpers\QR::factory(\Helpers\App::shortRoute($url->domain, $url->alias.$url->custom))->format('png')->create('uri') ?>" class="img-fluid rounded">
                            <div class="btn-group mt-2" role="group" aria-label="downloadQR">
                                <a href="?downloadqr=png" class="btn btn-primary btn-sm" id="downloadPNG"><?php ee('Download') ?></a>
                                <div class="btn-group" role="group">
                                    <button id="btndownloadqr" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">PNG</button>
                                    <ul class="dropdown-menu" aria-labelledby="btndownloadqr">
                                        <li><a class="dropdown-item" href="?downloadqr=pdf">PDF</a></li>
                                        <li><a class="dropdown-item" href="?downloadqr=svg">SVG</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 ps-3">
                            <div class="form-group">
                                <label for="short" class="form-label"><?php ee('Short Link') ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="modal-input" name="shortlink" value="<?php echo \Helpers\App::shortRoute($url->domain, $bio->alias) ?>">
                                    <div class="input-group-text bg-white">
                                        <button type="button" class="btn btn-primary copy" data-clipboard-text="<?php echo \Helpers\App::shortRoute($url->domain, $bio->alias) ?>"><?php ee('Copy') ?></button>
                                    </div>
                                </div>
                            </div>    
                            <div class="mt-3" id="modal-share">
                                <?php echo \Helpers\App::share(\Helpers\App::shortRoute($url->domain, $bio->alias)) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </div>        
    </div>
    <button type="submit" class="btn btn-primary"><?php ee('Update') ?></button>
</form>
<div class="modal fade" id="contentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php ee('Add Link or Content') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="modalcontent">
            <div class="collapse show" id="options">
                <div class="row">
                    <div class="col-md-3">
                        <a href="#modal-heading" class="d-block text-center border rounded p-3 h-100" data-bs-toggle="collapse" data-bs-parent="#modalcontent">
                            <h3><i class="mb-3 fa fa-heading"></i></h3>
                            <h5><?php ee('Heading') ?></h5>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#modal-text" class="d-block text-center border rounded p-3 h-100" data-bs-toggle="collapse" data-bs-parent="#modalcontent">
                            <h3><i data-feather="align-center" class="mb-3"></i></h3>
                            <h5><?php ee('Text') ?></h5>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#modal-divider" class="d-block text-center border rounded p-3 h-100" data-bs-toggle="collapse" data-bs-parent="#modalcontent">
                            <h3><i class="mb-3 fa fa-grip-lines"></i></h3>
                            <h5><?php ee('Divider') ?></h5>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#modal-links" class="d-block text-center border rounded p-3 h-100" data-bs-toggle="collapse" data-bs-parent="#modalcontent">
                            <h3><i data-feather="link" class="mb-3"></i></h3>
                            <h5><?php ee('Links') ?></h5>
                        </a>
                    </div>
                </div>
                <div class="row mt-3">   
                    <div class="col-md-3">
                        <a href="#modal-image" class="d-block text-center border rounded p-3 h-100" data-bs-toggle="collapse" data-bs-parent="#modalcontent">
                            <h3><i data-feather="image" class="mb-3"></i></h3>
                            <h5><?php ee('Image') ?></h5>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#modal-rss" class="d-block text-center border rounded p-3 h-100" data-bs-toggle="collapse" data-bs-parent="#modalcontent">
                            <h3><i data-feather="rss" class="mb-3"></i></h3>
                            <h5><?php ee('RSS Feed') ?></h5>
                        </a>
                    </div>                    
                    <div class="col-md-3">
                        <a href="#modal-youtube" class="d-block text-center border rounded py-3 px-2 h-100" data-bs-toggle="collapse" data-bs-parent="#modalcontent">
                            <img src="<?php echo assets('images/youtube.svg') ?>" width="30" class="mb-3">
                            <h5><?php ee('Youtube Video') ?></h5>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#modal-whatsapp" class="d-block text-center border rounded py-3 px-2 h-100" data-bs-toggle="collapse" data-bs-parent="#modalcontent">
                            <img src="<?php echo assets('images/whatsapp.svg') ?>" width="30" class="mb-3">
                            <h5><?php ee('WhatsApp Call') ?></h5>
                        </a>                        
                    </div>                    
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <a href="#modal-spotify" class="d-block text-center border rounded py-3 px-2 h-100" data-bs-toggle="collapse" data-bs-parent="#modalcontent">
                            <img src="<?php echo assets('images/spotify.svg') ?>" width="30" class="mb-3">
                            <h5><?php ee('Spotify Embed') ?></h5>
                        </a>
                    </div>                    
                    <div class="col-md-3">
                        <a href="#modal-itunes" class="d-block text-center border rounded py-3 px-2 h-100" data-bs-toggle="collapse" data-bs-parent="#modalcontent">
                            <img src="<?php echo assets('images/itunes.svg') ?>" width="30" class="mb-3">
                            <h5><?php ee('Apple Music Embed') ?></h5>
                        </a>
                    </div>                                      
                    <div class="col-md-3">
                        <a href="#modal-paypal" class="d-block text-center border rounded py-3 px-2 h-100" data-bs-toggle="collapse" data-bs-parent="#modalcontent">
                            <img src="<?php echo assets('images/paypal.svg') ?>" width="30" class="mb-3">
                            <h5><?php ee('Paypal Button') ?></h5>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#modal-tiktok" class="d-block text-center border rounded py-3 px-2 h-100" data-bs-toggle="collapse" data-bs-parent="#modalcontent">
                            <img src="<?php echo assets('images/tiktok.svg') ?>" width="30" class="mb-3">
                            <h5><?php ee('Tiktok Embed') ?></h5>
                        </a>
                    </div>
                </div>                
            </div>
            <div id="modal-text" class="collapse" data-name="<?php echo e('Text') ?>">
                <a href="#options" data-bs-toggle="collapse" data-bs-parent="#modalcontent" class="mb-4 d-block"><i data-feather="chevron-left"></i> <?php ee('Back') ?></a>
                <div class="form-group">
                    <label class="form-label"><?php ee('Text') ?></label>
                    <textarea class="form-control p-2" name="content" placeholder="e.g. some description here"></textarea>
                </div>
                <button type="button" data-trigger="insertcontent" data-type="text" class="btn btn-primary mt-3"><?php ee('Add Text') ?></button>
            </div>
            <div id="modal-links" class="collapse" data-name="<?php echo e('Links') ?>">
                <a href="#options" data-bs-toggle="collapse" data-bs-parent="#modalcontent" class="mb-4 d-block"><i data-feather="chevron-left"></i> <?php ee('Back') ?></a>
                <div class="form-group">
                    <label class="form-label"><?php ee('Text') ?></label>
                    <input type="text" class="form-control p-2" name="text" placeholder="e.g. My Site">                        
                </div>
                <div class="form-group mt-3">
                    <label class="form-label"><?php ee('Link') ?></label>
                    <input type="text" class="form-control p-2" name="link" placeholder="https://" data-error="<?php ee('Please enter a valid link') ?>">                        
                </div>
                <button type="button" data-trigger="insertcontent" data-type="link" class="btn btn-primary mt-3"><?php ee('Add Link') ?></button>
            </div>
            <div id="modal-image" class="collapse" data-name="<?php echo e('Image') ?>">
                <a href="#options" data-bs-toggle="collapse" data-bs-parent="#modalcontent" class="mb-4 d-block"><i data-feather="chevron-left"></i> <?php ee('Back') ?></a>
                <div class="form-group">
                    <label class="form-label"><?php ee('Image') ?></label>
                    <p><?php ee('Click the button add insert an Image then choose the file to upload.') ?></p>
                </div>
                <button type="button" data-trigger="insertcontent" data-type="image" class="btn btn-primary mt-3"><?php ee('Add Image') ?></button>
            </div>            
            <div id="modal-youtube" class="collapse" data-name="<?php echo e('Youtube Video') ?>">
                <a href="#options" data-bs-toggle="collapse" data-bs-parent="#modalcontent" class="mb-4 d-block"><i data-feather="chevron-left"></i> <?php ee('Back') ?></a>
                <div class="form-group">
                    <label class="form-label"><?php ee('Link to Video') ?></label>
                    <input type="text" class="form-control p-2" name="link" placeholder="e.g https://www.youtube.com/watch?v=dQw4w9WgXcQ" data-error="<?php ee('Please enter a valid youtube link') ?>">                        
                </div>
                <button type="button" data-trigger="insertcontent" data-type="youtube" class="btn btn-primary mt-3"><?php ee('Add Youtube Video') ?></button>
            </div>
            <div id="modal-whatsapp" class="collapse" data-name="<?php echo e('WhatsApp Call') ?>">
                <a href="#options" data-bs-toggle="collapse" data-bs-parent="#modalcontent" class="mb-4 d-block"><i data-feather="chevron-left"></i> <?php ee('Back') ?></a>
                <div class="form-group">
                    <label class="form-label"><?php ee('Phone Number') ?></label>
                    <input type="text" class="form-control p-2" name="phone" placeholder="e.g +1123456789">                        
                </div>
                <div class="form-group mt-2">
                    <label class="form-label"><?php ee('Label') ?></label>
                    <input type="text" class="form-control p-2" name="label" placeholder="e.g Call us">                        
                </div>
                <button type="button" data-trigger="insertcontent" data-type="whatsapp" class="btn btn-primary mt-3"><?php ee('Add WhatsApp Call') ?></button>
            </div>
            <div id="modal-spotify" class="collapse" data-name="<?php echo e('Spotify Embed') ?>">
                <a href="#options" data-bs-toggle="collapse" data-bs-parent="#modalcontent" class="mb-4 d-block"><i data-feather="chevron-left"></i> <?php ee('Back') ?></a>
                <div class="form-group">
                    <label class="form-label"><?php ee('Link to Spotify Song') ?></label>
                    <input type="text" class="form-control p-2" name="link" placeholder="e.g https://open.spotify.com/track/6PQ88X9TkUIAUIZJHW2upE?si=e8ab004e890a4d2f" data-error="<?php ee('Please enter a valid spotify link') ?>">                        
                </div>
                <button type="button" data-trigger="insertcontent" data-type="spotify" class="btn btn-primary mt-3"><?php ee('Add Spotify') ?></button>
            </div>
            <div id="modal-itunes" class="collapse" data-name="<?php echo e('Apple Music Embed') ?>">
                <a href="#options" data-bs-toggle="collapse" data-bs-parent="#modalcontent" class="mb-4 d-block"><i data-feather="chevron-left"></i> <?php ee('Back') ?></a>
                <div class="form-group">
                    <label class="form-label"><?php ee('Link to Apple Music Song') ?></label>
                    <input type="text" class="form-control p-2" name="link" placeholder="e.g https://music.apple.com/us/album/acapulco-jay-robinson-remix-single/1590557278" data-error="<?php ee('Please enter a valid apple music link') ?>">                        
                </div>
                <button type="button" data-trigger="insertcontent" data-type="itunes" class="btn btn-primary mt-3"><?php ee('Add Apple Music') ?></button>
            </div>
            <div id="modal-paypal" class="collapse" data-name="<?php echo e('PayPal Button') ?>">
                <a href="#options" data-bs-toggle="collapse" data-bs-parent="#modalcontent" class="mb-4 d-block"><i data-feather="chevron-left"></i> <?php ee('Back') ?></a>
                <div class="form-group">
                    <label class="form-label"><?php ee('Label') ?></label>
                    <input type="text" class="form-control p-2" name="label" placeholder="e.g New Hoodie For Sale">
                </div>                
                <div class="form-group mt-3">
                    <label class="form-label"><?php ee('PayPal Email') ?></label>
                    <input type="text" class="form-control p-2" name="email" placeholder="e.g myemail@domain.com">
                </div>
                <div class="form-group mt-3">
                    <label class="form-label"><?php ee('Amount') ?></label>
                    <input type="text" class="form-control p-2" name="amount" placeholder="e.g 10">
                </div>
                <div class="form-group mt-3">
                    <label class="form-label d-block mb-2"><?php ee('Currency') ?></label>
                    <select name="currency" data-toggle="select" class="form-control">
                        <?php foreach(\Helpers\App::currency() as $code => $currency): ?>
                            <option value="<?php echo $code ?>" <?php echo $code == "USD" ? 'selected' : '' ?>><?php echo $currency['label'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <button type="button" data-trigger="insertcontent" data-type="paypal" class="btn btn-primary mt-3"><?php ee('Add Paypal') ?></button>
            </div>
            <div id="modal-tiktok" class="collapse" data-name="<?php echo e('Tiktok Embed') ?>">
                <a href="#options" data-bs-toggle="collapse" data-bs-parent="#modalcontent" class="mb-4 d-block"><i data-feather="chevron-left"></i> <?php ee('Back') ?></a>
                <div class="form-group">
                    <label class="form-label"><?php ee('Link to Tiktok Video') ?></label>
                    <input type="text" class="form-control p-2" name="link" placeholder="e.g https://www.tiktok.com/@marvel/video/7016405255604817157" data-error="<?php ee('Please enter a valid tiktok link') ?>">                        
                </div>
                <button type="button" data-trigger="insertcontent" data-type="tiktok" class="btn btn-primary mt-3"><?php ee('Add Tiktok') ?></button>
            </div>
            <div id="modal-heading" class="collapse" data-name="<?php echo e('Heading') ?>">
                <a href="#options" data-bs-toggle="collapse" data-bs-parent="#modalcontent" class="mb-4 d-block"><i data-feather="chevron-left"></i> <?php ee('Back') ?></a>
                <div class="btn-group">
                    <label class="btn btn-default"><input type="radio" class="btn-check" name="type" value="h1" checked>H1</label>
                    <label class="btn btn-default"><input type="radio" class="btn-check" name="type" value="h2">H2</label>
                    <label class="btn btn-default"><input type="radio" class="btn-check" name="type" value="h3">H3</label>
                    <label class="btn btn-default"><input type="radio" class="btn-check" name="type" value="h4">H4</label>
                    <label class="btn btn-default"><input type="radio" class="btn-check" name="type" value="h5">H5</label>
                    <label class="btn btn-default"><input type="radio" class="btn-check" name="type" value="h6">H6</label>
                </div>
                <div class="form-group mt-3">
                    <label class="form-label"><?php ee('Text') ?></label>
                    <input type="text" class="form-control p-2" name="text" placeholder="e.g Heading">                        
                </div>
                <button type="button" data-trigger="insertcontent" data-type="heading" class="btn btn-primary mt-3"><?php ee('Add') ?></button>
            </div>
            <div id="modal-divider" class="collapse" data-name="<?php echo e('Divider') ?>">
                <a href="#options" data-bs-toggle="collapse" data-bs-parent="#modalcontent" class="mb-4 d-block"><i data-feather="chevron-left"></i> <?php ee('Back') ?></a>                
                <div class="form-group mt-3">
                    <p><label class="form-label"><?php ee('Color') ?></label></p>
                    <input type="text" class="form-control p-2" name="color" id="dividercolor" value="#fff">                        
                </div>
                <button type="button" data-trigger="insertcontent" data-type="divider" class="btn btn-primary mt-3"><?php ee('Add') ?></button>
            </div>
            <div id="modal-rss" class="collapse" data-name="<?php echo e('RSS Feed') ?>">
                <a href="#options" data-bs-toggle="collapse" data-bs-parent="#modalcontent" class="mb-4 d-block"><i data-feather="chevron-left"></i> <?php ee('Back') ?></a>                
                <div class="form-group mt-3">
                    <p><label class="form-label"><?php ee('RSS Feed') ?></label></p>
                    <input type="text" class="form-control p-2" name="link" id="link" value="" placeholder="e.g. https://mysite/rss" data-error="<?php ee('Please enter a valid link') ?>">
                </div>
                <button type="button" data-trigger="insertcontent" data-type="rss" class="btn btn-primary mt-3"><?php ee('Add') ?></button>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
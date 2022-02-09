<div class="container mt-5 mb-2" id="profile">    
    <div class="row">
        <div class="col-md-6 offset-md-3 text-center my-5">
            <?php if(isset($profiledata['avatar']) && $profiledata['avatar']): ?>
                <img src="<?php echo uploads($profiledata['avatar'], 'profile') ?>" class="rounded-circle mb-3" width="120" height="120">
            <?php else: ?>
                <img src="<?php echo $user->avatar() ?>" class="rounded-circle mb-3" width="120" height="120">
            <?php endif ?>
            <h3><span><?php echo $profile->name ?></span></h3></em>
            <div id="social" class="text-center mt-2">
                <?php foreach($profiledata['social'] as $key => $value): ?>
                    <?php if(empty($value)) continue ?>
                    <a href="<?php echo $value ?>" class="mx-2" target="_blank"><i class="fab fa-<?php echo $key ?>"></i></a>
                <?php endforeach ?>
            </div>
            <div id="content" class="mt-5">
                <?php foreach($profiledata['links'] as $value): ?>
                    <div class="item mb-3">
                        <?php if($value['type'] == "heading"): ?>
                            <?php if(in_array($value['format'], ['h1','h2','h3','h4','h5','h6'])):?>
                                <<?php echo $value['format'] ?>><?php echo $value['text'] ?></<?php echo $value['format'] ?>>
                            <?php else: ?>
                                <h1><?php echo $value['text'] ?></h1>
                            <?php endif ?>
                        <?php endif ?>
                        <?php if($value['type'] == "divider"): ?>
                            <hr>
                        <?php endif ?>

                        <?php if($value['type'] == "image"): ?>
                            <img src="<?php echo uploads($value['image'], 'profile') ?>" class="img-fluid rounded w-100">
                        <?php endif ?>

                        <?php if($value['type'] == "rss"): ?>
                            <div class="rss card card-body overflow-auto">
                                <?php $items = \Helpers\App::rss($value['link']) ?>
                                <?php if(!is_array($items)): ?>
                                    <?php echo $items ?>
                                <?php else: ?>
                                    <?php foreach($items as $item): ?>
                                        <div class="media mb-3">
                                            <?php if($item['image']): ?>
                                                <img class="mr-3" src="<?php echo $item['image'] ?>" alt="<?php echo $item['title'] ?>">
                                            <?php endif ?>
                                            <div class="media-body">
                                                <h6 class="mt-3"><a href="<?php echo $item['link'] ?>" target="_blank"><?php echo $item['title'] ?></a></h6>
                                                <?php echo $item['description'] ?>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </div>
                        <?php endif ?>

                        <?php if($value['type'] == "text"): ?>
                            <p><?php echo $value['text'] ?></p>
                        <?php endif ?>

                        <?php if($value['type'] == "whatsapp"): ?>
                            <a href="https://wa.me/<?php echo str_replace(' ', '', $value['phone']) ?>" class="btn btn-block d-block p-3 btn-custom"><img src="<?php echo assets('images/whatsapp.svg') ?>" height="25" class="mr-3"> <?php echo isset($value['label']) && $value['label'] ? $value['label'] : $value['phone'] ?></a>
                        <?php endif ?>
                        
                        <?php if($value['type'] == "link"): ?>
                            <a href="<?php echo $value['link'] ?>" class="btn btn-block d-block p-3 btn-custom"><?php echo $value['text'] ?></a>
                        <?php endif ?>

                        <?php if($value['type'] == "youtube"): ?>
                            <iframe width="100%" height="315" src="<?php echo $value["link"] ?>" class="rounded"></iframe>
                        <?php endif ?>

                        <?php if($value['type'] == "paypal"): ?>

                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">

                                <input type="hidden" name="business" value="<?php echo $value['email'] ?>">

                                <input type="hidden" name="cmd" value="_xclick">

                                <input type="hidden" name="item_name" value="<?php echo $value['label'] ?>">
                                <input type="hidden" name="amount" value="<?php echo $value['amount'] ?>">
                                <input type="hidden" name="currency_code" value="<?php echo $value['currency'] ?>">

                                <button type="submit" name="submit" class="btn btn-block d-block p-3 btn-custom w-100"><?php echo $value['label'] ?></button>                            
                            </form>
                        <?php endif ?>
                        <?php if($value['type'] == "spotify"): ?>
                            <iframe width="100%" height="315" src="<?php echo $value["link"] ?>" class="rounded"></iframe>
                        <?php endif ?>                        

                        <?php if($value['type'] == "tiktok"): ?>
                            <blockquote class="tiktok-embed rounded" cite="<?php echo $value['link'] ?>" data-video-id="<?php echo $value['id'] ?>" style="max-width: 605px;min-width: 325px;" > <section> </section> </blockquote> <script async src="https://www.tiktok.com/embed.js"></script>
                        <?php endif ?>                        
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    <div class="text-center mt-5 opacity-8">
        <a class="navbar-brand mr-0" href="<?php echo route('home') ?>">
            <?php if(config('logo')): ?>
                <img alt="<?php echo config('title') ?>" src="<?php echo uploads(config('logo')) ?>" width="80" id="navbar-logo">
            <?php else: ?>                
                <h1 class="h5 mt-2"><?php echo config('title') ?></h1>
            <?php endif ?>
        </a>   
    </div>
</div>
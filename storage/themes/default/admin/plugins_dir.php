<div class="d-flex">
    <div>
        <h1 class="h3 mb-5"><?php ee('Plugins Directory') ?></h1>
        <p></p>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
      <form action="<?php echo route('admin.plugins.dir') ?>" method="get" class="card card-body">
        <div class="d-flex">
          <h6><?php ee('Install Plugins') ?></h6>
          <div class="ms-auto">
            <a href="<?php echo route('admin.plugins.dir') ?>" class="btn btn-primary btn-sm"><?php ee('View All') ?></a>
          </div>
        </div>
        <div class="d-flex mt-3">
          <div class="input-group input-group-navbar">
              <input type="text" class="form-control" name="q" value="<?php echo request()->q ?>" placeholder="Search for plugins" aria-label="Search">
              <button class="btn" type="submit">
                <i class="align-middle" data-feather="search"></i>
              </button>
          </div>
        </div>
      </form>
    </div>
    <div class="col-md-12">        
        <div class="row">
        <?php if($plugins): ?>
            <h4 class="mb-4"><?php echo count($plugins) ?> Plugins</h4>
            <?php foreach($plugins as $plugin): ?>
                <div class="col-md-3 mb-3">
                    <div class="card card-body h-100 position-relative"> 
                        <?php if($plugin->installed): ?>
                            <p class="position-absolute top-0 start-50 translate-middle"><span class="badge bg-success">Installed</span></p>
                        <?php endif ?>
                        <p><?php echo $plugin->name ?> (v<?php echo $plugin->version ?>)  <?php echo $plugin->type == "paid" ? '<span class="badge ms-2 bg-success">Paid</span>' : '<span class="badge ms-2 bg-primary">Free</span>' ?></p>
                        <p><a href="<?php echo $plugin->link ?>" target="_blank"><strong><?php echo $plugin->author ?></strong></a></p>
                        <p><?php echo $plugin->description ?></p>
                        <?php if($plugin->type == "paid"): ?>
                            <p><a href="<?php echo $plugin->buy ?>" class="btn btn-success" target="_blank"><?php ee("Purchase") ?></a></p>
                        <?php else: ?>
                            <?php if($plugin->installed): ?>
                                <?php if(version_compare($plugin->installedversion, $plugin->version, '<')): ?>
                                    <p><a href="<?php echo route('admin.plugins.dir', ['install' => $plugin->tag]) ?>" class="btn btn-primary"><?php ee("Update") ?></a></p>
                                <?php endif ?>
                            <?php else: ?>
                                <p><a href="<?php echo route('admin.plugins.dir', ['install' => $plugin->tag]) ?>" class="btn btn-primary"><?php ee("Install") ?></a></p>
                            <?php endif ?>
                        <?php endif ?>
                    </div>                
                </div>
            <?php endforeach ?>
        <?php else: ?>
            <div class="col-md-12">
                <div class="card card-body"><?php ee('No results.') ?></div>
            </div>
        <?php endif ?>
        </div>   
    </div>    
</div>
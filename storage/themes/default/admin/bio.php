<div class="card flex-fill">
    <div class="card-header">
        <div class="d-flex">
            <div>
                <h5 class="card-title mb-0"><?php ee('Bio Pages') ?></h5>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover my-0">
            <thead>
                <tr>
                    <th><?php ee('ID') ?></th>
                    <th><?php ee('User') ?></th>
                    <th><?php ee('Link') ?></th>
                    <th><?php ee('Views') ?></th>
                    <th><?php ee('Date') ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($bios as $bio): ?>
                    <tr>
                        <td><?php echo $bio->id ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?php echo $bio->user->avatar() ?>" alt="" width="36" class="img-responsive rounded-circle">
                                <div class="ms-2">
                                    <?php echo ($bio->user->admin)?"<strong>{$bio->user->email}</strong>":$bio->user->email ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted" data-href="<?php echo Helpers\App::shortRoute($bio->url->domain, $bio->url->alias.$bio->url->custom) ?>"><?php echo Helpers\App::shortRoute($bio->url->domain, $bio->url->alias.$bio->url->custom) ?></span>
                            <a href="#copy" class="copy inline-copy" data-clipboard-text="<?php echo Helpers\App::shortRoute($bio->url->domain, $bio->url->alias.$bio->url->custom) ?>"><small><?php echo e("Copy")?></small></a>	                                        
                        </td>
                        <td><?php echo $bio->url->click ?></td>
                        <td><?php echo $bio->created_at ?></td>
                        <td>
                            <button type="button" class="btn btn-default shadow-lg bg-white" data-bs-toggle="dropdown" aria-expanded="false"><i data-feather="more-horizontal"></i></button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo route('admin.users.view', [$bio->user->id]) ?>"><i data-feather="user"></i> <?php ee('View User') ?></span></a></li>
                                <li><a class="dropdown-item" href="<?php echo route('stats', [$bio->url->id]) ?>"><i data-feather="bar-chart"></i> <?php ee('View Stats') ?></span></a></li>
                                <li><a class="dropdown-item" data-bs-toggle="modal" data-trigger="modalopen" data-bs-target="#deleteModal" href="<?php echo route('admin.bio.delete', [$bio->id, \Core\Helper::nonce('bio.delete')]) ?>"><i data-feather="trash"></i> <?php ee('Delete') ?></a></li>
                            </ul>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>    
    </div>
    <?php echo pagination('pagination') ?>
</div>
<div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php ee('Are you sure you want to delete this?') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><?php ee('You are trying to delete a record. This action is permanent and cannot be reversed.') ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php ee('Cancel') ?></button>
        <a href="#" class="btn btn-danger" data-trigger="confirm"><?php ee('Confirm') ?></a>
      </div>
    </div>
  </div>
</div>
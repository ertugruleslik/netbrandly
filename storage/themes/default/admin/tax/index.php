<div class="d-flex">
    <div>
        <h1 class="h3 mb-5"><?php ee('Tax Rates') ?></h1>
    </div>
    <div class="ms-auto">
        <a href="<?php echo route('admin.tax.new') ?>" class="btn btn-primary"><i data-feather="plus"></i> <?php ee('Add Tax Rate') ?></a>
    </div>
</div>
<div class="card flex-fill">    
    <div class="table-responsive">
        <table class="table table-hover my-0">
            <thead>
                <tr>
                    <th><?php ee('Name') ?></th>
                    <th><?php ee('Rate') ?></th>
                    <th><?php ee('Countries') ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rates as $rate): ?>
                    <tr>
                        <td>
                            <?php echo $rate->name ?>
                            <?php if($rate->status): ?>
                                <span class="badge bg-success"><?php ee("Enabled") ?></span>
                            <?php endif ?>
                        </td>
                        <td><?php echo $rate->rate ?>%</td>
                        <td><?php echo $rate->countries ?></td>
                        <td>
                            <button type="button" class="btn btn-default shadow-lg bg-white" data-bs-toggle="dropdown" aria-expanded="false"><i data-feather="more-horizontal"></i></button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo route('admin.tax.edit', [$rate->id]) ?>"><i data-feather="edit"></i> <?php ee('Edit') ?></a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" data-bs-toggle="modal" data-trigger="modalopen" data-bs-target="#deleteModal" href="<?php echo route('admin.tax.delete', [$rate->id, \Core\Helper::nonce('tax.delete')]) ?>"><i data-feather="trash"></i> <?php ee('Delete') ?></a></li>
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
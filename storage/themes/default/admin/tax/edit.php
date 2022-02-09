<h1 class="h3 mb-5"><?php ee('Edit Tax Rate') ?></h1>
<div class="card">
    <div class="card-body">
        <form method="post" action="<?php echo route('admin.tax.update', [$rate->id]) ?>" enctype="multipart/form-data">
            <?php echo csrf() ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-4">
                        <label for="domain" class="form-label"><?php ee('Name') ?></label>
                        <input type="text" class="form-control p-2" name="name" id="name" value="<?php echo $rate->name ?>" placeholder="e.g. Canada Rate">
                    </div>	
                </div>                
                <div class="col-md-4">
                    <div class="form-group mb-4">
                        <label for="root" class="form-label"><?php ee('Rate') ?> %</label>
                        <input type="text" class="form-control p-2" name="rate" id="rate" value="<?php echo $rate->rate ?>" placeholder="e.g. 15" readonly>
                    </div>	
                </div>                              
            </div>
            <div class="form-group mb-3">
                <label class="form-label"><?php echo e("Countries")?></label>
                <div class="input-group input-select">
                    <span class="input-group-text bg-white"><i data-feather="globe"></i></span>
                    <select name="countries[]" multiple data-toggle="select">
                        <?php foreach(\Core\Helper::Country(false) as $code => $country): ?>
                            <option value="<?php echo $country ?>" <?php echo in_array($country, $rate->countries) ? 'selected' : '' ?>><?php echo $country ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <div class="form-group mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" data-binary="true" id="status" name="status" value="1" data-toggle="togglefield" <?php echo $rate->status ? 'checked' : '' ?>>
                    <label class="form-check-label" for="status"><?php ee('Enabled') ?></label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i data-feather="plus"></i> <?php ee('Update Rate') ?></button>
        </form>
    </div>
</div>
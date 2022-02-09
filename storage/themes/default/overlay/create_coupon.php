<h1 class="h3 mb-2"><?php echo $name ?></h1>
<p class="text-muted mb-3"><?php echo $description ?></p>
<div class="row">
    <div class="col-md-8">
		<form method="post" action="<?php echo route("overlay.save", [$type]) ?>" enctype="multipart/form-data" id="settings-form" autocomplete="off">
			<div class="card">
				<div class="card-body">
                    <?php echo csrf() ?>
                    <div class="row">
						<div class="col-md-6">
							<div class="form-group mb-3">
								<label class="form-label" for="name"><?php ee("Name") ?></label>
								<input type="text" class="form-control" name="name" id="name"  placeholder="e.g. Promo" value="<?php echo old('name') ?>" data-required="true">
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="coupon" class="form-label"><?php ee("Coupon Code") ?></label>
								<input type="text" class="form-control" name="coupon" id="coupon"  placeholder="e.g. SAVE20" value="<?php echo old('coupon') ?>">
							</div>								
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-md-12">
							<div class="form-group">
								<label for="message" class="form-label"><?php ee("Custom Message") ?> (Max: 140 chars)</label>
								<textarea name="message" id="message" cols="30" rows="5" class="form-control" placeholder="e.g. <?php ee("Get a $10 discount with any purchase more than $50") ?>"><?php echo old('message') ?></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group mb-3">
								<label for="text" class="form-label"><?php ee("Button Text") ?></label>
								<input type="text" class="form-control" name="text" id="text"  placeholder="e.g. <?php ee("Copy") ?>" value="<?php echo old('text') ?>">
							</div>	
						</div>
					</div>					
				</div>
			</div>
			<div class="card">
				<div class="card-header mt-2">					
					<h5 class="card-title fw-bold"><i data-feather="plus-circle" class="me-2"></i> <a href="" data-bs-toggle="collapse" role="button" data-bs-target="#custom"><?php ee('Appearance Customization') ?></a></h5>
				</div>				
				<div class="card-body collapse" id="custom">					
					<div class="row">						
						<div class="col-md-6">
							<div class="form-group mb-5">
								<label class="form-label" for="bg"><?php ee("Overlay Background Color") ?></label> <br>
								<input type="text" name="bg" id="bg" value="<?php echo old('bg') ?>">
							</div>			
						</div>	
						<div class="col-md-6">
							<div class="form-group mb-5">
								<label class="form-label" for="color"><?php ee("Overlay Text Color") ?></label><br>
								<input type="text" name="color" id="color" value="<?php echo old('color') ?>">
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group mb-5">
								<label class="form-label" for="btnbg"><?php ee("Button Background Color") ?></label><br>
								<input type="text" name="btnbg" id="btnbg" value="<?php echo old('btnbg') ?>">
							</div>		
						</div>
						<div class="col-md-6">
							<div class="form-group mb-5">
								<label class="form-label" for="btncolor"><?php ee("Button Text Color") ?></label><br>
								<input type="text" name="btncolor" id="btncolor" value="<?php echo old('btncolor') ?>">
							</div>					
						</div>
					</div>				
					<div class="form-group">
						<label class="form-label d-block" for="position"><?php ee("Overlay Position") ?></label>
						<select name="position" id="position" class="form-control" data-toggle="select">						               
							<option value="bl"><?php ee("Bottom Left") ?></option>
							<option value="br" selected><?php ee("Bottom Right") ?></option> 
						</select>
					</div>
				</div>
			</div>
			<button type="submit" class="btn btn-primary"><?php ee("Create") ?></button>
		</form>
    </div>
    <div class="col-md-4">
        <div class="position-sticky" id="main-overlay">
			<div class="contact-box mx-0 d-block w-100">
                <p class="custom-text"><?php ee('Description') ?></p>
                <div class="d-flex align-items-center border rounded bg-white p-1">
                    <div>                         
                        <h4 class="mx-2 mb-0"><?php ee('Coupon') ?></h4>
                    </div>		
                    <div class="ms-auto">
                        <button type="button" class="btn btn-dark btn-lg"><?php ee("Copy") ?></button>
                    </div>
                </div>															
            </div>
        </div>		
    </div>
</div>
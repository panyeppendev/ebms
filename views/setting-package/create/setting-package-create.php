<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content p-3">
		<div class="row">
			<div class="col-12">
				<div class="row justify-content-between">
					<div class="col-3">
						<h4 class="card-title mt-1">Atur Limit Paket</h4>
					</div>
					<div class="col-9">
						<div class="row justify-content-end">
							<div class="col-3">
								<a href="<?= base_url() ?>settingpackage" class="btn btn-sm btn-primary btn-block">
									<i class="fa fa-list-alt"></i>
									Daftar Limit Paket
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-3">
			<form action="<?= base_url() ?>settingpackage/create" id="form-create" method="post">
				<input type="hidden" name="package" id="package-selected" value="<?= $packageSelected ?>">
			</form>
			<div class="col-6">
				<div class="card">
					<div class="card-body">
						<div class="form-group row">
							<label for="change-package" class="col-9 col-form-label font-weight-normal">
								PILIH PAKET
							</label>
							<div class="col-3">
								<select id="change-package" class="form-control form-control-sm" onchange="packageSelected(this)">
									<?php
									if ($packages){
										foreach ($packages as $package) {
											?>
											<option <?= $packageSelected == $package->id ? 'selected' : '' ?> value="<?= $package->id ?>"><?= $package->name ?></option>
											<?php
										}
									}
									?>
								</select>
							</div>
						</div>
						<h6 class="mb-3 text-muted">Detil Paket</h6>
						<form autocomplete="off" id="form-package" method="post" action="<?= base_url() ?>settingpackage/store">
							<input type="hidden" name="package" value="<?= $packageSelected ?>">
							<?php
							$tab = 1;
							if ($limits) {
								foreach ($limits as $limit) {
							?>
								<div class="form-group row">
									<input type="hidden" name="id[]" value="<?= $limit->id ?>">
									<label for="limit-<?= $limit->id ?>" class="col-4 col-form-label font-weight-normal">
										<?= $limit->name ?>
									</label>
									<div class="col-5">
										<input tabindex="<?= $tab++ ?>" type="text" name="nominal[]" class="form-control limit" id="limit-<?= $limit->id ?>" value="<?= $this->spm->getNominal($packageSelected, $limit->id)[0] ?>">
									</div>
									<div class="col-3">
										<input maxlength="1" tabindex="<?= $tab++ ?>" type="text" name="qty[]" class="form-control limit" id="qty-<?= $limit->id ?>" value="<?= $this->spm->getNominal($packageSelected, $limit->id)[1] ?>">
									</div>
								</div>
							<?php
								}
							}
							?>
						</form>
					</div>
				</div>
			</div>
			<div class="col-6">
				<div class="row">
					<div class="col-12">
						<div class="callout callout-warning mb-4">
							<h5>PERHATIAN!</h5>
							<ul>
								<li>Limit artinya batas maksimal pencairan setiap hari</li>
								<li>Nominal nol diasumsikan tidak ada limit</li>
							</ul>
						</div>
					</div>
					<div class="col-6">
						<button class="btn btn-primary btn-block" type="button" onclick="handleSubmit(this)">
							Simpan
						</button>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>


<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('setting-package/create/js-setting-package-create'); ?>

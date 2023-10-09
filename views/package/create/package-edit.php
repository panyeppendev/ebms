<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content p-3">
		<div class="row">
			<div class="col-12">
				<div class="row justify-content-between">
					<div class="col-3">
						<h4 class="card-title mt-1">Detil Paket</h4>
					</div>
					<div class="col-9">
						<div class="row justify-content-end">
							<div class="col-2">
								<a href="<?= base_url() ?>package" class="btn btn-sm btn-default btn-block">
									<i class="fa fa-list-alt"></i>
									List Paket
								</a>
							</div>
							<div class="col-2">
								<a href="<?= base_url() ?>package/create" class="btn btn-sm btn-primary btn-block">
									<i class="fa fa-list-alt"></i>
									Tambah Paket
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if ($package['status']) : ?>
		<div class="row mt-3">
			<div class="col-6">
				<div class="card">
					<div class="card-body">
						<form autocomplete="off" id="form-package" method="post" action="<?= base_url() ?>package/update">
							<input type="hidden" name="id" value="<?= $package['id'] ?>">
							<div class="form-group row">
								<label for="name" class="col-7 col-form-label font-weight-normal">Nama</label>
								<div class="col-5">
									<input type="text" maxlength="2" class="form-control text-uppercase" id="name" name="name" tabindex="0" readonly value="<?= $package['name'] ?>">
								</div>
							</div>
							<h6 class="mt-3 mb-3 text-muted">Detil Paket</h6>
							<?php
							if ($package['detail']) {
								$tab = 1;
								foreach ($package['detail'] as $account) {
							?>
								<div class="form-group row">
									<label for="account-<?= $account['id'] ?>" class="col-7 col-form-label font-weight-normal">
										<?= $account['name'] ?>
									</label>
									<div class="col-5">
										<input tabindex="<?= $tab++ ?>" type="text" name="account[<?= $account['id'] ?>]" class="form-control account" id="account-<?= $account['id'] ?>" value="<?= $account['nominal'] ?>">
									</div>
								</div>
							<?php
								}
							}
							?>

						</form>
					</div>
					<div class="card-footer">
						<div class="row justify-content-end">
							<div class="col-12 text-right text-primary">
								<h4 class="mb-0">Total: Rp. <?= number_format($package['amount'], 0, ',', '.') ?></h4>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-6">
				<div class="row">
					<div class="col-12">
						<div class="callout callout-warning mb-4">
							<h5>PERHATIAN!</h5>
							<ul>
								<li>Akun pada detil paket yang berisi nol diasumsikan tidak di-include-kan dalam paket</li>
							</ul>
						</div>
					</div>
					<div class="col-6">
						<button class="btn btn-primary btn-block" type="button" onclick="handleSubmit(this)">
							Simpan Perubahan
						</button>
					</div>
					<div class="col-6">
						<button class="btn btn-danger btn-block" type="button" onclick="handleDestroy(this, '<?= $package['id'] ?>')">
							Buang Paket
						</button>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<?php if (!$package['status']) : ?>
		<div class="row mt-5">
			<div class="error-page">
				<div class="error-content">
					<h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Ada masalah nih</h3>
					<p>
						Data tidak ditemukan. ID <?= $package['id'] ?> tidak valid<br>
					</p>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</section>
</div>

<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('package/create/js-package-edit'); ?>

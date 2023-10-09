<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content p-3">
		<div class="row justify-content-between">
			<div class="col-3">
				<h4 class="card-title mt-1">Tambah Pembelian</h4>
			</div>
			<div class="col-9">
				<div class="row justify-content-end">
					<div class="col-3">
						<a href="<?= base_url() ?>purchase" class="btn btn-sm btn-primary btn-block">
							<i class="fa fa-list-alt"></i>
							Daftar Transaksi
						</a>
					</div>
				</div>
			</div>
		</div>
		<form action="<?= base_url() ?>purchase/invoice" method="post" target="_blank" id="form-invoice">
			<input type="hidden" name="id" id="id" value="">
		</form>
		<div class="row mt-3">
			<div class="col-4">
				<div class="card">
					<div class="card-body">
						<form autocomplete="off" id="form-purchase">
							<div class="form-group row">
								<label for="nis" class="col-5 col-form-label font-weight-normal">NIS </label>
								<div class="col-7">
									<input data-inputmask="'mask' : '99999999999'" data-mask="" type="text" class="form-control" id="nis" name="nis" autofocus>
								</div>
							</div>
							<div class="form-group row">
								<label for="package" class="col-5 col-form-label font-weight-normal">Paket </label>
								<div class="col-7">
									<select class="form-control" id="package" name="package">
										<option value="">:Pilih:</option>
										<?php
										if ($packages) {
											foreach ($packages as $package) {
										?>
												<option value="<?= $package->id ?>"><?= $package->name ?></option>
										<?php
											}
										}
										?>
									</select>
								</div>
							</div>
							<h6 class="text-muted">Tambahan</h6>
							<div class="form-group row">
								<?php
								if ($addons) {
									foreach ($addons as $addon) {
										?>
										<div class="col-12 mt-2">
											<div class="icheck-primary d-inline">
												<input type="checkbox" class="check-addon" id="addon-<?= $addon->id ?>" name="addon[<?= $addon->id ?>]" value="<?=  $addon->nominal?>">
												<label for="addon-<?= $addon->id ?>" class="font-weight-normal"><?= $addon->name.' - '.number_format($addon->nominal, 0, ',', '.') ?></label>
											</div>
										</div>
										<?php
									}
								}
								?>
							</div>
						</form>
					</div>
					<div class="card-footer">
						<button class="btn btn-primary btn-block" onclick="saveTemp()">
							Cek Sekarang
						</button>
					</div>
				</div>
			</div>
			<div class="col-8">
				<div class="row">
					<div class="col-12" id="show-student">
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>

<div class="modal fade" id="modal-detail">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<input type="text" onkeyup="loadData()" id="changeName" class="form-control form-control-sm" placeholder="Cari nama">
			</div>
			<div class="modal-body" id="show-detail" style="min-height: 45vh; max-height: 85vh; overflow: auto"></div>
		</div>
	</div>
</div>

<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('purchase/create/js-purchase-create'); ?>

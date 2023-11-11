<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content p-3">
		<div class="row justify-content-between">
			<div class="col-3">
				<h4 class="card-title mt-1">Tambah Aktivasi</h4>
			</div>
			<div class="col-9">
				<div class="row justify-content-end">
<!--					<div class="col-3">-->
<!--						<a href="--><?php //= base_url() ?><!--purchase/reset" class="btn btn-sm btn-primary btn-block">-->
<!--							<i class="fa fa-database"></i>-->
<!--							Perbarui Data-->
<!--						</a>-->
<!--					</div>-->
					<div class="col-3">
						<a href="<?= base_url() ?>purchase" class="btn btn-sm btn-primary btn-block">
							<i class="fa fa-list-alt"></i>
							Daftar Transaksi
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-3">
			<div class="col-4">
				<div class="card">
					<div class="card-body">
						<div class="callout callout-warning mb-4">
							Setiap santri hanya boleh memiliki satu paket aktif
						</div>
						<form autocomplete="off" id="form-purchase">
							<div class="form-group row">
								<label for="nis" class="col-5 col-form-label font-weight-normal">NIS </label>
								<div class="col-7">
									<input data-inputmask="'mask' : '99999999999'" data-mask="" type="text" class="form-control" id="nis" name="nis" autofocus>
								</div>
							</div>
						</form>
					</div>
					<div class="card-footer">
						<button class="btn btn-primary btn-block" onclick="getById()">
							Cek Sekarang
						</button>
					</div>
				</div>
			</div>
			<div class="col-8">
				<div class="row">
					<div class="col-12" id="show-purchase">
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>

<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('purchase/activation/js-purchase-activation'); ?>

<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
		<div class="row justify-content-between">
			<div class="col-3">
				<h4 class="card-title mt-1">Transaksi Paket</h4>
			</div>
			<div class="col-9">
				<div class="row justify-content-end">
					<div class="col-4">
						<input autocomplete="off" type="text" onkeyup="purchases()" id="name" class="form-control form-control-sm" placeholder="Cari nama">
					</div>
					<div class="col-2">
						<select name="" id="change-type" class="form-control form-control-sm" onchange="purchases()">
							<option value="INACTIVE">TIDAK AKTIF</option>
							<option value="ACTIVE">AKTIF</option>
							<option value="DONE">SELESAI</option>
						</select>
					</div>
					<div class="col-3">
						<a href="<?= base_url() ?>purchase/activation" class="btn btn-sm btn-primary btn-block">
							<i class="fa fa-plus-circle"></i>
							Tambah Aktivasi
						</a>
					</div>
					<div class="col-3">
						<a href="<?= base_url() ?>purchase/create" class="btn btn-sm btn-primary btn-block">
							<i class="fa fa-plus-circle"></i>
							Tambah Pembelian
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-4" id="show-data"></div>
    </section>
    <!-- /.content -->
</div>


<?php $this->load->view('partials/footer'); ?>
<script src="<?= base_url('template') ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('template') ?>/plugins/daterangepicker/daterangepicker.js"></script>
<?php $this->load->view('purchase/js-purchase'); ?>

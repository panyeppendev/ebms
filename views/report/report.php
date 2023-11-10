<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
		<div class="row mt-5 justify-content-center">
			<div class="col-8">
				<div class="card">
					<div class="card-body text-center">
						<p class="mb-0">Silahkan pilih jenis laporan yang kamu inginkan</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-5 justify-content-center">
			<div class="col-3">
				<a href="<?= base_url() ?>report/purchase" class="btn btn-outline-primary btn-block py-5">
					<i class="fas fa-file-invoice fa-3x"></i>
					<span class="d-block mt-3">Pembelian Paket</span>
				</a>
			</div>
			<div class="col-3">
				<a href="<?= base_url() ?>report/mutation" class="btn btn-outline-primary btn-block py-5">
					<i class="fas fa-hand-holding-usd fa-3x"></i>
					<span class="d-block mt-3">Mutasi Kas</span>
				</a>
			</div>
			<div class="col-3">
				<a href="<?= base_url() ?>report/cashflow" class="btn btn-outline-primary btn-block py-5">
					<i class="fas fa-random fa-3x"></i>
					<span class="d-block mt-3">Arus Kas</span>
				</a>
			</div>
		</div>
    </section>
    <!-- /.content -->
</div>


<div class="modal fade" id="modal-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body" id="show-detail" style="max-height: 85vh; overflow: auto"></div>
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="wrap-loading__" style="display: none">
    <div class="loading__ fade-in-loading__">
        <div class="wrapper-loading__">
            <div class="lds-dual-ring"></div>
            <span class="font-italic text-loading__">Ke pasar beli pepaya, tunggu sebentar, ya.....</span>
        </div>
    </div>
</div>

<?php $this->load->view('partials/footer'); ?>
<script src="<?= base_url('template') ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('template') ?>/plugins/daterangepicker/daterangepicker.js"></script>
<?php $this->load->view('report/js-report'); ?>

<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
		<div class="row justify-content-between">
			<div class="col-3">
				<h6 class="mb-0 align-middle">Laporan Pembelian Paket</h6>
			</div>
			<div class="col-9">
				<div class="row justify-content-end">
					<div class="col-4">
						<div class="input-group">
							<div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </span>
							</div>
							<input type="text" class="form-control form-control-sm" id="reservation" placeholder="Semua waktu">
						</div>
					</div>
					<div class="col-2">
						<form action="<?= base_url() ?>report/exportPurchase" method="post" target="_blank">
							<input type="hidden" id="start-date" value="" name="start">
							<input type="hidden" id="end-date" value="" name="end">
							<button type="submit" class="btn btn-block btn-sm btn-default">
								<i class="fas fa-file-pdf"></i> Export
							</button>
						</form>
					</div>
					<div class="col-2">
						<a href="<?= base_url() ?>report" class="btn btn-primary btn-sm btn-block">
							<i class="fas fa-undo"></i> Halaman Awal
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
<?php $this->load->view('report/purchase/js-report-purchase'); ?>

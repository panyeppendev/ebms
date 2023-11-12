<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
		<div class="row justify-content-end">
			<div class="col-2">
				<select id="account" class="form-control form-control-sm" onchange="cashFlows()">
					<?php
					if ($accounts) {
						foreach ($accounts as $account) {
							?>
							<option value="<?= $account['id'] ?>"><?= $account['name'] ?></option>
							<?php
						}
					}
					?>
					<option value="DEPOSIT">TABUNGAN</option>
				</select>
			</div>
			<div class="col-3">
				<div class="input-group">
					<div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </span>
					</div>
					<input type="text" class="form-control form-control-sm" id="reservation" placeholder="Semua waktu">
				</div>
			</div>
			<div class="col-1">
				<form action="<?= base_url() ?>report/exportCashFlow" method="post" target="_blank">
					<input type="hidden" id="account-selected" name="account" value="">
					<input type="hidden" id="start" value="" name="start">
					<input type="hidden" id="end" value="" name="end">
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
		<div class="row mt-4" id="show-data"></div>
	</section>
	<!-- /.content -->
</div>

<?php $this->load->view('partials/footer'); ?>
<script src="<?= base_url('template') ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('template') ?>/plugins/daterangepicker/daterangepicker.js"></script>
<?php $this->load->view('report/cashflow/js-report-cashflow'); ?>

<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
		<div class="row justify-content-between">
			<div class="col-3">
				<h4 class="card-title mt-1">Pengaturan Buka Tutup Harian</h4>
			</div>
			<div class="col-9">
				<div class="row justify-content-end">
					<?php
					if (!$setting) { ?>
						<div class="col-3">
							<button class="btn btn-sm btn-primary btn-block" onclick="openDaily(this)">
								<i class="fa fa-plus-circle"></i>
								Buka Transaksi Harian
							</button>
						</div>
					<?php } else { ?>
						<div class="col-3">
							<button onclick="closeDaily(this)" class="btn btn-sm btn-danger btn-block">
								<i class="fa fa-plus-circle"></i>
								Tutup Transaksi Harian
							</button>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="row mt-4" id="show-data"></div>
    </section>
    <!-- /.content -->
</div>

<div class="modal fade" id="modal-open">
    <div class="modal-dialog modal-default">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Konfirmasi</h5>
            </div>
            <div class="modal-body" id="show-detail">
				<div class="form-group">
					<label for="account" class="font-weight-normal">Pilih Rekening</label>
					<select id="account" class="form-control">
						<option value="">:Pilih Rekening:</option>
						<?php
						if ($accounts) {
							foreach ($accounts as $account) {
								?>
								<option value="<?= $account->id ?>"><?= $account->name ?></option>
						<?php
							}
						}
						?>
					</select>
				</div>
				<div class="form-group">
					<label style="user-select: none;" for="text-confirm" class="font-weight-normal">Ketik "<?= $open ?>"</label>
					<input type="text" class="form-control" id="text-confirm" autocomplete="off">
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary btn-block" onclick="saveOpenDaily(this)">
					Buka Transaksi Hari Ini
				</button>
			</div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-close">
	<div class="modal-dialog modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<h5>Konfirmasi</h5>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label style="user-select: none;" for="text-confirm" class="font-weight-normal">Ketik "<?= $close ?>"</label>
					<input type="text" class="form-control" id="text-confirm-close" autocomplete="off">
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary btn-block" onclick="saveCloseDaily(this)">
					Tutup Transaksi Hari Ini
				</button>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('partials/footer'); ?>
<script src="<?= base_url('template') ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('template') ?>/plugins/daterangepicker/daterangepicker.js"></script>
<?php $this->load->view('set-daily/js-set-daily'); ?>

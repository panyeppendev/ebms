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
<!--					<div class="col-4">-->
<!--						<div class="input-group">-->
<!--							<div class="input-group-prepend">-->
<!--                        <span class="input-group-text">-->
<!--                            <i class="far fa-calendar-alt"></i>-->
<!--                        </span>-->
<!--							</div>-->
<!--							<input type="text" class="form-control form-control-sm" id="reservation" placeholder="Semua waktu">-->
<!--							<input type="hidden" id="start-date" value="">-->
<!--							<input type="hidden" id="end-date" value="">-->
<!--						</div>-->
<!--					</div>-->
					<div class="col-3">
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
<script src="<?= base_url('template') ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('template') ?>/plugins/daterangepicker/daterangepicker.js"></script>
<?php $this->load->view('purchase/js-purchase'); ?>

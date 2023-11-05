<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row mb-3 justify-content-between">
            <div class="col-4">
                <h5>Data Tabungan</h5>
            </div>
            <div class="col-8">
				<div class="row justify-content-end">
					<div class="col-5">
						<input id="change-name" type="text" class="form-control form-control-sm" onkeyup="loadData()">
					</div>
					<div class="col-4">
						<select onchange="loadData()" id="change-domicile" class="form-control form-control-sm">
							<option value="">.:Semua Domisili:.</option>
							<option value="Imam Ghazali">Imam Ghazali</option>
							<option value="Imam Maliki">Imam Maliki</option>
							<option value="Imam Hanafi">Imam Hanafi</option>
							<option value="Imam Hambali">Imam Hambali</option>
							<option value="Imam Sibaweh">Imam Sibaweh</option>
							<option value="Imam Syafi'i">Imam Syafi'i</option>
							<option value="Imam Ibnu Hajar Al-Haitami">Imam Ibnu Hajar Al-Haitami</option>
							<option value="Imam An-Nawawi">Imam An-Nawawi</option>
							<option value="Imam Ar-Rofi'i">Imam Ar-Rofi'i</option>
							<option value="Imam Haramain">Imam Haramain</option>
							<option value="Sayyidina Abu Bakar">Sayyidina Abu Bakar</option>
							<option value="Sayyidina Umar">Sayyidina Umar</option>
							<option value="Sayyidina Utsman">Sayyidina Utsman</option>
							<option value="Sayyidina Ali">Sayyidina Ali</option>
							<option value="Imam As-Suyuthi">Imam As-Suyuthi</option>
						</select>
					</div>
					<div class="col-3">
						<a href="<?= base_url() ?>deposit/create" class="btn btn-sm btn-default btn-block">
							<i class="fa fa-plus-circle"></i>
							Tambah Tabungan
						</a>
					</div>
				</div>
            </div>
        </div>
        <div class="row" id="show-data"></div>
    </section>
    <!-- /.content -->
</div>

<div class="modal fade" id="modal-detail">
    <div class="modal-dialog modal-default">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Detail Tabungan</h6>
                <div class="align-middle" data-dismiss="modal" title="Tutup" style="cursor: pointer">
                    <i class="fas fa-times-circle text-danger"></i>
                </div>
            </div>
            <div class="modal-body pt-0">
                <div class="row" id="show-detail"></div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('deposit/js-deposit'); ?>

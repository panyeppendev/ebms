<?php $this->load->view('partials/header'); ?>
<?php
if ($step[0] != 0) {
    $currentStep = $step[0];
} else {
    $currentStep = 0;
}
?>
<input type="hidden" id="current-step" value="<?= $currentStep ?>">
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
    <?php if ($setting == 'CLOSED') { ?>
            <div class="row mt-3">
                <div class="error-page" style="margin-top: 100px;">
                    <div class="error-content">
                        <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! ada masalah nih....</h3>
                        <p>
                            Pencairan uang saku baik tunai maupun non-tunai belum dibuka. Segera hubungi bagian admin ~<br>
                            <br>
                            <a href="<?= base_url() ?>">Kilik untuk kembali ke Beranda</a>
                        </p>

                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-body py-1">
                            <i class="fas fa-info-circle"></i>
                            Surat jarak jauh
                        </div>
                    </div>
                </div>
                <div class="col-5" data-toggle="modal" data-target="#modal-detail" id="show-recap"></div>
                <div class="col-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="reservation" placeholder="Hari ini">
                        <input type="hidden" id="filter-date" value="">
                    </div>
                </div>
                <div class="col-2">
                    <a href="<?= base_url() ?>long/add" class="btn btn-primary btn-sm btn-block">
                        <i class="fas fa-plus-circle"></i> Tambah Izin
                    </a>
                </div>
            </div>
            <hr class="mt-0">
            <div class="row justify-content-end">
                <div class="col-4" id="show-count-permission"></div>
                <div class="col-4">
                    <input onkeyup="filterLoad()" autocomplete="off" type="text" id="changeName" class="form-control form-control-sm" placeholder="Cari nama">
                </div>
                <div class="col-2">
                    <select onchange="filterLoad()" class="form-control form-control-sm" id="changeStatus">
                        <option value="">.:Semua Status:.</option>
                        <option value="ACTIVE">Aktif</option>
                        <option value="DISCIPLINE">Disiplin</option>
                        <option value="LATE">Terlambat belum ditindak</option>
                        <option value="LATE-DONE">Terlambat sudah ditindak</option>
                    </select>
                </div>
				<div class="col-2">
					<button class="btn btn-sm btn-primary btn-block" data-toggle="modal" data-target="#modal-doback">
						<i class="fas fa-arrow-down"></i> Kembali Perizinan
					</button>
				</div>
            </div>
            <div class="row mt-3" id="show-permission" style="min-height: 62.1vh; max-height: 63.1vh; overflow: auto"></div>
	<?php } ?>
	</section>
	<!-- /.content -->
</div>

<div class="modal fade" id="modal-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <input autocomplete="off" type="text" onkeyup="loadData()" id="changeName" class="form-control form-control-sm" placeholder="Cari nama">
            </div>
            <div class="modal-body" id="show-detail" style="min-height: 45vh; max-height: 85vh; overflow: auto"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-constitution">
	<div class="modal-dialog modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<h6>Form Tambah Tindakan Pelanggaran</h6>
			</div>
			<div class="modal-body" style="min-height: 45vh; max-height: 85vh; overflow: auto">
				<div class="row">
					<div class="col-12">
						<select id="change-constitution" class="form-control form-control-sm select2bs4">
							<option value="">.:Pilih Item Pelanggaran:.</option>
							<?php
							$textConstitutionCategory = [
								'LOW' => 'Ringan',
								'MEDIUM' => 'Sedang',
								'HIGH' => 'Berat',
								'TOP' => 'Sangat Berat'
							];
							if ($constitutions) {
								foreach ($constitutions as $constitution) {
									?>
									<option value="<?= $constitution->id ?>"><?= $textConstitutionCategory[$constitution->category].' - '.$constitution->name ?></option>
									<?php
								}
							}
							?>
						</select>
					</div>
				</div>

				<div class="row mt-4">
					<form id="form-add-punishment">
						<input type="hidden" id="id-permission" name="permission" value="0">
						<input type="hidden" id="id-student" name="nis" value="0">
						<input type="hidden" id="id-constitution" name="constitution" value="0">
					</form>
					<div class="col-12" id="show-constitution"></div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary btn-block" disabled id="button-save-punishment" onclick="savePunishment()">
					Simpan tindakan
				</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-doback">
	<div class="modal-dialog modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="card-title">Form kembali perizinan</h6>
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<label class="col-sm-4 col-form-label">NO. KARTU</label>
					<div class="col-sm-8">
						<input autocomplete="off" data-inputmask="'mask' : '999999999999999'" data-mask="" type="text" id="nis-doback" class="form-control"></div>
				</div>
				<div class="callout callout-success py-2 text-success mb-0">
					<i class="fas fa-exclamation"></i> Masukkan NIS lalu ENTER atau tekan tombol di bawah
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-sm btn-block btn-primary" onclick="getPermissionClicked()">
					Lakukan pengecekan
				</button>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('partials/footer'); ?>
<script src="<?= base_url('template') ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('template') ?>/plugins/daterangepicker/daterangepicker.js"></script>
<?php $this->load->view('long/js-long'); ?>

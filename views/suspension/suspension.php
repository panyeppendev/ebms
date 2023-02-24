<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
	<input type="hidden" id="result-auto" value="<?= $auto ?>">
    <!-- Main content -->
    <section class="content p-3">
		<div class="row justify-content-end">
			<div class="col-6" id="show-count-suspension"></div>
			<div class="col-4">
				<input onkeyup="filterLoad()" autocomplete="off" type="text" id="changeName" class="form-control form-control-sm" placeholder="Cari nama">
			</div>
			<div class="col-2">
				<select onchange="filterLoad()" class="form-control form-control-sm" id="changeStatus">
					<option value="">.:Semua Status:.</option>
					<option value="INACTIVE">Belum Aktif</option>
					<option value="ACTIVE">Aktif</option>
					<option value="DONE">Selesai</option>
				</select>
			</div>
		</div>
		<div class="row mt-3" id="show-suspension" style="max-height: 63.1vh; overflow: auto"></div>
	</section>
	<!-- /.content -->
</div>

<div class="modal fade" id="modal-detail">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
				<input autocomplete="off" type="text" onkeyup="loadDetail()" id="change-name" class="w-50 form-control form-control-sm" placeholder="Cari nama">
            </div>
            <div class="modal-body" id="show-detail" style="min-height: 45vh; max-height: 85vh; overflow: auto"></div>
        </div>
    </div>
</div>

<?php $this->load->view('partials/footer'); ?>
<script src="<?= base_url('template') ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('template') ?>/plugins/daterangepicker/daterangepicker.js"></script>
<?php $this->load->view('suspension/js-suspension'); ?>

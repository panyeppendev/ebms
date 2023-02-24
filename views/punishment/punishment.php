<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
		<div class="row">
			<div class="col-8" id="show-recap"></div>
			<div class="col-2">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text">
							<i class="far fa-calendar-alt"></i>
						</span>
					</div>
					<input type="text" class="form-control form-control-sm" id="reservation" placeholder="Semua waktu">
					<input type="hidden" id="filter-date" value="">
					<input type="hidden" id="filter-category" value="">
				</div>
			</div>
			<div class="col-2">
				<a href="<?= base_url() ?>punishment/add" class="btn btn-primary btn-sm btn-block">
					<i class="fas fa-plus-circle"></i> Tambah Ta'zir
				</a>
			</div>
		</div>
		<hr class="mt-0">
		<div class="row justify-content-end">
			<div class="col-5" id="show-count-punishment"></div>
			<div class="col-4">
				<input onkeyup="filterLoad()" autocomplete="off" type="text" id="changeName" class="form-control form-control-sm" placeholder="Cari nama">
			</div>
			<div class="col-3">
				<select onchange="filterLoad()" class="form-control form-control-sm" id="changeCategory">
					<option value="">.:Semua Kategori:.</option>
					<option value="LOW">Ringan</option>
					<option value="MEDIUM">Sedang</option>
					<option value="HIGH">Berat</option>
					<option value="TOP">Sangat Berat</option>
				</select>
			</div>
		</div>
		<div class="row mt-3" id="show-punishment" style="max-height: 63.1vh; overflow: auto"></div>
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
<?php $this->load->view('punishment/js-punishment'); ?>

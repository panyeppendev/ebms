<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
		<div class="row justify-content-end mb-3">
			<div class="col-2">
				<select class="form-control form-control-sm" id="change-type" onchange="loadData()">
					<option value="">.:Jenis:.</option>
					<option value="OBLIGATION">Kewajiban</option>
					<option value="PROHIBITION">Larangan</option>
					<option value="PENALTY">Hukuman</option>
				</select>
			</div>
			<div class="col-2">
				<select class="form-control form-control-sm" id="change-category" onchange="loadData()">
					<option value="">.:Kategori:.</option>
					<option value="LOW">Ringan</option>
					<option value="MEDIUM">Sedang</option>
					<option value="HIGH">Berat</option>
					<option value="TOP">Sangat Berat</option>
				</select>
			</div>
			<div class="col-2">
				<button type="button" class="btn btn-sm btn-primary btn-block" onclick="beforeShowModal()">
					<i class="fas fa-plus-circle"></i> Tambah Data
				</button>
			</div>
		</div>
		<div class="row" id="load-data"></div>
    </section>
    <!-- /.content -->
</div>

<!--MODAL ADD DATA-->
<div class="modal fade" id="modal-constitution" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-default">
		<div class="modal-content">
			<div class="modal-header py-2">
				<h6 class="modal-title">Form Tambah Data</h6>
			</div>
			<div class="modal-body">
				<form id="form-add" autocomplete="off">
					<input type="hidden" id="method" name="method" value="ADD">
					<input type="hidden" id="id" name="id" value="0">
					<div class="row">
						<div class="col-5">
							<div class="form-group">
								<label for="type">Jenis</label>
								<select class="form-control" id="type" name="type">
									<option value="">.:Jenis:.</option>
									<option value="OBLIGATION">Kewajiban</option>
									<option value="PROHIBITION">Larangan</option>
									<option value="PENALTY">Hukuman</option>
								</select>
							</div>
						</div>
						<div class="col-5">
							<label for="category">Kategori</label>
							<select class="form-control" name="category" id="category">
								<option value="">.:Kategori:.</option>
								<option value="LOW">Ringan</option>
								<option value="MEDIUM">Sedang</option>
								<option value="HIGH">Berat</option>
								<option value="TOP">Sangat Berat</option>
							</select>
						</div>
						<div class="col-2">
							<label for="category">Ayat</label>
							<input type="number" class="form-control" name="clause" id="clause">
						</div>
					</div>
					<div class="row">
					<div class="col-12">
						<div class="form-group mb-2">
							<label>Uraian</label>
							<textarea name="name" id="name" cols="30" rows="4" class="form-control"></textarea>
						</div>
					</div>
				</div>
				</form>
			</div>
			<div class="modal-footer justify-content-between p-2">
				<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Tutup</button>
				<button onclick="save()" type="button" class="btn btn-primary btn-sm">Simpan</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('constitution/js-constitution'); ?>

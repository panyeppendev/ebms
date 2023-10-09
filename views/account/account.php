<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content p-3">
		<div class="row justify-content-between mb-4">
			<div class="col-3">
				<h6 class="card-title mt-1">Rekening</h6>
			</div>
			<div class="col-9">
				<div class="row justify-content-end">
					<div class="col-3">
						<select name="change_category" id="change-category" class="form-control form-control-sm" onchange="accounts()">
							<option value="">:Kategori:</option>
							<option value="PACKAGE">Paket</option>
							<option value="ADDON">Tambahan</option>
							<option value="OTHER">Lain-lain</option>
						</select>
					</div>
					<div class="col-3">
						<button data-toggle="modal" data-target="#modal-account" type="button" class="btn btn-sm btn-primary btn-block" id="adduser">
							<i class="fa fa-plus-circle"></i>
							Tambah Rekening
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="show-account"></div>
	</section>
</div>

<div class="modal fade" id="modal-account" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-default">
		<div class="modal-content">
			<div class="modal-header py-2">
				<h6 class="modal-title">Form Tambah Akun</h6>
				<div class="align-middle" data-dismiss="modal" title="Tutup" style="cursor: pointer">
					<i class="fas fa-times-circle text-danger"></i>
				</div>
			</div>
			<div class="modal-body">
				<div class="callout callout-warning mb-4">
					<h5>PERHATIAN!</h5>
					<ul>
						<li><b>Rekening</b> hanya disimpan sekali. Selanjutnya tidak bisa dihapus</li>
						<li>Selain <b>nama rekening</b> bisa diedit</li>
					</ul>
				</div>
				<form autocomplete="off" id="form-role">
					<input type="hidden" name="id" id="id" value="">
					<div class="form-group">
						<label for="category">Kategori</label>
						<select name="category" id="category" class="form-control form-control-border">
							<option value="">:Pilih:</option>
							<option value="PACKAGE">Paket</option>
							<option value="ADDON">Tambahan</option>
							<option value="OTHER">Lain-lain</option>
						</select>
						<small class="text-danger errors" id="error-category"></small>
					</div>
					<div class="form-group">
						<label for="name">Nama</label>
						<input type="text" name="name" class="form-control form-control-border text-uppercase" id="name">
						<small class="text-danger errors" id="error-name"></small>
					</div>
					<div class="form-group">
						<label for="nominal">Nominal Bawaan</label>
						<input type="text" name="nominal" class="form-control form-control-border" id="nominal">
						<small class="text-danger errors" id="error-nominal"></small>
					</div>
				</form>
			</div>
			<div class="modal-footer justify-content-end p-2">
				<button type="button" class="btn btn-primary btn-sm" id="save-account">Simpan</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('account/js-account'); ?>

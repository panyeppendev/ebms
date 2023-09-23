<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content p-3">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header py-2 pr-2">
						<h6 class="card-title mt-1">Data Role</h6>
						<button data-toggle="modal" data-target="#modal-role" type="button" class="btn btn-sm btn-primary float-right" id="adduser">
							<i class="fa fa-plus-circle"></i>
							Tambah Role
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="show-role"></div>
	</section>
</div>

<div class="modal fade" id="modal-role" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-default">
		<div class="modal-content">
			<div class="modal-header py-2">
				<h6 class="modal-title">Form Tambah Role</h6>
				<div class="align-middle" data-dismiss="modal" title="Tutup" style="cursor: pointer">
					<i class="fas fa-times-circle text-danger"></i>
				</div>
			</div>
			<div class="modal-body">
				<form autocomplete="off" id="form-role">
					<div class="form-group">
						<label for="name">Nama</label>
						<input type="text" name="name" class="form-control form-control-border text-uppercase" id="name">
						<small class="text-danger errors" id="error-name"></small>
					</div>
				</form>
			</div>
			<div class="modal-footer justify-content-end p-2">
				<button type="button" class="btn btn-primary btn-sm" id="save-role">Simpan</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('role/js-role'); ?>

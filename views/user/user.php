<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header py-2 pr-2">
                        <h6 class="card-title mt-1">Data Pengguna</h6>
                        <button data-toggle="modal" data-target="#modal-user" type="button" class="btn btn-sm btn-primary float-right" id="adduser">
                            <i class="fa fa-plus-circle"></i>
                            Tambah Pengguna
                        </button>
                        <select onchange="getdata()" id="changeStatus" class="form-control form-control-sm float-right mr-2" style="width: 150px">
                            <option value="">..:Semua Status:..</option>
                            <option value="ACTIVE">AKTIF</option>
                            <option value="INACTIVE">NON AKTIF</option>
                        </select>
                        <select onchange="getdata()" id="changeRole" class="form-control form-control-sm float-right mr-2" style="width: 150px">
                            <option value="">..:Semua Akses:..</option>
							<?php
							if ($roles) {
								foreach ($roles as $role) {
									?>
									<option value="<?= $role->id ?>"><?= $role->name ?></option>
									<?php
								}
							}
							?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="showuser"></div>
    </section>
</div>

<div class="modal fade" id="modal-user" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-default">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Form Tambah Pengguna</h6>
                <div class="align-middle" data-dismiss="modal" title="Tutup" style="cursor: pointer">
                    <i class="fas fa-times-circle text-danger"></i>
                </div>
            </div>
            <div class="modal-body">
                <form autocomplete="off" id="formuser">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" class="form-control form-control-border text-uppercase" id="name">
                        <small class="text-danger errors" id="errorname"></small>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control form-control-border" id="password">
                        <small class="text-danger errors" id="errorpassword"></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="password_confirmation">Password Konfirmasi</label>
                        <input type="password" name="password_confirmation" class="form-control form-control-border" id="password_confirmation">
                        <small class="text-danger errors" id="errorpassword_confirmation"></small>
                    </div>
                    <div class="mb-2 d-flex justify-content-end" id="showPassword" style="cursor: pointer">
                        <small>
                            <span>Tampilkan Password</span> <i class="fa fa-eye"></i>
                        </small>
                    </div>
                    <div class="mb-2 d-none justify-content-end" id="hidePassword" style="cursor: pointer">
                        <small>
                            <span>Sembunyikan Password</span> <i class="fa fa-eye-slash"></i>
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-end p-2">
                <button type="button" class="btn btn-primary btn-sm" id="saveuser">Simpan</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-set" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-default">
		<div class="modal-content">
			<div class="modal-header py-2">
				<h6 class="modal-title">Form Atur Akses</h6>
				<div class="align-middle" data-dismiss="modal" title="Tutup" style="cursor: pointer">
					<i class="fas fa-times-circle text-danger"></i>
				</div>
			</div>
			<div class="modal-body">
				<form autocomplete="off" id="form-set">
					<input type="hidden" name="user" id="user-id" value="">
					<div class="form-group">
						<label for="role">Jabatan</label>
						<select name="role" id="role" class="form-control form-control-border">
							<option value="">..:Pilih:..</option>
							<?php
							if ($roles) {
								foreach ($roles as $role) {
									?>
									<option value="<?= $role->id ?>"><?= $role->name ?></option>
									<?php
								}
							}
							?>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer justify-content-end p-2">
				<button type="button" class="btn btn-primary btn-sm" onclick="saveSetRole()">Simpan</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('user/js-user'); ?>

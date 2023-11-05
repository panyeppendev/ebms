<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row mb-3 justify-content-between">
            <div class="col-4">
                <h5>Tambah Tabungan</h5>
            </div>
            <div class="col-3">
                <a href="<?= base_url() ?>deposit" class="btn btn-sm btn-default btn-block">
                    <i class="fa fa-undo"></i>
                    Data Tabungan
                </a>
            </div>
        </div>
        <div class="row">
			<div class="col-4">
				<div class="card mb-0">
					<div class="card-body">
						<input autofocus data-inputmask="'mask' : '999999999999999'" data-mask="" type="text" class="form-control" name="id" id="id" autocomplete="off">
						<small class="text-info">Masukkan NIS lalu ENTER</small>
						<form id="form-deposit" class="mt-2" autocomplete="off">
							<input type="hidden" name="nis" id="nis" value="0">
							<input type="hidden" name="nominal" id="nominal-real" value="0">
							<input readonly type="text" class="form-control" id="nominal">
						</form>
					</div>
					<div class="card-footer">
						<button class="btn btn-primary btn-block" onclick="saveButton()">
							Simpan
						</button>
					</div>
				</div>
			</div>
			<div class="col-8" id="show-check"></div>
		</div>
    </section>
    <!-- /.content -->
</div>

<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('deposit/create/js-deposit-create'); ?>

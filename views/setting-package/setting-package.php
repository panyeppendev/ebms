<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content p-3">
		<div class="row">
			<div class="col-12">
				<div class="row justify-content-between">
					<div class="col-3">
						<h4 class="card-title mt-1">Daftar Paket</h4>
					</div>
					<div class="col-9">
						<div class="row justify-content-end">
							<div class="col-2">
								<select id="change-package" class="form-control form-control-sm" onchange="packageSelected(this)">
									<?php
									if ($packages){
										foreach ($packages as $package) {
											?>
											<option value="<?= $package->id ?>"><?= $package->name ?></option>
									<?php
										}
									}
									?>
								</select>
							</div>
							<div class="col-2">
								<form action="<?= base_url() ?>settingpackage/create" id="form-create" method="post">
									<input type="hidden" name="package" id="package-selected" value="">
								</form>
								<button type="button" class="btn btn-sm btn-primary btn-block" onclick="createLimit()">
									<i class="fa fa-plus-circle"></i>
									Atur Limit
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-3" id="show-package"></div>
	</section>
</div>

<div class="modal fade" id="modal-package">
	<div class="modal-dialog modal-default">
		<div class="modal-content">
			<div class="modal-header py-2">
				<h6 class="modal-title">Detil Paket</h6>
			</div>
			<div class="modal-body p-0" id="show-detail">

			</div>
			<div class="modal-footer justify-content-end p-2">
				<button data-dismiss="modal" title="Tutup" type="button" class="btn btn-primary btn-sm px-4" >Tutup</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('setting-package/js-setting-package'); ?>

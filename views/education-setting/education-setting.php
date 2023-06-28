<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body py-3">
                        <div class="row">
                            <div class="col-4">
								<h6 class="mb-0 mt-1">Atur Wali Kelas</h6>
							</div>
                            <div class="col-8">
                                <div class="row justify-content-end">
									<div class="col-6">
										<select id="change-level" class="form-control form-control-sm">
											<option value="">.:Pilih Tingkat:.</option>
											<option value="I'dadiyah">I'dadiyah</option>
											<option value="Ula">Ula</option>
											<option value="Wustho">Wustho</option>
											<option value="Ulya">Ulya</option>
										</select>
									</div>
									<div class="col-6">
										<form id="form-set" method="post" action="<?= base_url() ?>educationsetting/set">
											<input type="hidden" name="level" id="level" value="">
										</form>
										<button type="button" class="btn btn-sm btn-primary btn-block" onclick="setLevel()">
											Atur
										</button>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<div class="col-6">
				<div class="card">
					<div class="card-body py-3">
						<div class="row">
							<div class="col-4">
								<h6 class="mb-0 mt-1">Atur Murid Kelas</h6>
							</div>
							<div class="col-8">
								<div class="row justify-content-end">
									<div class="col-6">
										<select id="change-level-murid" class="form-control form-control-sm">
											<option value="">.:Pilih Tingkat:.</option>
											<option value="I'dadiyah">I'dadiyah</option>
											<option value="Ula">Ula</option>
											<option value="Wustho">Wustho</option>
											<option value="Ulya">Ulya</option>
										</select>
									</div>
									<div class="col-6">
										<form id="form-murid-set" method="post" action="<?= base_url() ?>educationsetting/setmurid">
											<input type="hidden" name="level" id="level-murid" value="">
										</form>
										<button type="button" class="btn btn-sm btn-primary btn-block" onclick="setMurid()">
											Atur
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
    </section>
    <!-- /.content -->
</div>

<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('education-setting/js-education-setting'); ?>

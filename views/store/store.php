<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
            <div class="row">
                <section class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">Atur Tarif</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <form id="form-store" autocomplete="off">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">Pangkas Rambut</label>
                                            <div class="col-sm-5">
                                                <input type="text" name="name[BARBER]" class="form-control indonesian-currency">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">Jarak Dekat</label>
                                            <div class="col-sm-5">
                                                <input type="text" name="name[SHORT]" class="form-control indonesian-currency">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-0">
                                            <label class="col-sm-7 col-form-label">Jarak Jauh</label>
                                            <div class="col-sm-5">
                                                <input type="text" name="name[LONG]" class="form-control indonesian-currency">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-4" id="load-data"></div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary btn-block" onclick="save()">
                                Simpan Tarif Baru
                            </button>
                        </div>
                    </div>

					<div class="card">
						<div class="card-header">
							<h6 class="card-title">Atur Masa Izin Jarak Dekat</h6>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-5">
									<div class="callout callout-success py-2" id="show-term">

									</div>
								</div>
								<div class="col-4">
									<div class="input-group mb-3">
										<input type="number" class="form-control" name="minute" id="minute">
										<div class="input-group-append">
											<span class="input-group-text">
												Menit
											</span>
										</div>
									</div>
								</div>
								<div class="col-3">
									<button class="btn btn-primary btn-block" onclick="saveSettingTerm()">Simpan</button>
								</div>
							</div>
						</div>
					</div>
                </section>

                <section class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">Alasan Izin</h6>
                        </div>
                        <div class="card-body">
                            <form autocomplete="off" id="form-reason">
                                <div class="row">
                                    <div class="col-9">
                                        <div class="form-group">
                                            <label for="reason">Alasan</label>
                                            <input type="text" name="reason" class="form-control" id="reason">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="type">Jenis</label>
                                            <select name="type" id="type" class="form-control">
                                                <option value="">.:Pilih:.</option>
                                                <option value="SHORT">Dekat</option>
                                                <option value="LONG">Jauh</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <button class="btn btn-primary btn-block" onclick="saveReason()">
                                        Simpan Alasan Baru
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card mb-0">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-9">
                                                    <h6>Daftar Alasan</h6>
                                                </div>
                                                <div class="col-3">
                                                    <select onchange="loadReason()" id="changeType" class="form-control form-control-sm">
                                                        <option value="">.:Pilih:.</option>
                                                        <option value="SHORT">Dekat</option>
                                                        <option value="LONG">Jauh</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body" style="max-height: 50vh; overflow-y: auto;" id="load-reason"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
    </section>
    <!-- /.content -->
</div>

<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('store/js-store'); ?>

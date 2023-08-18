<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
            <div class="row">
                <section class="col-6">
					<div class="card">
						<div class="card-header">
							<h6 class="card-title">Print Data Pendaftar Pangkas</h6>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-8">
									<input type="date" class="form-control" name="date" id="date">
								</div>
								<div class="col-4">
									<button class="btn btn-primary btn-block" onclick="printBarber()">Print Out</button>
								</div>
							</div>
						</div>
					</div>
                </section>

                <section class="col-6">
					<div class="card">
						<div class="card-header">
							<h6 class="card-title">Print Data Pelanggaran</h6>
						</div>
						<div class="card-body">
							<div class="row">
								<input type="hidden" id="start-date" value="0">
								<input type="hidden" id="end-date" value="0">
								<div class="col-8">
									<input type="text" class="form-control form-control-sm reservation" id="reservation" placeholder="Semua waktu">
								</div>
								<div class="col-4">
									<button class="btn btn-primary btn-block" onclick="printPenalty()">Print Out</button>
								</div>
							</div>
						</div>
					</div>
                </section>
				<section class="col-6">
					<div class="card">
						<div class="card-header">
							<h6 class="card-title">Print Laporan Perizinan</h6>
						</div>
						<div class="card-body">
							<div class="row">
								<input type="hidden" id="start-date-perizinan" value="0">
								<input type="hidden" id="end-date-perizinan" value="0">
								<div class="col-8">
									<input type="text" class="form-control form-control-sm reservation" id="reservation-perizinan" placeholder="Semua waktu">
								</div>
								<div class="col-4">
									<button class="btn btn-primary btn-block" onclick="printPerizinan()">Print Out</button>
								</div>
							</div>
						</div>
					</div>
				</section>
				<section class="col-6">
					<div class="card">
						<div class="card-header">
							<h6 class="card-title">Print Laporan Pelanggaran</h6>
						</div>
						<div class="card-body">
							<div class="row">
								<input type="hidden" id="start-date-pelanggaran" value="0">
								<input type="hidden" id="end-date-pelanggaran" value="0">
								<div class="col-8">
									<input type="text" class="form-control form-control-sm reservation" id="reservation-pelanggaran" placeholder="Semua waktu">
								</div>
								<div class="col-4">
									<button class="btn btn-primary btn-block" onclick="printPelanggaran()">Print Out</button>
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
<script src="<?= base_url('template') ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('template') ?>/plugins/daterangepicker/daterangepicker.js"></script>
<?php $this->load->view('securityreport/js-securityreport'); ?>

<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
		<div class="row">
			<div class="col-1">
				<select id="grade" class="form-control form-control-sm" onchange="mutations()">
					<option value="">Semua</option>
					<?php
					if ($grades) {
						foreach ($grades as $grade) {
							?>
							<option value="<?= $grade->class ?>"><?= $grade->class ?></option>
							<?php
						}
					}
					?>
				</select>
			</div>
			<div class="col-1">
				<select id="level" class="form-control form-control-sm" onchange="mutations()">
					<option value="">Semua</option>
					<?php
					if ($levels) {
						foreach ($levels as $level) {
							?>
							<option value="<?= $level->level ?>"><?= $level->level ?></option>
							<?php
						}
					}
					?>
				</select>
			</div>
			<div class="col-2">
				<select id="room" class="form-control form-control-sm" onchange="mutations()">
					<option value="">Semua</option>
					<?php
					if ($rooms) {
						foreach ($rooms as $room) {
							?>
							<option value="<?= $room->name ?>"><?= $room->name ?></option>
							<?php
						}
					}
					?>
				</select>
			</div>
			<div class="col-2">
				<select id="account" class="form-control form-control-sm" onchange="mutations()">
					<?php
					if ($accounts) {
						foreach ($accounts as $account) {
							?>
							<option value="<?= $account['id'] ?>"><?= $account['name'] ?></option>
							<?php
						}
					}
					?>
					<option value="DEPOSIT">TABUNGAN</option>
				</select>
			</div>
			<div class="col-3">
				<div class="input-group">
					<div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </span>
					</div>
					<input type="text" class="form-control form-control-sm" id="reservation" placeholder="Semua waktu">
				</div>
			</div>
			<div class="col-1">
				<form action="<?= base_url() ?>report/exportMutation" method="post" target="_blank">
					<input type="hidden" id="grade-selected" name="grade" value="">
					<input type="hidden" id="level-selected" name="level" value="">
					<input type="hidden" id="room-selected" name="room" value="">
					<input type="hidden" id="account-selected" name="account" value="">
					<input type="hidden" id="start" value="" name="start">
					<input type="hidden" id="end" value="" name="end">
					<button type="submit" class="btn btn-block btn-sm btn-default">
						<i class="fas fa-file-pdf"></i> Export
					</button>
				</form>
			</div>
			<div class="col-2">
				<a href="<?= base_url() ?>report" class="btn btn-primary btn-sm btn-block">
					<i class="fas fa-undo"></i> Halaman Awal
				</a>
			</div>
		</div>
		<div class="row mt-4" id="show-data"></div>
	</section>
	<!-- /.content -->
</div>

<?php $this->load->view('partials/footer'); ?>
<script src="<?= base_url('template') ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('template') ?>/plugins/daterangepicker/daterangepicker.js"></script>
<?php $this->load->view('report/mutation/js-report-mutation'); ?>

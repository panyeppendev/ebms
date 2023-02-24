<div class="row">
    <?php if ($data['status'] !== 200) { ?>
        <div class="col-12">
            <h5 class="text-danger text-center">
                <?= $data['message'] ?>
            </h5>
        </div>
    <?php } else { ?>
        <div class="col-3 pr-0">
            <div class="card">
                <div class="card-body p-0">
                    <?php
                    $avatarPath = FCPATH . 'assets/avatars/' . $data['student']->id . '.jpg';

                    if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
                        $avatar = base_url('assets/avatars/default.jpg');
                    } else {
                        $avatar = base_url('assets/avatars/' . $data['student']->id . '.jpg');
                    }
                    ?>
                    <img src="<?= $avatar ?>" alt="IMAGE OF" style="width: 100%; border-radius: 3px;">
                </div>
            </div>
        </div>
        <div class="col-5 py-0 px-3" style="background-color: #f4f6f9">
            <div class="callout callout-success py-1 px-3">
                <b>DATA DIRI</b>
            </div>
			<div class="pl-3 mb-3">
				<span class="text-black"> - <?= $data['student']->id ?></span> <br>
				<span class="font-weight-bold"> - <?= $data['student']->name ?></span> <br>
				<span class="text-black"> - <?= $data['student']->place_of_birth . ', ' . dateIDFormat($data['student']->date_of_birth) ?></span> <br>
				<span class="text-black"> - <?= $data['student']->village . ', ' . str_replace(['Kabupaten ', 'Kota '], '', $data['student']->city) ?></span> <br>
				<span class="text-black"> - <?= $data['student']->domicile ?></span> <br>
				<span class="text-black"> - <?= $data['student']->father ?></span> <br>
			</div>
			<div class="row">
				<section class="col-6">
					<div class="callout callout-success py-1 px-3 mb-2">
						<b>Paket <?= isset($data['package']['info']['package']) ? $data['package']['info']['package'] : '' ?></b>
					</div>
					<div class="card">
						<div class="card-body py-2">
							<?php
							if ($data['package']['status'] === 200) {
							?>
							<div class="row">
								<div class="col-7">Saldo</div>
								<div class="col-5 text-right">
									<?= $data['package']['info']['limit'] ?>
								</div>
							</div>
							<hr class="my-1">
							<div class="row text-muted">
								<div class="col-7 pl-3">Tunai</div>
								<div class="col-5 text-right">
									<?= $data['package']['info']['cash'] ?>
								</div>
							</div>
							<hr class="my-1">
							<div class="row text-muted">
								<div class="col-7 pl-3">Non-Tunai</div>
								<div class="col-5 text-right">
									<?= $data['package']['info']['non_cash'] ?>
								</div>
							</div>
							<hr class="my-1">
							<div class="row">
								<div class="col-7">Sisa</div>
								<div class="col-5 text-right">
									<?= $data['package']['info']['residual'] ?>
								</div>
							</div>
							<?php } else { ?>
								<div class="row">
									<div class="col-12 text-center"><?= $data['package']['message'] ?></div>
								</div>
							<?php } ?>
						</div>
					</div>
				</section>
				<section class="col-6">
					<div class="callout callout-success py-1 px-3 mb-2">
						<b>Tabungan</b>
					</div>
					<div class="card">
						<div class="card-body py-2">
							<div class="row">
								<div class="col-7">Setoran</div>
								<div class="col-5 text-right">
									<?= $data['deposit']['kredit'] ?>
								</div>
							</div>
							<hr class="my-1">
							<div class="row text-muted">
								<div class="col-7 pl-3">Tunai</div>
								<div class="col-5 text-right">
									<?= $data['deposit']['cash'] ?>
								</div>
							</div>
							<hr class="my-1">
							<div class="row text-muted">
								<div class="col-7 pl-3">Non-Tunai</div>
								<div class="col-5 text-right">
									<?= $data['deposit']['non_cash'] ?>
								</div>
							</div>
							<hr class="my-1">
							<div class="row">
								<div class="col-7">Saldo</div>
								<div class="col-5 text-right">
									<?= $data['deposit']['residual'] ?>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
        </div>
    <?php } ?>
</div>

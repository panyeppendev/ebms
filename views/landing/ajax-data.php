<div class="row">
    <?php if ($data['status'] !== 200) { ?>
        <div class="col-12">
            <h5 class="text-danger text-center">
                <?= $data['message'] ?>
            </h5>
        </div>
    <?php } else { ?>
        <div class="col-5">
			<?php
			$avatarPath = FCPATH . 'assets/avatars/' . $data['student']->id . '.jpg';

			if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
				$avatar = base_url('assets/avatars/default.jpg');
			} else {
				$avatar = base_url('assets/avatars/' . $data['student']->id . '.jpg');
			}
			?>
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-12 text-center">
							<img src="<?= $avatar ?>" alt="IMAGE OF" style="border-radius: 3px; width: 42%">
							<hr class="mt-4">
						</div>
						<div class="col-12">
							<div class="pl-3">
								<span class="text-black"> - <?= $data['student']->id ?></span> <br>
								<span class="font-weight-bold"> - <?= $data['student']->name ?></span> <br>
								<span class="text-black"> - <?= $data['student']->place_of_birth . ', ' . dateIDFormat($data['student']->date_of_birth) ?></span> <br>
								<span class="text-black"> - <?= $data['student']->village . ', ' . str_replace(['Kabupaten ', 'Kota '], '', $data['student']->city) ?></span> <br>
								<span class="text-black"> - <?= $data['student']->domicile ?></span> <br>
								<span class="text-black"> - <?= $data['student']->father ?></span> <br>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
		<div class="col-7">
			<div class="row">
				<div class="col-6">
					<div class="card">
						<div class="card-body pb-2">
							<div class="callout callout-success py-3 px-3 mb-4">
								<b>Paket <?= isset($data['package']['info']['package']) ? $data['package']['info']['package'] : '' ?></b>
							</div>
							<?php
							if ($data['package']['status'] === 200) {
								?>
								<div class="row">
									<div class="col-7">Saldo</div>
									<div class="col-5 text-right">
										<?= number_format($data['package']['info']['limit'], 0, ',', '.') ?>
									</div>
								</div>
								<hr class="my-1">
								<div class="row">
									<div class="col-7">Tunai</div>
									<div class="col-5 text-right">
										<?= number_format($data['package']['info']['cash'], 0, ',', '.') ?>
									</div>
								</div>
								<hr class="my-1">
								<div class="row">
									<div class="col-7">Non-tunai</div>
									<div class="col-5 text-right">
										<?= number_format($data['package']['info']['non_cash'], 0, ',', '.') ?>
									</div>
								</div>
								<hr class="my-1">
								<?php
									if ($data['package']['info']['detail']) {
										$text = [
											'POCKET_CANTEEN' => 'Kantin',
											'POCKET_STORE' => 'Koperasi',
											'POCKET_LIBRARY' => 'Perpustakaan',
											'POCKET_SECURITY' => 'KAMTIB',
											'POCKET_BARBER' => 'Pangkas Rambut'
										];

										foreach ($data['package']['info']['detail'] as $item) {
								?>
										<div class="row text-danger text-sm">
											<div class="col-7 pl-3">
												- <?= $text[$item['status']] ?>
											</div>
											<div class="col-5 text-right">
												<?= number_format($item['total'], 0, ',', '.') ?>
											</div>
										</div>
										<hr class="my-1">
								<?php
										}
									}
								?>
								<div class="row text-success font-weight-bold">
									<div class="col-7">Sisa</div>
									<div class="col-5 text-right">
										<?= number_format($data['package']['info']['residual'], 0, ',', '.') ?>
									</div>
								</div>
							<?php } else { ?>
								<div class="row">
									<div class="col-12 text-center"><?= $data['package']['message'] ?></div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="col-6">
					<div class="card">
						<div class="card-body pb-2">
							<div class="callout callout-success py-3 px-3 mb-4">
								<b>Tabungan</b>
							</div>
							<div class="row">
								<div class="col-7">Setoran</div>
								<div class="col-5 text-right">
									<?= number_format($data['deposit']['kredit'], 0, ',', '.') ?>
								</div>
							</div>
							<hr class="my-1">
							<div class="row">
								<div class="col-7">Tunai</div>
								<div class="col-5 text-right">
									<?= number_format($data['deposit']['cash'], 0, ',', '.') ?>
								</div>
							</div>
							<hr class="my-1">
							<div class="row">
								<div class="col-7">Non-Tunai</div>
								<div class="col-5 text-right">
									<?= number_format($data['deposit']['non_cash'], 0, ',', '.') ?>
								</div>
							</div>
							<hr class="my-1">
							<?php
							if ($data['deposit']['detail']) {
								$text = [
										'DEPOSIT_CANTEEN' => 'Kantin',
										'DEPOSIT_STORE' => 'Koperasi',
										'DEPOSIT_LIBRARY' => 'Perpustakaan',
										'DEPOSIT_SECURITY' => 'KAMTIB',
										'DEPOSIT_BARBER' => 'Pangkas Rambut'
								];

								foreach ($data['deposit']['detail'] as $item) {
									?>
									<div class="row text-danger text-sm">
										<div class="col-7 pl-3">
											- <?= $text[$item['status']] ?>
										</div>
										<div class="col-5 text-right">
											<?= number_format($item['total'], 0, ',', '.') ?>
										</div>
									</div>
									<hr class="my-1">
									<?php
								}
							}
							?>
							<div class="row">
								<div class="col-7">Saldo</div>
								<div class="col-5 text-right">
									<?= number_format($data['deposit']['residual'], 0, ',', '.') ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    <?php } ?>
</div>

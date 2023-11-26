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
								<span class="text-black"> - <?= $data['student']->class.', '.$data['student']->level ?></span> <br>
								<span class="text-black"> - <?= $data['student']->class_of_formal.', '.$data['student']->level_of_formal ?></span> <br>
								<span class="text-black"> - <?= $data['student']->father ?></span> <br>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
		<div class="col-7">
			<div class="row">
				<section class="col-6">
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-body pb-2">
									<?php
									if ($data['purchase']['status']) {
										?>
										<div class="callout callout-success py-3 px-3 mb-4">
											<b>Saat ini: Paket <?= $data['purchase']['package'] ?></b>
										</div>
										<div class="row">
											<div class="col-7">Saldo Awal</div>
											<div class="col-5 text-right">
												<?= number_format($data['purchase']['income'], 0, ',', '.') ?>
											</div>
										</div>
										<hr class="my-1">
										<div class="row text-danger text-sm">
											<div class="col-7 pl-3">
												- Pencairan tunai
											</div>
											<div class="col-5 text-right">
												<?= number_format($data['purchase']['cash'], 0, ',', '.') ?>
											</div>
										</div>
										<div class="row text-danger text-sm">
											<div class="col-7 pl-3">
												- Pencairan Non-tunai
											</div>
											<div class="col-5 text-right">
												<?= number_format($data['purchase']['credit'], 0, ',', '.') ?>
											</div>
										</div>
										<hr class="my-1">
										<div class="row">
											<div class="col-7">Total pencairan</div>
											<div class="col-5 text-right">
												<?= number_format($data['purchase']['disbursement'], 0, ',', '.') ?>
											</div>
										</div>
										<hr class="my-1">
										<div class="row text-success font-weight-bold">
											<div class="col-7">Saldo uang saku</div>
											<div class="col-5 text-right">
												<?= number_format($data['purchase']['balance'], 0, ',', '.') ?>
											</div>
										</div>
									<?php } else { ?>
										<div class="row">
											<div class="col-12 text-center">Tidak ada data untuk ditampilkan</div>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="col-12">
							<div class="card">
								<div class="card-body pb-2">
									<div class="callout callout-success py-3 px-3 mb-4">
										<b>Tabungan</b>
									</div>
									<div class="row">
										<div class="col-7">Saldo Awal</div>
										<div class="col-5 text-right">
											<?= number_format($data['deposit']['credit'], 0, ',', '.') ?>
										</div>
									</div>
									<hr class="my-1">
									<div class="row text-danger text-sm">
										<div class="col-7 pl-3">
											- Pencairan tunai
										</div>
										<div class="col-5 text-right">
											<?= number_format($data['deposit']['cash'], 0, ',', '.') ?>
										</div>
									</div>
									<div class="row text-danger text-sm">
										<div class="col-7 pl-3">
											- Pencairan Non-tunai
										</div>
										<div class="col-5 text-right">
											<?= number_format($data['deposit']['debit'], 0, ',', '.') ?>
										</div>
									</div>
									<hr class="my-1">
									<div class="row">
										<div class="col-7">Total pencairan</div>
										<div class="col-5 text-right">
											<?= number_format($data['deposit']['total'], 0, ',', '.') ?>
										</div>
									</div>
									<hr class="my-1">
									<div class="row text-success font-weight-bold">
										<div class="col-7">Saldo uang saku</div>
										<div class="col-5 text-right">
											<?= number_format($data['deposit']['balance'], 0, ',', '.') ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</section>
				<section class="col-6">
					<div class="card">
						<div class="card-body pb-2">
							<div class="callout callout-success py-3 px-3 mb-4">
								<b>Absensi Madrasah</b>
							</div>
							<?php
							if ($data['presence']){
								$total = 0;
								foreach ($data['presence'] as $d) {
									$total += $d->amount;
							?>
							<div class="row">
								<div class="col-7"><?= strtoupper($d->presence) ?></div>
								<div class="col-5 text-right">
									<?= $d->amount ?>
								</div>
							</div>
							<hr class="my-1">
								<?php
							}
								?>
							<div class="row">
								<div class="col-7">Jumlah</div>
								<div class="col-5 text-right">
									<?= $total ?>
								</div>
							</div>
							<?php
							}
							?>
						</div>
					</div>
				</section>
			</div>
		</div>
    <?php } ?>
</div>

<div class="row">
    <?php if ($data['status'] !== 200) { ?>
        <div class="col-12">
            <h5 class="text-danger text-center">
                <?= $data['message'] ?>
            </h5>
        </div>
    <?php } else { ?>
		<div class="col-4">
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
							<img src="<?= $avatar ?>" alt="IMAGE OF" style="border-radius: 3px; width: 65%">
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
		<div class="col-8">
			<div class="row text-sm">
				<div class="col-8">
					<?php
					$text = [
							'LOW' => 'Ringan',
							'MEDIUM' => 'Sedang',
							'HIGH' => 'Berat',
							'TOP' => 'Sangat Berat',
					];
					if ($data['punishment']) {
					foreach ($data['punishment'] as $item) {
						?>
						<div class="card">
							<div class="card-header">
								<div class="row justify-content-between">
									<div class="col-8">
										Lima Pelanggaran <?= $text[$item['category']] ?> Terakhir
									</div>
									<div class="col-4 text-right">
										Total <span class="badge badge-primary"><?= $item['count'] ?></span>
									</div>
								</div>
							</div>
							<div class="card-body py-2">
								<span>
									<?php
									if ($item['detail']) {
										foreach ($item['detail'] as $punishment) {
											?>
											- <?= $punishment->name ?> <br>
											<?php
										}
									}
									?>
								</span>
							</div>
						</div>
						<?php
					}
				}else {
					?>
					<div class="card">
						<div class="card-body">
							<h5>Belum ada pelanggaran</h5>
						</div>
					</div>
					<?php
				}
					?>
				</div>
				<div class="col-4">
					<div class="card">
						<div class="card-body pb-2">
							<div class="callout callout-success py-2 px-3 mb-4">
								<b>Perizinan</b>
							</div>
							<?php
							$textPermission = ['LONG' => 'jauh', 'SHORT' => 'dekat'];
							if ($data['permission']) {
								foreach ($data['permission'] as $item) {
							?>
									<div class="row">
										<div class="col-7">Jarak <?= $textPermission[$item->type] ?></div>
										<div class="col-5 text-right">
											<b><?= $item->total ?></b> kali
										</div>
									</div>
									<hr class="my-1">
							<?php
								}
							}
							if ($data['reason']) {
								foreach ($data['reason'] as $item) {
							?>
								<div class="row">
									<div class="col-10 pl-3">- <?= $item->reason ?></div>
									<div class="col-2 text-right"><?= $item->total ?></div>
								</div>
							<?php
								}
							}
							?>
						</div>
					</div>
					<div class="card">
						<div class="card-body pb-3">
							<div class="callout callout-danger py-2 px-3 mb-3">
								<b>Skorsing</b>
							</div>
							<div class="row">
								<div class="col-12">
									<?php
										if ($data['suspension']) {
									?>
											Sedang dalam masa skorsing dan akan berakhir pada : <br>
											<?= dateDisplayWithDay($data['suspension']->expired_at) ?>
									<?php
										}else{
											echo 'Tidak dalam masa skorsing';
										}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    <?php } ?>
</div>

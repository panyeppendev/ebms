<div class="row">
    <?php
    if ($data['student']) {
        $avatarPath = FCPATH . 'assets/avatars/' . $data['student']->id . '.jpg';

        if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
            $avatar = base_url('assets/avatars/default.jpg');
        } else {
            $avatar = base_url('assets/avatars/' . $data['student']->id . '.jpg');
        }

        $city = str_replace(['Kabupaten', 'Kota'], '', $data['student']->city);
    ?>
        <div class="col-12">
            <div class="row">
                <div class="col-8">
                    <b><?= $data['student']->name ?></b> <br>
                    <hr class="my-2">
                    <?= $data['student']->village ?>, <?= $city ?> <br>
                    <?= $data['student']->domicile ?> <br>
                    <?= $data['student']->class ?> - <?= $data['student']->level ?> <br>
                    <?= $data['student']->class_of_formal ?> - <?= $data['student']->level_of_formal ?>
                    <hr class="my-2">
					<?php
						if ((int)$data['status'] === 200) {
					?>
						<dl>
							<dt>Pelanggaran</dt>
							<dd><?= $data['constitution'] ?></dd>
							<dt>Tindakan</dt>
							<?php
							if ($data['penalty']) {
								foreach ($data['penalty'] as $penalty) {
									?>
									<dd>- <?= $penalty->name ?></dd>
									<?php
								}
							}else{
								echo '<dd class="text-danger">Data tindakan tidak ditemukan</dd>';
							}
							?>
						</dl>
						<div class="callout callout-success">
							<h6>Potensi kelipatan pelanggaran</h6>
							<?php
							if ($data['punishment']) {
								$text = [
										'LOW' => 'Ringan',
										'MEDIUM' => 'Sedang',
										'HIGH' => 'Berat',
										'TOP' => 'Sangat Berat'
								];
								foreach ($data['punishment'] as $item) {
									?>
									<div class="row">
										<div class="col-8">- <?= $text[$item->category] ?></div>
										<div class="col-4"><?= $item->total ?></div>
									</div>
									<?php
								}
							} else {
								?>
								<div class="row">
									<div class="col-12 text-center">Tidak ada pelanggaran</div>
								</div>
								<?php
							}
							?>
						</div>
					<?php
						} else {
					?>
						<div class="callout callout-danger">
							<h6>Proses gagal..!! Pesan error:</h6>
							<p><?= $data['message'] ?></p>
						</div>
					<?php
						}
					?>
                </div>
				<div class="col-4">
					<div class="box-profile">
						<div class="text-center">
							<img src="<?= $avatar ?>" alt="IMAGE OF <?= $data['student']->name ?>" style="width: 100%; border-radius: 3px;">
						</div>
					</div>
				</div>
            </div>
        </div>
    <?php
    } else {
        echo '<span class="text-center text-danger">Data santri gagal dimuat</span>';
    }
    ?>
</div>

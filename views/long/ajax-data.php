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
                <div class="col-7 text-right">
                    <b><?= $data['student']->name ?></b> <br>
                    <hr class="my-2">
                    <?= $data['student']->village ?>, <?= $city ?> <br>
                    <?= $data['student']->domicile ?> <br>
                    <hr class="my-2">
                    <?= $data['student']->class ?> - <?= $data['student']->level ?> <br>
                    <?= $data['student']->class_of_formal ?> - <?= $data['student']->level_of_formal ?>
					<?php if ($status == 400 || $status == 401) { ?>
						<div class="callout callout-danger mt-4 text-left">
							<p>
								<span class="text-danger"> Proses tidak bisa dilanjutkan karena: </span><br> <br>
								<?= $message ?>
							</p>
							<?php if ($status == 401) { ?>
								<button class="btn btn-sm btn-primary btn-block" onclick="doEmergency(this)">Izin Darurat</button>
							<?php } ?>
						</div>
					<?php } ?>
                </div>
				<div class="col-5">
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

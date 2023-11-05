<?php
if ($data['status'] == 200) {
    $avatarPath = FCPATH . 'assets/avatars/' . $data['student']->id . '.jpg';

    if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
        $avatar = base_url('assets/avatars/default.jpg');
    } else {
        $avatar = base_url('assets/avatars/' . $data['student']->id . '.jpg');
    }

    $city = str_replace(['Kabupaten', 'Kota'], '', $data['student']->city);
?>
    <div class="row">
        <div class="col-3">
            <div class="box-profile">
                <div class="text-center">
                    <img src="<?= $avatar ?>" alt="IMAGE OF <?= $data['student']->name ?>" style="width: 100%; border-radius: 3px;">
                </div>
            </div>
        </div>
        <div class="col-9">
            <div class="row">
                <div class="col-12">
                    <dl class="row">
                        <dt class="col-sm-3 font-weight-normal mb-1">Nama</dt>
                        <dd class="col-sm-9 mb-1 font-weight-bold"><?= $data['student']->name ?></dd>
                        <dt class="col-sm-3 font-weight-normal mb-1">Alamat</dt>
                        <dd class="col-sm-9 mb-1">
                            <?= $data['student']->village ?>, <?= $city ?>
                        </dd>
                        <dt class="col-sm-3 font-weight-normal mb-1">Domisili</dt>
                        <dd class="col-sm-9 mb-1">
                            <?= $data['student']->domicile ?>
                        </dd>
                        <dt class="col-sm-3 font-weight-normal mb-1">Diniyah</dt>
                        <dd class="col-sm-9 mb-1"><?= $data['student']->class ?> - <?= $data['student']->level ?></dd>
                        <dt class="col-sm-3 font-weight-normal mb-1">Formal</dt>
                        <dd class="col-sm-9 mb-1"><?= $data['student']->class_of_formal ?> - <?= $data['student']->level_of_formal ?></dd>
                    </dl>
                </div>
				<div class="col-8 text-xs">
					<div class="callout callout-success py-2">
                <span class="text-success font-weight-bold">
                    Riwayat Tabungan
                </span>
						<hr class="my-1">
						<table style="width: 100%">
							<tbody>
							<tr>
								<td>Kredit</td>
								<td>Rp.</td>
								<td class="text-right">
									<?= number_format($data['credit'], 0, ',', '.') ?>
								</td>
							</tr>
							<tr>
								<td>Debet</td>
								<td>Rp.</td>
								<td class="text-right">
									<?= number_format($data['debit'], 0, ',', '.') ?>
								</td>
							</tr>
							<tr class="text-success font-weight-bold">
								<td>Saldo</td>
								<td>Rp.</td>
								<td class="text-right">
									<?= number_format($data['balance'], 0, ',', '.') ?>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
            </div>
        </div>
    </div>
<?php
} else {
    echo 'Gagal memuat data. Segera hubungi Developer';
}
?>

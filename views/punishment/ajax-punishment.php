<?php
    if ($punishments) {
        $text = [
            'LOW' => 'Ringan',
            'MEDIUM' => 'Sedang',
            'HIGH' => 'Berat',
			'TOP' => 'Sangat Berat'
        ];
		foreach ($punishments as $punishment) {
			$avatarPath = FCPATH . 'assets/avatars/' . $punishment->student_id . '.jpg';

			if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
				$avatar = base_url('assets/avatars/default.jpg');
			} else {
				$avatar = base_url('assets/avatars/' . $punishment->student_id . '.jpg');
			}

			$city = str_replace(['Kabupaten', 'Kota '], '', $punishment->city);

			$category = $punishment->category;
			$textCategory = [
				'LOW' => '<span class="text-black">Ringan</span>',
				'MEDIUM' => '<span class="text-primary">Sedang</span>',
				'HIGH' => '<span class="text-warning">Berat</span>',
				'TOP' => '<span class="text-danger">Sangat Berat</span>',
			];
?>
<div class="col-12">
    <div class="card mb-2" style="height: 65px;">
        <div class="card-body p-0">
            <div class="row h-100 ml-0">
                <div class="col-1 h-100 px-0">
                    <img class="h-100" src="<?= $avatar ?>" alt="IMAGE OF <?= $punishment->name ?>" style="border-top-left-radius: 0.25rem; border-bottom-left-radius: 0.25rem;">
                </div>
                <div class="col-11 pl-0">
                        <div class="row py-2">
							<div class="col-4">
                                <span class="align-middle font-weight-bold"><?= $punishment->name ?></span>
                                <br>
                                <span class="text-muted">
                                    <?= $punishment->village ?>, <?= $city ?>
                                </span>
                            </div>
							<div class="col-3">
                                <span class="align-middle">
                                    <?= $punishment->class.' - '.$punishment->level ?> |
                                    <?= $punishment->class_of_formal.' - '.$punishment->level_of_formal ?>
                                </span>
                                <br>
                                <span class="text-muted">
                                    <?= $punishment->domicile ?>
                                </span>
                            </div>
							<div class="col-5">
								<div class="row justify-content-between text-xs">
									<div class="col-7 text-muted">
										<?= datetimeIDShirtFormat($punishment->created_at) ?>
									</div>
									<div class="col-5 text-right pr-3">
										<?= $textCategory[$category] ?>
									</div>
								</div>
								<div class="row">
									<div class="col-12">
										<span class="align-middle text-xs">
											<?= $punishment->constitution ?>
										</span>
									</div>
								</div>
							</div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
<?php
    }
}else{
?>
<div class="col-12 text-center text-danger pt-5">
    <div class="mb-3">
        <i class="fas fa-exclamation fa-4x"></i>
    </div>
    <h6>Tidak ada data untuk ditampilkan</h6>
</div>
<?php
}
?>

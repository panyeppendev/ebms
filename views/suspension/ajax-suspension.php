<?php
    if ($suspensions) {
        $text = [
            'LOW' => 'Ringan',
            'MEDIUM' => 'Sedang',
            'HIGH' => 'Berat',
			'TOP' => 'Sangat Berat'
        ];
		foreach ($suspensions as $suspension) {
			$avatarPath = FCPATH . 'assets/avatars/' . $suspension->student_id . '.jpg';

			if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
				$avatar = base_url('assets/avatars/default.jpg');
			} else {
				$avatar = base_url('assets/avatars/' . $suspension->student_id . '.jpg');
			}

			$city = str_replace(['Kabupaten', 'Kota '], '', $suspension->city);

			$status = $suspension->status;
			$textStatus = [
				'INACTIVE' => '<span class="text-warning">Belum Aktif</span>',
				'ACTIVE' => '<span class="text-success">Aktif</span>',
				'DONE' => '<span class="text-danger">Selesai</span>'
			];
?>
<div class="col-12">
    <div class="card mb-2" style="height: 65px;">
        <div class="card-body p-0">
            <div class="row h-100 ml-0">
                <div class="col-1 h-100 px-0">
                    <img class="h-100" src="<?= $avatar ?>" alt="IMAGE OF <?= $suspension->name ?>" style="border-top-left-radius: 0.25rem; border-bottom-left-radius: 0.25rem;">
                </div>
                <div class="col-11 pl-0">
                        <div class="row py-2">
							<div class="col-4">
                                <span class="align-middle font-weight-bold"><?= $suspension->name ?></span>
                                <br>
                                <span class="text-muted">
                                    <?= $suspension->village ?>, <?= $city ?>
                                </span>
                            </div>
							<div class="col-3">
                                <span class="align-middle">
                                    <?= $suspension->class.' - '.$suspension->level ?> |
                                    <?= $suspension->class_of_formal.' - '.$suspension->level_of_formal ?>
                                </span>
                                <br>
                                <span class="text-muted">
                                    <?= $suspension->domicile ?>
                                </span>
                            </div>
							<div class="col-3 text-xs font-italic">
								<?php
									$now = date('Y-m-d H:i:s');
									if ($status === 'ACTIVE') {
								?>
										<span class="text-success">Sedang aktif</span>
										<br>
										<span><?= showDiffSuspension($suspension->expired_at,  $now)?></span>
								<?php
									} else {
										echo ($status === 'DONE') ? 'Selesai' : 'Belum diaktifkan';
									}
								?>
							</div>
							<div class="col-2 pr-4 pt-2">
								<?php
									if ($status === 'INACTIVE') {
								?>
									<div class="row">
										<div class="col-8">
											<select id="term" class="form-control form-control-sm">
												<option value="">.::.</option>
												<option value="30">30</option>
												<option value="60">60</option>
												<option value="90">90</option>
											</select>
										</div>
										<div class="col-4">
											<button onclick="doActive(<?= $suspension->id ?>)" class="btn btn-sm btn-primary btn-block">
												<i class="fas fa-save"></i>
											</button>
										</div>
									</div>
								<?php
									}elseif ($status === 'DONE') {
								?>
										<button class="btn btn-sm btn-default text-success btn-block">
											<i class="fas fa-check"></i> Selesai
										</button>
								<?php
									} else {
										if ($now >= $suspension->expired_at) {
								?>
											<button onclick="doDone(<?= $suspension->id ?>)" class="btn btn-sm btn-primary btn-block">
												Selesaikan
											</button>
								<?php
										} else {
								?>
											<div class="row">
												<div class="col-8">
													<div class="input-group mb-3">
														<input type="number" class="form-control form-control-sm" id="custom-day">
														<div class="input-group-append">
														<span class="input-group-text py-0 px-2">
															Hari
														</span>
														</div>
													</div>
												</div>
												<div class="col-4">
													<button onclick="doCustom(<?= $suspension->id ?>)" class="btn btn-sm btn-primary btn-block">
														<i class="fas fa-save"></i>
													</button>
												</div>
											</div>
								<?php
										}
									}
								?>
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

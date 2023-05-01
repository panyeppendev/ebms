<?php
if ($data) {
    $avatarPath = FCPATH . 'assets/avatars/' . $data->id . '.jpg';

    if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
        $avatar = base_url('assets/avatars/default.jpg');
    } else {
        $avatar = base_url('assets/avatars/' . $data->id . '.jpg');
    }

    $city = str_replace(['Kabupaten', 'Kota'], '', $data->city);
    $status = $data->status;
    if ($status == 'AKTIF') {
        $class = 'success';
    } elseif ($status == 'BERHENTI') {
        $class = 'danger';
    } else {
        $class = 'warning';
    }
?>

			<div class="col-9">
				<div class="callout callout-success py-1 px-3">
					<b>DATA DIRI</b>
				</div>
				<dl class="row mb-0">
					<dt class="col-sm-3 font-weight-normal mb-1">NIS</dt>
					<dd class="col-sm-9 mb-1">
						<?= $data->id ?>
					</dd>
					<dt class="col-sm-3 font-weight-normal mb-1">Nama</dt>
					<dd class="col-sm-9 mb-1 font-weight-bold"><?= $data->name ?></dd>
					<dt class="col-sm-3 font-weight-normal mb-1">Tetala</dt>
					<dd class="col-sm-9 mb-1"><?= $data->place_of_birth ?>, <?= dateIDFormat($data->date_of_birth) ?></dd>
					<dt class="col-sm-3 font-weight-normal mb-1">Alamat</dt>
					<dd class="col-sm-9 mb-1"><?= $data->address ?>, <?= $data->village ?>
						<br><?= $data->district ?> <?= $city ?> <?= $data->province ?>, <?= $data->postal_code ?>
					</dd>
					<dt class="col-sm-3 font-weight-normal mb-1">Domisili</dt>
					<dd class="col-sm-9 mb-1">
						<span class="badge badge-success"><?= $data->status_of_domicile ?></span>
						<?= $data->domicile ?>
					</dd>
					<dt class="col-sm-3 font-weight-normal mb-1">Diniyah</dt>
					<dd class="col-sm-9 mb-1"><?= $data->class ?> - <?= $data->level ?></dd>
					<dt class="col-sm-3 font-weight-normal mb-1">Formal</dt>
					<dd class="col-sm-9 mb-1"><?= $data->class_of_formal ?> - <?= $data->level_of_formal ?></dd>
				</dl>
			</div>
			<div class="col-3">
				<div class="card">
					<div class="card-body p-0">
						<img src="<?= $avatar ?>" alt="IMAGE OF <?= $data->name ?>" style="width: 100%; border-radius: 3px;">
					</div>
				</div>
			</div>
			<div class="col-12">
				<?php if ($old) { ?>
				<div class="callout callout-success py-3 px-3">
					- Biaya Pendaftaran : <b>Rp. <?= number_format($old, 0, ',', '.') ?></b>
				</div>
				<?php }else{ ?>
				<div class="callout callout-danger py-3 px-3">
					Santri ini sudah melunasi biaya tahunan
				</div>
				<?php } ?>
			</div>
		</div>
<?php
}
?>

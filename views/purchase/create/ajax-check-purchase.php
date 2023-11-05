<div class="row">
	<?php
	if ($data['status']) :
	$avatarPath = FCPATH . 'assets/avatars/' . $data['student']->id . '.jpg';

	if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
		$avatar = base_url('assets/avatars/default.jpg');
	} else {
		$avatar = base_url('assets/avatars/' . $data['student']->id . '.jpg');
	}

	$city = str_replace(['Kabupaten', 'Kota'], '', $data['student']->city);
	?>
	<div class="col-9 pl-5 pt-3">
		<b><?= $data['student']->name ?></b> <br>
		<hr class="my-2">
		<?= $data['student']->village ?>, <?= $city ?> <br>
		<?= $data['student']->domicile ?> <br>
		<hr class="my-2">
		<?= $data['student']->class ?> - <?= $data['student']->level ?> <br>
		<?= $data['student']->class_of_formal ?> - <?= $data['student']->level_of_formal ?>
		<hr class="my-2">
		<div class="row mb-2">
			<div class="col-8">
				<span class="text-uppercase">Rincian Pembelian</span>
			</div>
		</div>
		<?php
		if ($data['purchases']) {
			$total = 0;
			foreach ($data['purchases'] as $purchase) {
				$total += $purchase->nominal;
		?>
			<div class="row">
				<div class="col-8">
					<span class="text-muted"><?= $purchase->name ?></span>
				</div>
				<div class="col-4 text-right pr-5">
					<?= number_format($purchase->nominal, 0, ',', '.') ?>
				</div>
			</div>
		<?php
			}
		}
		?>
		<hr class="my-2">
		<div class="row">
			<div class="col-8">
				<span class="text-uppercase">Total</span>
			</div>
			<div class="col-4 text-right pr-5">
				<h5 class="text-primary">Rp. <?= number_format($total, 0, ',', '.') ?></h5>
			</div>
		</div>
		<div class="row mt-5">
			<div class="col-6">
				<button class="btn btn-block btn-primary" onclick="store('<?= $data['student']->id ?>', '<?= $data['package'] ?>', '<?= $data['package_name'] ?>', <?= $total ?>)">
					Simpan
				</button>
			</div>
			<div class="col-6">
				<button class="btn btn-block btn-danger" onclick="destroy()">
					Batalkan
				</button>
			</div>
		</div>
	</div>
	<div class="col-3 mt-3">
		<div class="box-profile">
			<div class="text-center">
				<img src="<?= $avatar ?>" alt="IMAGE OF <?= $data['student']->name ?>" style="width: 100%; border-radius: 3px;">
			</div>
		</div>
	</div>
	<?php endif; ?>
	<?php if (!$data['status']) : ?>
	<div class="col-12 text-center">
		<div class="error-page" style="margin-top: 100px;">
			<div class="error-content ml-0">
				<h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! ada masalah nih....</h3>
				<p>
					Data santri tidak ditemukan. Segera hubungi bagian admin ~<br>
				</p>

			</div>
		</div>
	</div>
	<?php endif; ?>
</div>

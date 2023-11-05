<div class="row">
	<?php
	if ($data) {
		$avatarPath = FCPATH . 'assets/avatars/' . $data['nis']. '.jpg';

		if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
			$avatar = base_url('assets/avatars/default.jpg');
		} else {
			$avatar = base_url('assets/avatars/' . $data['nis']. '.jpg');
		}
		?>
		<div class="col-12">
			<div class="row">
				<div class="col-4">
					<div class="box-profile">
						<div class="text-center">
							<img src="<?= $avatar ?>" alt="IMAGE OF <?= $data['name'] ?>" style="width: 100%; border-radius: 3px;">
						</div>
					</div>
				</div>
				<div class="col-8">
					<div class="row">
						<div class="col-12">
							<b><?= $data['name'] ?></b> <br>
							<hr class="my-2">
							<?= $data['address'] ?> <br>
							<?= $data['domicile'] ?> <br>
							<hr class="my-2">
							<?= $data['diniyah'] ?> <br>
							<?= $data['formal'] ?>
						</div>
					</div>
					<div class="col-12 mt-5">
						<div class="callout callout-success py-3 px-3 mb-4">
							<b>Paket : <?= $data['package'] ?></b>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
	} else {
		?>
		<div class="col-12">
			<div class="callout callout-danger py-2">
					<span class="text-danger">
						Info paket tidak tersedia
					</span>
			</div>
		</div>
		<?php
	}
?>
</div>

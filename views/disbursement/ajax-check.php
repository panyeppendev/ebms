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
		<div class="col-7">
			<div class="row">
				<div class="col-5">
					<div class="box-profile">
						<div class="text-center">
							<img src="<?= $avatar ?>" alt="IMAGE OF <?= $data['name'] ?>" style="width: 100%; border-radius: 3px;">
						</div>
					</div>
				</div>
				<div class="col-7">
					<b><?= $data['name'] ?></b> <br>
					<hr class="my-2">
					<?= $data['address'] ?> <br>
					<?= $data['domicile'] ?> <br>
					<hr class="my-2">
					<?= $data['diniyah'] ?> <br>
					<?= $data['formal'] ?>
				</div>
			</div>
		</div>
		<div class="col-5">
			<div class="callout callout-success py-2">
                <span class="text-success font-weight-bold">
                    Paket : <?= $data['package'] ?>
                </span>
				<div class="text-xs">
					<hr class="my-1">
					<table style="width: 100%" class="text-muted">
						<tbody>
						<tr>
							<td style="width: 65%">Limit Harian</td>
							<td style="width: 35%" class="text-right">
								<?= number_format($data['daily'], 0, ',', '.') ?>
							</td>
						</tr>
						<tr>
							<td>Debit Tunai</td>
							<td class="text-right">
								<?= number_format($data['disbursement'], 0, ',', '.') ?>
							</td>
						</tr>
<!--						<tr>-->
<!--							<td>Debet Non-Tunai</td>-->
<!--							<td class="text-right">-->
<!--								-->
<!--							</td>-->
<!--						</tr>-->
						<tr>
							<td>Saldo Limit</td>
							<td class="text-right">
								<?= number_format($data['total'], 0, ',', '.') ?>
							</td>
						</tr>
						</tbody>
					</table>
					<hr class="my-1">
					<table style="width: 100%">
						<tbody>
							<tr>
								<td style="width: 65%">Cadangan</td>
								<td style="width: 35%" class="text-right text-success">
									<?= number_format($data['reserved'], 0, ',', '.') ?>
								</td>
							</tr>
						</tbody>
					</table>
					<hr class="my-1">
					<table style="width: 100%">
						<tbody>
<!--						<tr>-->
<!--							<td style="width: 65%">Kredit Tabungan</td>-->
<!--							<td class="text-right" style="width: 35%">-->
<!--								--><?php //= number_format(0, 0, ',', '.') ?>
<!--							</td>-->
<!--						</tr>-->
<!--						<tr>-->
<!--							<td>Debet Tunai</td>-->
<!--							<td class="text-right">-->
<!--								--><?php //= number_format(0, 0, ',', '.') ?>
<!--							</td>-->
<!--						</tr>-->
<!--						<tr>-->
<!--							<td>Debet Non-Tunai</td>-->
<!--							<td class="text-right">-->
<!--								--><?php //= number_format(0, 0, ',', '.') ?>
<!--							</td>-->
<!--						</tr>-->
						<tr>
							<td>Saldo Tabungan</td>
							<td class="text-right">
								<?= number_format(0, 0, ',', '.') ?>
							</td>
						</tr>
						</tbody>
					</table>
					<hr class="my-1">
				</div>
				<table style="width: 100%">
					<tbody>
					<tr class="text-success font-weight-bold">
						<td style="width: 65%">Total Pencairan</td>
						<td class="text-right" style="width: 35%">
							<?= number_format($data['total'], 0, ',', '.') ?>
						</td>
					</tr>
					</tbody>
				</table>
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

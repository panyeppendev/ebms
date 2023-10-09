<div class="col-12">
	<div class="card" style="height: 71.8vh;">
		<!-- /.card-header -->
		<div class="card-body table-responsive p-0" style="height: 100%;" id="cardScroll">
			<table class="table table-head-fixed table-hover">
				<thead>
				<tr>
					<th>NO</th>
					<th>BIO SANTRI</th>
					<th class="text-center">PAKET</th>
					<th class="text-right">OPSI</th>
				</tr>
				</thead>
				<tbody>
				<?php
				if ($purchases) {
					$no = 1;
					foreach ($purchases as $purchase) {
						$status = $purchase->status;
						if ($status == 'ACTIVE') {
							$text = ['text-success', 'Aktif'];
						}else{
							$text = ['text-danger', 'Non-Aktif'];
						}
						?>
						<tr>
							<td class="align-middle"><?= $no++ ?></td>
							<td class="align-middle">
								<?= $purchase->name ?>
								<br>
								<small>
									<?= $purchase->domicile ?> - <?= $purchase->village.', '.str_replace(['Kabupaten ', 'Kota '], '', $purchase->city) ?>
								</small>
							</td>
							<td class="align-middle text-right">
								<?= $purchase->package ?> | <?= number_format($purchase->amount, 0, ',', '.') ?>
								<br>
								<span style="font-size: 8px" class="mx-2 <?= $text[0] ?>"><i class="fas fa-circle fa-xs"></i> <?= $text[1] ?></span>
							</td>
							<td class="align-middle text-right">
								<button onclick="activate('<?= $purchase->id ?>')" class="btn btn-success px-2 <?= ($purchase->status == 'INACTIVE') ? 'd-inline-block' : 'd-none' ?>" title="Aktifkan paket ini">
									<i class="fas fa-check"></i>
								</button>
								<button onclick="finished('<?= $purchase->id ?>')" class="btn btn-danger px-2 <?= ($purchase->status == 'ACTIVE') ? 'd-inline-block' : 'd-none' ?>" title="Selesaikan paket ini">
									<i class="fas fa-times"></i>
								</button>
							</td>
						</tr>
						<?php
					}
				} else {
					echo '<tr class="text-center"><td colspan="6"><h6 class="text-danger">Tak ada data untuk ditampilkan</h6></td></tr>';
				}
				?>
				</tbody>
			</table>
		</div>
		<!-- /.card-body -->
		<div class="card-footer"></div>
	</div>
</div>

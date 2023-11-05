<div class="col-12">
	<div class="card" style="height: 71.8vh;">
		<!-- /.card-header -->
		<div class="card-body table-responsive p-0" style="height: 100%;" id="cardScroll">
			<table class="table table-head-fixed table-hover">
				<thead>
				<tr>
					<th>NO</th>
					<th>NIS</th>
					<th>NAMA</th>
					<th>DOMISILI</th>
					<th>ALAMAT</th>
					<th class="text-center">PAKET</th>
					<th class="text-right">NOMINAL</th>
					<th>OPSI</th>
				</tr>
				</thead>
				<tbody>
				<?php
				if ($purchases) {
					$no = 1;
					foreach ($purchases as $purchase) {
						?>
						<tr>
							<td class="align-middle"><?= $no++ ?></td>
							<td class="align-middle">
								<?= $purchase->id ?>
							</td>
							<td class="align-middle">
								<?= $purchase->name ?>
							</td>
							<td class="align-middle">
								<?= $purchase->domicile ?>
							</td>
							<td class="align-middle">
								<?= $purchase->village.', '.str_replace(['Kabupaten ', 'Kota '], '', $purchase->city) ?>
							</td>
							<td class="align-middle text-center">
								<?= $purchase->package ?>
							</td>
							<td class="align-middle text-right">
								<?= number_format($purchase->amount, 0, ',', '.') ?>
							</td>
							<td class="align-middle text-center">
								<form action="<?= base_url() ?>purchase/invoice" method="post" target="_blank" class="d-inline-block">
									<input type="hidden" name="id" value="<?= $purchase->purchase ?>">
									<button class="btn btn-sm btn-default" type="submit">
										<i class="fas fa-print"></i>
									</button>
								</form>
								<?php
								if ($purchase->status == 'INACTIVE') {
									?>
									<button class="btn btn-sm btn-danger" onclick="destroy('<?= $purchase->purchase ?>')">
										<i class="fas fa-trash"></i>
									</button>
								<?php
								}else{
								?>
									<button class="btn btn-sm btn-danger" disabled>
										<i class="fas fa-trash"></i>
									</button>
								<?php } ?>
							</td>
						</tr>
						<?php
					}
				} else {
					echo '<tr class="text-center"><td colspan="8"><h6 class="text-danger">Tak ada data untuk ditampilkan</h6></td></tr>';
				}
				?>
				</tbody>
			</table>
		</div>
		<!-- /.card-body -->
		<div class="card-footer"></div>
	</div>
</div>

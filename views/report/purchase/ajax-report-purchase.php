<div class="col-12">
	<div class="card">
		<!-- /.card-header -->
		<?php if ($purchases) { ?>
		<div class="card-body table-responsive" style="height: 100%;" id="cardScroll">
			<h5 class="text-center">Laporan Pembelian Paket</h5>
			<p class="text-center">Tanggal <?= @dateIDFormatShort($start) .' s.d.'.@dateIDFormatShort($end) ?></p>
			<h6 class="mb-3">Rekapitulasi</h6>
			<table class="table table-head-fixed table-hover mb-5">
				<thead>
				<tr>
					<th>NO</th>
					<th>PAKET</th>
					<th>QTY</th>
					<th class="text-right">JUMLAH</th>
				</tr>
				</thead>
				<tbody>
				<?php
					$no = 1;
					$qtyCount = 0;
					$totalCount = 0;
					foreach ($purchases as $purchase) {
						$totalCount += $purchase->total;
						?>
						<tr>
							<td class="align-middle"><?= $no++ ?></td>
							<td class="align-middle">
								<?= $purchase->package ?>
							</td>
							<td class="align-middle">
								<?= $purchase->qty ?>
							</td>
							<td class="align-middle text-right">
								<?= number_format($purchase->total, 0, ',', '.') ?>
							</td>
						</tr>
						<?php
					}
				?>
				<tr class="font-weight-bold">
					<td colspan="3" class="text-center align-middle">TOTAL</td>
					<td class="align-middle text-right">
						<?= number_format($totalCount, 0, ',', '.') ?>
					</td>
				</tr>
				</tbody>
			</table>
			<h6 class="mb-3">Rincian</h6>
			<table class="table table-head-fixed table-hover">
				<thead>
				<tr>
					<th>NO</th>
					<th>REKENING</th>
					<th>QTY</th>
					<th class="text-right">JUMLAH</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$no = 1;
				$totalDetail = 0;
				foreach ($detail as $purchase) {
					$totalDetail += $purchase->total;
					?>
					<tr>
						<td class="align-middle"><?= $no++ ?></td>
						<td class="align-middle">
							<?= $purchase->name ?>
						</td>
						<td class="align-middle">
							<?= $purchase->qty ?>
						</td>
						<td class="align-middle text-right">
							<?= number_format($purchase->total, 0, ',', '.') ?>
						</td>
					</tr>
					<?php
				}
				?>
				<tr class="font-weight-bold">
					<td colspan="3" class="text-center align-middle">TOTAL</td>
					<td class="align-middle text-right">
						<?= number_format($totalDetail, 0, ',', '.') ?>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		<?php } else { ?>
			<p class="text-center text-danger mt-5">Tidak ada data untuk ditampilkan</p>
		<?php } ?>
	</div>
</div>

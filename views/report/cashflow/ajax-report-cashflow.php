<div class="col-12">
	<div class="card">
		<!-- /.card-header -->
		<?php if ($cashFlows) { ?>
		<div class="card-body table-responsive" style="height: 100%;" id="cardScroll">
			<h5 class="text-center">Laporan Arus Kas</h5>
			<p class="text-center"><?= $date ?></p>
			<h6 class="mb-3">Rekening : <?= $account ?></h6>
			<table class="table table-head-fixed table-hover mb-5">
				<thead>
				<tr>
					<th>NO</th>
					<th>ROLE</th>
					<th class="text-right">NOMINAL</th>
				</tr>
				</thead>
				<tbody>
				<?php
					$no = 1;
					$total = 0;
					foreach ($cashFlows as $cashFlow) {
						$total += $cashFlow->total;
					?>
						<tr>
							<td class="align-middle"><?= $no++ ?></td>
							<td class="align-middle">
								<?= $cashFlow->role ?>
							</td>
							<td class="align-middle text-right">
								<?= number_format($cashFlow->total, 0, ',', '.') ?>
							</td>
						</tr>
						<?php
					}
				?>
				<tr class="font-weight-bold">
					<td colspan="2" class="text-center align-middle">TOTAL</td>
					<td class="align-middle text-right">
						<?= number_format($total, 0, ',', '.') ?>
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

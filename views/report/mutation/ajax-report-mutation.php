<div class="col-12">
	<div class="card">
		<!-- /.card-header -->
		<?php if ($mutations) { ?>
		<div class="card-body table-responsive" style="height: 100%;" id="cardScroll">
			<h5 class="text-center">Laporan Mutasi Kas</h5>
			<p class="text-center"><?= $date ?></p>
			<h6 class="mb-3">Rekening : <?= $account ?></h6>
			<table class="table table-head-fixed table-hover mb-5">
				<thead>
				<tr>
					<th>NO</th>
					<th>NAMA</th>
					<th>DOMISILI</th>
					<th>KELAS</th>
					<th class="text-right">KREDIT</th>
					<th class="text-right">DEBIT</th>
					<th class="text-right">SALDO</th>
				</tr>
				</thead>
				<tbody>
				<?php
					$no = 1;
					$totalCredit = 0;
					$totalDebit = 0;
					$total = 0;
					foreach ($mutations as $mutation) {
						$credit = $mutation['credit'];
						$debit = $mutation['debit'];
						$residual = $credit - $debit;

						$totalCredit += $credit;
						$totalDebit += $debit;
						$total = $totalCredit - $totalDebit;
					?>
						<tr>
							<td class="align-middle"><?= $no++ ?></td>
							<td class="align-middle">
								<?= $mutation['name'] ?>
							</td>
							<td class="align-middle">
								<?= $mutation['domicile'] ?>
							</td>
							<td class="align-middle">
								<?= $mutation['class'] ?>
							</td>
							<td class="align-middle text-right">
								<?= number_format($credit, 0, ',', '.') ?>
							</td>
							<td class="align-middle text-right">
								<?= number_format($debit, 0, ',', '.') ?>
							</td>
							<td class="align-middle text-right">
								<?= number_format($residual, 0, ',', '.') ?>
							</td>
						</tr>
						<?php
					}
				?>
				<tr class="font-weight-bold">
					<td colspan="4" class="text-center align-middle">TOTAL</td>
					<td class="align-middle text-right">
						<?= number_format($totalCredit, 0, ',', '.') ?>
					</td>
					<td class="align-middle text-right">
						<?= number_format($totalDebit, 0, ',', '.') ?>
					</td>
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

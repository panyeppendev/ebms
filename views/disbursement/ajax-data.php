<div class="col-12 px-0">
	<div class="card" style="height: 71.8vh;">
		<!-- /.card-header -->
		<div class="card-body table-responsive p-0" style="height: 100%;" id="cardScroll">
			<table class="table table-head-fixed table-hover">
				<thead>
				<tr>
					<th>NO</th>
					<th>NAMA</th>
					<th>ALAMAT</th>
					<th>DOMISILI</th>
					<th>PENDIDIKAN</th>
					<th class="text-right">NOMINAL</th>
					<th class="text-center">OPSI</th>
				</tr>
				</thead>
				<tbody>
				<?php
				if ($disbursements) {
					$no = 1;
					foreach ($disbursements as $disbursement) {
						?>
						<tr>
							<td class="align-middle"><?= $no++ ?></td>
							<td class="align-middle">
								<?= $disbursement->name ?>
							</td>
							<td class="align-middle">
								<?= $disbursement->village.', '.str_replace(['Kota ', 'Kabupaten '], '', $disbursement->city) ?>
							</td>
							<td class="align-middle">
								<?= $disbursement->domicile ?>
							</td>
							<td class="align-middle text-sm">
								<?= $disbursement->class.' - '.$disbursement->level ?>
							</td>
							<td class="align-middle text-right">
								<?= number_format($disbursement->amount, 0, ',', '.') ?>
							</td>
							<td class="align-middle text-center">
								<button onclick="destroy('<?= $disbursement->id ?>')" class="btn btn-danger px-2" title="Hapus transaksi ini">
									<i class="fas fa-trash"></i>
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

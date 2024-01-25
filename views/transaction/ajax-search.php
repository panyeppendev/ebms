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
					<th class="text-center">OPSI</th>
				</tr>
				</thead>
				<tbody>
				<?php
				if ($data) {
					$no = 1;
					foreach ($data as $transaction) {
						?>
						<tr>
							<td class="align-middle"><?= $no++ ?></td>
							<td class="align-middle">
								<?= $transaction->name ?>
							</td>
							<td class="align-middle">
								<?= $transaction->village.', '.str_replace(['Kota ', 'Kabupaten '], '', $transaction->city) ?>
							</td>
							<td class="align-middle">
								<?= $transaction->domicile ?>
							</td>
							<td class="align-middle text-sm">
								<?= $transaction->class.' - '.$transaction->level ?>
							</td>
							<td class="align-middle text-center">
								<button onclick="copyToClipboard('<?= $transaction->id ?>')" class="btn btn-primary px-2" title="Salin ID">
									<i class="fas fa-copy"></i>
								</button>
							</td>
						</tr>
						<?php
					}
				} else {
					echo '<tr class="text-center"><td colspan="7"><h6 class="text-danger">Tak ada data untuk ditampilkan</h6></td></tr>';
				}
				?>
				</tbody>
			</table>
		</div>
		<!-- /.card-body -->
		<div class="card-footer"></div>
	</div>
</div>

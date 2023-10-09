<div class="col-12">
	<div class="card" style="height: 71.8vh;">
		<!-- /.card-header -->
		<div class="card-body table-responsive p-0" style="height: 100%;" id="cardScroll">
			<table class="table table-head-fixed table-hover">
				<thead>
				<tr>
					<th>NO</th>
					<th>NAMA</th>
					<th class="text-right">NOMINAL</th>
					<th class="text-center">OPSI</th>
				</tr>
				</thead>
				<tbody>
				<?php
				if ($packages) {
					$no = 1;
					foreach ($packages as $package) {
						?>
						<tr>
							<td class="align-middle"><?= $no++ ?></td>
							<td class="align-middle">
								<?= $package->name ?>
							</td>
							<td class="align-middle text-right">
								<?= number_format($package->amount, 0, ',', '.') ?>
							</td>
							<td class="align-middle text-center">
								<a href="<?= base_url() ?>package/edit/<?= $package->id ?>">Edit</a> |
								<span style="cursor: pointer" class="text-primary" onclick="detail('<?= $package->id ?>')">Detil</span>
							</td>
						</tr>
						<?php
					}
				} else {
					echo '<tr class="text-center"><td colspan="4"><h6 class="text-danger">Tak ada data untuk ditampilkan</h6></td></tr>';
				}
				?>
				</tbody>
			</table>
		</div>
		<!-- /.card-body -->
		<div class="card-footer"></div>
	</div>
</div>

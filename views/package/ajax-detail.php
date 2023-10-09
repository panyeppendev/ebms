<table class="table table-head-fixed table-hover">
	<thead>
	<tr>
		<th>NO</th>
		<th>AKUN</th>
		<th class="text-right">NOMINAL</th>
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
					<?= number_format($package->nominal, 0, ',', '.') ?>
				</td>
			</tr>
			<?php
		}
	} else {
		echo '<tr class="text-center"><td colspan="3"><h6 class="text-danger">Tak ada data untuk ditampilkan</h6></td></tr>';
	}
	?>
	</tbody>
</table>

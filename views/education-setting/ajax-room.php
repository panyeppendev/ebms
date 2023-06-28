<table class="table table-striped table-hover">
	<thead>
	<tr>
		<th>NO</th>
		<th>ID</th>
		<th>NAMA</th>
		<th>ALAMAT</th>
		<th>DOMISILI</th>
		<th>TINGKAT</th>
		<th>KELAS</th>
	</tr>
	</thead>
	<tbody>
	<?php
	if ($data) {
		$no = 1;
		foreach ($data as $d) {
			?>
			<tr>
				<td><?= $no++ ?></td>
				<td><?= $d->id ?></td>
				<td><?= $d->name ?></td>
				<td><?= $d->village.', '.str_replace(['Kabupaten ', 'Kota'], '', $d->city) ?></td>
				<td><?= $d->domicile ?></td>
				<td><?= $d->level ?></td>
				<td><?= $d->class.' - '.rombel($d->rombel) ?></td>
			</tr>
			<?php
		}
	}
	?>
	</tbody>
</table>

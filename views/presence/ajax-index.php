<?php
if ($data) {
?>
<table class="table table-sm table-striped table-hover">
	<thead>
	<tr>
		<th>NO</th>
		<th>ID</th>
		<th>NAMA</th>
		<th>ALAMAT</th>
		<th>DOMISILI</th>
		<th>A</th>
		<th>I</th>
		<th>S</th>
		<th>JML</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$no = 1;
	foreach ($data as $d) {
	?>
		<tr>
			<td><?= $no++ ?></td>
			<td><?= $d->id ?></td>
			<td><?= $d->name ?></td>
			<td><?= $d->village.', '.str_replace(['Kota ', 'Kabupaten '], '', $d->city) ?></td>
			<td><?= $d->domicile ?></td>
			<td><?= $d->a ?></td>
			<td><?= $d->i ?></td>
			<td><?= $d->s ?></td>
			<td><?= $d->a + $d->i + $d->s ?></td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>
<?php
}else{
?>
	<h6 class="pt-5 text-center">
		Tidak ada data. Pastikan tingkat, kelas, dan rombel sudah dipilih
	</h6>
<?php
}
?>

<table class="table table-striped table-hover">
	<thead>
	<tr>
		<th>ID</th>
		<th>NAMA</th>
		<th>DOMISILI</th>
		<th>KELAS</th>
		<th>ROMBEL</th>
	</tr>
	</thead>
	<tbody>
	<?php
	if ($data) {
		foreach ($data as $d) {
			?>
			<tr>
				<td>
					<div class="icheck-primary d-inline" onclick="setCount()">
						<input class="check-rombel" type="checkbox" id="checkbox-<?= $d->id ?>" name="id[]" value="<?= $d->id ?>">
						<label for="checkbox-<?= $d->id ?>">
							<?= $d->id ?>
						</label>
					</div>
				</td>
				<td><?= $d->name ?></td>
				<td><?= $d->domicile ?></td>
				<td><?= $d->class ?></td>
				<td><?= rombel($d->rombel) ?></td>
			</tr>
			<?php
		}
	}
	?>
	</tbody>
</table>

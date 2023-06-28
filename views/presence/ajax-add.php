<table class="table table-sm table-striped table-hover">
	<thead>
	<tr>
		<th style="width: 5%">NO</th>
		<th style="width: 10%">ID</th>
		<th style="width: 39%">NAMA</th>
		<th style="width: 25%">DOMISILI</th>
		<th style="width: 7%" class="text-center">A</th>
		<th style="width: 7%" class="text-center">I</th>
		<th style="width: 7%" class="text-center">S</th>
	</tr>
	</thead>
	<tbody>
	<?php
	if ($data) {
		$no = 1;
		$no1 = -2;
		$no2 = -1;
		$no3 = 0;
		foreach ($data as $item) {
	?>
			<tr>
				<td><?= $no++ ?></td>
				<td><?= $item->id ?></td>
				<td><?= $item->name ?></td>
				<td><?= $item->domicile ?></td>
				<td>
					<input type="number" name="a[<?= $item->id ?>]" onkeypress="nextTab(this, event)" class="form-control form-control-sm input-presence"  placeholder="0" tabindex="<?= $no1+=3 ?>">
				</td>
				<td>
					<input type="number" name="i[<?= $item->id ?>]" onkeypress="nextTab(this, event)" class="form-control form-control-sm input-presence" placeholder="0" tabindex="<?= $no2+=3 ?>">
				</td>
				<td>
					<input type="number" name="s[<?= $item->id ?>]" onkeypress="nextTab(this, event)" class="form-control form-control-sm input-presence" placeholder="0" tabindex="<?= $no3+=3 ?>">
				</td>
			</tr>
	<?php
		}
	}
	?>
	</tbody>
</table>

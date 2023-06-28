<div class="card">
	<div class="card-body">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>NO</th>
					<th>KELAS</th>
					<th>ROMBEL</th>
					<th>WALI KELAS</th>
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
						<td><?= $d->class ?></td>
						<td><?= $d->rombel ?></td>
						<td><?= $d->teacher ?></td>
					</tr>
			<?php
				}
			}
			?>
			</tbody>
		</table>
	</div>
</div>

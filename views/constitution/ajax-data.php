<div class="col-12">
	<div class="card">
		<div class="card-body pt-1">
			<table class="table table-striped table-sm">
				<thead>
					<tr>
						<th>NO</th>
						<th>KATEGORY</th>
						<th>URAIAN</th>
						<th>PASAL</th>
						<th>AYAT</th>
						<th class="text-center">OPSI</th>
					</tr>
				</thead>
				<tbody>
				<?php
				if ($datas) {
					$textType = [
						'OBLIGATION' => 'Kewajiban',
						'PROHIBITION' => 'Larangan',
						'PENALTY' => 'Sanksi'
					];

					$textCategory = [
						'text' => [
							'LOW' => 'Ringan',
							'MEDIUM' => 'Sedang',
							'HIGH' => 'Berat',
							'TOP' => 'Sangat Berat',
						],
						'class' => [
							'LOW' => 'muted',
							'MEDIUM' => 'info',
							'HIGH' => 'warning',
							'TOP' => 'danger',
						]
					];

					$no = 1;
					foreach ($datas as $data) {
						?>
						<tr>
							<td><?= $no++ ?></td>
							<td>
								<span class="text-<?= $textCategory['class'][$data->category] ?>">
									<?= $textCategory['text'][$data->category] ?>
								</span>
							</td>
							<td><?= $data->name ?></td>
							<td><?= $textType[$data->type] ?></td>
							<td><?= $data->clause ?></td>
							<td class="text-center">
								<button class="btn btn-default btn-sm" onclick="getById(<?= $data->id ?>)">
									<i class="fas fa-pen-alt"></i>
								</button>
							</td>
						</tr>
				<?php
					}
				} else {
					echo '<tr><td colspan="5" class="text-center text-danger">Tidak ada data untuk ditampilkan</td></tr>';
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="col-12">
	<div class="card">
		<div class="card-body pt-1">
			<table class="table table-striped table-sm">
				<thead>
					<tr>
						<th style="width: 5%">NO</th>
						<th style="width: 10%">KATEGORY</th>
						<th style="width: 60%">URAIAN</th>
						<th style="width: 10%">PASAL</th>
						<th style="width: 5%">AYAT</th>
						<th style="width: 10%" class="text-center">OPSI</th>
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
						$idDefault = ['M-10001', 'H-10001', 'T-10001'];
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
								<button <?= (in_array($data->id, $idDefault)) ? 'disabled' : '' ?> class="btn btn-default btn-sm" onclick="getById('<?= $data->id ?>')">
									<i class="fas fa-pen-alt"></i>
								</button>
								<button <?= (in_array($data->id, $idDefault)) ? 'disabled' : '' ?> class="btn btn-danger btn-sm" onclick="destroy('<?= $data->id ?>')">
									<i class="fas fa-trash"></i>
								</button>
							</td>
						</tr>
				<?php
					}
				} else {
					echo '<tr><td colspan="6" class="text-center text-danger">Tidak ada data untuk ditampilkan</td></tr>';
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>

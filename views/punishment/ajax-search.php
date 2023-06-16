<table class="table table-head-fixed table-hover">
	<thead>
	<tr>
		<th>NO</th>
		<th colspan="2" class="text-center">NAMA</th>
		<th>ALAMAT</th>
		<th>DOMISILI</th>
		<th>DINIYAH</th>
		<th>FORMAL</th>
	</tr>
	</thead>
	<tbody>
	<?php
	if ($datas) {
		$no = 1;
		foreach ($datas as $data) {
			$avatarPath = FCPATH . 'assets/avatars/' . $data->id . '.jpg';

			if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
				$avatar = base_url('assets/avatars/default.jpg');
			} else {
				$avatar = base_url('assets/avatars/' . $data->id . '.jpg');
			}

			$city = str_replace(['Kabupaten', 'Kota'], '', $data->city);

			?>
			<tr>
				<td class="align-middle"><?= $no++ ?></td>
				<td style="cursor: pointer;" title="Klik untuk detail" onclick="detailData(<?= $data->id ?>)">
					<img style="border-radius: 5px;" alt="Foto <?= $data->name ?>" width="45px" class="table-avatar" src="<?= $avatar ?>">
				</td>
				<td class="align-middle">
					<b><?= $data->name ?></b>
					<br>
					<span style="cursor: pointer" title="Salin ID ke clipboard" onclick="copyToClipboard(<?= $data->id ?>)">
                                        <small class="text-success">
                                            <?= $data->id ?>
                                        </small>
                                        <i class="fas fa-copy ml-1 text-success"></i>
                                    </span>
				</td>
				<td class="align-middle"><?= $data->village . '<br>' . $city ?></td>
				<td class="align-middle">
					<span class="badge badge-success"><?= $data->status_of_domicile ?></span>
					<br>
					<?= $data->domicile ?>
				</td>
				<td class="align-middle"><?= $data->class . '<br>' . $data->level ?></td>
				<td class="align-middle"><?= $data->class_of_formal . '<br>' . $data->level_of_formal ?></td>
			</tr>
			<?php
		}
	} else {
		echo '<tr class="text-center"><td colspan="8"><h6 class="text-danger">Tak ada data untuk ditampilkan</h6></td></tr>';
	}
	?>
	</tbody>
</table>

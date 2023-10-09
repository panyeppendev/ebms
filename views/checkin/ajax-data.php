<div class="col-12">
    <table class="table table-head-fixed table-hover">
        <thead>
            <tr>
                <th>NO</th>
                <th class="text-center">NIS</th>
                <th>NAMA</th>
                <th>ALAMAT</th>
                <th>DOMISILI</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($datas) {
				$no = 1;
                $key_values = array_column($datas, 'status'); 
                array_multisort($key_values, SORT_ASC, $datas);

				foreach ($datas as $data) {
					$status = $data['status'];
					if ($status) {
						$status = '<span class="badge badge-success">Checkin</span>';
					}else{
						$status = '<span class="badge badge-danger">Tidak Checkin</span>';
					}
					?>
					<tr>
						<td class="align-middle"><?= $no++ ?></td>
						<td class="align-middle"><?= $data['id'] ?></td>
						<td class="align-middle">
							<b><?= $data['name'] ?></b>
						</td>
						<td class="align-middle">
							<?= $data['domicile'] ?>
						</td>
						<td class="align-middle"><?= $data['address'] ?></td>
						<td class="align-middle"><?= $status ?></td>
					</tr>
            <?php
                }
            } else {
                echo '<tr class="text-center"><td colspan="6"><h6 class="text-danger">Tak ada data untuk ditampilkan</h6></td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

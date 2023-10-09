<div class="col-12">
	<div class="card" style="height: 71.8vh;">
		<!-- /.card-header -->
		<div class="card-body table-responsive p-0" style="height: 100%;" id="cardScroll">
			<table class="table table-head-fixed table-hover">
				<thead>
				<tr>
					<th>NO</th>
					<th>NAMA</th>
					<th>KATEGORY</th>
					<th class="text-right">NOMINAL BAWAAN</th>
					<th class="text-center">STATUS</th>
					<th class="text-center">OPSI</th>
				</tr>
				</thead>
				<tbody>
				<?php
				if ($accounts) {
					$categories = ['PACKAGE' => 'Paket', 'ADDON' => 'Tambahan', 'OTHER' => 'Lain-lain'];
					$no = 1;
					foreach ($accounts as $account) {
						$status = $account->status;
						if ($status == 'ACTIVE') {
							$display = '<span class="text-success">Aktif</span>';
							$status = 'INACTIVE';
							$text = 'Non-Aktifkan';
						}else{
							$display = '<span class="text-danger">Non-Aktif</span>';
							$status = 'ACTIVE';
							$text = 'Aktifkan';
						}
						?>
						<tr>
							<td class="align-middle"><?= $no++ ?></td>
							<td class="align-middle">
								<?= $account->name ?>
							</td>
							<td class="align-middle">
								<?= $categories[$account->category] ?>
							</td>
							<td class="align-middle text-right">
								<?= number_format($account->nominal, 0, ',', '.') ?>
							</td>
							<td class="align-middle text-center">
								<?= $display ?>
							</td>
							<td class="align-middle text-center">
								<span style="cursor: pointer" class="text-primary" onclick="edit('<?= $account->id ?>')">Edit</span>
								<span style="font-size: 8px" class="mx-2 text-default"><i class="fas fa-circle fa-xs"></i></span>
								<span style="cursor: pointer" class="text-primary" onclick="setStatus('<?= $account->id ?>', '<?= $status ?>')"><?= $text ?></span>
							</td>
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
		<!-- /.card-body -->
		<div class="card-footer"></div>
	</div>
</div>

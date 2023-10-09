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
				</tr>
				</thead>
				<tbody>
				<?php
				if ($accounts) {
					$categories = ['DEBIT' => 'Pengeluaran', 'CREDIT' => 'Pemasukan'];
					$no = 1;
					foreach ($accounts as $account) {
						?>
						<tr>
							<td class="align-middle"><?= $no++ ?></td>
							<td class="align-middle">
								<?= $account->name ?>
							</td>
							<td class="align-middle">
								<?= $categories[$account->category] ?>
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

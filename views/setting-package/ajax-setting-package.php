<div class="col-12">
	<div class="card" style="height: 71.8vh;">
		<!-- /.card-header -->
		<div class="card-body table-responsive p-0" style="height: 100%;" id="cardScroll">
			<table class="table table-head-fixed table-hover">
				<thead>
				<tr>
					<th>NO</th>
					<th>REKENING</th>
					<th class="text-right">NOMINAL</th>
				</tr>
				</thead>
				<tbody>
				<?php
				if ($settings) {
					$no = 1;
					foreach ($settings as $setting) {
						?>
						<tr>
							<td class="align-middle"><?= $no++ ?></td>
							<td class="align-middle">
								<?= $setting->name ?>
							</td>
							<td class="align-middle text-right">
								<?= number_format($setting->nominal, 0, ',', '.') ?>
							</td>
						</tr>
						<?php
					}
				} else {
					echo '<tr class="text-center"><td colspan="3"><h6 class="text-danger">Limit belum diatur</h6></td></tr>';
				}
				?>
				</tbody>
			</table>
		</div>
		<!-- /.card-body -->
		<div class="card-footer"></div>
	</div>
</div>

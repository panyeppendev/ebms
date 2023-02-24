<div class="callout callout-danger">
	<?php
		if ($data['status'] === 200) {
	?>
		<h6>Jenis Pelanggaran</h6>
		<span><?= $data['chapter'] ?></span> <br>
		<span><?= $data['item'] ?></span>
		<hr>
		<h6>Tindakan</h6>
		<ul>
			<?php
			if ($data['punishment_status'] === 200) {
				foreach ($data['punishment'] as $punishment) {
			?>
				<li><?= $punishment['item'] ?></li>
			<?php
				}
			}else{
				echo '<h6 class="text-danger">Data sanksi tidak ditemukan</h6>';
			}
			?>
		</ul>
	<?php
		} else {
			echo '<h6 class="text-danger">Data tidak ditemukan</h6>';
		}
	?>
</div>

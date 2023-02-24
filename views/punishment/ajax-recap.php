<div class="row">
    <div class="col-3" style="cursor: pointer" onclick="beforeLoadDetail('LOW')">
        <div class="card">
            <div class="card-body py-1 text-black">
				<i class="fas fa-info-circle"></i>
                <span>Ringan : <?= $data['low'] ?></span>
            </div>
        </div>
    </div>
    <div class="col-3" style="cursor: pointer" onclick="beforeLoadDetail('MEDIUM')"">
        <div class="card">
            <div class="card-body py-1">
                <i class="fas fa-info-circle"></i>
                <span>Sedang : <?= $data['medium'] ?></span>
            </div>
        </div>
    </div>
	<div class="col-3" style="cursor: pointer" onclick="beforeLoadDetail('HIGH')">
		<div class="card">
			<div class="card-body py-1">
				<i class="fas fa-info-circle"></i>
				<span>Berat : <?= $data['high'] ?></span>
			</div>
		</div>
	</div>
	<div class="col-3" style="cursor: pointer" onclick="beforeLoadDetail('TOP')">
		<div class="card">
			<div class="card-body py-1">
				<i class="fas fa-info-circle"></i>
				<span>Sangat Berat : <?= $data['top'] ?></span>
			</div>
		</div>
	</div>
</div>

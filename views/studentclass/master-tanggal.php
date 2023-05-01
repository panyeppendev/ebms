<div class="col-4">
    <div class="form-group">
        <label for="date_of_entry">Tanggal Masuk (Masehi)</label>
        <div class="row">
            <div class="col-3">
                <select class="custom-select form-control-border" id="date_of_entry" name="date_of_entry" tabindex="19">
                    <option value="">00</option>
                    <?php
                    $l = 1;
                    for ($i = 1; $i <= 31; $i++) {
                    ?>
                        <option value="<?= sprintf('%02d', $i); ?>">
                            <?= sprintf('%02d', $i); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-5">
                <select class="custom-select form-control-border" id="month_of_entry" name="month_of_entry" tabindex="20">
                    <option value="">00</option>
                    <?php
                    $bulan = [
                        1 =>
                        'Januari',
                        'Februari',
                        'Maret',
                        'April',
                        'Mei',
                        'Juni',
                        'Juli',
                        'Agustus',
                        'September',
                        'Oktober',
                        'November',
                        'Desember'
                    ];
                    $k = 1;
                    for ($p = 1; $p <= 12; $p++) {
                    ?>
                        <option value="<?= sprintf('%02d', $p); ?>">
                            <?= $bulan[$p]; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-4">
                <select class="custom-select form-control-border" id="year_of_entry" name="year_of_entry" tabindex="21">
                    <option value="">0000</option>
                    <?php
                    $now = date('Y');
                    for ($b = 2015; $b <= $now; $b++) {
                    ?>
                        <option value="<?= $b; ?>">
                            <?= $b; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <small class="text-danger messages" id="errordate_of_entry"></small>
    </div>
</div>
<div class="col-4">
    <div class="form-group">
        <label for="date_of_entry_hijriah">Tanggal Masuk (Hijriah)</label>
        <div class="row">
            <div class="col-3">
                <select class="custom-select form-control-border" id="date_of_entry_hijriah" name="date_of_entry_hijriah" tabindex="22">
                    <option value="">00</option>
                    <?php
                    $l = 1;
                    for ($i = 1; $i <= 30; $i++) {
                    ?>
                        <option value="<?= sprintf('%02d', $i); ?>">
                            <?= sprintf('%02d', $i); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-5">
                <select class="custom-select form-control-border" id="month_of_entry_hijriah" name="month_of_entry_hijriah" tabindex="23">
                    <option value="">00</option>
                    <?php
                    $bulan = [
                        1 =>
                        'Muharram',
                        'Shafar',
                        'Rabi\'ul Awal',
                        'Rabi\'ul Tsani',
                        'Jumadal Ula',
                        'Jumadal Akhirah',
                        'Rajab',
                        'Sya\'ban',
                        'Ramadhan',
                        'Syawal',
                        'Dzul Qo\'dah',
                        'Dzul Hijjah'
                    ];
                    $k = 1;
                    for ($p = 1; $p <= 12; $p++) {
                    ?>
                        <option value="<?= sprintf('%02d', $p); ?>">
                            <?= $bulan[$p]; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-4">
                <select class="custom-select form-control-border" id="year_of_entry_hijriah" name="year_of_entry_hijriah" tabindex="24">
                    <option value="">0000</option>
                    <?php
                    $now = 1444;
                    for ($b = 1437; $b <= $now; $b++) {
                    ?>
                        <option value="<?= $b; ?>">
                            <?= $b; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <small class="text-danger messages" id="errordate_of_entry_hijriah"></small>
    </div>
</div>
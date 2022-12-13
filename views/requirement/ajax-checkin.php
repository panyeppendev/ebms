<?php
if ($data) {
?>
    <table class="table table-hover text-nowrap">
        <thead>
            <tr class="text-center">
                <th>NO</th>
                <th>URAIRAN</th>
                <th colspan="2">PILIHAN</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($data as $d) {
                $account = $d->account;
                $id = $d->id;

                if ($kelas == 'Takhossus') {
                    $kelasSatu = 'Minimal 3 kali';
                    $kelasDua = 'Ajian Kitab Sore dan Ajian Kitab Sabtu Malam';
                } elseif ($kelas == 'Praktik') {
                    $kelasSatu = 'Minimal 2 kali';
                    $kelasDua = 'Ajian Kitab Sabtu Malam';
                } else {
                    $kelasSatu = 'Minimal 2 kali';
                    $kelasDua = 'Ajian Kitab Sabtu Malam';
                }

                if ($account == 3) {
                    $name = $d->name . ' ' . $kelasSatu;
                } elseif ($account == 6) {
                    $name = $d->name . ' ' . $kelasDua;
                } else {
                    $name = $d->name;
                }
            ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= $name ?></td>
                    <td>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="yes-<?= $d->id ?>" name="status[<?= $d->id ?>]" value="YES" <?= ($d->status == 'YES') ? 'checked' : '' ?>>
                            <label for="yes-<?= $d->id ?>" class="custom-control-label">Lengkap</label>
                        </div>
                    </td>
                    <td>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="no-<?= $d->id ?>" name="status[<?= $d->id ?>]" value="NO" <?= ($d->status == 'NO') ? 'checked' : '' ?>>
                            <label for="no-<?= $d->id ?>" class="custom-control-label">Tidak Lengkap</label>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php
}
?>
<?php
if ($data['status'] == 200) {
    $avatarPath = FCPATH . 'assets/avatars/' . $data['student']->id . '.jpg';

    if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
        $avatar = base_url('assets/avatars/default.jpg');
    } else {
        $avatar = base_url('assets/avatars/' . $data['student']->id . '.jpg');
    }

    $city = str_replace(['Kabupaten', 'Kota'], '', $data['student']->city);
?>
    <div class="col-2">
        <div class="box-profile">
            <div class="text-center">
                <img src="<?= $avatar ?>" alt="IMAGE OF <?= $data['student']->name ?>" style="width: 100%; border-radius: 3px;">
            </div>
        </div>
    </div>
    <div class="col-6">
        <dl class="row">
            <dt class="col-sm-3 font-weight-normal mb-1">Nama</dt>
            <dd class="col-sm-9 mb-1 font-weight-bold"><?= $data['student']->name ?></dd>
            <dt class="col-sm-3 font-weight-normal mb-1">Alamat</dt>
            <dd class="col-sm-9 mb-1">
                <?= $data['student']->village ?>, <?= $city ?>
            </dd>
            <dt class="col-sm-3 font-weight-normal mb-1">Domisili</dt>
            <dd class="col-sm-9 mb-1">
                <?= $data['student']->domicile ?>
            </dd>
            <dt class="col-sm-3 font-weight-normal mb-1">Diniyah</dt>
            <dd class="col-sm-9 mb-1"><?= $data['student']->class ?> - <?= $data['student']->level ?></dd>
            <dt class="col-sm-3 font-weight-normal mb-1">Formal</dt>
            <dd class="col-sm-9 mb-1"><?= $data['student']->class_of_formal ?> - <?= $data['student']->level_of_formal ?></dd>
        </dl>
    </div>
    <div class="col-4 text-xs">
        <div class="callout callout-success py-2">
            <span class="text-success font-weight-bold">
                <?= $data['package'] ?>
            </span>
            <hr class="my-1">
            <table style="width: 100%">
                <tbody>
                    <tr>
                        <td style="width: 60%">Uang Saku</td>
                        <td style="width: 10%">Rp.</td>
                        <td style="width: 30%" class="text-right">
                            <?= number_format($data['pocket'], 0, ',', '.') ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Tabungan</td>
                        <td>Rp.</td>
                        <td class="text-right">
                            <?= number_format($data['deposit'], 0, ',', '.') ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr class="my-1">
            <table style="width: 100%">
                <tbody>
                    <tr>
                        <td style="width: 60%">Tunai</td>
                        <td style="width: 10%">Rp.</td>
                        <td class="text-right" style="width: 30%">
                            <?= number_format($data['cash'], 0, ',', '.') ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Non Tunai</td>
                        <td>Rp.</td>
                        <td class="text-right">
                            <?= number_format($data['canteen'], 0, ',', '.') ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr class="my-1">
            <table style="width: 100%">
                <tbody>
                    <tr class="text-success font-weight-bold">
                        <td style="width: 60%">Total</td>
                        <td style="width: 10%">Rp.</td>
                        <td class="text-right" style="width: 30%">
                            <?= number_format($data['total'], 0, ',', '.') ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php
} else {
    echo 'Gagal memuat data. Segera hubungi Developer';
}
?>
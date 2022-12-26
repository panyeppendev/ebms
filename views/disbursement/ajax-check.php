<div class="row">
    <?php
    if ($data['student']) {
        $avatarPath = FCPATH . 'assets/avatars/' . $data['student']->id . '.jpg';

        if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
            $avatar = base_url('assets/avatars/default.jpg');
        } else {
            $avatar = base_url('assets/avatars/' . $data['student']->id . '.jpg');
        }

        $city = str_replace(['Kabupaten', 'Kota'], '', $data['student']->city);
    ?>
        <div class="col-7">
            <div class="row">
                <div class="col-5">
                    <div class="box-profile">
                        <div class="text-center">
                            <img src="<?= $avatar ?>" alt="IMAGE OF <?= $data['student']->name ?>" style="width: 100%; border-radius: 3px;">
                        </div>
                    </div>
                </div>
                <div class="col-7">
                    <b><?= $data['student']->name ?></b> <br>
                    <hr class="my-2">
                    <?= $data['student']->village ?>, <?= $city ?> <br>
                    <?= $data['student']->domicile ?> <br>
                    <hr class="my-2">
                    <?= $data['student']->class ?> - <?= $data['student']->level ?> <br>
                    <?= $data['student']->class_of_formal ?> - <?= $data['student']->level_of_formal ?>
                </div>
            </div>
        </div>
    <?php
    } else {
        echo '<span class="text-center text-danger">Data santri gagal dimuat</span>';
    }
    ?>
    <div class="col-5">
        <?php
        if ($data['package']['status'] == 200) {
        ?>
            <div class="callout callout-success py-2">
                <span class="text-success font-weight-bold">
                    <?= $data['package']['info'] ?>
                </span>
                <div class="text-xs">
                    <hr class="my-1">
                    <table style="width: 100%" class="text-muted">
                        <tbody>
                            <tr>
                                <td style="width: 65%">Limit Uang Saku</td>
                                <td style="width: 35%" class="text-right">
                                    <?= number_format($data['pocket']['limit'], 0, ',', '.') ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Debet Tunai</td>
                                <td class="text-right">
                                    <?= number_format($data['pocket']['cash'], 0, ',', '.') ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Debet Non-Tunai</td>
                                <td class="text-right">
                                    <?= number_format($data['pocket']['noncash'], 0, ',', '.') ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Sisa Limit Uang Saku</td>
                                <td class="text-right">
                                    <?= number_format($data['pocket']['total'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr class="my-1">
                    <table style="width: 100%">
                        <tbody>
                            <?php
                            if ($data['pocket']['total'] <= 0) {
                            ?>
                                <tr class="text-center text-danger">
                                    <td colspan="2">Limit uang saku harian tidak tersedia</td>
                                </tr>
                            <?php
                            } else {
                            ?>
                                <tr>
                                    <td style="width: 65%">Limit Harian</td>
                                    <td style="width: 35%" class="text-right text-success">
                                        <?= number_format($data['daily']['limit'], 0, ',', '.') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 65%">Simpanan dua hari</td>
                                    <td style="width: 35%" class="text-right text-success">
                                        <?= number_format($data['daily']['residual'], 0, ',', '.') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Debet Tunai</td>
                                    <td class="text-right text-danger">
                                        <?= number_format($data['daily']['cash'], 0, ',', '.') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Debet Non-Tunai</td>
                                    <td class="text-right text-danger">
                                        <?= number_format($data['daily']['noncash'], 0, ',', '.') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Limit tersedia</td>
                                    <td class="text-right text-success">
                                        <?= number_format($data['daily']['total'], 0, ',', '.') ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>

                        </tbody>
                    </table>
                    <hr class="my-1">
                    <table style="width: 100%">
                        <tbody>
                            <tr>
                                <td style="width: 65%">Kredit Tabungan</td>
                                <td class="text-right" style="width: 35%">
                                    <?= number_format($data['deposit']['kredit'], 0, ',', '.') ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Debet Tunai</td>
                                <td class="text-right">
                                    <?= number_format($data['deposit']['cash'], 0, ',', '.') ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Debet Non-Tunai</td>
                                <td class="text-right">
                                    <?= number_format($data['deposit']['noncash'], 0, ',', '.') ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Saldo Tersedia</td>
                                <td class="text-right">
                                    <?= number_format($data['deposit']['total'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr class="my-1">
                </div>
                <table style="width: 100%">
                    <tbody>
                        <tr class="text-success font-weight-bold">
                            <td style="width: 65%">Total Pencairan</td>
                            <td class="text-right" style="width: 35%">
                                <?php
                                $dailyTotal = $data['daily']['total'];
                                $pocketLimit = $data['pocket']['total'];
                                if ($pocketLimit <= 0) {
                                    $dailyTotal = 0;
                                } else {
                                    $dailyTotal;
                                }
                                ?>
                                <?= number_format($dailyTotal  + $data['deposit']['total'], 0, ',', '.') ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php
        } else {
        ?>
            <div class="callout callout-danger py-2">
                <span class="text-danger">
                    Info paket tidak tersedia
                </span>
            </div>
        <?php
        }
        ?>
    </div>
</div>
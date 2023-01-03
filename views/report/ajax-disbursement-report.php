<div class="card">
    <div class="card-header">
        <h6 class="text-center">
            Rekapitulasi Sisa Distribusi Tahap <?= ($step != 0) ? 'Tahap ' . $step : 'Semua Tahap' ?>
        </h6>
    </div>
    <div class="card-body pt-0">
        <table class="table table-sm table-striped">
            <thead>
                <tr class="text-center">
                    <th class="align-middle" rowspan="2">NO</th>
                    <th class="align-middle" rowspan="2">DOMISILI</th>
                    <th colspan="3">UANG SAKU</th>
                    <th colspan="3">SARAPAN</th>
                    <th colspan="3">DPU</th>
                    <th class="align-middle" rowspan="2">JUMLAH SISA</th>
                </tr>
                <tr class="text-center">
                    <th class="font-weight-light">KREDIT</th>
                    <th class="font-weight-light">DEBET</th>
                    <th class="font-weight-light">SISA</th>
                    <th class="font-weight-light">KREDIT</th>
                    <th class="font-weight-light">DEBET</th>
                    <th class="font-weight-light">SISA</th>
                    <th class="font-weight-light">KREDIT</th>
                    <th class="font-weight-light">DEBET</th>
                    <th class="font-weight-light">SISA</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $num = 1;
                if ($data['status'] == 200) {
                    $totalKreditPocket = 0;
                    $totalDebetPocket = 0;
                    $totalResidualPocket = 0;
                    $totalKreditBreakfast = 0;
                    $totalDebetBreakfast = 0;
                    $totalResidualBreakfast = 0;
                    $totalKreditDpu = 0;
                    $totalDebetDpu = 0;
                    $totalResidualDpu = 0;
                    $grandTotal = 0;
                    foreach ($data['data'] as $d) {
                        $totalKreditPocket += $d['kredit_pocket'];
                        $totalDebetPocket += $d['debet_pocket'];
                        $totalResidualPocket += $d['kredit_pocket'] - $d['debet_pocket'];
                        $totalKreditBreakfast += $d['kredit_breakfast'];
                        $totalDebetBreakfast += $d['debet_breakfast'];
                        $totalResidualBreakfast += $d['kredit_breakfast'] - $d['debet_breakfast'];
                        $totalKreditDpu += $d['kredit_dpu'];
                        $totalDebetDpu += $d['debet_dpu'];
                        $totalResidualDpu += $d['kredit_dpu'] - $d['debet_dpu'];
                        $grandTotal += ($d['kredit_pocket'] - $d['debet_pocket']) + ($d['kredit_breakfast'] - $d['debet_breakfast']) + ($d['kredit_dpu'] - $d['debet_dpu']);
                ?>
                        <tr>
                            <td><?= $num++ ?></td>
                            <td><?= $d['domicile'] ?></td>
                            <td class="text-right"><?= number_format($d['kredit_pocket'], 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format($d['debet_pocket'], 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format($d['kredit_pocket'] - $d['debet_pocket'], 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format($d['kredit_breakfast'], 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format($d['debet_breakfast'], 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format($d['kredit_breakfast'] - $d['debet_breakfast'], 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format($d['kredit_dpu'], 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format($d['debet_dpu'], 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format($d['kredit_dpu'] - $d['debet_dpu'], 0, ',', '.') ?></td>
                            <td class="text-right">
                                <?= number_format(($d['kredit_pocket'] - $d['debet_pocket']) + ($d['kredit_breakfast'] - $d['debet_breakfast']) + ($d['kredit_dpu'] - $d['debet_dpu']), 0, ',', '.') ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr class="font-weight-bold">
                        <td colspan="2" class="text-center">TOTAL</td>
                        <td class="text-right"><?= number_format($totalKreditPocket, 0, ',', '.') ?></td>
                        <td class="text-right"><?= number_format($totalDebetPocket, 0, ',', '.') ?></td>
                        <td class="text-right"><?= number_format($totalResidualPocket, 0, ',', '.') ?></td>
                        <td class="text-right"><?= number_format($totalKreditBreakfast, 0, ',', '.') ?></td>
                        <td class="text-right"><?= number_format($totalDebetBreakfast, 0, ',', '.') ?></td>
                        <td class="text-right"><?= number_format($totalResidualBreakfast, 0, ',', '.') ?></td>
                        <td class="text-right"><?= number_format($totalKreditDpu, 0, ',', '.') ?></td>
                        <td class="text-right"><?= number_format($totalDebetDpu, 0, ',', '.') ?></td>
                        <td class="text-right"><?= number_format($totalResidualDpu, 0, ',', '.') ?></td>
                        <td class="text-right"><?= number_format($grandTotal, 0, ',', '.') ?></td>
                    </tr>
                <?php
                } else {
                    echo '<tr class="text-danger text-center"><td colspan="12">Tidak adata untuk ditampilkan</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
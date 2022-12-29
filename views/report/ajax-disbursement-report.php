<div class="card">
    <div class="card-header">
        <h6 class="text-center">
            Rekapitulasi Sisa Distribusi Tahap <?= ($step != 0) ? 'Tahap ' . $step : 'Semua Tahap' ?>
        </h6>
    </div>
    <div class="card-body pt-0">
        <table class="table table-sm">
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
                    foreach ($data['data'] as $d) {
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
                } else {
                    echo '<tr class="text-danger text-center"><td colspan="12">Tidak adata untuk ditampilkan</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
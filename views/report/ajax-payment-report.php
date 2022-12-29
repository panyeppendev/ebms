<div class="card">
    <div class="card-header">
        <h6 class="text-center">
            Rekapitulasi Pembayaran <?= ($step != 0) ? 'Tahap ' . $step : 'Semua Tahap' ?>
        </h6>
    </div>
    <div class="card-body pt-0">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>URAIAN</th>
                    <th>QTY</th>
                    <th>NOMINAL</th>
                    <th>JUMLAH</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($pocket) {
                    $total = 0;
                    foreach ($pocket as $p) {
                        $qty = $p->qty;
                        $amount = $p->amount;
                        $jumlah = $amount * $qty;
                        $total += $jumlah;
                ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>Uang Saku Paket <?= $p->package ?></td>
                            <td><?= $qty ?></td>
                            <td class="text-right">
                                <?= number_format($amount, 0, ',', '.') ?>
                            </td>
                            <td class="text-right">
                                <?= number_format($jumlah, 0, ',', '.') ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr class="font-weight-bold">
                        <td colspan="4" class="text-center">SUB TOTAL</td>
                        <td class="text-right">
                            <?= number_format($total, 0, ',', '.') ?>
                        </td>
                    </tr>
                <?php } ?>
                <?php
                if ($besidesPocket) {
                    $totalBesidesPocket = 0;
                    foreach ($besidesPocket as $bp) {
                        $qty = $bp->qty;
                        $amount = $bp->amount;
                        $jumlah = $amount * $qty;
                        $totalBesidesPocket += $jumlah;
                ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $bp->name ?></td>
                            <td><?= $qty ?></td>
                            <td class="text-right">
                                <?= number_format($amount, 0, ',', '.') ?>
                            </td>
                            <td class="text-right">
                                <?= number_format($jumlah, 0, ',', '.') ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr class="font-weight-bold">
                        <td colspan="4" class="text-center">SUB TOTAL</td>
                        <td class="text-right">
                            <?= number_format($totalBesidesPocket, 0, ',', '.') ?>
                        </td>
                    </tr>
                    <tr class="font-weight-bold">
                        <td colspan="4" class="text-center">GRAND TOTAL</td>
                        <td class="text-right">
                            <?= number_format($total + $totalBesidesPocket, 0, ',', '.') ?>
                        </td>
                    </tr>
                <?php } else {
                    echo '<tr class="text-danger text-center"><td colspan="5">Tidak adata untuk ditampilkan</td></tr>';
                } ?>
            </tbody>
        </table>
    </div>
</div>
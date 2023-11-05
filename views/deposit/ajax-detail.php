<div class="col-12">
    <table class="table table-sm table-stripped mb-0">
        <thead>
            <tr>
                <th>NO</th>
                <th>TANGGAL</th>
                <th colspan="2">NOMINAL</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($data[0]) {
                $no = 1;
                foreach ($data[0] as $d) {
            ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= dateIDFormatShort($d->created_at) ?></td>
                        <td>Rp.</td>
                        <td class="text-right">
                            <?= number_format($d->amount, 0, ',', '.') ?>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="4" class="text-center">Tidak ada data</td></tr>';
            }
            ?>
        </tbody>
    </table>
    <hr class="my-2">
    <div class="text-success text-center">
        Total: <b><?= $data[1] ?></b> Transaksi
    </div>
</div>

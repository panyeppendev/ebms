<table class="table table-hover table-head-fixed text-nowrap table-sm">
    <thead>
        <tr class="text-center">
            <th>NO</th>
            <th>NAMA AKUN</th>
            <th colspan="2" style="width: 20%">NOMINAL</th>
            <th>TIPE</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $order = 1;
        if ($datas) {
            foreach ($datas as $data) {
                $package = $data->package;
                if ($package == 'GENERAL') {
                    $textPackage = 'Biaya Umum';
                } elseif ($package == 'AB') {
                    $textPackage = 'Paket A-B';
                } else {
                    $textPackage = 'Paket C-D';
                }
        ?>
                <tr>
                    <td class="text-center"><?= $order++ ?></td>
                    <td><?= $data->name ?></td>
                    <td>Rp.</td>
                    <td class=text-right><?= number_format($data->amount, 0, ',', '.') ?></td>
                    <td class="text-center"><?= $textPackage ?></td>
                </tr>
        <?php
            }
        } else {
            echo '<tr class="text-center text-danger"><td colspan="5">Tidak ada data untuk ditampilkan</td> </tr>';
        }
        ?>
    </tbody>
</table>
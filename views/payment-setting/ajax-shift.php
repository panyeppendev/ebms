<table class="table table-hover table-head-fixed text-nowrap table-sm">
    <thead>
        <tr class="text-center">
            <th>NO</th>
            <th>SHIFT</th>
            <th>PUKUL</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $order = 1;
        if ($shifts) {
            $name = [
                'BREAKFAST' => 'SARAPAN',
                'MORNING' => 'PAGI',
                'AFTERNOON' => 'SORE',
                'NIGHT' => 'MALAM'
            ];
            foreach ($shifts as $shift) {
        ?>
                <tr class="text-center">
                    <td><?= $order++ ?></td>
                    <td><?= $name[$shift->name] ?></td>
                    <td><?= $shift->begin ?> - <?= $shift->finish ?></td>
                </tr>
        <?php
            }
        } else {
            echo '<tr class="text-danger text-center"><td colspan="3">Tidak ada data</td></tr>';
        }
        ?>

    </tbody>
</table>
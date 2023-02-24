<div class="card mb-0">
    <div class="card-body py-2 px-3">
        <?php
        if ($stores) {
            ?>
            <dl class="mb-0">
            <?php
            $text = [
                'BARBER' => 'Pangkas Rambut',
                'SHORT' => 'Jarak Dekat',
                'LONG' => 'Jarak Jauh',
            ];
            foreach ($stores as $s) {
            ?>
                <dt><?= $text[$s->name] ?></dt>
                <dd>
                    <?=  number_format($s->price, 0, ',', '.') ?>
                </dd>
            <?php
            }
            ?>
            </dl>
            <?php
        }else {
            echo '<h6 class="text-center text-danger">Tidak ada data</h6>';
        }
        ?>
    </div>
</div>
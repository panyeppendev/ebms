<div class="col-12">
    <ul class="todo-list ui-sortable" data-widget="todo-list">
        <?php
        if ($data) {
            foreach ($data as $d) {
                $status = $d->status;
        ?>
                <li class="<?= ($status == 'UNCHECKED') ? 'done' : '' ?>">
                    <!-- checkbox -->
                    <div class="icheck-primary d-inline ml-2">
                        <?php if ($setting == 'OPEN') { ?>
                            <input type="checkbox" value="" onchange="changeStatus(<?= $d->id ?>)" <?= ($status == 'CHECKED') ? 'checked' : '' ?>>
                        <?php } ?>
                    </div>
                    <!-- todo text -->
                    <span class="text"><?= $d->name ?></span>
                    <!-- General tools such as edit or delete-->
                    <div class="tools">
                        <?php if ($setting == 'OPEN') { ?>
                            <i class="fas fa-edit text-primary" onclick="edit(<?= $d->id ?>)"></i>
                            <i class="fas fa-trash" onclick="deleteAccount(<?= $d->id ?>)"></i>
                        <?php } ?>
                    </div>
                </li>
        <?php
            }
        } else {
            echo '<li class="text-center text-danger">Tidak ada data untuk ditampilkan</li>';
        }
        ?>
    </ul>
</div>
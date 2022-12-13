<div class="row">
    <?php if ($data['status'] != 200) { ?>
        <div class="col-12">
            <h5 class="text-danger text-center">
                <?= $data['message'] ?>
            </h5>
        </div>
    <?php } else { ?>
        <div class="col-4 pr-0">
            <div class="card">
                <div class="card-body p-0">
                    <?php
                    $avatarPath = FCPATH . 'assets/avatars/' . $data['student']->id . '.jpg';

                    if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
                        $avatar = base_url('assets/avatars/default.jpg');
                    } else {
                        $avatar = base_url('assets/avatars/' . $data['student']->id . '.jpg');
                    }
                    ?>
                    <img src="<?= $avatar ?>" alt="IMAGE OF" style="width: 100%; border-radius: 3px;">
                </div>
            </div>
        </div>
        <div class="col-8 p-3 pb-0" style="background-color: #f4f6f9">
            <div class="callout callout-success py-1 px-3">
                <b>DATA DIRI</b>
            </div>
            <dl class="row">
                <dt class="col-sm-3 font-weight-normal mb-1">NIS</dt>
                <dd class="col-sm-9 mb-1">
                    <?= $data['student']->id ?>
                </dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Nama</dt>
                <dd class="col-sm-9 mb-1 font-weight-bold"><?= $data['student']->name ?></dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Tetala</dt>
                <dd class="col-sm-9 mb-1">
                    <?= $data['student']->place_of_birth . ', ' . dateIDFormat($data['student']->date_of_birth) ?>
                </dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Alamat</dt>
                <dd class="col-sm-9 mb-1"><?= $data['student']->village . ', ' . $data['student']->city ?></dd>
                <dt class="col-sm-3 font-weight-normal mb-1">Domisili</dt>
                <dd class="col-sm-9 mb-1">
                    <?= $data['student']->domicile ?>
                </dd>
            </dl>
            <?php
            if ($data['package'] == 200) {
            ?>
                <div class="callout callout-success py-1 px-3">
                    <b>INFORMASI PAKET <?= $data['step'] ?></b>
                </div>
                <dl class="row">
                    <dt class="col-sm-3 font-weight-normal mb-1">Paket</dt>
                    <dd class="col-sm-9 mb-1 font-weight-bold"><?= $data['package_name'] ?></dd>
                    <dt class="col-sm-3 font-weight-normal mb-1">Sisa Uang Saku</dt>
                    <dd class="col-sm-9 mb-1">Rp. <?= $data['package_message']['pocket'] ?></dd>
                    <dt class="col-sm-3 font-weight-normal mb-1">Sisa Tabungan</dt>
                    <dd class="col-sm-9 mb-1">Rp. <?= $data['package_message']['deposit'] ?></dd>
                </dl>
            <?php
            } else {
            ?>
                <div class="callout callout-danger py-2 px-3">
                    <b><i><?= $data['package_message'] ?></i></b>
                </div>
            <?php
            }
            ?>
        </div>
    <?php } ?>
</div>
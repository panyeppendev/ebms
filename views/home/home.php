<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row">
            <div class="col-5">
                <div class="row">
                    <div class="col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Jumlah Santri</span>
                                <span class="info-box-number"><?= $data[0]->amount ?> Orang</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="callout callout-success">
                            <h6 class="text-center">Data Santri Per Domisili</h6>
                            <table class="table table-sm table-stripped">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>DOMISILI</th>
                                        <th class="text-center">JML</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($data[1]) {
                                        $no = 1;
                                        foreach ($data[1] as $d) {
                                    ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $d->domicile ?></td>
                                                <td class="text-center"><?= $d->amount ?></td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-7">
                <div class="row">
                    <div class="col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-info">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </span>
                            <div class="row w-100">
                                <?php
                                if ($data[2]) {
                                    foreach ($data[2] as $dd) {
                                        $package = $dd->package;
                                        if ($package == 'UNKNOWN') {
                                            $package = 'Tidak Beli';
                                        } else {
                                            $package;
                                        }
                                ?>
                                        <div class="col-<?= ($package == 'Tidak Beli') ? 4 : 2 ?>">
                                            <div class="info-box-content <?= ($package == 'Tidak Beli') ? 'text-danger' : '' ?>">
                                                <span class="info-box-text"><?= $package ?></span>
                                                <span class="info-box-number"><?= $dd->amount ?></span>
                                            </div>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="callout callout-success">
                            <h6 class="text-center">Rekapitulasi Paket <?= $step ?></h6>
                            <table class="table table-sm table-stripped">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>DOMISILI</th>
                                        <th>A</th>
                                        <th>B</th>
                                        <th>C</th>
                                        <th>D</th>
                                        <th>Tidak Beli</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($data[3]) {
                                        $no = 1;
                                        foreach ($data[3] as $ddd) {
                                    ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $ddd->domicile ?></td>
                                                <td><?= $ddd->A ?></td>
                                                <td><?= $ddd->B ?></td>
                                                <td><?= $ddd->C ?></td>
                                                <td><?= $ddd->D ?></td>
                                                <td><?= $ddd->Tidak ?></td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('home/js-home'); ?>
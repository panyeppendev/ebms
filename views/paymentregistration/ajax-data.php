<div class="col-12">
    <div class="card" style="height: 71.8vh;">
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0" style="height: 100%;" id="cardScroll">
            <table class="table table-head-fixed table-hover">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th colspan="2" class="text-center">NAMA</th>
                        <th>ALAMAT</th>
                        <th>DOMISILI</th>
                        <th>NOMINAL</th>
                        <th>OPSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($datas) {
                        $no = 1;
                        foreach ($datas as $data) {
                            $avatarPath = FCPATH . 'assets/avatars/' . $data->student_id . '.jpg';

                            if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
                                $avatar = base_url('assets/avatars/default.jpg');
                            } else {
                                $avatar = base_url('assets/avatars/' . $data->student_id . '.jpg');
                            }

                            $city = str_replace(['Kabupaten', 'Kota'], '', $data->city);
                    ?>
                            <tr>
                                <td class="align-middle"><?= $no++ ?></td>
                                <td>
                                    <img style="border-radius: 5px;" alt="Foto <?= $data->name ?>" width="45px" class="table-avatar" src="<?= $avatar ?>">
                                </td>
                                <td class="align-middle">
                                    <b><?= $data->name ?></b>
                                    <br>
                                    <small class="text-success">
                                        Kuitansi : <?= $data->id ?>
                                    </small>
                                </td>
                                <td class="align-middle"><?= $data->village . '<br>' . $city ?></td>
                                <td class="align-middle">
                                    <?= $data->domicile ?>
                                </td>
                                <td class="align-middle">
                                    <?= 'Rp. ' . number_format($data->amount, 0, ',', '.') ?>
                                </td>
                                <td class="align-middle">
                                    <div class="btn-group">
										<form target="_blank" action="<?= base_url() ?>paymentregistration/printdata" method="post">
											<input type="hidden" name="id_package" id="id-package" value="<?= $data->id ?>">
											<button type="submit" class="btn btn-default btn-sm" title="Print Kuitansi">
												<i class="fas fa-print"></i>
											</button>
										</form>
                                    </div>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<tr class="text-center"><td colspan="8"><h6 class="text-danger">Tak ada data untuk ditampilkan</h6></td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <b><b>
        </div>
    </div>
</div>

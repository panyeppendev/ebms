<div class="col-12">
    <div class="card" style="height: 71.8vh;">
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0" style="height: 100%;" id="cardScroll">
            <table class="table table-head-fixed table-hover">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th colspan="2" class="text-center">NAMA</th>
                        <th>JABATAN</th>
                        <th>STATUS</th>
                        <th>OPSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($datas) {
                        $no = 1;
                        foreach ($datas as $data) {
                            $avatarPath = FCPATH . 'assets/images/users/' . $data->id . '.jpg';

                            if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
                                $avatar = base_url('assets/images/users/default.png');
                            } else {
                                $avatar = base_url('assets/images/users/' . $data->id . '.jpg');
                            }

							$status = $data->status;
                    ?>
                            <tr>
                                <td class="align-middle"><?= $no++ ?></td>
                                <td class="align-middle">
                                    <img style="border-radius: 5px;" alt="Foto <?= $data->name ?>" width="45px" class="table-avatar" src="<?= $avatar ?>">
                                </td>
                                <td class="align-middle">
                                    <b><?= $data->name ?></b>
                                </td>
								<td class="align-middle">
									<ul>
										<?php
										$roleUser = $this->um->roleUser($data->id);
										if ($roleUser) {
											foreach ($roleUser as $d) {
												?>
												<li>
													<?= $d->name ?>
													<a href="javascript:" onclick="deleteRoleUser(<?= $d->id ?>)">
														<small>Cabut Jabatan</small>
													</a>
												</li>
												<?php
											}
										} else {
											echo '<li class="text-danger">Jabatan user belum diatur</li>';
										}
										?>
									</ul>
								</td>
                                <td class="align-middle">
									<button onclick="updateStatus(<?= $data->id ?>, 'ACTIVE')" type="button" class="btn btn-default btn-sm <?= ($status == 'ACTIVE') ? 'd-none' : '' ?>" title="Aktifkan Pengguna Ini">
										<i class="fas fa-user-check text-success"></i> Aktifkan
									</button>

									<button onclick="updateStatus(<?= $data->id ?>, 'INACTIVE')" type="button" class="btn btn-default btn-sm <?= ($status != 'ACTIVE') ? 'd-none' : '' ?>" title="Non-Aktifkan Pengguna Ini">
										<i class="fas fa-user-slash text-danger"></i> Non-Aktifkan
									</button>
                                </td>
                                <td class="align-middle">
									<button onclick="setID('<?= $data->id ?>')" data-toggle="modal" data-target="#modal-set" type="button" class="btn btn-default btn-sm" title="Atur Jabatan">
										<i class="fas fa-cogs"></i>
									</button>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<tr class="text-center"><td colspan="5"><h6 class="text-danger">Tak ada data untuk ditampilkan</h6></td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <b>Total Pengguna : <?= $amount ?> orang<b>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-disbursement" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Form Tambah Pencairan Uang Saku</h6>
                <div class="align-middle" data-dismiss="modal" title="Tutup" style="cursor: pointer">
                    <i class="fas fa-times-circle text-danger"></i>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-8">
                        <div class="row">
                            <div class="col-5">
                                <div class="form-group row mb-0">
                                    <label for="nis" class="col-sm-4 col-form-label">NIS</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nis" name="nis">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group row mb-0">
                                    <label for="nominal" class="col-sm-4 col-form-label">Nominal</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nominal" name="nominal">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-5">
                                <div class="card mb-0">
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                            <img src="<?= base_url() ?>assets/avatars/4300001.jpg" alt="IMAGE OF" style="width: 95%; border-radius: 3px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-7">
                                <dl class="row">
                                    <dt class="col-sm-3 font-weight-normal">NIS</dt>
                                    <dd class="col-sm-9">4300001</dd>
                                    <dt class="col-sm-3 font-weight-normal">Nama</dt>
                                    <dd class="col-sm-9 font-weight-bold">RAHMAN FARUQ</dd>
                                    <dt class="col-sm-3 font-weight-normal">Domisili</dt>
                                    <dd class="col-sm-9">Imam Hambali</dd>
                                    <dt class="col-sm-3 font-weight-normal">Alamat</dt>
                                    <dd class="col-sm-9">
                                        Dusun Karpote RT 001/RW 002 <br>
                                        Larangan Slampar, Tlanakan <br>
                                        Pamekasan Jawa Timur
                                    </dd>
                                    <dt class="col-sm-3 font-weight-normal">Diniyah</dt>
                                    <dd class="col-sm-9">Jilid 1</dd>
                                    <dt class="col-sm-3 font-weight-normal">Formal</dt>
                                    <dd class="col-sm-9">1 - SD</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="callout callout-success py-2">
                            <h6 class="m-0">Paket A + Transport</h6>
                            <hr class="my-2">
                            <table style="width: 100%">
                                <tbody>
                                    <tr>
                                        <td>Uang Saku</td>
                                        <td>Rp.</td>
                                        <td>10.000</td>
                                    </tr>
                                    <tr>
                                        <td>Simpanan</td>
                                        <td>Rp.</td>
                                        <td>10.000</td>
                                    </tr>
                                    <tr>
                                        <td>Kantin</td>
                                        <td>Rp.</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <td>Tunai</td>
                                        <td>Rp.</td>
                                        <td>0</td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr class="my-2">
                            Jumlah Uang Saku : <b class="text-success"> Rp. 20.000</b>
                        </div>
                        <div class="text-xs pl-3">
                            <table style="width: 100%">
                                <tbody>
                                    <tr>
                                        <td>22 Jan</td>
                                        <td>20.000</td>
                                        <td class="text-center">Tunai</td>
                                    </tr>
                                    <tr>
                                        <td>22 Jan</td>
                                        <td>20.000</td>
                                        <td class="text-center">Kantin</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-start p-2">
                <span class="text-danger text-xs">
                    <ul class="mb-0">
                        <li>Pastikan cursor fokus pada bidang inputan</li>
                        <li>Tekan ENTER untuk cek dan simpan</li>
                    </ul>
                </span>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
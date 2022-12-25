<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <label for="nis">ID CARD <small class="text-success">Tekan F2 untuk fokus</small> </label>
                            <input data-inputmask="'mask' : '999999999999999'" data-mask="" type="text" class="form-control" id="id" name="id" autofocus>
                        </div>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
            <div class="col-8" id="show-data">

            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('card/js-card'); ?>
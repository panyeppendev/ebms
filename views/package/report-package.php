<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title; ?></title>
    <link rel="shortcut icon" href="<?= base_url() ?>/assets/logo.png">
    <style>
        * {
            font-family: 'Courier New', Courier, monospace;
            /* font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; */
            font-size: 10pt;
        }

        .container {
            width: 800px;
            display: relative;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .col-12 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .col-8 {
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }

        .col-7 {
            flex: 0 0 58.333333%;
            max-width: 58.333333%;
        }

        .col-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }

        .col-5 {
            flex: 0 0 41.666667%;
            max-width: 41.666667%;
        }

        .col-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }

        .logo {
            width: 100%;
            margin-top: 8px;
        }

        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin-top: 0.1rem;
            margin-bottom: 0.1rem;
            margin-block-start: 0px;
            margin-block-end: 0px;
            font-family: inherit;
            font-weight: bold;
            color: inherit;
        }

        .invoice-title {
            font-size: 3.5rem;
        }

        .text-right {
            text-align: end;
        }

        hr {
            margin-top: 0.6rem;
            margin-bottom: 0.6rem;
            border: 0;
            border-top: 1px solid rgb(0 0 0 / 82%)
        }

        table {
            border-collapse: collapse;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            background-color: transparent;
        }

        .tablestripped {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            background-color: transparent;
        }

        .tablebottom {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            background-color: transparent;
        }

        .mb-0 {
            margin-bottom: 0px;
        }

        .mt-2 {
            margin-top: 3rem;
        }

        .mb-2 {
            margin-bottom: 2rem;
        }

        .tablestripped th {
            vertical-align: top;
            border-top: 1px solid #999797;
            border-bottom: 1px solid #999797;
        }

        .tablestripped td {
            vertical-align: top;
            border-top: 1px solid #999797;
        }

        .tablebottom td,
        .tablebottom th {
            vertical-align: top;
            border-top: 1px dashed #999797;
        }

        #line-bottom {
            border-top: 1px solid #999797;
        }

        .table-xl th {
            padding: 0.5rem;
        }

        .table-xl td {
            padding: 0.3rem;
        }

        .table-sm td,
        .table-sm th {
            padding: 0.2rem;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .notes {
            padding-left: 25px;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h6>
                    DATA PEMBAYARAN PAKET TAHAP KE-5
                </h6>
                <table>
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NAMA</th>
                            <th>DOMISILI</th>
                            <th>ALAMAT</th>
                            <th>PAKET</th>
                            <th>SAKU</th>
                            <th>SARAPAN</th>
                            <th>DPU</th>
                            <th>ADMIN</th>
                            <th>TRANSPORT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $pocket = [
                            'A' => 150000,
                            'B' => 150000,
                            'C' => 300000,
                            'D' => 300000,
                            'UNKNOWN' => 0,
                            '' => 0,
                            NULL => 0
                        ];

                        $moorning = [
                            'A' => 0,
                            'B' => 120000,
                            'C' => 0,
                            'D' => 120000,
                            'UNKNOWN' => 0,
                            '' => 0,
                            NULL => 0
                        ];
                        if ($datas) {
                            $no = 1;
                            foreach ($datas as $d) {
                                $package = $d->package;
                                if ($package == 'UNKNOWN' || $package == '' || $package == NULL) {
                                    $packageFinal = '';
                                } else {
                                    $packageFinal = $package;
                                }
                                $qty = $d->amount;
                        ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= $d->name ?></td>
                                    <td><?= $d->domicile ?></td>
                                    <td><?= $d->village . ', ' . $d->city ?></td>
                                    <td class="text-center"><?= $packageFinal ?></td>
                                    <td><?= $pocket[$package] ?></td>
                                    <td><?= $moorning[$package] ?></td>
                                    <td><?= ($package == 'UNKNOWN') ? 0 : 200000 ?></td>
                                    <td><?= ($package == 'UNKNOWN') ? 0 : 15000 ?></td>
                                    <td><?= $d->transport ?></td>
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
    <!-- <script>
        window.print()
        setTimeout(() => {
            window.close()
        }, 2000);
    </script> -->
</body>

</html>
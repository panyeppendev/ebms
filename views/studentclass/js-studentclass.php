<script>
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

    $('body').on('keyup', e => {
        if (e.keyCode == 113) {
            $('#changeName').focus().val('')
        }
    })

    $(function() {
        loadData()
    })

    const loadData = () => {
        let name = $('#changeName').val()
        let classn = $('#changeClass').val()
		let rombel = $('#changeRombel').val()
		let level = $('#changeLevel').val()
		$.ajax({
            url: '<?= base_url() ?>studentclass/getdata',
            method: 'POST',
            data: {
                name,
                class: classn,
				rombel,
				level
            },
            success: function(response) {
                $('#showStudent').html(response)
            }
        })
    }

    $('#changeDomicile').on('change', function() {
        $('#domicile_print').val($(this).val())
    })

    const detailData = id => {
        $('#modal-detail').modal('show')
        $.ajax({
            url: '<?= base_url() ?>studentclass/detaildata',
            method: 'POST',
            data: {
                id
            },
            beforeSend: function() {
                $('#showDetail').text('Tunggu! Data sedang dicari...............')
            },
            success: function(response) {
                $('#showDetail').html(response)
            }
        })
    }

    $('#addstudent').on('click', function() {
        $('#id').val('0')
        $('#modal-student').modal('show')
        $('#alert-administration').removeClass('d-none')
        let period = $('#changePeriod').val()
        let currentPeriod = $('#current_current').val()
        if (period == currentPeriod || period == '') {
            $('#date_of_entry').val('<?= getMasehiExplode()[2] ?>')
            $('#month_of_entry').val('<?= getMasehiExplode()[1] ?>')
            $('#year_of_entry').val('<?= getMasehiExplode()[0] ?>')

            $('#date_of_entry_hijriah').val('<?= getHijriExplode()[2] ?>')
            $('#month_of_entry_hijriah').val('<?= getHijriExplode()[1] ?>')
            $('#year_of_entry_hijriah').val('<?= getHijriExplode()[0] ?>')
            $('.date-administration').attr('readonly', true)
            $('.date-administration').attr('aria-disabled', true)
        } else {
            $('#date_of_entry').val('')
            $('#month_of_entry').val('')
            $('#year_of_entry').val('')

            $('#date_of_entry_hijriah').val('')
            $('#month_of_entry_hijriah').val('')
            $('#year_of_entry_hijriah').val('')
            $('.date-administration').attr('readonly', false)
            $('.date-administration').attr('aria-disabled', false)
        }
    })

    $('#changePeriod').on('change', function() {
        let period = $(this).val()

        if (period == '') {
            $('#period').val('<?= $period ?>')
        } else {
            $('#period').val(period)
        }
    })


    $('.form-control').keypress(function(e) {
        if (e.which == 13 && $(this).val() != '') {
            e.preventDefault()
            let $next = $('[tabIndex=' + (+this.tabIndex + 1) + ']');
            if (!$next.length) {
                $next = $('[tabIndex=1]');
            }
            $next.focus().select();
        }
    });

    let iddata = ''
    $('#province').on('keypress', function() {
        getAddress('provinces', $('#province'), $('#city'))
    })

    $('#city').on('keypress', function() {
        getAddress('cities', $('#city'), $('#district'))
    })

    $('#district').on('keypress', function() {
        getAddress('districts', $('#district'), $('#village'))
    })

    $('#village').on('keypress', function() {
        getAddress('villages', $('#village'), $('#postal_code'))
    })

    const getAddress = (url, el, target) => {
        let api = ''
        if (url == 'provinces') {
            api = '<?= base_url() ?>data/' + url
        } else {
            api = '<?= base_url() ?>data/' + url + '/' + iddata
        }
        el.autocomplete({
            source: api,
            select: (event, ui) => {
                if (url == 'villages') {
                    target.val(ui.item.description)
                    $('#father_nik').focus().select()
                } else {
                    iddata = ui.item.description
                    target.prop('readonly', false)
                    target.focus().select()
                }
            }
        });
    }

    $('#modal-student').on('hidden.bs.modal', () => {
        $('#formstudent')[0].reset();
        $('.messages').html('')
        $('.form-control-border').removeClass('is-invalid')
        $('#city').prop('readonly', true)
        $('#district').prop('readonly', true)
        $('#village').prop('readonly', true)
    })

    $('#modal-student').on('shown.bs.modal', () => {
        $('#nik').focus().select()
    })

    $('#cancel').on('click', function() {
        Swal.fire({
            title: 'Kamu yakin, nih?',
            text: 'Tindakan ini akan mereset semua inputan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin dong',
            cancelButtonText: 'Gak jadi'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#modal-student').modal('hide')
            }
        })
    })

    $('#save').on('click', function() {
        Swal.fire({
            title: 'Kamu yakin, nih?',
            text: 'Pastikan semua inputan sudah terisi',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin dong',
            cancelButtonText: 'Gak jadi'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>student/save',
                    method: 'post',
                    data: $('#formstudent').serialize(),
                    dataType: 'JSON',
                    beforeSend: function() {
                        $('#save').prop('disabled', true)
                    },
                    success: function(data) {

                        $('#save').prop('disabled', false)
                        if (data.status == 400) {
                            let errors = data.errors
                            if (errors) {
                                if (errors.nik) {
                                    $('#errornik').html(errors.nik)
                                    $('#nik').addClass('is-invalid')
                                } else {
                                    $('#errornik').html('')
                                    $('#nik').removeClass('is-invalid')
                                }

                                if (errors.kk) {
                                    $('#errorkk').html(errors.kk)
                                    $('#kk').addClass('is-invalid')
                                } else {
                                    $('#errorkk').html('')
                                    $('#kk').removeClass('is-invalid')
                                }

                                if (errors.name) {
                                    $('#errorname').html(errors.name)
                                    $('#name').addClass('is-invalid')
                                } else {
                                    $('#errorname').html('')
                                    $('#name').removeClass('is-invalid')
                                }

                                if (errors.last_education) {
                                    $('#errorlast_education').html(errors.last_education)
                                    $('#last_education').addClass('is-invalid')
                                } else {
                                    $('#errorlast_education').html('')
                                    $('#last_education').removeClass('is-invalid')
                                }

                                if (errors.place_of_birth) {
                                    $('#errorplace_of_birth').html(errors.place_of_birth)
                                    $('#place_of_birth').addClass('is-invalid')
                                } else {
                                    $('#errorplace_of_birth').html('')
                                    $('#place_of_birth').removeClass('is-invalid')
                                }

                                if (errors.date_of_birth) {
                                    $('#errordate_of_birth').html(errors.date_of_birth)
                                    $('#date_of_birth').addClass('is-invalid')
                                } else {
                                    $('#errordate_of_birth').html('')
                                    $('#date_of_birth').removeClass('is-invalid')
                                }

                                if (errors.month_of_birth) {
                                    $('#errordate_of_birth').html(errors.month_of_birth)
                                    $('#month_of_birth').addClass('is-invalid')
                                } else {
                                    $('#errordate_of_birth').html('')
                                    $('#month_of_birth').removeClass('is-invalid')
                                }

                                if (errors.year_of_birth) {
                                    $('#errordate_of_birth').html(errors.year_of_birth)
                                    $('#year_of_birth').addClass('is-invalid')
                                } else {
                                    $('#errordate_of_birth').html('')
                                    $('#year_of_birth').removeClass('is-invalid')
                                }

                                if (errors.address) {
                                    $('#erroraddress').html(errors.address)
                                    $('#address').addClass('is-invalid')
                                } else {
                                    $('#erroraddress').html('')
                                    $('#address').removeClass('is-invalid')
                                }

                                if (errors.province) {
                                    $('#errorprovince').html(errors.province)
                                    $('#province').addClass('is-invalid')
                                } else {
                                    $('#errorprovince').html('')
                                    $('#province').removeClass('is-invalid')
                                }

                                if (errors.city) {
                                    $('#errorcity').html(errors.city)
                                    $('#city').addClass('is-invalid')
                                } else {
                                    $('#errorcity').html('')
                                    $('#city').removeClass('is-invalid')
                                }

                                if (errors.district) {
                                    $('#errordistrict').html(errors.district)
                                    $('#district').addClass('is-invalid')
                                } else {
                                    $('#errordistrict').html('')
                                    $('#district').removeClass('is-invalid')
                                }

                                if (errors.village) {
                                    $('#errorvillage').html(errors.village)
                                    $('#village').addClass('is-invalid')
                                } else {
                                    $('#errorvillage').html('')
                                    $('#village').removeClass('is-invalid')
                                }

                                if (errors.father_nik) {
                                    $('#errorfather_nik').html(errors.father_nik)
                                    $('#father_nik').addClass('is-invalid')
                                } else {
                                    $('#errorfather_nik').html('')
                                    $('#father_nik').removeClass('is-invalid')
                                }

                                if (errors.father) {
                                    $('#errorfather').html(errors.father)
                                    $('#father').addClass('is-invalid')
                                } else {
                                    $('#errorfather').html('')
                                    $('#father').removeClass('is-invalid')
                                }

                                if (errors.mother_nik) {
                                    $('#errormother_nik').html(errors.mother_nik)
                                    $('#mother_nik').addClass('is-invalid')
                                } else {
                                    $('#errormother_nik').html('')
                                    $('#mother_nik').removeClass('is-invalid')
                                }

                                if (errors.mother) {
                                    $('#errormother').html(errors.mother)
                                    $('#mother').addClass('is-invalid')
                                } else {
                                    $('#errormother').html('')
                                    $('#mother').removeClass('is-invalid')
                                }

                                if (errors.phone) {
                                    $('#errorphone').html(errors.phone)
                                    $('#phone').addClass('is-invalid')
                                } else {
                                    $('#errorphone').html('')
                                    $('#phone').removeClass('is-invalid')
                                }

                                if (errors.status_of_domicile) {
                                    $('#errorstatus_of_domicile').html(errors.status_of_domicile)
                                    $('#status_of_domicile').addClass('is-invalid')
                                } else {
                                    $('#errorstatus_of_domicile').html('')
                                    $('#status_of_domicile').removeClass('is-invalid')
                                }

                                if (errors.domicile) {
                                    $('#errordomicile').html(errors.domicile)
                                    $('#domicile').addClass('is-invalid')
                                } else {
                                    $('#errordomicile').html('')
                                    $('#domicile').removeClass('is-invalid')
                                }

                                if (errors.class) {
                                    $('#errorclass').html(errors.class)
                                    $('#class').addClass('is-invalid')
                                } else {
                                    $('#errorclass').html('')
                                    $('#class').removeClass('is-invalid')
                                }

                                if (errors.class_of_formal) {
                                    $('#errorclass_of_formal').html(errors.class_of_formal)
                                    $('#class_of_formal').addClass('is-invalid')
                                } else {
                                    $('#errorclass_of_formal').html('')
                                    $('#class_of_formal').removeClass('is-invalid')
                                }

                                if (errors.level_of_formal) {
                                    $('#errorlevel_of_formal').html(errors.level_of_formal)
                                    $('#level_of_formal').addClass('is-invalid')
                                } else {
                                    $('#errorlevel_of_formal').html('')
                                    $('#level_of_formal').removeClass('is-invalid')
                                }

                                if (errors.date_of_entry) {
                                    $('#errordate_of_entry').html(errors.date_of_entry)
                                    $('#date_of_entry').addClass('is-invalid')
                                } else {
                                    $('#errordate_of_entry').html('')
                                    $('#date_of_entry').removeClass('is-invalid')
                                }

                                if (errors.month_of_entry) {
                                    $('#errordate_of_entry').html(errors.month_of_entry)
                                    $('#month_of_entry').addClass('is-invalid')
                                } else {
                                    $('#errordate_of_entry').html('')
                                    $('#month_of_entry').removeClass('is-invalid')
                                }

                                if (errors.year_of_entry) {
                                    $('#errordate_of_entry').html(errors.year_of_entry)
                                    $('#year_of_entry').addClass('is-invalid')
                                } else {
                                    $('#errordate_of_entry').html('')
                                    $('#year_of_entry').removeClass('is-invalid')
                                }

                                if (errors.date_of_entry_hijriah) {
                                    $('#errordate_of_entry_hijriah').html(errors.date_of_entry_hijriah)
                                    $('#date_of_entry_hijriah').addClass('is-invalid')
                                } else {
                                    $('#errordate_of_entry_hijriah').html('')
                                    $('#date_of_entry_hijriah').removeClass('is-invalid')
                                }

                                if (errors.month_of_entry_hijriah) {
                                    $('#errordate_of_entry_hijriah').html(errors.month_of_entry_hijriah)
                                    $('#month_of_entry_hijriah').addClass('is-invalid')
                                } else {
                                    $('#errordate_of_entry_hijriah').html('')
                                    $('#month_of_entry_hijriah').removeClass('is-invalid')
                                }

                                if (errors.year_of_entry_hijriah) {
                                    $('#errordate_of_entry_hijriah').html(errors.year_of_entry_hijriah)
                                    $('#year_of_entry_hijriah').addClass('is-invalid')
                                } else {
                                    $('#errordate_of_entry_hijriah').html('')
                                    $('#year_of_entry_hijriah').removeClass('is-invalid')
                                }
                            }
                            return false
                        }

                        $('.messages').html('')
                        $('.form-control-border').removeClass('is-invalid')
                        loadData()

                        let type = data.type
                        if (type == 'ENTRI') {
                            Swal.fire({
                                title: 'Mantab..!',
                                text: 'Pilih lanjut jika ingin nambah data lagi',
                                icon: 'success',
                                showDenyButton: true,
                                confirmButtonColor: '#3085d6',
                                denyButtonColor: '#d33',
                                confirmButtonText: 'Lanjut',
                                denyButtonText: 'Tutup saja'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#city').prop('readonly', true)
                                    $('#district').prop('readonly', true)
                                    $('#village').prop('readonly', true)
                                    $('#formstudent')[0].reset();
                                } else if (result.isDenied) {
                                    $('#modal-student').modal('hide')
                                }
                            })
                        } else {
                            $('#modal-student').modal('hide')
                            toastr.success('Yeaahh..! Satu data berhasil diperbarui')
                            detailData(data.id)
                        }
                    }
                })
            }
        })
    })

    const editData = id => {
        $.ajax({
            url: '<?= base_url() ?>student/cekid',
            method: 'POST',
            data: {
                id
            },
            dataType: 'JSON',
            success: function(response) {
                let status = response.status
                if (status == 400) {
                    toastr.error('Oppsss..! Data tidak ditemukan')
                    return false
                }

                let data = response.data
                let dateOfBirth = data.date_of_birth
                let dateSeparate = dateOfBirth.split('-')
                let dateOfEntry = data.date_of_entry
                let dateEntrySeparate = dateOfEntry.split('-')
                let dateOfEntryHijriah = data.date_of_entry_hijriah
                let dateEntrySeparateHijriah = dateOfEntryHijriah.split('-')
                console.log(data.domicile);

                $('#id').val(id)
                $('#period').val(data.period)
                $('#nik').val(data.nik)
                $('#kk').val(data.kk)
                $('#name').val(data.name)
                $('#last_education').val(data.last_education)
                $('#place_of_birth').val(data.place_of_birth)
                $('#date_of_birth').val(dateSeparate[2])
                $('#month_of_birth').val(dateSeparate[1])
                $('#year_of_birth').val(dateSeparate[0])
                $('#address').val(data.address)
                $('#postal_code').val(data.postal_code)
                $('#province').val(data.province)
                $('#city').val(data.city)
                $('#district').val(data.district)
                $('#village').val(data.village)
                $('#father_nik').val(data.father_nik)
                $('#father').val(data.father)
                $('#mother_nik').val(data.mother_nik)
                $('#mother').val(data.mother)
                $('#phone').val(data.phone)
                $('#status_of_domicile').val(data.status_of_domicile)
                $('#domicile').val(data.domicile)
                $('#class').val(data.class)
                $('#class_of_formal').val(data.class_of_formal)
                $('#level_of_formal').val(data.level_of_formal)
                $('#date_of_entry').val(dateEntrySeparate[2])
                $('#month_of_entry').val(dateEntrySeparate[1])
                $('#year_of_entry').val(dateEntrySeparate[0])
                $('#date_of_entry_hijriah').val(dateEntrySeparateHijriah[2])
                $('#month_of_entry_hijriah').val(dateEntrySeparateHijriah[1])
                $('#year_of_entry_hijriah').val(dateEntrySeparateHijriah[0])

                $('.date-administration').attr('readonly', true)
                $('.date-administration').attr('aria-disabled', true)
                $('#alert-administration').addClass('d-none')

                $('#modal-detail').modal('hide')
                $('#modal-student').modal('show')
            }
        })
    }

    function copyToClipboard(text) {
        var sampleTextarea = document.createElement("textarea");
        document.body.appendChild(sampleTextarea);
        sampleTextarea.value = text; //save main text in it
        sampleTextarea.select(); //select textarea contenrs
        document.execCommand("copy");
        document.body.removeChild(sampleTextarea);
        toastr.success('ID berhasil disalin ke clipboard')
    }

    const makeCard = (el, id) => {
        Swal.fire({
            title: 'Yakin, nih?',
            text: 'Tindakan ini tidak bisa dibatalkan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin, dong',
            cancelButtonText: 'Masih ragu'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>student/makecard',
                    method: 'POST',
                    data: {
                        id
                    },
                    dataType: 'JSON',
                    beforeSend: () => {
                        $(el).prop('disabled', true)
                    },
                    success: res => {
                        $(el).prop('disabled', false)
                        let status = res.status
                        if (status == 400) {
                            toastr.error(`Gagal! ${res.message}`)
                            return false
                        }

                        toastr.success('Yeahh..! Kartu sudah dibuat')
                        detailData(id)
                    }
                })
            }
        })
    }

    const activeCard = (el, id, nis) => {
        Swal.fire({
            title: 'Yakin, nih?',
            text: 'Pastikan kartu sudah diprint out',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin, dong',
            cancelButtonText: 'Masih ragu'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>student/activecard',
                    method: 'POST',
                    data: {
                        id
                    },
                    dataType: 'JSON',
                    beforeSend: () => {
                        $(el).prop('disabled', true)
                    },
                    success: res => {
                        $(el).prop('disabled', false)
                        let status = res.status
                        if (status == 400) {
                            toastr.error(`Oppss..! ${ re.message }`)
                            return false
                        }

                        toastr.success('Yeaah..! Satu kartu berhasil diaktivasi')
                        detailData(nis)
                    }
                })
            }
        })
    }

    const blockCard = (el, id, nis) => {
        Swal.fire({
            title: 'Yakin, nih?',
            text: 'Tindakan ini tidak bisa dibatalkan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin, dong',
            cancelButtonText: 'Masih ragu'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url() ?>student/blockcard',
                    method: 'POST',
                    data: {
                        id
                    },
                    dataType: 'JSON',
                    beforeSend: () => {
                        $(el).prop('disabled', true)
                    },
                    success: res => {
                        $(el).prop('disabled', false)
                        let status = res.status
                        if (status == 400) {
                            toastr.error(`Oppss..! ${ re.message }`)
                            return false
                        }

                        toastr.success('Yeaah..! Satu kartu berhasil diaktivasi')
                        detailData(nis)
                    }
                })
            }
        })
    }
</script>
</body>

</html>

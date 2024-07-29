<?php $this->load->view('template/header'); ?>
<?= $this->session->flashdata('pesan') ?>
<style>
#preview_logo {
    display: none;
}
</style>

<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <a href="" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahPemasukan"><i
                        class="fa fa-plus"></i>
                    Tambah</a>
                <div class="clearfix"></div>
            </div>

            <?php $no_kegiatan = 1; foreach($kegiatan as $k): ?>
                <h2>
                 <b><span class="badge badge-primary fa fa-apple"> <?= $k['nama_kegiatan'] ?></span></b>
                </h2>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <table id="datatable-buttons-<?= $no_kegiatan ?>" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Pemasukan</th>
                                        <th>Nominal</th>
                                        <th>Tgl Pemasukan</th>
                                        <th>Bukti</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no_laporan = 1; foreach ($data as $pemasukan): ?>
                                        <?php if ($pemasukan['id_kegiatan'] == $k['id_kegiatan']): ?>
                                            <tr>
                                                <td><?= $no_laporan++ ?></td>
                                                <td><?= $pemasukan['jenis_pemasukan'] ?></td>
                                                <td><?= rupiah($pemasukan['nominal']) ?></td>
                                                <td><?= tgl_indo($pemasukan['tgl_pemasukan']) ?></td>
                                                <td>
                                                <?php $stt = $pemasukan['bukti_transfer']; ?>
                                                <?php if($stt == ''){ ?>
                                                <img src="<?= base_url('themes/no_images.png') ?>" width="50px">
                                                <a href="" class="btn btn-sm btn-primary" data-toggle="modal"
                                                    data-target="#uploadBukti<?= $pemasukan['id_pemasukan'] ?>"><i
                                                        class="fa fa-upload"></i></a>
                                                <?php }else{ ?>
                                                <img src="<?= base_url('themes/bukti_transfer/'.$pemasukan['bukti_transfer']) ?>"
                                                    width="200px">
                                                <a href="javascript:void(0)"
                                                    onclick="hapusbuktitransfer('<?= $pemasukan['id_pemasukan'] ?>')"
                                                    class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                                <?php } ?>
                                                </td>
                                                    
                                                <td>
                                                    <a href="" class="btn btn-warning" data-toggle="modal"
                                                    data-target="#edit<?= $pemasukan['id_pemasukan'] ?>"><i class="fa fa-edit"></i>
                                                    Edit</a>
                                                </td>
                                            </tr>
                                            <?php $no_laporan++; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php $no_kegiatan++; endforeach; ?>
            <!-- /page content -->
        </div>
    </div>
</div>



<!-- modal tambah -->
<div class="modal fade" id="modalTambahPemasukan" tabindex="-1" role="dialog" aria-labelledby="modalTambahPemasukanLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahPemasukanLabel">Tambah <?= $judul ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="" style="width:100%">
                    <form id="add" method="post">
                         <tr>
                            <td><label for="nama">Nama Kegiatan:</label></td>
                        </tr>
                        <tr>
                                <td>
                                    <select name="id_kegiatan" class="form-control" required="">
                                        <?php 
                                          $kegiatan;
                                          foreach($kegiatan as $row): ?>
                                            <option value="<?= $row['id_kegiatan'] ?>"><?= $row['nama_kegiatan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                        </tr>
                        <tr>
                            <td><label for="nama">Jenis Pemasukan:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="jenis_pemasukan" id="jenis_pemasukan" class="form-control" autocomplete="off"
                                    required placeholder="Jenis Pemasukan"></td>
                        </tr>
                        <tr>
                            <td><label for="nominal">Nominal:</label></td>
                        </tr>
                        <tr>
                            <td><input type="number" name="nominal" id="nominal" class="form-control" autocomplete="off"
                                    required placeholder="Nominal"></td>
                        </tr>
                        <tr>
                            <td><label for="tgl_pemasukan">Tanggal Pemasukan:</label></td>
                        </tr>
                        <tr>
                            <td><input type="date" name="tgl_pemasukan" id="tgl_pemasukan" class="form-control" autocomplete="off"
                                    required placeholder="Tanggal Pemasukan" value="<?= date('Y-m-d') ?>"></td>
                        </tr>
                        
                        <tr>
                            <td><br><input type="submit" name="kirim" value="Simpan" class="btn btn-success"></td>
                        </tr>
                    </form>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- Modal edit bukti transfer -->
<?php foreach($data as $pemasukan): ?>
    <div class="modal fade" id="uploadBukti<?= $pemasukan['id_pemasukan'] ?>" tabindex="-1" role="dialog"
        aria-labelledby="uploadBukti<?= $pemasukan['id_pemasukan'] ?>Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadBukti<?= $pemasukan['id_pemasukan'] ?>">Upload Bukti Transfer</h5>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="" style="width:100%">
                        <form id="uploadBukti" method="post">
                            <input type="hidden" name="id_pemasukan" value="<?= $pemasukan['id_pemasukan'] ?>"
                                class="form-control" readonly>
                            <tr>
                                <td>
                                    <label>Bukti Transfer</label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="file" name="foto" id="bukti_transfer" class="form-control"
                                        onchange="previewLOGO()" required>
                                    <img id="preview_logo" alt="image preview" width="50%" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <br>
                                    <button href="" class="btn btn-warning" data-dismiss="modal">Kembali</button>
                                    &nbsp;&nbsp;
                                    <input type="submit" name="kirim" value="Simpan" class="btn btn-success">
                                    &nbsp;&nbsp;
                                </td>
                            </tr>
                        </form>                   
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

<!-- modal edit pemasukan -->
<?php foreach($data as $pemasukan): ?>
<div class="modal fade" id="edit<?= $pemasukan['id_pemasukan'] ?>" tabindex="-1" role="dialog"
    aria-labelledby="modalEditpemasukanLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-default">
                <h5 class="modal-title" id="modalEditpemasukanLabel">Edit <?= $judul ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-danger">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="" style="width:100%">
                    <form id="edit" method="post">
                        <input type="hidden" name="id_pemasukan" value="<?= $pemasukan['id_pemasukan'] ?>">
                        <tr>
                            <td><label for="nama">Nama Kegiatan:</label></td>
                        </tr>
                        <tr>
                                <td>
                                    <select name="id_kegiatan" class="form-control" required="">
                                        <?php 
                                          $kegiatan;
                                          foreach($kegiatan as $row): ?>
                                            <option value="<?= $row['id_kegiatan'] ?>" <?= $row['id_kegiatan'] == $pemasukan['id_kegiatan'] ? 'selected' : '' ?>><?= $row['nama_kegiatan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                        </tr>
                        <tr>
                            <td><label for="nama">Jenis pemasukan:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="jenis_pemasukan" id="jenis_pemasukan" class="form-control" autocomplete="off"
                                    value="<?= $pemasukan['jenis_pemasukan'] ?>" required></td>
                        </tr>     
                        <tr>
                            <td><label for="nominal">Nominal:</label></td>
                        </tr>
                        <tr>
                            <td><input type="number" name="nominal" id="nominal" class="form-control" autocomplete="off"
                                    value="<?= $pemasukan['nominal'] ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="tgl_pemasukan">Tanggal Pemasukan:</label></td>
                        </tr>
                        <tr>
                            <td><input type="date" name="tgl_pemasukan" id="tgl_pemasukan" class="form-control" autocomplete="off"
                                    value="<?= $pemasukan['tgl_pemasukan'] ?>" required></td>
                        </tr>
                        
                        <tr>
                            <td>
                                <br><input type="submit" name="kirim" value="Simpan" class="btn btn-success">
                                <a href="javascript:void(0)" onclick="hapuspemasukan('<?= $pemasukan['id_pemasukan'] ?>')"
                                    class="btn btn-danger">Hapus</a>
                            </td>
                        </tr>
                    </form>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>


<script>
    $(document).ready(function() {
        <?php $no_kegiatan = 1; foreach($kegiatan as $k): ?>
            $('#datatable-buttons-<?= $no_kegiatan ?>').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        <?php $no_kegiatan++; endforeach; ?>
    });

    
//add data
$(document).ready(function() {
    $('#add').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('user/keuangan/pemasukan/api_add') ?>",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function(data) {
                if (data.status) {
                    $('#modalTambahPemasukan');
                    $('#add')[0].reset();
                    swal({
                        title: "Berhasil",
                        text: "Data berhasil ditambahkan",
                        type: "success",
                        showConfirmButton: true,
                        confirmButtonText: "OKEE",
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    // Hapus tag HTML dari pesan error
                    var errorMessage = $('<div>').html(data.message).text();
                    swal({
                        title: "Gagal",
                        text: errorMessage, // Menampilkan pesan error dari server
                        type: "error",
                        showConfirmButton: true,
                        confirmButtonText: "OK",
                    });
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                // Menampilkan pesan error jika terjadi kesalahan pada AJAX request
                swal({
                    title: "Error",
                    text: "Terjadi kesalahan saat mengirim data",
                    type: "error",
                    showConfirmButton: true,
                    confirmButtonText: "OK",
                });
            }
        });
    });
});

//edit file
$(document).on('submit', '#edit', function(e) {
    e.preventDefault();
    var form_data = new FormData(this);

    $.ajax({
        type: "POST",
        url: "<?php echo site_url('user/keuangan/pemasukan/api_edit/') ?>" + form_data.get('id_pemasukan'),
        dataType: "json",
        data: form_data,
        processData: false,
        contentType: false,
        //memanggil swall ketika berhasil
        success: function(data) {
            $('#edit' + form_data.get('id_pemasukan'));
            swal({
                title: "Berhasil",
                text: "Data Berhasil Diubah",
                type: "success",
                showConfirmButton: true,
                confirmButtonText: "OKEE",
            }).then(function() {
                location.reload();
            });
        },
        //memanggil swall ketika gagal
        error: function(data) {
            swal({
                title: "Gagal",
                text: "Data Gagal Diubah",
                type: "error",
                showConfirmButton: true,
                confirmButtonText: "OKEE",
            }).then(function() {
                location.reload();
            });
        }
    });
});

//ajax hapus pemasukan
function hapuspemasukan(id_pemasukan) {
    swal({
        title: "Apakah Anda Yakin?",
        text: "Data Akan Dihapus",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Tidak, Batalkan!",
        closeOnConfirm: false,
        closeOnCancel: true // Set this to true to close the dialog when the cancel button is clicked
    }).then(function(result) {
        if (result.value) { // Only delete the data if the user clicked on the confirm button
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('user/keuangan/pemasukan/api_hapus/') ?>" + id_pemasukan,
                dataType: "json",
            }).done(function() {
                swal({
                    title: "Berhasil",
                    text: "Data Berhasil Dihapus",
                    type: "success",
                    showConfirmButton: true,
                    confirmButtonText: "OKEE"
                }).then(function() {
                    location.reload();
                });
            }).fail(function() {
                swal({
                    title: "Gagal",
                    text: "Data Gagal Dihapus",
                    type: "error",
                    showConfirmButton: true,
                    confirmButtonText: "OKEE"
                }).then(function() {
                    location.reload();
                });
            });
        } else { // If the user clicked on the cancel button, show a message indicating that the deletion was cancelled
            swal("Batal hapus", "Data Tidak Jadi Dihapus", "error");
        }
    });
}

//upload logo
$(document).on('submit', '#uploadBukti', function(e) {
        e.preventDefault();
        var form_data = new FormData(this);

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('user/keuangan/pemasukan/api_upload/') ?>" +
                form_data.get(
                    'id_pemasukan'),
            dataType: "json",
            data: form_data,
            processData: false,
            contentType: false,
            //memanggil swall ketika berhasil
            success: function(data) {
                $('#uploadBukti' + form_data.get('id_pemasukan'));
                swal({
                    title: "Berhasil",
                    text: "Data Berhasil Diubah",
                    type: "success",
                    showConfirmButton: true,
                    confirmButtonText: "OKEE",
                }).then(function() {
                    location.reload();
                });
            },
            //memanggil swall ketika gagal
            error: function(data) {
                swal({
                    title: "Gagal",
                    text: "Data Gagal Diubah",
                    type: "error",
                    showConfirmButton: true,
                    confirmButtonText: "OKEE",
                }).then(function() {
                    location.reload();
                });
            }
        });
    });


    //ajax hapus foto
    function hapusbuktitransfer(id_pemasukan) {
        swal({
            title: "Apakah Anda Yakin?",
            text: "Background Akan Dihapus",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Tidak, Batalkan!",
            closeOnConfirm: false,
            closeOnCancel: true // Set this to true to close the dialog when the cancel button is clicked
        }).then(function(result) {
            if (result
                .value
            ) { // Only delete the data if the user clicked on the confirm button
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('user/keuangan/pemasukan/api_hapus_foto/') ?>" +
                        id_pemasukan,
                    dataType: "json",
                }).done(function() {
                    swal({
                        title: "Berhasil",
                        text: "Background Berhasil Dihapus",
                        type: "success",
                        showConfirmButton: true,
                        confirmButtonText: "OKEE"
                    }).then(function() {
                        location.reload();
                    });
                }).fail(function() {
                    swal({
                        title: "Gagal",
                        text: "Background Gagal Dihapus",
                        type: "error",
                        showConfirmButton: true,
                        confirmButtonText: "OKEE"
                    }).then(function() {
                        location.reload();
                    });
                });
            } else { // If the user clicked on the cancel button, show a message indicating that the deletion was cancelled
                swal("Batal hapus", "Data Tidak Jadi Dihapus", "error");
            }
        });
    }

    //preview Logo
    function previewLOGO() {
        document.getElementById("preview_logo").style.display = "block";
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("bukti_transfer").files[0]);

        oFReader.onload = function(oFREvent) {
            document.getElementById("preview_logo").src = oFREvent.target.result;
        };

    };

</script>

<?php $this->load->view('template/footer'); ?>
<?php 

function rupiah($angka){
  $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
  return $hasil_rupiah;
}

//format tanggal indonesia
function tgl_indo($tanggal){
  $bulan = array (
    1 =>   'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
  );
  $pecahkan = explode('-', $tanggal);
  
  // variabel pecahkan 0 = tanggal
  // variabel pecahkan 1 = bulan
  // variabel pecahkan 2 = tahun
  
  return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

?>
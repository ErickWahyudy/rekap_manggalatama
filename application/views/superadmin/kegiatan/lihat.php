<?php $this->load->view('template/header'); ?>
<?= $this->session->flashdata('pesan') ?>

<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <a href="" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahKegiatan"><i
                        class="fa fa-plus"></i>
                    Tambah</a>
        

                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Tahun</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <?php $no=1; foreach($data as $kegiatan): ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $kegiatan['nama_kegiatan'] ?></td>
                                    <td><?= $kegiatan['tahun'] ?></td>
                                    <td>
                                        <?php if($kegiatan['status'] == 'ON'): ?>
                                        <span class="btn btn-success">Aktif</span>
                                        <?php else: ?>
                                        <span class="btn btn-danger">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="" class="btn btn-warning" data-toggle="modal"
                                            data-target="#edit<?= $kegiatan['id_kegiatan'] ?>"><i class="fa fa-edit"></i>
                                            Edit</a>
                                    </td>
                                </tr>
                                <?php $no++; endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /page content -->
        </div>
    </div>
</div>


<!-- modal tambah pasien -->
<div class="modal fade" id="modalTambahKegiatan" tabindex="-1" role="dialog" aria-labelledby="modalTambahKegiatanLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahKegiatanLabel">Tambah <?= $judul ?></h5>
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
                            <td><input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" autocomplete="off"
                                    required placeholder="Nama Kegiatan"></td>
                        </tr>
                        <tr>
                            <td><label for="tahun">Tahun:</label></td>
                        </tr>
                        <tr>
                            <td><input type="number" name="tahun" id="tahun" class="form-control" autocomplete="off"
                                    required placeholder="Tahun"></td>
                        </tr>
                        <tr>
                            <td><label for="status">Status:</label></td>
                        </tr>
                        <tr>
                            <td>
                                <select name="status" id="status" class="form-control">
                                    <option value="ON">Aktif</option>
                                    <option value="OFF">Tidak Aktif</option>
                                </select>
                            </td>
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

<!-- modal edit kegiatan -->
<?php foreach($data as $kegiatan): ?>
<div class="modal fade" id="edit<?= $kegiatan['id_kegiatan'] ?>" tabindex="-1" role="dialog"
    aria-labelledby="modalEditkegiatanLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-default">
                <h5 class="modal-title" id="modalEditkegiatanLabel">Edit <?= $judul ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-danger">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="" style="width:100%">
                    <form id="edit" method="post">
                        <input type="hidden" name="id_kegiatan" value="<?= $kegiatan['id_kegiatan'] ?>">
                        <tr>
                            <td><label for="nama">Nama Kegiatan:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" autocomplete="off"
                                    value="<?= $kegiatan['nama_kegiatan'] ?>" required></td>
                        </tr>     
                        <tr>
                            <td><label for="tahun">Tahun:</label></td>
                        </tr>
                        <tr>
                            <td><input type="number" name="tahun" id="tahun" class="form-control" autocomplete="off"
                                    value="<?= $kegiatan['tahun'] ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="status">Status:</label></td>
                        </tr>
                        <tr>
                            <td>
                                <select name="status" id="status" class="form-control">
                                    <option value="ON" <?php if($kegiatan['status'] == 'ON') echo 'selected' ?>>Aktif</option>
                                    <option value="OFF" <?php if($kegiatan['status'] == 'OFF') echo 'selected' ?>>Tidak Aktif</option>
                                </select>
                            </td>
                        </tr>          
                      
                        <tr>
                            <td>
                                <br><input type="submit" name="kirim" value="Simpan" class="btn btn-success">
                                <a href="javascript:void(0)" onclick="hapuskegiatan('<?= $kegiatan['id_kegiatan'] ?>')"
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
//add data
$(document).ready(function() {
    $('#add').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('superadmin/kegiatan/api_add') ?>",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function(data) {
                if (data.status) {
                    $('#modalTambahKegiatan');
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
        url: "<?php echo site_url('superadmin/kegiatan/api_edit/') ?>" + form_data.get('id_kegiatan'),
        dataType: "json",
        data: form_data,
        processData: false,
        contentType: false,
        //memanggil swall ketika berhasil
        success: function(data) {
            $('#edit' + form_data.get('id_kegiatan'));
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

//ajax hapus kegiatan
function hapuskegiatan(id_kegiatan) {
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
                url: "<?php echo site_url('superadmin/kegiatan/api_hapus/') ?>" +
                    id_kegiatan,
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

</script>

<?php $this->load->view('template/footer'); ?>
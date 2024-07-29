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
                <a href="" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahPengeluaran"><i
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
                                        <th>Jenis Pengeluaran</th>
                                        <th>Nominal</th>
                                        <th>Tgl Pengeluaran</th>
                                        <th>Bukti</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <?php $no=1; foreach($data as $pengeluaran): ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $pengeluaran['nama_kegiatan'] ?></td>
                                    <td><?= $pengeluaran['jenis_pengeluaran'] ?></td>
                                    <td><?= rupiah($pengeluaran['nominal']) ?></td>
                                    <td><?= tgl_indo($pengeluaran['tgl_pengeluaran']) ?></td>
                                    <td>
                                        <?php if($pengeluaran['bukti_nota'] == null): ?>
                                        <img src="<?= base_url('themes/no_images.png') ?>" width="50px">
                                        <?php else: ?>
                                        <img src="<?= base_url('themes/bukti_nota/'.$pengeluaran['bukti_nota']) ?>" width="200px">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="" class="btn btn-warning" data-toggle="modal"
                                            data-target="#edit<?= $pengeluaran['id_pengeluaran'] ?>"><i class="fa fa-edit"></i>
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
<div class="modal fade" id="modalTambahPengeluaran" tabindex="-1" role="dialog" aria-labelledby="modalTambahPengeluaranLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahPengeluaranLabel">Tambah <?= $judul ?></h5>
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
                                          $kegiatan = $this->db->get('tb_kegiatan')->result_array();
                                          foreach($kegiatan as $row): ?>
                                            <option value="<?= $row['id_kegiatan'] ?>"><?= $row['nama_kegiatan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                        </tr>
                        <tr>
                            <td><label for="nama">Jenis Pengeluaran:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="jenis_pengeluaran" id="jenis_pengeluaran" class="form-control" autocomplete="off"
                                    required placeholder="Jenis Pengeluaran"></td>
                        </tr>
                        <tr>
                            <td><label for="nominal">Nominal:</label></td>
                        </tr>
                        <tr>
                            <td><input type="number" name="nominal" id="nominal" class="form-control" autocomplete="off"
                                    required placeholder="Nominal"></td>
                        </tr>
                        <tr>
                            <td><label for="tgl_pengeluaran">Tanggal Pengeluaran:</label></td>
                        </tr>
                        <tr>
                            <td><input type="date" name="tgl_pengeluaran" id="tgl_pengeluaran" class="form-control" autocomplete="off"
                                    required placeholder="Tanggal pengeluaran" value="<?= date('Y-m-d') ?>"></td>
                        </tr>
                        <tr>
                            <td><label for="bukti">Bukti: *Nota / Kwitansi</label></td>
                        </tr>
                        <tr>
                            <td>
                            <input type="file" name="foto" id="bukti_nota" class="form-control"
                                        onchange="previewLOGO()">
                                    <img id="preview_logo" alt="image preview" width="50%" />
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

<!-- modal edit pengeluaran -->
<?php foreach($data as $pengeluaran): ?>
<div class="modal fade" id="edit<?= $pengeluaran['id_pengeluaran'] ?>" tabindex="-1" role="dialog"
    aria-labelledby="modalEditpengeluaranLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-default">
                <h5 class="modal-title" id="modalEditpengeluaranLabel">Edit <?= $judul ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-danger">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="" style="width:100%">
                    <form id="edit" method="post">
                        <input type="hidden" name="id_pengeluaran" value="<?= $pengeluaran['id_pengeluaran'] ?>">
                        <tr>
                            <td><label for="nama">Nama Kegiatan:</label></td>
                        </tr>
                        <tr>
                                <td>
                                    <select name="id_kegiatan" class="form-control" required="">
                                        <?php 
                                          $kegiatan = $this->db->get('tb_kegiatan')->result_array();
                                          foreach($kegiatan as $row): ?>
                                            <option value="<?= $row['id_kegiatan'] ?>" <?= $row['id_kegiatan'] == $pengeluaran['id_kegiatan'] ? 'selected' : '' ?>><?= $row['nama_kegiatan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                        </tr>
                        <tr>
                            <td><label for="nama">Jenis Pengeluaran:</label></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="jenis_pengeluaran" id="jenis_pengeluaran" class="form-control" autocomplete="off"
                                    value="<?= $pengeluaran['jenis_pengeluaran'] ?>" required></td>
                        </tr>     
                        <tr>
                            <td><label for="nominal">Nominal:</label></td>
                        </tr>
                        <tr>
                            <td><input type="number" name="nominal" id="nominal" class="form-control" autocomplete="off"
                                    value="<?= $pengeluaran['nominal'] ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="tgl_pengeluaran">Tanggal pengeluaran:</label></td>
                        </tr>
                        <tr>
                            <td><input type="date" name="tgl_pengeluaran" id="tgl_pengeluaran" class="form-control" autocomplete="off"
                                    value="<?= $pengeluaran['tgl_pengeluaran'] ?>" required></td>
                        </tr>
                       
                        <tr>
                            <td>
                                <br><input type="submit" name="kirim" value="Simpan" class="btn btn-success">
                                <a href="javascript:void(0)" onclick="hapuspengeluaran('<?= $pengeluaran['id_pengeluaran'] ?>')"
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
            url: "<?= site_url('user/keuangan/pengeluaran/api_add') ?>",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function(data) {
                if (data.status) {
                    $('#modalTambahPengeluaran');
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
        url: "<?php echo site_url('user/keuangan/pengeluaran/api_edit/') ?>" + form_data.get('id_pengeluaran'),
        dataType: "json",
        data: form_data,
        processData: false,
        contentType: false,
        //memanggil swall ketika berhasil
        success: function(data) {
            $('#edit' + form_data.get('id_pengeluaran'));
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

//ajax hapus pengeluaran
function hapuspengeluaran(id_pengeluaran) {
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
                url: "<?php echo site_url('user/keuangan/pengeluaran/api_hapus/') ?>" + id_pengeluaran,
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

//preview Logo
function previewLOGO() {
        document.getElementById("preview_logo").style.display = "block";
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("bukti_nota").files[0]);

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
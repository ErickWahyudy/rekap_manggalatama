<?php $this->load->view('template/header'); ?>
<?= $this->session->flashdata('pesan') ?>

<?php $no_kegiatan = 1; foreach($kegiatan as $k): ?>
<h2>
    <b><span class="badge badge-primary fa fa-apple"> <?= $k['nama_kegiatan'] ?></span></b>
</h2>

<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <table id="datatable-buttons-<?= $no_kegiatan ?>" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Total Pemasukan</th>
                                        <th>Total Pengeluaran</th>
                                        <th>Sisa Dana</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no_laporan = 1; foreach($data as $laporan): ?>
                                        <?php if($laporan['id_kegiatan'] == $k['id_kegiatan']): ?>
                                            <tr>
                                                <td><?= $no_laporan ?></td>
                                                <td><?= $laporan['nama_kegiatan'] ?></td>
                                                <td>
                                                    <?= rupiah($total_pemasukan[$laporan['id_kegiatan']]) ?>
                                                    <a href="<?= site_url('superadmin/keuangan/laporan/detail_pemasukan/'.$laporan['id_kegiatan']) ?>" class="btn btn-info btn-xs">
                                                    <span class="fa fa-eye"></span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?= rupiah($total_pengeluaran[$laporan['id_kegiatan']]) ?>
                                                    <a href="<?= site_url('superadmin/keuangan/laporan/detail_pengeluaran/'.$laporan['id_kegiatan']) ?>" class="btn btn-info btn-xs">
                                                    <span class="fa fa-eye"></span>
                                                    </a>
                                                </td>
                                                <td><?= rupiah($total_pemasukan[$laporan['id_kegiatan']] - $total_pengeluaran[$laporan['id_kegiatan']]) ?></td>
                                               
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
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
<?php $no_kegiatan++; endforeach; ?>

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

    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

?>

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
</script>

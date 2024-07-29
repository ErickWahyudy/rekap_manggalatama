<?php $this->load->view('template/header'); ?>
<?= $this->session->flashdata('pesan') ?>

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
                            <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Total Pemasukan</th>
                                        <th>Total Pengeluaran</th>
                                        <th>Sisa Dana</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=1; foreach($data as $laporan): ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td><?= $laporan['nama_kegiatan'] ?></td>
                                        <td><?= rupiah($total_pemasukan[$laporan['id_kegiatan']]) ?></td>
                                        <td><?= rupiah($total_pengeluaran[$laporan['id_kegiatan']]) ?></td>
                                        <td><?= rupiah($total_pemasukan[$laporan['id_kegiatan']] - $total_pengeluaran[$laporan['id_kegiatan']]) ?></td>
                                        <td>
                                            <a href="<?= site_url('superadmin/keuangan/laporan/detail/'.$laporan['id_kegiatan']) ?>" class="btn btn-info btn-xs">Detail</a>
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

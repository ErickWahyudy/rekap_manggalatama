<!-- application/views/superadmin/keuangan/detail_laporan.php -->

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
        <h2>Detail Pengeluaran: <?= $kegiatan['nama_kegiatan'] ?></h2>
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
                                        <th>Jenis Pemasukan</th>
                                        <th>Nominal</th>
                                        <th>Tgl Pemasukan</th>
                                        <th>Bukti</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($data as $pemasukan): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $pemasukan['jenis_pemasukan'] ?></td>
                                            <td><?= rupiah($pemasukan['nominal']) ?></td>
                                            <td><?= tgl_indo($pemasukan['tgl_pemasukan']) ?></td>
                                            <td>
                                                <?php if($pemasukan['bukti_transfer'] == null): ?>
                                                <img src="<?= base_url('themes/no_images.png') ?>" width="50px">
                                                <?php else: ?>
                                                <img src="<?= base_url('themes/bukti_transfer/'.$pemasukan['bukti_transfer']) ?>" width="200px">
                                                <?php endif; ?>
                                            </td>
                                        </tr>
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

<?php $this->load->view('template/footer'); ?>

<?php 

function rupiah($angka){
    return 'Rp ' . number_format($angka, 2, ',', '.');
}

function tgl_indo($tanggal){
    $bulan = array (
        1 => 'Januari',
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

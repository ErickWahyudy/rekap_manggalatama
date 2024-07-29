<?php $this->load->view('template/header'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="row" style="display: inline-block;">
   
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="tile-stats">
            <a href="<?= base_url('user/keuangan/pemasukan') ?>">
                <div class="icon"><i class="fa fa-money"></i></div>
                <div class="count"><i class="fa fa-plus"></i></div>
                <h3 style="color: #1ABB9C;">Tambah Data Pemasukan</h3>
            </a>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="tile-stats">
            <a href="<?= base_url('user/keuangan/pengeluaran') ?>">
                <div class="icon"><i class="fa fa-dollar"></i></div>
                <div class="count"><i class="fa fa-plus"></i></div>
                <h3 style="color: #1ABB9C;">Tambah Data Pengeluaran</h3>
            </a>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="tile-stats">
            <a href="<?= base_url('user/keuangan/laporan') ?>">
                <div class="count"> <i class="fa fa-bar-chart"></i></div>
                <h3 style="color: #1ABB9C;">Laporan Keuangan</h3>
            </a>
            </div>
        </div>

        
    </div>
</div>


<?php $this->load->view('template/footer'); ?>
<?php
/**
 * @var $this \CodeIgniter\View\View
 * @var $pager \CodeIgniter\Pager\Pager
 */
$this->extend('staff/template');
$this->section('css_block');
    echo link_tag('https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css');
$this->endSection();
$this->section('content');
?>
    <!-- Page Header -->
    <div class="page-header row no-gutters py-4">
        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
            <span class="text-uppercase page-subtitle">PPD Online</span>
            <h3 class="page-title"><?php echo lang('Dashboard');?></h3>
        </div>
    </div>
    <!-- End Page Header -->

<div class="card mb-3">
    <div class="card-header border-bottom">
        <div class="panel-heading">
            <span class="pull-right clickable panel-toggle panel-button-tab-left">
            <small class="badge badge-success">Jumlah Tiket</small>
            </span>
        </div>
    </div>
    <div class="container">
        <div class="row mt-3">
        <!-- dashboard card -->
            <div class="col-lg-3 col-6">            
                <div class="small-box bg-info">
                <div class="inner">
                    <h3><?php echo $count_status_active;?></h3>
                    <p><?php echo lang('Admin.tickets.active'); ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <a href="<?php echo site_url(route_to('staff_tickets'));?>" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                <div class="inner">
                    <h3><?php echo $count_status_overdue;?></h3>

                    <p><?php echo lang('Admin.form.overdue'); ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="<?php echo site_url(route_to('staff_tickets_overdue'));?>" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?php echo $count_status_answered;?></h3>

                    <p><?php echo lang('Admin.form.answered'); ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="<?php echo site_url(route_to('staff_tickets_answered'));?>" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?php echo $count_status_closed;?></h3>

                    <p><?php echo lang('Admin.form.closed'); ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <a href="<?php echo site_url(route_to('staff_tickets_closed'));?>" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>        
        </div>
    </div>
</div>
<div class="card mb-3">
    <div class="card-header">        
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true"><h6>Grafik</h6></a>
                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false"><h6>Tabel</h6></a>
            </div>
        </nav>
    </div>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
        <?php
        $arai = array_count_values($ticket);
        $jumlah = array();
        $kategori = array();
        foreach ($arai as $a=>$b) {
            array_push($jumlah, $b) ;
            array_push($kategori, $a);        
        }        
        ?>
        <div class="card mb-3">
        <!-- Grafik Permintaan Data -->
            <div class="card-header border-bottom">
                Jumlah Permintaan Data
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-toggle="collapse" data-target="#diagram1">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>    
            </div>
            <div class="card-body">
                <div class="canvas-wrapper" id="diagram1">
                    <canvas id="myChart1" height="200" width="600"></canvas>
                </div>
            </div> 
            <!-- Grafik penyelesaian tiket -->
            <div class="card-header">
                Jumlah Tiket Selesai Menurut Petugas
                <?php
                    $arai1 = array_count_values($ticket_done);
                    $jumlah1 = array();
                    $kategori1 = array();
                    foreach ($arai1 as $a1=>$b1) {
                        array_push($jumlah1, $b1) ;
                        array_push($kategori1, $a1);        
                    }        
                ?>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-toggle="collapse" data-target="#diagram2">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>    
            </div>
            <div class="card-body">
                <div class="canvas-wrapper" id="diagram2">
                    <canvas id="myChart1" height="200" width="600"></canvas>
                </div>
            </div>
        </div>
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <div class="card mb-3">
                    <div class="card-header">
                    Tabel Permintaan Data Menurut Kategori
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table1" class="table table-striped table-bordered">   
                                <thead>
                                    <tr>
                                        <th>
                                            No
                                        </th>
                                        <th>
                                            Kategori Data
                                        </th>
                                        <th>
                                            Jumlah
                                        </th>
                                    </tr>
                                </thead>
                                <?php
                                    $number = 1;
                                    foreach($arai as $a => $b){
                                echo '<tr>'.'<td>';
                                echo $number;
                                echo  '</td>'.'<td>';
                                echo $a;
                                echo  '</td>'.'<td>';
                                echo $b.'</td>'.'</tr>';
                                $number++;
                                    }
                                ?>
                            </table>
                    </div>
            </div>
            </div>

            <div class="card mb-3">
                    <div class="card-header">
                    Tabel Penyelesaian Tiket Menurut Petugas
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table2" class="table table-striped table-bordered">   
                                <thead>
                                    <tr>
                                        <th>
                                            No
                                        </th>
                                        <th>
                                            Nama Petugas
                                        </th>
                                        <th>
                                            Jumlah Tiket Selesai
                                        </th>
                                    </tr>
                                </thead>
                                <?php
                                    $number1 = 1;
                                    foreach($arai1 as $a1 => $b1){
                                echo '<tr>'.'<td>';
                                echo $number1;
                                echo  '</td>'.'<td>';
                                echo $a1;
                                echo  '</td>'.'<td>';
                                echo $b1.'</td>'.'</tr>';
                                $number1++;
                                    }
                                ?>
                            </table>
                    </div>
            </div>
            </div>
        </div>       

</div>
<!-- chart 2 -->
<?php
$this->endSection();
$this->section('script_block');
echo script_tag('https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js');
    script_tag('https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js');
?>
    <script>
    // script grafik kategori permintaan data        
        var data_viewer = [];
        var data_name = [];
        data_name = <?php echo (json_encode($kategori)); ?>;
        data_viewer = <?php echo (json_encode($jumlah)); ?>;     
        var b = data_viewer.map(Number);

    Highcharts.chart('diagram1', {
    chart: {
        type: 'column'
    },
    title: {
        text: null
    },
    subtitle: {
        text: null
    },
    xAxis: {
        categories: data_name
    },
    yAxis: {
        title: {
            text: 'Jumlah'
        },
        tickInterval : 1

    },
    legend: {
        enabled: false
    },
    credits: {
        enabled: false
    },
    series: [
        {
            data: b
        }
    ]
});

    // script grafik permintaan data        
    var data_viewer1 = [];
    var data_name1 = [];
    data_name1 = <?php echo (json_encode($kategori1)); ?>;
    data_viewer1 = <?php echo (json_encode($jumlah1)); ?>;     
    var b1 = data_viewer1.map(Number);

    Highcharts.chart('diagram2', {
    chart: {
        type: 'column'
    },
    title: {
        text: null
    },
    subtitle: {
        text: null
    },
    xAxis: {
        categories: data_name1
    },
    yAxis: {
        title: {
            text: 'Jumlah'
        },
        tickInterval : 1

    },
    legend: {
        enabled: false
    },
    credits: {
        enabled: false
    },
    series: [
        {
            data: b1
        }
    ]
});

$(document).ready(function() {
    $('#table1').DataTable();
} );

$(document).ready(function() {
    $('#table2').DataTable();
} );
</script>
<?php
$this->endSection();
?>
function dashboard1(){
    var b = data_viewer.map(Number);

    Highcharts.chart('diagram1', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Jumlah Permintaan Data'
    },
    subtitle: {
        text: '(Berdasarkan Kategori Data)'
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
    series: [
        {
            data: b
        }
    ]
});
}
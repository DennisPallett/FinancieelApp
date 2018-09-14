import { Component, OnInit } from '@angular/core';
import { forEach } from '@angular/router/src/utils/collection';
import { Chart } from 'angular-highcharts';
import * as Highcharts from 'highcharts';
import { ReportingService } from './reporting.service';

@Component({
  template: '<div [chart]="chart"></div>',
  selector: 'saldo-chart'
})
export class SaldoChartComponent implements OnInit {
  chart = new Chart({
    chart: {
      type: 'line'
    },
    title: {
      text: 'Saldo'
    },
    xAxis: {
      type: 'datetime',
      labels: {
        formatter: function () {
          return Highcharts.dateFormat('%m-%d-%y', this.value);
        }
      },
      title: {
        text: 'Datum'
      }
    },
    yAxis: {
      min: 0,
      title: {
        text: 'Bedrag'
      },
      labels: {
        formatter: function () {
          return '€ ' + this.value;
        }
      }
    },
    legend: {
      align: 'right',
      x: -30,
      verticalAlign: 'top',
      y: 25,
      floating: true,
      backgroundColor: 'white',
      borderColor: '#CCC',
      borderWidth: 1,
      shadow: false
    },
    colors: ['red', 'green'],
    plotOptions: {
      column: {
        dataLabels: {
          enabled: false,
          color: 'white'
        }
      }
    },
    series: []
  });

  constructor(private reportingService: ReportingService) {
  }

  ngOnInit() {
    this.chart.ref$.subscribe((chart) => {
      chart.showLoading();
    });

    this.reportingService.getBalance().subscribe(
      (balanceList) => {
        this.chart.ref$.subscribe((chart) => {
          chart.hideLoading()

          var serie = { name: "Saldo", data: [] };

          balanceList.forEach(function (item) {
            serie.data.push([+item.timestamp*1000, +item.balance]);
          });

          chart.addSeries(serie);
        });
      });
  }

}

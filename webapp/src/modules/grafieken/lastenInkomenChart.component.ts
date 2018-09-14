import { Component, OnInit } from '@angular/core';
import { forEach } from '@angular/router/src/utils/collection';
import { Chart } from 'angular-highcharts';
import * as Highcharts from 'highcharts';
import { ReportingService } from './reporting.service';

@Component({
  template: '<div [chart]="chart"></div>',
  selector: 'lasten-inkomen-chart'
})
export class LastenInkomenChartComponent implements OnInit {
  chart = new Chart({
    chart: {
      type: 'column'
    },
    title: {
      text: 'Lasten/inkomen per maand'
    },
    xAxis: {
      categories: []
    },
    yAxis: {
      min: 0,
      title: {
        text: 'Bedrag'
      },
      stackLabels: {
        enabled: true,
        style: {
          fontWeight: 'bold',
          color: 'gray'
        }
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

    this.reportingService.getCategoryByMonth().subscribe(
      (categoriesByMonth) => {
        this.chart.ref$.subscribe((chart) => {
          chart.hideLoading()
          chart.xAxis[0].setCategories(categoriesByMonth.categories);
          categoriesByMonth.series.forEach(function (serie) {
            chart.addSeries(serie);
          });
        });
      });
  }

}

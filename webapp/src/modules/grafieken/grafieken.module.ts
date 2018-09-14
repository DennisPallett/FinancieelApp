import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { SharedModule } from '../shared/shared.module';
import { ChartModule } from 'angular-highcharts';

import { GrafiekenComponent } from './grafieken.component';

import { ReportingService } from './reporting.service';
import { LastenInkomenChartComponent } from './lastenInkomenChart.component';
import { SaldoChartComponent } from './saldoChart.component';


@NgModule({
  declarations: [
    GrafiekenComponent,
    LastenInkomenChartComponent,
    SaldoChartComponent
  ],
  imports: [
    SharedModule,
    ChartModule,
    RouterModule.forChild([
      { path: 'grafieken', component: GrafiekenComponent }
    ])
  ],
  providers: [ReportingService]
})
export class GrafiekenModule { }

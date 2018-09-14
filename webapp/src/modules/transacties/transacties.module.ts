import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { SharedModule } from '../shared/shared.module';

import { TransactiesComponent } from './transacties.component';
import { TransactiesFilterPipe } from './transactiesfilter.pipe';


@NgModule({
  declarations: [
    TransactiesComponent,
    TransactiesFilterPipe
  ],
  imports: [
    SharedModule,
    RouterModule.forChild([
      { path: 'transacties', component: TransactiesComponent }
    ])
  ],
  providers: []
})
export class TransactiesModule { }

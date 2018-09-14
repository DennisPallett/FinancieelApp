import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { SharedModule } from '../shared/shared.module';

import { MaandOverzichtenComponent } from './maandoverzichten.component';


@NgModule({
  declarations: [
    MaandOverzichtenComponent
  ],
  imports: [
    SharedModule,
    RouterModule.forChild([
      { path: 'maandoverzichten', component: MaandOverzichtenComponent }
    ])
  ],
  providers: []
})
export class MaandOverzichtenModule { }

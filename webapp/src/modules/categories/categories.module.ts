import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { SharedModule } from '../shared/shared.module';
import { ClassifyComponent } from './classify.component';

@NgModule({
  declarations: [
    ClassifyComponent
  ],
  imports: [
    SharedModule,
    RouterModule.forChild([
      { path: 'categories/classify', component: ClassifyComponent }
    ])
  ],
  providers: []
})
export class CategoriesModule { }

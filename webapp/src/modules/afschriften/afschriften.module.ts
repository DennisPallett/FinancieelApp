import { NgModule } from '@angular/core'; 
import { RouterModule, Routes } from '@angular/router';

import { SharedModule } from '../shared/shared.module';

import { OverzichtComponent } from './overzicht/overzicht.component';
import { AfschriftenService } from './afschriften.service';
import { MonthNamePipe } from '../shared/monthname';
import { ImporterenComponent } from './importeren/importeren.component';


@NgModule({
  declarations: [
    OverzichtComponent,
    ImporterenComponent
  ],
  imports: [
    SharedModule,
    RouterModule.forChild([
      { path: 'afschriften', component: OverzichtComponent },
      { path: 'afschriften/importeren', component: ImporterenComponent }
    ])
  ],
  providers: [AfschriftenService, MonthNamePipe]
})
export class AfschriftenModule { }

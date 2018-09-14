import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { SharedModule } from '../shared/shared.module';
import { ProfileModule } from '../profile/profile.module';
import { MaandOverzichtenModule } from '../maandoverzichten/maandoverzichten.module';

import { AppComponent } from './app.component';
import { HomeComponent } from './home/home.component';
import { NotFoundComponent } from './notfound/notfound.component';
import { GrafiekenModule } from '../grafieken/grafieken.module';
import { TransactiesModule } from '../transacties/transacties.module';
import { AfschriftenModule } from '../afschriften/afschriften.module';
import { CategoriesModule } from '../categories/categories.module';

@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    NotFoundComponent
  ],
  imports: [
    SharedModule,
    ProfileModule,
    MaandOverzichtenModule,
    GrafiekenModule,
    TransactiesModule,
    AfschriftenModule,
    CategoriesModule,
    RouterModule.forRoot([
      { path: 'home', component: HomeComponent },
      { path: '', redirectTo: 'home', pathMatch: 'full' },
      { path: '**', component: NotFoundComponent}
    ]),
    
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }

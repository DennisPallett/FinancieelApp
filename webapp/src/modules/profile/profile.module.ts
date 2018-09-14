import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { SharedModule } from '../shared/shared.module';

import { ProfileService } from './profile.service';

import { ProfileListComponent } from './profilelist/profilelist.component';
import { ProfileFilterPipe } from './profilelist/profilefilter.pipe';

import { ProfileDetailsComponent } from './profile-details/profile-details.component';
import { ProfileDetailsGuard } from './profile-details/profile-details.guard';


@NgModule({
  declarations: [
    ProfileListComponent,
    ProfileFilterPipe,
    ProfileDetailsComponent
  ],
  imports: [
    SharedModule,
    RouterModule.forChild([
      { path: 'profiles', component: ProfileListComponent },
      { path: 'profile/:id', component: ProfileDetailsComponent, canActivate: [ProfileDetailsGuard], resolve: { 'profile': ProfileDetailsGuard } },
    ])
  ],
  providers: [ProfileService, ProfileDetailsGuard]
})
export class ProfileModule { }

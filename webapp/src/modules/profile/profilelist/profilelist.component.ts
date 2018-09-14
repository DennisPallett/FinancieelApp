import { Component, OnInit } from '@angular/core';
import { IProfile } from '../profile.model';
import { ProfileService } from '../profile.service';

@Component({
  templateUrl: './profilelist.component.html',
  styleUrls: ['./profilelist.component.css']
})

export class ProfileListComponent implements OnInit {
  searchFilter: '';

  showPhotos: boolean = true;

  lastClickedEmail: string = '';

  profiles: IProfile[] = [];

  hasError: boolean = false;

  errorMessage: string = '';

  constructor(private profileService: ProfileService) {
  }

  ngOnInit() {
    this.profileService.getProfiles().subscribe(
      (profiles) => {
        this.profiles = profiles;
      },
      (error) => {
        this.hasError = true;
        this.errorMessage = error.message;
      });
  }

  reset () {
    this.searchFilter = '';
  };

  togglePhotos() {
    this.showPhotos = !this.showPhotos;
  }

  emailClicked(event: string) {
    this.lastClickedEmail = event;
  }
}

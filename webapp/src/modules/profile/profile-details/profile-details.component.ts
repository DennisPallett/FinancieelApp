import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { OnInit } from '@angular/core';
import { ProfileService } from '../profile.service';
import { IProfile } from '../profile.model';

@Component({
  templateUrl: './profile-details.component.html',
  styleUrls: ['./profile-details.component.css']
})

export class ProfileDetailsComponent implements OnInit {
  profile: IProfile;

  constructor(private route: ActivatedRoute, private profileService: ProfileService) {
  }

  ngOnInit() {
    this.profile = this.route.snapshot.data.profile;
  }
}

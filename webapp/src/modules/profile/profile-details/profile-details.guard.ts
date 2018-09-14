import { CanActivate, Resolve } from "@angular/router/src/interfaces";
import { ActivatedRouteSnapshot, RouterStateSnapshot } from "@angular/router/src/router_state";
import { Injectable } from "@angular/core";
import { Router } from "@angular/router";
import { IProfile } from "../profile.model";
import { Observable } from "rxjs";
import { ProfileService } from "../profile.service";
import { map } from 'rxjs/operators';

@Injectable()
export class ProfileDetailsGuard implements CanActivate, Resolve<IProfile> {
  
  constructor(private router: Router, private profileService: ProfileService) { }

  canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean {
    const id = Number(route.url[1].path);

    if (!isNaN(id) && Number(id) > 0) {
      return true;
    }

    this.router.navigate(['/profiles']);
    return false;
  }

  resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): IProfile | Observable<IProfile> | Promise<IProfile> {
    const id = Number(route.url[1].path);

    return this.profileService.getProfile(id).pipe(map(profile => {
      if (profile) {
        return profile;
      } else {
        this.router.navigate(['/profiles']);
        return null;
      }
    }));
  }
}

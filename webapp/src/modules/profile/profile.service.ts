import { IProfile } from "./profile.model";
import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { Observable } from "rxjs";
import { map } from 'rxjs/operators';

@Injectable()
export class ProfileService {

  constructor(private httpClient: HttpClient) {
  }

  getProfiles(): Observable<IProfile[]> {
    return this.httpClient.get<IProfile[]>('./assets/mockservice/getprofiles.json');
  }

  getProfile(id: number): Observable<IProfile> {
    return this.getProfiles().pipe(map((profiles: IProfile[]) => profiles.find(p => p.id === id)));
  }

}

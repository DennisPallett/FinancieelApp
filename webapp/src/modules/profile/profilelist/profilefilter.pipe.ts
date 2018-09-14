import { Pipe } from "@angular/core";
import { IProfile } from '../profile.model';

@Pipe({
  name: 'ProfileFilter'
})

export class ProfileFilterPipe {
  transform(profiles: IProfile[], keyword: string): IProfile[] {
    // indien er geen zoek keyword is -> alle profielen terug geven
    if (typeof(keyword) == 'undefined' || keyword.trim().length == 0)
      return profiles;

    keyword = keyword.toLocaleLowerCase();

    // filter profielen
    return profiles.filter(function (profile) {
      return (
        profile.firstname.toLocaleLowerCase().indexOf(keyword) != -1
        ||
        profile.surname.toLocaleLowerCase().indexOf(keyword) != -1
        ||
        profile.jobtitle.toLocaleLowerCase().indexOf(keyword) != -1
        ||
        profile.email.toLocaleLowerCase().indexOf(keyword) != -1
        ||
        profile.phoneNumber.toLocaleLowerCase().indexOf(keyword) != -1
      );
    });
  }
}

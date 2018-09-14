import { Pipe } from "@angular/core";

@Pipe({
  name: 'ConvertToSpaces'
})

export class ConvertToSpacesPipe {
  transform(value: string, character: string): string {
    return value.replace(character, ' ');
  }
}

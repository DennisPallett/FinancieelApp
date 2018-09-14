import { Pipe } from "@angular/core";

@Pipe({
  name: 'MonthName'
})

export class MonthNamePipe {
  names = {
    1: "januari",
    2: "februari",
    3: "maart",
    4: "april",
    5: "mei",
    6: "juni",
    7: "juli",
    8: "augustus",
    9: "september",
    10: "oktober",
    11: "november",
    12: "december"
  };

  transform(value: number): string {
    if (typeof (this.names[value]) != 'undefined')
      return this.names[value];

    return value.toString();
  }
}

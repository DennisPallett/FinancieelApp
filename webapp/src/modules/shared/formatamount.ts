import { Pipe } from "@angular/core";

@Pipe({
  name: 'FormatAmount'
})

export class FormatAmountPipe {
  transform(value: string): string {
    return parseFloat(value).toFixed(2);
  }
}

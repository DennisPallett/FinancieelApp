import { Pipe } from "@angular/core";
import { ITransactie } from "../shared/transactie.model";

@Pipe({
  name: 'TransactiesFilter'
})

export class TransactiesFilterPipe {
  transform(transacties: ITransactie[], onlyShowUncategorized: boolean): ITransactie[] {
    if (!onlyShowUncategorized)
      return transacties;

    return transacties.filter(function (transactie) {
      return (transactie.category == null);
    });

   
  }
}

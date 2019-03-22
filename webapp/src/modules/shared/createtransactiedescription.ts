import { Pipe } from "@angular/core";
import { ITransactie } from "./transactie.model";

@Pipe({
  name: 'CreateTransactieDescription'
})

export class CreateTransactieDescriptionPipe {
  transform(value: ITransactie): string {
    if (value.is_card_payment == false && value.remittance_info != null)
      return value.remittance_info

    if (value.is_card_payment && value.is_cash_withdrawal)
      return "Geld pinnen";

    if (value.is_card_payment) {
      if (value.shop_card_payment != null)
        return value.shop_card_payment.description;
      else
        return value.description;
    }

    if (value.description.length > 0 && !value.description.startsWith("/TRTP/"))
      return value.description;

    return value.other_party_name;
  }
}

export interface ITransactie {
  id: number,
  amount: number,
  category: string,
  category_name: string,
  category_group_key: string,
  description: string,
  remittance_info: string,
  is_card_payment: boolean,
  is_cash_withdrawal: boolean,
  booking_date: Date,
  value_date: Date,
  other_party_name: string,
  shop_card_payment: {
    nr: string,
    description: string,
    timestamp: Date
  }
}

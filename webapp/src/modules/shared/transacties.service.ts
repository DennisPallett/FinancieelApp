import { ITransactie } from "./transactie.model";
import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { Observable } from "rxjs";
import { map } from 'rxjs/operators';
import { environment } from "../../environments/environment";
import { IClassifyResult } from "./classifyresult.model";

@Injectable()
export class TransactiesService {

  private apiAction: string = "transacties";

  constructor(private httpClient: HttpClient) {
  }

  getAllTransacties(): Observable<ITransactie[]> {
    return this.httpClient.get<ITransactie[]>(environment.apiUrl + this.apiAction);
  }

  getTransactiesForMonth(month: number, year: number): Observable<ITransactie[]> {
    return this.getTransactiesForMonthSortedBy(month, year, "amount", "ASC");
  }

  getTransactiesForMonthSortedBy(month: number, year: number, sortBy: string, sortOrder: string): Observable<ITransactie[]> {
    return this.httpClient.get<ITransactie[]>(environment.apiUrl + this.apiAction, {
      params:
        {
          month: month.toString(),
          year: year.toString(),
          sortby: sortBy,
          sortorder: sortOrder
        }
    });
  }

  updateCategory(transactieId: number, category: string): Observable<boolean> {
    return this.httpClient.post<boolean>(environment.apiUrl + this.apiAction + "/update-category", {
      id: transactieId,
      category: category 
    });
  }

  classify(): Observable<IClassifyResult> {
    return this.httpClient.post<IClassifyResult>(environment.apiUrl + this.apiAction + "/classify", {});
  }

}

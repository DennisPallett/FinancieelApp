import { ITransactie } from "./transactie.model";
import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { Observable } from "rxjs";
import { map } from 'rxjs/operators';
import { environment } from "../../environments/environment";

@Injectable()
export class TransactiesService {

  private apiAction: string = "transacties";

  constructor(private httpClient: HttpClient) {
  }

  getAllTransacties(): Observable<ITransactie[]> {
    return this.httpClient.get<ITransactie[]>(environment.apiUrl + this.apiAction);
  }

  getTransactiesForMonth(month: number, year: number): Observable<ITransactie[]> {
    return this.httpClient.get<ITransactie[]>(environment.apiUrl + this.apiAction, {
      params:
        {
          month: month.toString(),
          year: year.toString()
        }
    });
  }

}
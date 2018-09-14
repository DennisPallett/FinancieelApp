import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { Observable } from "rxjs";
import { map } from 'rxjs/operators';
import { environment } from "../../environments/environment";
import { ICategoryByMonth } from "./categoryByMonth.model";
import { IBalance } from "./balance.model";

@Injectable()
export class ReportingService {

  private apiAction: string = "reporting";

  constructor(private httpClient: HttpClient) {
  }

  getCategoryByMonth(): Observable<ICategoryByMonth> {
    return this.httpClient.get<ICategoryByMonth>(environment.apiUrl + this.apiAction + "/category-by-month");
  }

  getBalance(): Observable<IBalance[]> {
    return this.httpClient.get<IBalance[]>(environment.apiUrl + this.apiAction + "/balance");
  }

}

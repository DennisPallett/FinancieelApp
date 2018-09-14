import { ITransactie } from "./transactie.model";
import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { Observable } from "rxjs";
import { map } from 'rxjs/operators';
import { environment } from "../../environments/environment";
import { IMonth } from "./month.model";

@Injectable()
export class DatesService {

  private apiAction: string = "dates";

  constructor(private httpClient: HttpClient) {
  }

  getMonths(): Observable<IMonth[]> {
    return this.httpClient.get<IMonth[]>(environment.apiUrl + this.apiAction + "/months");
  }

}

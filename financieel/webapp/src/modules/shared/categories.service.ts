import { ITransactie } from "./transactie.model";
import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { Observable } from "rxjs";
import { map } from 'rxjs/operators';
import { environment } from "../../environments/environment";
import { ICategory } from "./category.model";

@Injectable()
export class CategoriesService {

  private apiAction: string = "categories";

  constructor(private httpClient: HttpClient) {
  }

  getCategories(): Observable<ICategory[]> {
    return this.httpClient.get<ICategory[]>(environment.apiUrl + this.apiAction);
  }

}

import { Component, OnInit } from '@angular/core';
import { TransactiesService } from '../shared/transacties.service';
import { ITransactie } from '../shared/transactie.model';
import { forEach } from '@angular/router/src/utils/collection';
import { DatesService } from '../shared/dates.service';
import { IMonth } from '../shared/month.model';
import { CategoriesService } from '../shared/categories.service';
import { ICategory } from '../shared/category.model';
import { ICategoryGroup } from '../shared/categorygroup.model';
import { IClassifyResult } from '../shared/classifyresult.model';

@Component({
  templateUrl: './classify.component.html'
})
export class ClassifyComponent implements OnInit {
  classifyInProgress: boolean = false;

  classifyResult: IClassifyResult = null;

  classifyError: string = null;

  classifyErrorCode: number = null;

  constructor(private transactiesService: TransactiesService) {
  }

  ngOnInit() {
    
  }

  public startClassification() {
    this.classifyInProgress = true;
    this.classifyResult = null;

    this.transactiesService.classify().subscribe(
      (result) => {
        this.classifyInProgress = false;
        this.classifyResult = result;
      },
      (error) => {
        this.classifyInProgress = false;
        this.classifyError = error.error;
        this.classifyErrorCode = error.status;
      }
    );
  }
  

  
}

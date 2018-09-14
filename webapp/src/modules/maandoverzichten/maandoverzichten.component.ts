import { Component, OnInit } from '@angular/core';
import { TransactiesService } from '../shared/transacties.service';
import { ITransactie } from '../shared/transactie.model';
import { forEach } from '@angular/router/src/utils/collection';
import { DatesService } from '../shared/dates.service';
import { IMonth } from '../shared/month.model';

@Component({
  templateUrl: './maandoverzichten.component.html',
  styleUrls: ['./maandoverzichten.component.css']
})
export class MaandOverzichtenComponent implements OnInit {
  availableMonths: IMonth[] = [];

  currentMonthYear: string = '';

  currentMonth: number = 0;

  currentYear: number = 0;

  vasteLasten: ITransactie[] = [];

  vasteLastenTotaal: number = 0;

  boodschappen: ITransactie[] = [];

  boodschappenTotaal: number = 0;

  brandstof: ITransactie[] = [];

  brandstofTotaal: number = 0;

  overigeUitgaven: ITransactie[] = [];

  overigeUitgavenTotaal: number = 0;

  inkomen: ITransactie[] = [];

  inkomenTotaal: number = 0;

  totaleUitgaven: number = 0;

  totaleInkomen: number = 0;

  constructor(private transactiesService: TransactiesService, private datesService: DatesService) {
  }

  ngOnInit() {
    this.datesService.getMonths().subscribe((months) => {
      this.availableMonths = months;
    });
  }

  public changeMonth() {
    var split = this.currentMonthYear.split('-');
    this.currentMonth = parseInt(split[0]);
    this.currentYear = parseInt(split[1]);

    this.loadTransacties();
  }

  private loadTransacties() {
    this.transactiesService.getTransactiesForMonth(this.currentMonth, this.currentYear).subscribe(
      (transacties) => {
        this.totaleUitgaven = 0;
        this.totaleInkomen = 0;

        this.processVasteLasten(transacties);
        this.processOverigeUitgaven(transacties);
        this.processBoodschappen(transacties);
        this.processBrandstof(transacties);
        this.processInkomen(transacties);
        //console.log(transacties);
      },
      (error) => {
        console.log(error);
      });
  }

  private processVasteLasten(transacties: ITransactie[]) {
    this.vasteLasten = transacties.filter(function (transactie) {
      return (transactie.category != null && transactie.category.startsWith("vaste_lasten"));
    });

    this.vasteLastenTotaal = 0;
    this.vasteLasten.forEach((transactie) => {
      this.vasteLastenTotaal = +this.vasteLastenTotaal + +transactie.amount;
    });

    this.totaleUitgaven += +this.vasteLastenTotaal;
  }

  private processBoodschappen(transacties: ITransactie[]) {
    this.boodschappen = transacties.filter(function (transactie) {
      return (transactie.category != null && transactie.category == 'boodschappen');
    });

    this.boodschappenTotaal = 0;
    this.boodschappen.forEach((transactie) => {
      this.boodschappenTotaal = +this.boodschappenTotaal + +transactie.amount;
    });

    this.totaleUitgaven += +this.boodschappenTotaal;
  }

  private processBrandstof(transacties: ITransactie[]) {
    this.brandstof = transacties.filter(function (transactie) {
      return (transactie.category != null && transactie.category == 'brandstof');
    });

    this.brandstofTotaal = 0;
    this.brandstof.forEach((transactie) => {
      this.brandstofTotaal = +this.brandstofTotaal + +transactie.amount;
    });

    this.totaleUitgaven += +this.brandstofTotaal;
  }

  private processOverigeUitgaven(transacties: ITransactie[]) {
    this.overigeUitgaven = transacties.filter(function (transactie) {
      return (transactie.category == null && transactie.amount < 0);
    });

    this.overigeUitgavenTotaal = 0;
    this.overigeUitgaven.forEach((transactie) => {
      this.overigeUitgavenTotaal = +this.overigeUitgavenTotaal + +transactie.amount;
    });

    this.totaleUitgaven += +this.overigeUitgavenTotaal;
  }

  private processInkomen(transacties: ITransactie[]) {
    this.inkomen = transacties.filter(function (transactie) {
      return (transactie.amount > 0);
    });

    this.inkomenTotaal = 0;
    this.inkomen.forEach((transactie) => {
      this.inkomenTotaal = +this.inkomenTotaal + +transactie.amount;
    });

    this.totaleInkomen += +this.inkomenTotaal;
  }

}

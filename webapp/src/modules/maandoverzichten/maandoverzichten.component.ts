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

  sparen: ITransactie[] = [];

  brandstofTotaal: number = 0;

  overigeUitgaven: [] = [];

  overigeUitgavenTotaal: number = 0;

  inkomen: ITransactie[] = [];

  inkomenTotaal: number = 0;

  totaleUitgaven: number = 0;

  totaleInkomen: number = 0;

  totaalSparen: number = 0;

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
        this.totaleUitgaven = transacties
                              .filter(t => t.amount < 0 && t.category_group_key != 'sparen')
                              .map(t => +t.amount)
                              .reduce((totaal, value) => totaal += value);

        this.totaleInkomen = transacties
                            .filter(t => t.amount >= 0)
                            .map(t => +t.amount)
                            .reduce((totaal, value) => totaal += value);

        this.totaalSparen = transacties
                            .filter(t => t.category_group_key == 'sparen')
                            .map(t => +t.amount).reduce((totaal, value) => totaal += value);

        this.processVasteLasten(transacties);
        this.processOverigeUitgaven(transacties);
        this.processBoodschappen(transacties);
        this.processBrandstof(transacties);
        this.processInkomen(transacties);
        this.processSparen(transacties);
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
  }

  private processBoodschappen(transacties: ITransactie[]) {
    this.boodschappen = transacties.filter(function (transactie) {
      return (transactie.category != null && transactie.category == 'boodschappen');
    });

    this.boodschappenTotaal = 0;
    this.boodschappen.forEach((transactie) => {
      this.boodschappenTotaal = +this.boodschappenTotaal + +transactie.amount;
    });
  }

  private processBrandstof(transacties: ITransactie[]) {
    this.brandstof = transacties.filter(function (transactie) {
      return (transactie.category != null && transactie.category == 'brandstof');
    });

    this.brandstofTotaal = 0;
    this.brandstof.forEach((transactie) => {
      this.brandstofTotaal = +this.brandstofTotaal + +transactie.amount;
    });
  }

  private processSparen(transacties: ITransactie[]) {
    this.sparen = transacties.filter(function (transactie) {
      return (transactie.category != null && transactie.category == 'sparen');
    });
  }

  private processOverigeUitgaven(transacties: ITransactie[]) {
    var categoriesToSkip = ['brandstof', 'boodschappen', 'vaste_lasten', 'sparen'];

    this.overigeUitgaven = [];

    transacties.forEach((transactie => {
      // specifieke transacties skippen
      if (transactie.amount >= 0) return;
      if (categoriesToSkip.indexOf(transactie.category) > -1) return;
      if (categoriesToSkip.indexOf(transactie.category_group_key) > -1) return;

      let category = transactie.category_name != null ? transactie.category_name : 'Onbekend';
      if (!(category in this.overigeUitgaven)) this.overigeUitgaven[category] = [];

      this.overigeUitgaven[category].push(transactie);

      this.overigeUitgavenTotaal = +this.overigeUitgavenTotaal + +transactie.amount;
    }));

    console.log(this.overigeUitgaven);
  }

  private processInkomen(transacties: ITransactie[]) {
    this.inkomen = transacties.filter(function (transactie) {
      return (transactie.amount > 0);
    });

    this.inkomenTotaal = 0;
    this.inkomen.forEach((transactie) => {
      this.inkomenTotaal = +this.inkomenTotaal + +transactie.amount;
    });
  }

}

import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';

import { MailComponent } from './mail.component';
import { ConvertToSpacesPipe } from './converttospaces.pipe';
import { TransactiesService } from './transacties.service';
import { FormatAmountPipe } from './formatamount';
import { CreateTransactieDescriptionPipe } from './createtransactiedescription';
import { DatesService } from './dates.service';
import { MonthNamePipe } from './monthname';
import { MomentModule } from 'ngx-moment';
import 'moment/locale/nl';
import { CategoriesService } from './categories.service';

@NgModule({
  declarations: [
    ConvertToSpacesPipe,
    MailComponent,
    FormatAmountPipe,
    CreateTransactieDescriptionPipe,
    MonthNamePipe
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpClientModule,
    BrowserModule,
    MomentModule
  ],
  exports: [
    ConvertToSpacesPipe,
    MailComponent,
    FormatAmountPipe,
    CreateTransactieDescriptionPipe,
    FormsModule,
    HttpClientModule,
    BrowserModule,
    MonthNamePipe,
    MomentModule
  ],
  providers: [TransactiesService, DatesService, CategoriesService]
})
export class SharedModule { }

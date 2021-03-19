import { NgModule, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { NgxDatatableModule } from '@swimlane/ngx-datatable';
import { IonicModule } from '@ionic/angular';

import { TransactionencoursPageRoutingModule } from './transaction-routing.module';

import { TransactionencoursPage } from './transaction.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    TransactionencoursPageRoutingModule,
    NgxDatatableModule
  ],
  declarations: [TransactionencoursPage],

  schemas: [CUSTOM_ELEMENTS_SCHEMA]
})
export class TransactionencoursPageModule {}

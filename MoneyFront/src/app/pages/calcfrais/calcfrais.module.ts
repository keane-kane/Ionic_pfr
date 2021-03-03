import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { CalcfraisPageRoutingModule } from './calcfrais-routing.module';

import { CalcfraisPage } from './calcfrais.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    CalcfraisPageRoutingModule
  ],
  declarations: [CalcfraisPage]
})
export class CalcfraisPageModule {}

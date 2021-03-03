import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { AdminAgencePageRoutingModule } from './admin-agence-routing.module';

import { AdminAgencePage } from './admin-agence.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    AdminAgencePageRoutingModule
  ],
  declarations: [AdminAgencePage]
})
export class AdminAgencePageModule {}

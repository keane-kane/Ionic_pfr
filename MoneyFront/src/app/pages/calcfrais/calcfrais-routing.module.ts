import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { CalcfraisPage } from './calcfrais.page';

const routes: Routes = [
  {
    path: '',
    component: CalcfraisPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class CalcfraisPageRoutingModule {}

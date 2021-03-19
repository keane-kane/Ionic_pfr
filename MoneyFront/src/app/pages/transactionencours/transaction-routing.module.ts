import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { TransactionencoursPage } from './transaction.page';

const routes: Routes = [
  {
    path: '',
    component: TransactionencoursPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class TransactionencoursPageRoutingModule {}

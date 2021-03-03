import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { AdminAgencePage } from './admin-agence.page';

const routes: Routes = [
  {
    path: '',
    component: AdminAgencePage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class AdminAgencePageRoutingModule {}

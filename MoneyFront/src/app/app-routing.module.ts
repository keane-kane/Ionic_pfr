import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';
import { AuthGuard } from './core/gaurds/auth.gaurd';
import { PageNotFoundComponent } from './pages/page-not-found/page-not-found.component';

const routes: Routes = [
  {
    path: 'home',
    loadChildren: () => import('./pages/home/home.module').then( m => m.HomePageModule)
  },
  {
    path: 'login',
    loadChildren: () => import('./pages/login/login.module').then( m => m.LoginPageModule)
  },
  {
    path: 'accueil',
    loadChildren: () => import('./pages/accueil/accueil.module').then( m => m.AccueilPageModule),
  },
  {
    path: 'register',
    loadChildren: () => import('./pages/register/register.module').then( m => m.RegisterPageModule),
    canActivate: [AuthGuard]
  },
  {
    path: 'admin-agence',
    loadChildren: () => import('./pages/admin-agence/admin-agence.module').then( m => m.AdminAgencePageModule),
    canLoad: [AuthGuard]
  },
  {
    path: 'sidemenu',
    loadChildren: () => import('./pages/sidemenu/sidemenu.module').then( m => m.SidemenuPageModule)
  },
  {
    path: 'calcfrais',
    loadChildren: () => import('./pages/calcfrais/calcfrais.module').then( m => m.CalcfraisPageModule),

  },
  {
    path: 'depot',
    loadChildren: () => import('./pages/depot/depot.module').then( m => m.DepotPageModule),

  },
  {
    path: 'tabs',
    loadChildren: () => import('./pages/tabs/tabs.module').then( m => m.TabsPageModule)
  },
  {
    path: 'commission',
    loadChildren: () => import('./pages/commission/commission.module').then( m => m.CommissionPageModule)
  },
  {
    path: 'retrait',
    loadChildren: () => import('./pages/retrait/retrait.module').then( m => m.RetraitPageModule)
  },
  {
    path: 'mestransaction',
    loadChildren: () => import('./pages/transaction/transaction.module').then( m => m.TransactionPageModule),
    canActivate: [AuthGuard]
  },
  {
    path: 'ttesmestransaction',
    loadChildren: () => import('./pages/transaction/transaction.module').then( m => m.TransactionPageModule),
    canActivate: [AuthGuard]
  },
  {
    path: 'error',
    component: PageNotFoundComponent,
    // loadChildren: () => import('src/app/shared/error/error.module').then(m => m.ErrorModule)
  },
  {
    path: '',
    redirectTo: 'login',
    pathMatch: 'full'
  },
  {
    path: '**',
    redirectTo: 'error',
    pathMatch: 'full'
  },
];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule { }

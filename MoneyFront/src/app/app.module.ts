import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { RouteReuseStrategy } from '@angular/router';

import { IonicModule, IonicRouteStrategy } from '@ionic/angular';

import { AppComponent } from './app.component';
import { AppRoutingModule } from './app-routing.module';
import { AuthGuard } from './core/gaurds/auth.gaurd';
import { httpInterceptorProviders } from './core/helpers';
import { HttpClientModule } from '@angular/common/http';
import { SidemenuPageModule } from './pages/sidemenu/sidemenu.module';
import { SidemenuPage } from './pages/sidemenu/sidemenu.page';

import { SMS } from '@ionic-native/sms/ngx';
@NgModule({
  declarations: [AppComponent, SidemenuPage],
  entryComponents: [],
  imports: [
  BrowserModule,
    IonicModule.forRoot(),
    AppRoutingModule,
    HttpClientModule,
  ],

  providers: [
    AuthGuard,
    SMS,
    httpInterceptorProviders,
    { provide: RouteReuseStrategy, useClass: IonicRouteStrategy }
  ],
  bootstrap: [AppComponent],
})
export class AppModule {}

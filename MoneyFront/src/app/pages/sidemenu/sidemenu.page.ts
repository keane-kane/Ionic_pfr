import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from 'src/app/core/services/auth.service';
import { RouteStateService } from 'src/app/core/services/route-state.service';
import { SessionService } from 'src/app/core/services/session.service';
import { UserContextService } from 'src/app/core/services/user-context.service';

@Component({
  selector: 'app-sidemenu',
  templateUrl: './sidemenu.page.html',
  styleUrls: ['./sidemenu.page.scss'],
})
export class SidemenuPage implements OnInit {


  navigate: any =
  [
    {
      title : 'Home',
      url   : '/home',
      icon  : 'home'
    },
    {
      title : 'Transactions',
      url   : '/transaction',
      icon  : 'sync'
    },
    {
      title : 'Commissions',
      url   : '/commision',
      icon  : 'logo-euro'
    },
    {
      title : 'Calculatrice',
      url   : '/calcfrais',
      icon  : 'calculator'
    },

  ];
  constructor(
    private router: Router,
    private routeStateService: RouteStateService,
    private sessionService: SessionService,
    private authService: AuthService,
    private userContextService: UserContextService
  ){

  }

  ngOnInit(): void {
  }


  logOut(){
    this.routeStateService.removeAll();
    this.userContextService.logout();
    this.authService.logout();
    this.sessionService.removeItem('active-menu');
    this.router.navigate(['/login']);
  }


}

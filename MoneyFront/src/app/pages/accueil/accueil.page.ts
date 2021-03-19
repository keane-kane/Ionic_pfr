import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Platform } from '@ionic/angular';
import { RouteStateService } from 'src/app/core/services/route-state.service';
import { SessionService } from 'src/app/core/services/session.service';
import { UserContextService } from 'src/app/core/services/user-context.service';
import { SidemenuPage } from '../sidemenu/sidemenu.page';
import { TabsPage } from '../tabs/tabs.page';
import { SharedService } from 'src/app/core/services/shared.service';

@Component({
  selector: 'app-accueil',
  templateUrl: './accueil.page.html',
  styleUrls: ['./accueil.page.scss'],
})
export class AccueilPage implements OnInit {

  rootPage: any = TabsPage;
  montant: any;
  dateString: string;
  currentUser: any;
  constructor(
    platform: Platform,
    private router: Router,
    private routeStateService: RouteStateService,
    private sessionService: SessionService,
    private userContextService: UserContextService,
    private sharedService: SharedService,
    ) {
    platform.ready().then(() => {
      // Okay, so the platform is ready and our plugins are available.
      // Here you can do any higher level native things you might need.

    });
  }

  ngOnInit() {
    this.sharedService.url = '/users';
    this.currentUser = this.sessionService.getItem('currentUser');
    const { username } = this.currentUser;
    this.sharedService.getById(username).subscribe(
      res => {
           this.montant = new Intl.NumberFormat().format(res.agencePartenaire.compte.montant);
           console.log(this.montant);
      });
    this.dateString = this.formattedDate();
  }
  logOut(){
    this.routeStateService.removeAll();
    this.userContextService.logout();
    this.sessionService.removeItem('active-menu');
    this.router.navigate(['/login']);
  }

  formattedDate() {
    const m = new Date();
    const dateString =
    m.getUTCFullYear() + '/' +
    ('0' + (m.getUTCMonth() + 1)).slice(-2) + '/' +
    ('0' + m.getUTCDate()).slice(-2) + ' ' +
    ('0' + m.getUTCHours()).slice(-2) + ':' +
    ('0' + m.getUTCMinutes()).slice(-2)
    ;
    console.log(dateString);
    return dateString;
    // ('0' + m.getUTCSeconds()).slice(-2)
  }

}

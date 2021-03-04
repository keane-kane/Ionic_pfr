import { Component, OnInit } from '@angular/core';
import { Platform } from '@ionic/angular';
import { SidemenuPage } from '../sidemenu/sidemenu.page';
import { TabsPage } from '../tabs/tabs.page';

@Component({
  selector: 'app-accueil',
  templateUrl: './accueil.page.html',
  styleUrls: ['./accueil.page.scss'],
})
export class AccueilPage implements OnInit {

  rootPage: any = TabsPage;

  constructor(platform: Platform) {
    platform.ready().then(() => {
      // Okay, so the platform is ready and our plugins are available.
      // Here you can do any higher level native things you might need.

    });
  }

  ngOnInit() {
    console.log('vieve');
  }

}

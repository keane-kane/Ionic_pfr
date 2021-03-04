import { Component } from '@angular/core';
import { SidemenuPage } from './pages/sidemenu/sidemenu.page';

@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent {
  rootPage: any = SidemenuPage;
  constructor() {}
}

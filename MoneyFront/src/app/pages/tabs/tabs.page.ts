import { Component, OnInit } from '@angular/core';
import { SessionService } from 'src/app/core/services/session.service';

@Component({
  selector: 'app-tabs',
  templateUrl: './tabs.page.html',
  styleUrls: ['./tabs.page.scss'],
})
export class TabsPage implements OnInit {


  currentUser: any;
  constructor(
    private sessionService: SessionService) {

  }

  ngOnInit() {
    this.currentUser = this.sessionService.getItem('currentUser');
  }

}


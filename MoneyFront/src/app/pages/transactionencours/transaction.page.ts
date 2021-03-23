import { Component, OnInit } from '@angular/core';
import { SharedService } from 'src/app/core/services/shared.service';

import { ViewEncapsulation } from '@angular/core';
import { TabsPage } from '../tabs/tabs.page';
import { SessionService } from '../../core/services/session.service';

@Component({
  selector: 'app-transaction',
  templateUrl: './transaction.page.html',
  styleUrls: ['./transaction.page.scss'],
  encapsulation: ViewEncapsulation.None
})
export class TransactionencoursPage implements OnInit {

  rootPage2: any = TabsPage;
  currentUser: any;
  segment = 'mestransaction';
  transaction: any;
  transactions: any;

  constructor(
      private sharedService: SharedService,
      private sessionService: SessionService,
      )
  { }

  ngOnInit() {

    this.sharedService.url = '/transacannler';
    this.sharedService.getAll().subscribe(
        res => {
             this.transactions = res;
             console.log(this.transactions);
        });



  }

}

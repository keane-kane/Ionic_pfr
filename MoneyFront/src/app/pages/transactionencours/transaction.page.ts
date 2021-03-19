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
  userA: string;
  total: any;
  montant: any;

  constructor(
      private sharedService: SharedService,
      private sessionService: SessionService,
      )
  { }

  ngOnInit() {

    if (this.segment === 'mestransaction'){
      this.sharedService.url = '/users';
      this.currentUser = this.sessionService.getItem('currentUser');
      const {username} = this.currentUser;

      this.sharedService.getById(username).subscribe(
        transac => {
             this.transaction = transac.transactions;
             this.total = new Intl.NumberFormat().format(this.getTotal(this.transaction));
             // console.log(transac);
        });

    }
    this.sharedService.url = '/transactions';
    this.sharedService.getAll().subscribe(
        res => {
             this.transactions = res;
             this.montant = new Intl.NumberFormat().format(this.getTotal(res));
             console.log(this.transactions);
        });



  }
  segmentChanged(ev: any) {
    console.log(this.segment);

    this.sharedService.getAll().subscribe(
      res => {
           this.transactions = res;
           this.montant = new Intl.NumberFormat().format(this.getTotal(res));

      });
  }


  getTotal(data){
    let m = 0;
    for (const t of data) {
    // tslint:disable-next-line: radix partTransfert
    m +=  parseInt(t.montant);
   }
    return Number(m);
  }

}

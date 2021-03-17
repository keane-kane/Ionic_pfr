import { Component, OnInit } from '@angular/core';
import { SessionService } from 'src/app/core/services/session.service';
import { SharedService } from 'src/app/core/services/shared.service';
import { TabsPage } from '../tabs/tabs.page';

@Component({
  selector: 'app-commission',
  templateUrl: './commission.page.html',
  styleUrls: ['./commission.page.scss'],
})
export class CommissionPage implements OnInit {
  rootPage1: any = TabsPage;
  commissions: any;
  total: any;
  constructor(
    private sessionService: SessionService,
    private sharedService: SharedService,
  ) {
    this.sharedService.url = '/users';
   }

  ngOnInit() {

    const { username } =  this.sessionService.getItem('currentUser');
    this.sharedService.getById(username).subscribe(
      res => {
           this.commissions = res.transactions;
           this.total = new Intl.NumberFormat().format(this.getTotal(this.commissions));

           console.log(this.total);
      });
  }

  getTotal(data){
    let m = 0;
    for (const t of data) {
    // tslint:disable-next-line: radix
    m +=  parseInt(t.montant);
   }
    return Number(m);
  }
}

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
  rootPage: any = TabsPage;
  commissions: any;
  total: any;
  constructor(
    private sessionService: SessionService,
    private sharedService: SharedService,
  ) {
    this.sharedService.url = '/transactions';
   }

  ngOnInit() {

    this.sharedService.getAll().subscribe(
      res => {
           this.commissions = res;
           this.total = new Intl.NumberFormat().format(this.getTotal(this.commissions));
           console.log(this.commissions);
      });
  }

  getTotal(data){
    let m = 0;
    let d = 0;
    for (const t of data) {
    // tslint:disable-next-line: radix partTransfert
    m +=  parseInt(t.partDepot);
    // tslint:disable-next-line: radix
    d +=  parseInt(t.partRetrait);
   }
    return Number(m + d);
  }
}

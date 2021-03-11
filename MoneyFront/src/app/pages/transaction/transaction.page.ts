import { Component, OnInit } from '@angular/core';
import { SharedService } from 'src/app/core/services/shared.service';

import { ViewEncapsulation } from '@angular/core';
import { TabsPage } from '../tabs/tabs.page';

@Component({
  selector: 'app-transaction',
  templateUrl: './transaction.page.html',
  styleUrls: ['./transaction.page.scss'],
  encapsulation: ViewEncapsulation.None
})
export class TransactionPage implements OnInit {
  // rootPage: any = TabsPage;
  segment: any;
  transac = [];
  public data: any;
  public columns: any;
  public rows: any;
  public multi: any;
  constructor(private sharedService: SharedService) {
    this.sharedService.url = '/transactions';
    this.columns = [
      { name: 'Date' },
      { name: 'User' },
      { name: 'Type' },
      { name: 'Montant' },
      { name: 'Frais' }
    ];
   }

  ngOnInit() {
    this.sharedService.getAll().subscribe(
      res => {
           this.rows = res;
           console.log(this.rows);
      });


  }
  segmentChanged(ev: any) {
    console.log('Segment changed', ev);
  }
  onSubmit(){

  }


}

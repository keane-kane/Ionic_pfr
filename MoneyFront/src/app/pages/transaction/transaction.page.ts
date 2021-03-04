import { Component, OnInit } from '@angular/core';
import { SharedService } from 'src/app/core/services/shared.service';

import { ViewEncapsulation } from '@angular/core';

@Component({
  selector: 'app-transaction',
  templateUrl: './transaction.page.html',
  styleUrls: ['./transaction.page.scss'],
  encapsulation: ViewEncapsulation.None
})
export class TransactionPage implements OnInit {

  segment: any;
  transac = [];
  public data: any;
  public columns: any;
  public rows: any;
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

import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-sidemenu',
  templateUrl: './sidemenu.page.html',
  styleUrls: ['./sidemenu.page.scss'],
})
export class SidemenuPage implements OnInit {
  navigate: any =
  [
    {
      title : 'Home',
      url   : '/home',
      icon  : 'home'
    },
    {
      title : 'Transactions',
      url   : '/transaction',
      icon  : 'sync'
    },
    {
      title : 'Commissions',
      url   : '/commision',
      icon  : 'logo-euro'
    },
    {
      title : 'Calculatrice',
      url   : '/calcfrais',
      icon  : 'calculator'
    },
  ];
  constructor(){

  }

  ngOnInit(): void {
  }





}

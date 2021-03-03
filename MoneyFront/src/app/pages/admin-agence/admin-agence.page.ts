import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-admin-agence',
  templateUrl: './admin-agence.page.html',
  styleUrls: ['./admin-agence.page.scss'],
})
export class AdminAgencePage implements OnInit {

  constructor() {
    document.getElementById("menu-bas").style.display = "block";
  }

  ngOnInit() {

  }

}

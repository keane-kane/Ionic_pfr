import { Component, OnInit } from '@angular/core';
import { FormGroup } from '@angular/forms';

@Component({
  selector: 'app-retrait',
  templateUrl: './retrait.page.html',
  styleUrls: ['./retrait.page.scss'],
})
export class RetraitPage implements OnInit {


  formGroup: FormGroup;
  segment = 'emetteur';

  constructor() { }

  ngOnInit() {
  }
  segmentChanged(ev: any) {
    console.log('Segment changed', ev);
  }
  onSubmit(){

  }
}

import { stringify } from '@angular/compiler/src/util';
import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { SharedService } from 'src/app/core/services/shared.service';

@Component({
  selector: 'app-depot',
  templateUrl: './depot.page.html',
  styleUrls: ['./depot.page.scss'],
})
export class DepotPage implements OnInit {

  form: FormGroup;
  segment = 'emetteur';
  frais: number | any;
  montantTotal: string;
  montant: number;
  depots: any;

  constructor(
    private sharedService: SharedService,
  ) {
     this.sharedService.url = '/transactions';
   }

  ngOnInit() {
    this.initForm();
  }

  initForm() {
    this.form = new FormGroup({
      montant: new FormControl('', {
        updateOn: 'blur',
        validators: [Validators.required, Validators.minLength(1)]
      }),
      frais: new FormControl(''),
      type: new FormControl('depot'),
      clientTrans: new FormGroup({
        cniClient: new FormControl('', {
          updateOn: 'blur',
          validators: [Validators.required]
        }),
        cniBeneficiaire: new FormControl(''),
        nomClient: new FormControl('', {
          updateOn: 'blur',
          validators: [Validators.required]
        }),
        nomBeneficiaire: new FormControl('', {
          updateOn: 'blur',
          validators: [Validators.required]
        }),
        phoneBeneficiaire: new FormControl('', {
          updateOn: 'blur',
          validators: [Validators.required]
        }),
        phoneClient: new FormControl('', {
          updateOn: 'blur',
          validators: [Validators.required]
        }),
      }),
    });
  }
  getFrais(montant){
    console.log(montant);
    this.frais = this.sharedService.getFrais(montant);
    this.montantTotal = String(Number(this.frais) + Number(montant));
  }
  segmentChanged(ev: any) {
    console.log('Segment changed', ev);
  }

  onSubmit() {
    if (this.form.invalid){
      return;
    }
    this.form.value.clientTrans.phoneBeneficiaire = this.form.value.clientTrans.phoneBeneficiaire.toString();
    this.form.value.clientTrans.phoneClient = this.form.value.clientTrans.phoneClient.toString();
    this.form.value.clientTrans.cniClient = this.form.value.clientTrans.cniClient.toString();

    console.log(this.form.value);
    this.sharedService.create(this.form.value).subscribe((depot) => {
      console.log(depot);
      this.depots = depot;
    });
  }

}

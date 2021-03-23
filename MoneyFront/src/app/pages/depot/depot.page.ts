import { stringify } from '@angular/compiler/src/util';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AlertController, LoadingController, ToastController } from '@ionic/angular';
import { SharedService } from 'src/app/core/services/shared.service';
import { RouteStateService } from 'src/app/core/services/route-state.service';

import { SMS } from '@ionic-native/sms/ngx';

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
    private sms: SMS,
    private fb: FormBuilder,
    public router: Router,
    private sharedService: SharedService,
    public alertController: AlertController,
    public loadingController: LoadingController,
    public toastController: ToastController,
    private routeStateService: RouteStateService,
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
  sendSms(){
    this.sms.hasPermission();
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
    this.presentAlert();
    // this.sharedService.create(this.form.value).subscribe((depot) => {
    //   console.log(depot);
    //   this.depots = depot;
    // });

  }
  async presentAlert() {
    const { montant, frais, type, clientTrans } = this.form.value;
    const alert = await this.alertController.create({
      cssClass: 'confirmeDepot',
      header: 'confirmation dépôt',
      message: `<p>Emetteur</p><p>${clientTrans.nomClient} </p>
                <p>Telephone</p><p>${clientTrans.phoneClient}</p>
                <p>N°CNI</p><p>${clientTrans.cniClient}</p>
                <p>Montant a envoyer</p><p>${montant}</p>
                <p>Recepteur</p><p>${clientTrans.nomBeneficiaire}</p>
                <p>Telephone</p><p>${clientTrans.phoneBeneficiaire}</p>`,
      buttons: [
        {
          text: 'annuler',
          role: 'cancel',

          handler: () => {
            console.log('Confirm Cancel: blah');
          },
        },
        {
          text: 'confirmer',
          cssClass: 'secondary',
          handler: () => {
            this.presentLoading();
            this.sharedService.create(this.form.value).subscribe(
              (depot) => {
                this.presentToast(
                  'success',
                  'transaction effectuee avec succes'
                );
                this.getCode(depot);
                //  this.router.navigateByUrl('/transaction');
                this.routeStateService.add(
                  'Depot',
                  '/mestransaction',
                  null,
                  true
                );
              },
              (err) => {
                this.presentToast();
              }
            );
          },
        },
      ],
    });
    await alert.present();
  }

  async presentLoading() {
    const loading = await this.loadingController.create({
      cssClass: 'my-custom-class',
      message: 'S\'il vous plaît, attendez...',
      duration: 1000,
    });
    await loading.present();

    const { role, data } = await loading.onDidDismiss();
    console.log('Loading dismissed!');
  }

  async presentToast(color = 'danger', message = 'une erreur est survenue.') {
    const toast = await this.toastController.create({
      color,
      message,
      duration: 2000,
      position: 'top',
    });
    toast.present();
  }

  async getCode(r) {
    const alert = await this.alertController.create({
      message: 'CODE : ' + r.code,
      buttons: ['Okay']

    });

    await alert.present();


  }
}

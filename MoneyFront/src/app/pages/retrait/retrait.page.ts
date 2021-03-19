import { Component, OnInit } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormGroup, NgForm, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AlertController, LoadingController, ToastController } from '@ionic/angular';
import { SharedService } from 'src/app/core/services/shared.service';
import { RouteStateService } from '../../core/services/route-state.service';

@Component({
  selector: 'app-retrait',
  templateUrl: './retrait.page.html',
  styleUrls: ['./retrait.page.scss'],
})
export class RetraitPage implements OnInit {

  form: FormGroup;
  segment = 'beneficiaire';
  clientData: any = {};
  retrait: any = {};
  infos: any = {};
  annulerretrait: FormGroup;
  afficher = false;

  constructor(    private fb: FormBuilder,
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

    this.annulerretrait =  new FormGroup({
      annulerTransac: new FormControl(true),
      type: new FormControl('depot')
    });
  }

 // init form
  initForm() {
    this.form = new FormGroup({
      montant: new FormControl(''),
      frais: new FormControl(''),
      type: new FormControl('retrait'),
      clientTrans: new FormGroup({
        cniClient: new FormControl(''),
        cniBeneficiaire:  new FormControl('', {
          updateOn: 'blur',
          validators: [Validators.required]
        }),
        nomClient: new FormControl(''),
        nomBeneficiaire: new FormControl(''),
        phoneBeneficiaire: new FormControl(''),
        phoneClient: new FormControl(''),
        annulerTransac: new FormControl(false),
      }),
    });
  }

 // set data
  // setData(data, form){
  //   const { montant , code} = data ;
  //   const {nomBeneficiaire, nomClient, cniClient, phoneClient, phoneBeneficiaire }  = data.clientTrans ;

  //   form.value.code = code ;
  //   form.value.montant = montant ;
  //   form.value.clientTrans.nomBeneficiaire = nomBeneficiaire;
  //   form.value.clientTrans.nomClient = nomClient;
  //   form.value.clientTrans.cniClient = cniClient;
  //   form.value.clientTrans.phoneClient = phoneClient;
  //   form.value.clientTrans.phoneBeneficiaire = phoneBeneficiaire;
  //   const  cnib = form.value.clientTrans.cniBeneficiaire = form.value.clientTrans.cniBeneficiaire.toString();
  //   console.log(form.value);
  //   console.log(form);

  // }

// recuparation du code de retrait;
  getClientRetrait(code){
    if (code.valid){
      console.log(code);
      const c = '243778149 493828411';
      // tslint:disable-next-line: deprecation
      this.sharedService.getCLientR(code.value).subscribe(
        (retrait) => {
          this.clientData = retrait;
          this.infos = this.clientData.clientTrans;

        },
        (err) => {
        },
        () => {}
      );
    }
  }

  segmentChanged(ev: any) {
    console.log('Segment changed', ev);
  }


  onSubmit(){
    this.sharedService.url = '/transactions';
    this.presentAlertRetrait();
  }

  async presentAlertRetrait() {

    const { montant , code, frais} = this.clientData ;
    const {nomBeneficiaire, nomClient, cniClient, phoneClient, phoneBeneficiaire }  = this.infos ;
    this.form.value.code = code ;
    this.form.value.montant = montant ;
    this.form.value.frais = Number(frais);
    this.form.value.clientTrans.nomBeneficiaire = nomBeneficiaire;
    this.form.value.clientTrans.nomClient = nomClient;
    this.form.value.clientTrans.cniClient = cniClient;
    this.form.value.clientTrans.phoneClient = phoneClient;
    this.form.value.clientTrans.phoneBeneficiaire = phoneBeneficiaire;

    const  cnib = this.form.value.clientTrans.cniBeneficiaire = this.form.value.clientTrans.cniBeneficiaire.toString();
    console.log(this.form.value);

    const alert = await this.alertController.create({
      cssClass: 'confirmeRetrait',
      header: 'confirmation Retraitt',
      message: `<p>Emetteur</p><p>${nomBeneficiaire}</p>
                <p>Telephone</p><p>${phoneBeneficiaire}</p>
                <p>N°CNI</p><p>${cnib}</p>
                <p>Montant a envoyer</p><p>${montant}</p>
                <p>Emetteur</p><p>${nomClient}</p>
                <p>Telephone</p><p>${phoneClient}</p>`,
      buttons: [
        {
          text: 'annuler',
          role: 'cancel',

          handler: () => {
            console.log('Confirm Cancel:eeeeeee');
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
                this.router.navigateByUrl('/mestransaction');
                this.routeStateService.add(
                  'Retrait',
                  '/transaction',
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

  // annuler depot
  onCancel() {
    const { montant , code, frais} = this.clientData ;
    const {nomBeneficiaire, nomClient, cniClient, phoneClient, phoneBeneficiaire }  = this.infos ;
    this.form.value.code = code ;
    this.form.value.montant = montant ;
    this.form.value.type = 'depot' ;
    this.form.value.annulerTransac = true ;
    this.form.value.frais = Number(frais);
    this.form.value.clientTrans.nomBeneficiaire = nomBeneficiaire;
    this.form.value.clientTrans.nomClient = nomClient;
    this.form.value.clientTrans.cniClient = cniClient;
    this.form.value.clientTrans.phoneClient = phoneClient;
    this.form.value.clientTrans.phoneBeneficiaire = phoneBeneficiaire;
    this.form.value.clientTrans.cniBeneficiaire = this.form.value.clientTrans.cniBeneficiaire.toString();

    console.log(this.form.value);
    
    this.presentCancelTransaction( this.form.value);
  }

async presentCancelTransaction(transaction) {
  const alert = await this.alertController.create({
    cssClass: 'my-custom-class',
    header: 'Confirm !',
    message: 'Souhaitez vous annuler la transaction',
    buttons: [
      {
        text: 'Non',
        role: 'cancel',
        cssClass: 'secondary',
      },
      {
        text: 'Oui',
        handler: () => {
          if (this.form.valid) {
            this.presentLoading();
            this.sharedService.create(transaction).subscribe(
              (res) => {
                this.presentToast('success', 'La transaction a été annuler !');
                this.routeStateService.add(
                  'Annuler',
                  '/mestransaction',
                  null,
                  true
                );
              },
              (err) => {
                this.presentToast();
              }
            );
          }
        },
      },
    ],
  });
  await alert.present();
}
}

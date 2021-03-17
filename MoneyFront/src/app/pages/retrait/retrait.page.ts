import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, NgForm, Validators } from '@angular/forms';
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
  segment = 'emetteur';
  clientData: any = {};
  infos: any = {};
  retrait: any = {};
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
  }

  initForm() {
    this.form = new FormGroup({
      montant: new FormControl(''),
      frais: new FormControl(0),
      type: new FormControl('retrait'),
      clientTrans: new FormGroup({
        cniClient: new FormControl(''),
        cniBeneficiaire:  new FormControl('',  {
          updateOn: 'blur',
          validators: [Validators.required, Validators.minLength(1)]
        }),
        nomClient: new FormControl(''),
        nomBeneficiaire: new FormControl(''),
        phoneBeneficiaire: new FormControl(''),
        phoneClient: new FormControl(''),
      }),
    });
  }

// recuparation du code de retrait;

  getClientRetrait(code){
    if (code.valid){
      console.log(code);
      this.sharedService.url = '/transactions';
      const c = '381969784';
      // tslint:disable-next-line: deprecation
      this.sharedService.getCLientR(code.value).subscribe(
        (retrait) => {
          this.clientData = retrait;
          this.infos = this.clientData.clientTrans;
          console.log(this.clientData);
          this.afficher = true;

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
 
    this.presentAlert();
  }

 async presentAlert() {
  const { montant , code} = this.clientData ;
  const {nomBeneficiaire, nomClient, cniClient, phoneClient, phoneBeneficiaire }  = this.infos ;

  this.form.value.code = code ;
  this.form.value.montant = montant ;
  this.form.value.clientTrans.nomBeneficiaire = nomBeneficiaire;
  this.form.value.clientTrans.nomClient = nomClient;
  this.form.value.clientTrans.cniClient = cniClient;
  this.form.value.clientTrans.phoneClient = phoneClient;
  this.form.value.clientTrans.phoneBeneficiaire = phoneBeneficiaire;
  const  cnib = this.form.value.clientTrans.cniBeneficiaire = this.form.value.clientTrans.cniBeneficiaire.toString();
  console.log(this.form.value);
  const alert = await this.alertController.create({
    cssClass: 'confirmeDepot',
    header: 'confirmation Retraitt',
    message: `<p>Emetteur</p><p>${nomBeneficiaire} </p><p>Telephone</p><p>${phoneBeneficiaire}</p><p>N°CNI</p><p>${cnib}</p><p>Montant a envoyer</p><p>${montant}</p><p>Emetteur</p><p>${nomClient}</p><p>Telephone</p><p>${phoneClient}</p>`,
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
              this.router.navigateByUrl('/transaction');
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

 onCancel() {
  this.clientData.annulertransac = true;
  this.clientData.dateRetrait = new Date();
  console.log(this.clientData);
  
  this.presentAlertCancelTransaction(this.clientData);
}

async presentAlertCancelTransaction(transaction) {
  const alert = await this.alertController.create({
    cssClass: 'my-custom-class',
    header: 'Confirm!',
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
            const { id } = transaction;

            this.presentLoading();
            this.sharedService.update(transaction, id).subscribe(
              (res) => {
                this.presentToast('success');
                this.routeStateService.add(
                  'Users',
                  '/transaction',
                  null,
                  true
                );;
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

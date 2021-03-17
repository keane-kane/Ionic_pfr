import { Component, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { AlertController, LoadingController } from '@ionic/angular';
import { SharedService } from 'src/app/core/services/shared.service';

@Component({
  selector: 'app-calcfrais',
  templateUrl: './calcfrais.page.html',
  styleUrls: ['./calcfrais.page.scss'],
})
export class CalcfraisPage implements OnInit {
  fraisForm: { montant: number; type: string } = {
    montant: 0,
    type: '',
  };
  constructor(
    private sharedService: SharedService,
    public alertController: AlertController,
    public loadingController: LoadingController,
  ) { }

  ngOnInit() {
  }

  onSubmit(gfraisform: NgForm){
    if (gfraisform.valid && this.fraisForm.type === 'depot' && this.fraisForm.montant > 0 ) {
      const {montant} = gfraisform.value;
      const frais = this.sharedService.getFrais(montant);
      console.log(frais);
      this.alertFrais(montant, frais);
   }
  }
  async alertFrais(m, f){
    const alert = await this.alertController.create({
      cssClass: 'fraisc',
      header: 'Calcul de frais',
      message: `<p>Pour une transaction de ${m}  les frais s'élevent à:</p><p class="danger">${f} </p>`,
      buttons: [
        {
          text: 'Retour',
          role: 'cancel',

          handler: () => {
            console.log('Confirm Cancel: blah');
          },
        },
      ],
    });
    await alert.present();
  }

}

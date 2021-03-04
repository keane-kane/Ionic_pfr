import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ToastController, LoadingController } from '@ionic/angular';

@Component({
  selector: 'app-home',
  templateUrl: 'home.page.html',
  styleUrls: ['home.page.scss'],
})
export class HomePage implements OnInit  {

  constructor(
    private router: Router,
    private toastCtrl: ToastController,
    private loadingCtrl: LoadingController,
  ) {

  }
 ngOnInit(){
   setInterval(() => {
    // this.router.navigate(['/login']);
    this.router.navigate(['/accueil']);
  } ,  2000);
 }
  async presentToast() {
    const toast = await this.toastCtrl.create({
      message: 'Bienvenu dans Save Money SA',
      duration: 2000,
      position: 'middle',
      cssClass: 'toast-costum-class',
    });
    toast.present();
  }


}

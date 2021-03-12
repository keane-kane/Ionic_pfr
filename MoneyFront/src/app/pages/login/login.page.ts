import { Component, OnInit } from '@angular/core';
import { AuthService } from 'src/app/core/services/auth.service';
import { RouteStateService } from 'src/app/core/services/route-state.service';
import { UserContextService } from 'src/app/core/services/user-context.service';
import { JwtHelperService } from '@auth0/angular-jwt';
import { ToastController } from '@ionic/angular';
import { NgForm } from '@angular/forms';
@Component({
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.scss'],
})
export class LoginPage implements OnInit {

  form: { email: string; password: string } = {
    email: '',
    password: '',
  };
  msgs: any = {};
  showPwd = false;
  submitted = false;
  disabledButton;

  constructor(
    private routeStateService: RouteStateService,
    private userContextService: UserContextService,
    private authService: AuthService,
    public toastController: ToastController
  ) {}

  ngOnInit() {
    this.msgs = [
      { severity: 'info', detail: 'UserName: ads@gmail.com' },
      { severity: 'info', detail: 'Password: password' },
    ];
  }

  async presentToast() {
    const toast = await this.toastController.create({
      color: 'danger',
      message: 'numero ou mot de passe incorrect.',
      duration: 2000,
      position: 'top',
    });
    toast.present();
  }
  onSubmit(data: NgForm) {
    console.log(data.value);
    this.submitted = true;
    if (!data.valid){
        return 0;
    }
    // tslint:disable-next-line: deprecation
    this.authService.login(data.value).subscribe(
      (userData) => {
        const helper = new JwtHelperService();

        // tslint:disable-next-line: no-string-literal
        const decodedToken = helper.decodeToken(userData['token']);
        const expirationDate = decodedToken.exp;
        const isExpired = decodedToken.iat;

        switch (decodedToken.roles[0]) {
          case 'ROLE_CAISSIER':
          case 'ROLE_ADMIN_SYS': {
            this.userContextService.setUser(decodedToken);
            this.routeStateService.add(
              'Users',
              '/accueil',
              null,
              true
            );
            break;
          }
          case 'ROLE_ADMIN_AGENCE':
            case 'ROLE_USER_AGENCE': {
              this.userContextService.setUser(decodedToken);
              this.routeStateService.add(
                'Users',
                '/accueil',
                null,
                true
              );
              break;
            }
        }
      },
      (error) => {
        this.presentToast();
        console.log(error);
      },
      () => {}
    );
  }

}

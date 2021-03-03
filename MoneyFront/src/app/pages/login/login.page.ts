import { Component, OnInit } from '@angular/core';
import { AuthService } from 'src/app/core/services/auth.service';
import { RouteStateService } from 'src/app/core/services/route-state.service';
import { UserContextService } from 'src/app/core/services/user-context.service';
import { JwtHelperService } from '@auth0/angular-jwt';
@Component({
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.scss'],
})
export class LoginPage implements OnInit {
  email: string;
  password: string;
  showPwd = false;
  msgs: any = {};

  disabledButton;

  constructor(
    private routeStateService: RouteStateService,
    private userContextService: UserContextService,
    private authService: AuthService
  ) {}

  ngOnInit() {
    this.msgs = [
      { severity: 'info', detail: 'UserName: ads@gmail.com' },
      { severity: 'info', detail: 'Password: password' },
    ];
  }

  onSubmit(form) {
    console.log(form.value);
    this.authService.login(form.value).subscribe(
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
              '/transaction',
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
                '/transaction',
                null,
                true
              );
              break;
            }
        }
      },
      (error) => {

        console.log(error);
      }
    );
  }

}

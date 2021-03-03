import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { User } from 'src/app/core/models/user.model';

@Component({
  selector: 'app-register',
  templateUrl: './register.page.html',
  styleUrls: ['./register.page.scss'],
})
export class RegisterPage implements OnInit {

  user: User;
  userForm: FormGroup;
  showPwd = false;

  disabledButton;
  constructor(
    private fb: FormBuilder,
    ) { }

  ngOnInit() {

    this.userForm = this.fb.group({
      nom: new FormControl('', Validators.required),
      prenom: new FormControl('', Validators.compose([Validators.required])),
      password: new FormControl('', [Validators.required]),
      profil: new FormControl('', Validators.required),
      phone: new FormControl('', Validators.required),
      email: new FormControl('', [Validators.required, Validators.email]),
      avatar: new FormControl(null),
      id: new FormControl(''),
    });
  }

  onRegister(){

  }
}

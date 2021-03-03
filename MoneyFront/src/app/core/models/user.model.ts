
export abstract class User {
     id: number;
     password: string;
     email: string;
     nom: string;
     prenom: string;
     avatar: Blob;
     archive: boolean;
     address?: string;
     birthDate: Date;
     phone: string;

    // constructor(user: User) {
    //   this.id        = user.id;
    //   this.username  = user.username;
    //   this.password  = user.password;
    //   this.email     = user.email;
    //   this.nom = user.nom;
    //   this.prenom  = user.prenom;
    //   this.avatar     = user.avatar;
    //   this.archive   = user.archive;
    //   this.address   = user.address;
    //  }

}

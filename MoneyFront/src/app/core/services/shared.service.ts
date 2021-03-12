import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { throwError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';
import { environment } from 'src/environments/environment';
import { HttpHeaders } from '@angular/common/http';

@Injectable({
  providedIn: 'root',
})
export class SharedService {
  constructor(private http: HttpClient) {}
  url = '';
  headers = new HttpHeaders({ 'Content-Type': 'application/json' });
  options = { headers: this.headers };

  tabs: { val: number, m: number[] }[] = [
      {val: 425, m: [ 0, 5000]},
      {val: 850,  m: [5000, 10000]},
      {val: 1270 , m: [10000, 15000]},
      {val: 1695 , m: [15000, 20000]},
      {val: 2500 , m: [20000, 50000]},
      {val: 3000 , m: [50000, 60000]},
      {val: 4000 , m: [60000, 75000]},
      {val: 5000 , m: [75000, 120000]},
      {val: 6000 , m: [120000, 150000]},
      {val: 7000 , m: [150000, 200000]},
      {val: 8000 , m: [200000, 250000]},
      {val: 9000 , m: [250000, 300000]},
      {val: 12000, m: [300000, 400000]},
      {val: 15000, m: [400000, 750000]},
      {val: 22000, m: [750000, 900000]},
      {val: 25000, m: [900000, 1000000]},
      {val: 27000, m: [1000000, 1125000]},
      {val: 30000, m: [1125000, 1400000]},
      {val: 35000, m: [1400000, 2000000]},
  ];




  getAll(): any {
    return this.http
      .get(environment.apiUrl + this.url, {
        headers: { Accept: 'application/json' },
      })
      .pipe(
        map((data) => {
          const datas = [];
          for (const key in data) {
            if (data.hasOwnProperty(key)) {
              datas.push({ ...data[key] });
            }
          }
          return datas;
        }, catchError(this.handleError))
      );
  }

  getById(id: number): any {
    return this.http.get(`${environment.apiUrl}${this.url}/${id}`, {
      headers: { Accept: 'application/json' },
    });
  }

  create(data: any): any {
    return this.http
      .post(`${environment.apiUrl}${this.url}`, data)
      .pipe(catchError(this.handleError));
  }

  update(data: any, id: number): any {
    return this.http
      .put(`${environment.apiUrl}${this.url}/${id}`, data, {
        headers: { 'Content-Type': 'multipart/form-data' },
      })
      .pipe(catchError(this.handleError));
  }

  delete(id: number): any {
    return this.http
      .delete(`${environment.apiUrl}${this.url}/${id}`)
      .pipe(catchError(this.handleError));
  }

  private handleError(error: HttpErrorResponse): any {
    if (error.error instanceof ErrorEvent) {
      // A client-side or network error occurred. Handle it accordingly.

      console.error('An error occurred:', error.error);
    } else {
      // The backend returned an unsuccessful response code.

      // The response body may contain clues as to what went wrong.

      console.error(`Backend returned code ${error}, ` + `body was: ${error}`);
    }

    // return an observable with a user-facing error message

    return throwError('Something bad happened. Please try again later.');
  }

  getFrais(m){
    if (m >= 2000000){
      return m * 0.02;
    }else {
      for (const tab of this.tabs) {
        if (m >= tab.m[0] && ++m   <= tab.m[1]) {
          return tab.val;
        }
      }
    }
  }

}

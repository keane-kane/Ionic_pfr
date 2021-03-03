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
  url = '';
  headers = new HttpHeaders({ 'Content-Type': 'application/json' });
  options = { headers: this.headers };
  constructor(private http: HttpClient) {}

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
}

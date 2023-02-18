import { Injectable } from '@angular/core';
import { HttpEvent, HttpHandler, HttpInterceptor, HttpRequest } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { Router } from '@angular/router';

@Injectable()
export class ErrorInterceptor implements HttpInterceptor {
      constructor(public router: Router) {
      }

      intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
            return next.handle(request).pipe(catchError(err => {
                  //console.log(err);
                  if (err.status === 401) {
                        // auto logout if 401 response returned from api
                        localStorage.clear();
                        this.router.navigate(['/login']);
                  }
                  if (err.error.data) {
                        return throwError(err);
                  }
                  const e = err.error.message || err.statusText;
                  return throwError(e);
            }));
      }
}

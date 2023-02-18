import { Injectable } from '@angular/core';
import { HttpRequest, HttpHandler, HttpEvent, HttpInterceptor } from '@angular/common/http';
import { EMPTY, Observable } from 'rxjs';
import { GlobalFunctions } from '../services/globalFunctions';

@Injectable()
export class HeaderInterceptor implements HttpInterceptor {

    constructor(private globalFunctions: GlobalFunctions) { }

    intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        // add authorization header with jwt token if available
        const token = localStorage.getItem('token');
        //console.log(token + " TOKEN");
        // const uuid = localStorage.getItem('uuid');
        // if (this.globalFunctions.autoLogOut() && !request.url.includes('logout')) {
        //     this.globalFunctions.signout(true);
        //     return EMPTY;
        // }
        // // else if (token && uuid) {
        // else if (token) {
        if (token) {
            request = request.clone({
                setHeaders: {
                    Authorization: `Bearer ${token}`
                    // uuid: uuid
                }
            });
        }
        return next.handle(request);
    }
}

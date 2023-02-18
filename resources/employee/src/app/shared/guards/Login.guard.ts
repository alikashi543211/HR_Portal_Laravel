import {Injectable} from '@angular/core';
import {ActivatedRouteSnapshot, CanActivate, Router, RouterStateSnapshot} from '@angular/router';
import {Observable} from 'rxjs';

@Injectable({
    providedIn: 'root'
})
export class LoginGuard implements CanActivate {
    constructor(
        public router: Router) {
    }

    canActivate(next: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {

        ///// WE HAVE TO IMPLEMENT OUR LOGIC HERE:: START
        const token = localStorage.getItem('token');
        if (token) {
            if (state.url.includes('refresh')) {
                localStorage.clear();
                this.router.navigate(['/login']);
            }
            else this.router.navigate(['/home']);
            return false;
        }
        else {
            return true;
        }

        /////////////////// END
    }
}

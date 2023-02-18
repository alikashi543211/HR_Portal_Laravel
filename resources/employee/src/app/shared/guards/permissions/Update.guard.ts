import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, RouterStateSnapshot } from '@angular/router';
import { permissionsIndices } from '../../constants/permissions';
import { AuthService } from '../../services/auth.service';
import { GlobalFunctions } from '../../services/globalFunctions';
import { ToastrService } from 'ngx-toastr';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
})
export class UpdateGuard implements CanActivate {
    constructor(private toastrService: ToastrService, private authService: AuthService, private globalFunctions: GlobalFunctions) {
    }

    canActivate(next: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
        ///// WE HAVE TO IMPLEMENT OUR LOGIC HERE:: START
        let permissions: any;
        let response = false;
        permissions = this.authService.getPermissions();
        if (this.globalFunctions.autoLogOut()) {
            this.globalFunctions.signout(true);
            return false;
        }
        /* if (state.url.includes('roles')) {
            if (permissions && permissions.roles[permissionsIndices.UPDATE]) response = true;
        } else {
            if (state.url.includes('admin-users')) {
                if (permissions && permissions['admin-users'][permissionsIndices.UPDATE]) response = true;
            } else {
                if (state.url.includes('app-users')) {
                    if (permissions && permissions['app-users'][permissionsIndices.UPDATE]) response = true;
                }
            }
        } */
        if (response) return response;
        else {
            this.toastrService.error('You are not allowed to perform this action.');
            return response;
        }
        /////////////////// END
    }
}

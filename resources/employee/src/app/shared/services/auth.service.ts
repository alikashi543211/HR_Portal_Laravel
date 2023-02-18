import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Router } from '@angular/router';

import { ToastrService } from 'ngx-toastr';
import { Observable, Subject } from 'rxjs';
import { environment } from 'src/environments/environment';
import { urls } from '../constants/urls';
// import { FirebaseService } from './firebase.service';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  userObjSubject: Subject<any>;

  constructor(private http: HttpClient, private router: Router, private toastrService: ToastrService) {
    this.userObjSubject = new Subject();
  }

  storeUserData(token, userObj, permissions = null): void {
    if (token == null) {
      localStorage.setItem('userObj', JSON.stringify(userObj));
      if (permissions) localStorage.setItem('permissions', JSON.stringify(permissions));
      this.userObjSubject.next(true);
      return;
    } else {
      localStorage.setItem('token', token);
      localStorage.setItem('userObj', JSON.stringify(userObj));
      if (permissions) localStorage.setItem('permissions', JSON.stringify(permissions));
      this.userObjSubject.next(true);
    }
  }

  getPermissions(): JSON {
    if (localStorage.getItem('permissions')) return JSON.parse(localStorage.getItem('permissions'));
    else return null;
  }

  getUserObj(): JSON {
    if (localStorage.getItem('userObj')) return JSON.parse(localStorage.getItem('userObj'));
    else return null;
  }

  login(params = {}): Observable<any> {
    return this.http.post<any>(`${environment.apiUrl + urls.login}`, params);
  }

  logout(params = {}): Observable<any> {
    return this.http.post<any>(`${environment.apiUrl + urls.logout}`, params);
  }

}

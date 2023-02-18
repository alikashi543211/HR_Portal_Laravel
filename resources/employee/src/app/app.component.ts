import { Component, OnDestroy, OnInit } from '@angular/core';
import { AuthService } from './shared/services/auth.service';
// import { FirebaseService } from './shared/services/firebase.service';


@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit, OnDestroy {
  // constructor(private firebaseService: FirebaseService, private authService: AuthService) { }
  constructor(private authService: AuthService) { }

  ngOnInit() {
    /* if (localStorage.getItem('token')) {
      this.firebaseService.initializeNotificationSettings();
    } */
  }

  ngOnDestroy() {
  }
}

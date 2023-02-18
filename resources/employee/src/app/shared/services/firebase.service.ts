import { Injectable, OnDestroy } from '@angular/core';
import { AngularFireMessaging } from '@angular/fire/messaging';
import { ToastrService } from 'ngx-toastr';
import { AngularFireDatabase, AngularFireList } from '@angular/fire/database';
import { takeUntil } from 'rxjs/operators';
import { Subject } from 'rxjs';
import { AuthService } from './auth.service';
import { HomeService } from './home.service';
import { v4 as uuidv4 } from 'uuid';
import { Router } from '@angular/router';
import { MatDialog } from '@angular/material/dialog';
import { ConfirmModalComponent } from 'src/app/components/confirm-modal/confirm-modal.component';

@Injectable({
  providedIn: 'root'
})
export class FirebaseService implements OnDestroy {

  notificationsCount = 0;
  totalNotifications = 0;
  notificationRef: AngularFireList<any>;
  notifications = [];
  notificationSubscriber;
  pageSize = 10;
  private destroyed$ = new Subject<void>();

  constructor(private fireMessaging: AngularFireMessaging, private authService: AuthService,
    private homeService: HomeService, private angularFireDatabase: AngularFireDatabase,
    private toastrService: ToastrService, private router: Router, private modalRef: MatDialog) {

  }

  ngOnDestroy() {
    this.removeSubscrtiption();
  }

  removeSubscrtiption() {
    this.destroyed$.next();
    this.destroyed$.complete();
  }

  requestPermissions() {

    this.notifications = [];
    this.notificationsCount = 0;
    this.pageSize = 10;

    this.fireMessaging.requestPermission
      .subscribe((res: any) => {
        //console.log(res);
        this.getToken();
      },
        err => {
          //console.log(err);
        });
  }

  getToken() {
    this.fireMessaging.requestToken
      .subscribe((res: any) => {
        //console.log('Token:' + res);
        let uuid;
        if (localStorage.getItem('uuid')) {
          uuid = localStorage.getItem('uuid');
        } else {
          uuid = this.getUuid();
        }
        /* this.homeService.updateNotificationDetails({ uuid: uuid, firebase_token: res }).subscribe(res => {
          //console.log(res);
          this.initializeNotificationSettings();
          localStorage.setItem('uuid', uuid);
        }) */
      },
        err => {
          //console.log(err);
        });
  }

  initializeNotificationSettings() {
    this.destroyed$ = new Subject<void>();

    this.notifications = [];
    this.notificationsCount = 0;
    this.pageSize = 10;

    this.initializeInAppNotifications();
    this.fetchNotification();
    // this.subscribeNotification();
  }

  private initializeInAppNotifications() {
    this.fireMessaging.messages.pipe(takeUntil(this.destroyed$)).subscribe((res: any) => {
      if (res.data.logout) this.showReloadAlert();
      else this.showInAppNotification(res.data);
    })
  }

  private fetchNotification() {
    let currentUser: any;
    currentUser = this.authService.getUserObj();
    if (!currentUser) return;
    this.notificationRef = this.angularFireDatabase.list(currentUser.business_reference_id + '/notifications', ref => {
      ref.orderByChild('created_at')
      return ref;
    });
    this.subscribeNotification();
  }

  private subscribeNotification() {
    //console.log('Notification Subscribe');
    this.notificationSubscriber = this.notificationRef.snapshotChanges().pipe(takeUntil(this.destroyed$)).subscribe(notifications => {
      //console.log('check this', notifications);
      let tempNotification = notifications.slice();
      tempNotification = tempNotification.reverse();
      let unreadCount = tempNotification.length;
      this.notifications = [];
      for (const notification of tempNotification) {
        if (notification.payload) {
          if (!notification.payload.val().show) {
            unreadCount--;
            if (unreadCount < 0) unreadCount = 0;
            continue;
          }
          else {
            this.notifications.push(notification);
            if (notification.payload.val().is_read) {
              unreadCount--;
            }
          }
        }
      }


      this.notificationsCount = unreadCount;
      this.totalNotifications = this.notifications.length;

      //console.log('TOTAL COUNT', this.totalNotifications);
      //console.log('UNREAD MESSAGES:', this.notificationsCount);
    });
    // this.notificationRef.snapshotChanges(['child_added']).subscribe(snapshot => {
    //   //console.log(snapshot);
    //   // all records after the last continue to invoke this function
    //   const length = snapshot.length;
    //   for (let i = length - 1; i >= 0; i--) {
    //     if (snapshot[i].type == 'child_added') {
    //       if (snapshot[i].payload.val().data.type == 'educator_updated') {
    //         // this.refreshDetails(1);
    //       } else {
    //         this.toastrService.success(snapshot[i].payload.val().description);
    //       }
    //       break;
    //     }
    //   }
    // });

  }

  readNotification(notification) {
    const dataRead = notification.payload.val();

    if (dataRead.is_read) return;
    this.notificationRef.update(notification.key, { is_read: true }).then(res => {
      //console.log(notification.payload.val());

      if (dataRead.logout) {
        const obj = {
          uuid: localStorage.getItem('uuid')
        };
        this.authService.logout(obj)
          .subscribe((res: any) => {
            this.removeSubscrtiption();
            localStorage.clear();
            this.router.navigate(['']);
          },
            (e) => {
              if (typeof (e) == 'string') {
                this.toastrService.error(e);
              } else {
                this.toastrService.error(e[0]);
              }
            });
        return;
      }


      // if (notification.payload.val().data.type == 'school_shop') {
      //   this.router.navigate(['schoolshop/details/' + notification.payload.val().data.id]);
      // }
      // if (notification.payload.val().data.type == 'community') {
      //   const commentId = notification.payload.val().data.model.original_comment ? notification.payload.val().data.model.original_comment : '';
      //   const replyId = notification.payload.val().data.model.original_reply ? notification.payload.val().data.model.original_reply : '';
      //   this.router.navigate(['/communityfeature/view-post/view-post', {
      //     title: 'View Post',
      //     postId: notification.payload.val().data.id,
      //     commentId: commentId,
      //     replyId: replyId
      //   }]);
      // }
      // if (notification.payload.val().data.type == 'direct_message') {
      //   this.router.navigate(['/communityfeature/direct-message/' + notification.payload.val().data.model.conversation_id, {
      //     userId: notification.payload.val().data.model.sender,
      //     roomId: notification.payload.val().data.model.room_id,
      //   }]);
      // }

    }).catch(err => {
      // //console.log(err);
    });
  }

  showInAppNotification(data) {
    this.toastrService.success(data?.body, data?.title);
  }

  getUuid() {
    //console.log(uuidv4());
    return uuidv4();
  }

  loadMore(event) {
    event.stopPropagation();
    if (this.totalNotifications > this.pageSize) {
      this.pageSize = this.pageSize + 10;
    }
  }

  showReloadAlert() {
    const data = {
      title: 'Permissions updated!',
      message: 'Your permissions have been updated. Please login again to continue.',
      type: 1
    };
    const dialog = this.modalRef.open(ConfirmModalComponent, {
      data: data,
      width: '400px',
      height: 'auto',
      hasBackdrop: true,
      disableClose: true,
      position: {
        top: '20px'
      }
    });

    dialog.afterClosed()
      .subscribe((res: any) => {
        this.modalRef.closeAll();
        localStorage.clear();
        this.router.navigate(['']);
      });
  }
}

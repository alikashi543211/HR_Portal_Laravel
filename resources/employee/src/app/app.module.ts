import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { NgModule } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { RouterModule } from '@angular/router';


import { AppRoutingModule } from './app.routing';

import { AppComponent } from './app.component';
import { HomeGuard } from './shared/guards/Home.guard';
import { NgxUiLoaderModule, NgxUiLoaderHttpModule, NgxUiLoaderConfig } from 'ngx-ui-loader';
import { ToastrModule } from 'ngx-toastr';
import { ErrorInterceptor } from './shared/interceptors/error.interceptor';
import { HTTP_INTERCEPTORS } from '@angular/common/http';
import { HeaderInterceptor } from './shared/interceptors/header.interceptor';
import { LoginGuard } from './shared/guards/Login.guard';
import { CreateGuard } from './shared/guards/permissions/Create.guard';
import { UpdateGuard } from './shared/guards/permissions/Update.guard';
import { ViewGuard } from './shared/guards/permissions/View.guard';

// import { AngularFireDatabaseModule } from '@angular/fire/database';
// import { AngularFireMessagingModule } from '@angular/fire/messaging';
// import { AngularFireModule } from '@angular/fire';
import { environment } from 'src/environments/environment';
import { ConfirmModalModule } from './components/confirm-modal/confirm-modal.module';
import { ConfirmModalComponent } from './components/confirm-modal/confirm-modal.component';
import { MatDialogModule } from '@angular/material/dialog';
import { CommonModule } from '@angular/common';
import { SetupPasswordComponent } from './setup-password/setup-password.component';
import { MatButtonModule } from '@angular/material/button';
import { MatCardModule } from '@angular/material/card';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { MatTooltipModule } from '@angular/material/tooltip';

const ngxUiLoaderConfig: NgxUiLoaderConfig = {
    "bgsOpacity": 0.5,
    "bgsPosition": "center-center",
    "bgsSize": 60,
    "blur": 2,
    "fgsPosition": "center-center",
    "fgsSize": 60,
    "fgsColor": '#e1251b',
    "gap": 24,
    "logoPosition": "center-center",
    "logoSize": 120,
    // "logoUrl": "assets/images/inside-loader.gif",
    "overlayColor": "rgba(40, 40, 40, 0.8)",
    "pbColor": "red",
    "pbDirection": "ltr",
    "pbThickness": 3,
    "hasProgressBar": true,
    "text": "Please Wait",
    "textColor": "#FFFFFF",
    "textPosition": "center-center",

};

@NgModule({
    imports: [
        BrowserAnimationsModule,
        FormsModule,
        ReactiveFormsModule,
        HttpClientModule,
        RouterModule,
        AppRoutingModule,
        MatInputModule,
        MatButtonModule,
        MatFormFieldModule,
        MatCardModule,
        MatIconModule,
        // FormsModule,
        // ReactiveFormsModule,
        MatTooltipModule,
        NgxUiLoaderModule.forRoot(ngxUiLoaderConfig),
        NgxUiLoaderHttpModule.forRoot({ 'showForeground': true, }),
        ToastrModule.forRoot(
            {
                timeOut: 5000,
                positionClass: 'toast-top-right',
                preventDuplicates: false
            },
        ),
        // AngularFireMessagingModule,
        // AngularFireDatabaseModule,
        // AngularFireModule.initializeApp(environment.firebaseConfig),
        ConfirmModalModule,
        MatDialogModule
    ],
    declarations: [
        AppComponent,
        SetupPasswordComponent
    ],
    providers: [
        HomeGuard,
        LoginGuard,
        CreateGuard,
        UpdateGuard,
        ViewGuard,
        {
            provide: HTTP_INTERCEPTORS,
            useClass: ErrorInterceptor,
            multi: true,
        },
        {
            provide: HTTP_INTERCEPTORS,
            useClass: HeaderInterceptor,
            multi: true
        }
    ],
    bootstrap: [AppComponent],
    entryComponents: [ConfirmModalComponent]
})
export class AppModule { }

// This file can be replaced during build by using the `fileReplacements` array.
// `ng build --prod` replaces `environment.ts` with `environment.prod.ts`.
// The list of file replacements can be found in `angular.json`.

export const environment = {
  production: false,
  // DEV ENVIRONMENT 
  // apiUrl: 'https://demos.tabdili.website/ttfn-dev/public/api/',

  // LOCAL ENVIRONMENT 
  // apiUrl: 'http://192.168.1.150/ttfn/public/api/',
  apiUrl: 'http://localhost/hr-portal/public/api/',

  // STAGING ENVIRONMENT 
  // apiUrl: 'https://demos.tabdili.website/ttfn-api-admin/public/api/',

  basePath: 'https://demos.tabdili.website/ttfn-admin/',

  // Firebase Config
  /* firebaseConfig: {
    apiKey: "AIzaSyDGVpB2MsHU8Vc7t8xsNwTvCpnzbnpXvTM",
    authDomain: "ttfn-93bcc.firebaseapp.com",
    databaseURL: "https://ttfn-93bcc-default-rtdb.firebaseio.com",
    projectId: "ttfn-93bcc",
    storageBucket: "ttfn-93bcc.appspot.com",
    messagingSenderId: "383556015200",
    appId: "1:383556015200:web:a17320610a1d8e0b1edf05",
    measurementId: "G-0M1CGK12QD"
  }, */
};

/*
 * For easier debugging in development mode, you can import the following file
 * to ignore zone related error stack frames such as `zone.run`, `zoneDelegate.invokeTask`.
 *
 * This import should be commented out in production mode because it will have a negative impact
 * on performance if an error is thrown.
 */
// import 'zone.js/dist/zone-error';  // Included with Angular CLI.

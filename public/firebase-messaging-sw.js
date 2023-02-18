/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
firebase.initializeApp({
  apiKey: "AIzaSyBI_-2VliQ7dzqLRwsSI4KUZy27s4Yeqec",
  authDomain: "push-notification-6c9bd.firebaseapp.com",
  projectId: "push-notification-6c9bd",
  storageBucket: "push-notification-6c9bd.appspot.com",
  messagingSenderId: "345671504039",
  appId: "1:345671504039:web:dbd6f116528e6ad3738b82",
  measurementId: "G-W429M9388V"
});

/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
  console.log(
    "[firebase-messaging-sw.js] Received background message ",
    payload,
  );
  var listener = new BroadcastChannel('listener');
  listener.postMessage('It works !!');
  /* Customize notification here */
  const notificationTitle = payload.data.title;
  const notificationOptions = {
    body: payload.data.body,
    icon: "/itwonders-web-logo.png",
  };

  return self.registration.showNotification(
    notificationTitle,
    notificationOptions,
  );
});

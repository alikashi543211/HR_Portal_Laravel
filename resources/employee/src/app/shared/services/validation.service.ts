import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class ValidationService {

  constructor() { }

  // FOR ERROR VALIDATION OF FIELDS (MIN & MAX LENGTHS)
  checkFieldLength(field, min = 0, max = 0): boolean {
    if (!field) { return false; }
    else {
      if (field.length < min || field.length > max) { return true; }
    }
  }

  // EMAIL VALIDATION
  validateEmail(email): boolean {
    if (!email) return false;
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return !re.test(String(email).toLowerCase());
  }
  
}

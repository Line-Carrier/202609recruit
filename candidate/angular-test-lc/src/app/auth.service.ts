import { Injectable } from '@angular/core';
import { Observable, delay, of, throwError } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class AuthService {
  /**
   * Local-only stand-in for an authentication API.
   * No real credentials or production endpoints belong in this exercise.
   */
  login(username: string, password: string): Observable<void> {
    if (username === 'candidate' && password === 'angular') {
      return of(void 0).pipe(delay(250));
    }

    return throwError(() => new Error('Invalid username or password')).pipe(delay(250));
  }
}

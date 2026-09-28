import { Injectable } from '@angular/core';
import { Observable, delay, of } from 'rxjs';

export type ServiceState = 'Operational' | 'Degraded' | 'Offline';

export interface ServiceStatus {
  name: string;
  state: ServiceState;
  detail: string;
}

const SERVICE_STATUSES: ServiceStatus[] = [
  { name: 'Network', state: 'Operational', detail: 'All regions are responding normally.' },
  { name: 'Authentication', state: 'Degraded', detail: 'Some sign-ins may take longer than usual.' },
  { name: 'Notifications', state: 'Offline', detail: 'Delivery is temporarily unavailable.' },
];

@Injectable({ providedIn: 'root' })
export class StatusService {
  getStatuses(): Observable<ServiceStatus[]> {
    return of(SERVICE_STATUSES).pipe(delay(300));
  }
}

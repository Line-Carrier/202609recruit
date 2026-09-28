import { CommonModule } from '@angular/common';
import { ChangeDetectorRef, Component, OnInit, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { ServiceState, ServiceStatus, StatusService } from './status.service';

@Component({
  selector: 'app-status-page',
  imports: [CommonModule, FormsModule, RouterLink],
  template: `
    <main class="status-page">
      <a routerLink="/">Back to sign in</a>
      <div class="heading">
        <div>
          <p class="eyebrow">DevDesk</p>
          <h1>Service status</h1>
        </div>
        <button type="button" (click)="refresh()">Refresh</button>
      </div>

      <form>
        <label for="state-filter">Show</label>
        <select id="state-filter" [(ngModel)]="selectedState" name="state">
          <option value="All">All services</option>
          @for (state of states; track state) {
            <option [value]="state">{{ state }}</option>
          }
        </select>
      </form>

      @if (loading) {
        <p role="status">Refreshing service status...</p>
      } @else if (errorMessage) {
		<p class="form-error" role="alert">{{ errorMessage }}</p>
	  } @else {
		@if (visibleStatuses.length === 0) {
			<p role="status">No service statuses to display.</p>
		} @else {
			<section aria-label="Service statuses">
			@for (service of visibleStatuses; track service.name) {
				<article class="status-row">
				<span 
					class="status-dot"
					role="img"
					[class]="'status-' + service.state.toLowerCase()"
					[attr.aria-label]="'Status: ' + service.state">
				</span>
				<div>
					<h2>{{ service.name }}</h2>
					<p>{{ service.detail }}</p>
				</div>
				<strong>{{ service.state }}</strong>
				</article>
			}
			</section>
		}
      }
    </main>
  `,
  styles: `
    :host { display: block; }
    .status-page { max-width: 720px; margin: 0 auto; padding: 40px 24px; }
    .heading { display: flex; align-items: center; justify-content: space-between; gap: 20px; margin: 32px 0 24px; }
    .eyebrow { margin: 0 0 8px; color: #2563eb; font-size: .75rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
    h1, h2 { margin: 0; color: #172033; }
    h1 { font-size: 2.25rem; }
    h2 { font-size: 1rem; }
    button { min-height: 40px; padding: 0 16px; border: 0; border-radius: 8px; background: #2563eb; color: #fff; cursor: pointer; font: inherit; font-weight: 700; }
    form { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; color: #475569; }
    select { min-height: 38px; padding: 0 10px; border: 1px solid #cbd5e1; border-radius: 8px; font: inherit; }
    section { display: grid; gap: 10px; }
    .status-row { display: flex; align-items: center; gap: 14px; padding: 18px; border: 1px solid #e2e8f0; border-radius: 12px; background: #fff; }
    .status-row div { flex: 1; }
    .status-row p { margin: 5px 0 0; color: #64748b; font-size: .9rem; }
    .status-row strong { font-size: .85rem; }
    .status-dot { width: 12px; height: 12px; border-radius: 50%; }
    .status-operational { background: #22c55e; }
    .status-degraded { background: #f59e0b; }
    .status-offline { background: #ef4444; }
  `,
})
export class StatusPageComponent implements OnInit {
  private readonly statusService = inject(StatusService);
  private readonly changeDetectorRef = inject(ChangeDetectorRef);

  readonly states: ServiceState[] = ['Operational', 'Degraded', 'Offline'];
  statuses: ServiceStatus[] = [];
  selectedState: ServiceState | 'All' = 'All';
  loading = true;
  errorMessage = '';

  ngOnInit(): void {
    this.refresh();
  }

  get visibleStatuses(): ServiceStatus[] {
	if (this.selectedState === 'All') {
		return this.statuses;
	}

	return this.statuses.filter(service => service.state === this.selectedState);
  }

  refresh(): void {
	this.errorMessage = '';
    this.loading = true;

    this.statusService.getStatuses().subscribe({
      next: (statuses) => {
        this.statuses = statuses;
        this.loading = false;
		this.changeDetectorRef.markForCheck();
      },
	  error: (error) => {
		this.errorMessage = error.message;
		this.loading = false;
		this.changeDetectorRef.markForCheck();
	  }
    });
  }
}

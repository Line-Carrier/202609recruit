import { ComponentFixture, TestBed } from '@angular/core/testing';
import { StatusPageComponent } from './status-page.component';
import { ServiceStatus, StatusService } from './status.service';
import { Subject } from 'rxjs';
import { provideRouter } from '@angular/router';

describe('StatusPageComponent', () => {
  let fixture: ComponentFixture<StatusPageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [StatusPageComponent],
      providers: [StatusService, provideRouter([])],
    }).compileComponents();

    fixture = TestBed.createComponent(StatusPageComponent);
  });

  it('filters the displayed services by state', () => {
	const component = fixture.componentInstance;
	const statusService = TestBed.inject(StatusService);

	const request = new Subject<ServiceStatus[]>();

	vi.spyOn(statusService, 'getStatuses').mockReturnValue(request);

	fixture.detectChanges();

	const statuses: ServiceStatus[] = [
		{ name: 'Network', state: 'Operational', detail: 'Working normally.' },
		{ name: 'Authentication', state: 'Degraded', detail: 'Running slowly.' },
		{ name: 'Notifications', state: 'Offline', detail: 'Unavailable.' },
	];

	request.next(statuses);

	expect(component.statuses.length).toBe(3);
	expect(component.loading).toBe(false);

	fixture.detectChanges();

	const elements = fixture.nativeElement.querySelectorAll('.status-row');

	expect(elements.length).toBe(3);

	component.selectedState = 'Degraded';

	expect(component.visibleStatuses.length).toBe(1);
	expect(component.visibleStatuses[0].name).toBe('Authentication');
	});

	it('stops loading and shows an error when refresh fails', () => {
		const component = fixture.componentInstance;
		const statusService = TestBed.inject(StatusService);
		const request = new Subject<ServiceStatus[]>();

		vi.spyOn(statusService, 'getStatuses').mockReturnValue(request);

		fixture.detectChanges();

		expect(component.loading).toBe(true);

		request.error(new Error('Unable to load service statuses'));

		expect(component.loading).toBe(false);
		expect(component.errorMessage).toBe('Unable to load service statuses');
	});

	it('uses a non-submit refresh button', () => {
		fixture.detectChanges();

		const refreshButton = fixture.nativeElement.querySelector('.heading button');

		expect(refreshButton.getAttribute('type')).toBe('button');
	});

	it('provides accessible text for status icons', () => {
		const statusService = TestBed.inject(StatusService);
		const request = new Subject<ServiceStatus[]>();

		vi.spyOn(statusService, 'getStatuses').mockReturnValue(request);

		fixture.detectChanges();

		const statuses: ServiceStatus[] = [
			{ name: 'Network', state: 'Operational', detail: 'Working normally.' },
		];

		request.next(statuses);

		fixture.detectChanges();

		const statusDot = fixture.nativeElement.querySelector('.status-dot');

		expect(statusDot.getAttribute('aria-label')).toBe(`Status: ${statuses[0].state}`);
	});
});
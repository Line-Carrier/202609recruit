import { ComponentFixture, TestBed } from '@angular/core/testing';
import { App } from './app';
import { AuthService } from './auth.service';
import { Subject } from 'rxjs';

describe('App', () => {
  let fixture: ComponentFixture<App>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [App],
      providers: [AuthService],
    }).compileComponents();

    fixture = TestBed.createComponent(App);
  });

  it('creates the login screen', () => {
    expect(fixture.componentInstance).toBeTruthy();
  });

  it('marks fields as touched when submitting an invalid form', () => {
	const component = fixture.componentInstance;
	const authService = TestBed.inject(AuthService);
	const loginSpy = vi.spyOn(authService, 'login');

	expect(component.loginForm.invalid).toBe(true);

	component.submit();

	expect(component.loginForm.controls.username.touched).toBe(true);
	expect(component.loginForm.controls.password.touched).toBe(true);
	expect(loginSpy).not.toHaveBeenCalled();
  });

  it('sets loading while authentication is pending', () => {
	const component = fixture.componentInstance;
	const authService = TestBed.inject(AuthService);

	// controlled Observable used to keep the login request pending
	const loginRequest = new Subject<void>();
	
	// replace the real login request with the controlled Observable
	const loginSpy = vi.spyOn(authService, 'login').mockReturnValue(loginRequest);

	component.loginForm.setValue({
		username: 'candidate',
		password: 'angular',
	});

	component.submit();

	// verify if the submit() took the values from component.loginForm.setValue({}) and passed them correctly to authService.login()
	expect(loginSpy).toHaveBeenCalledWith('candidate', 'angular');
	expect(component.loading).toBe(true);
  });

  it('shows the success state after a successful login', () => {
	const component = fixture.componentInstance;
	const authService = TestBed.inject(AuthService);
	const loginRequest = new Subject<void>();

	vi.spyOn(authService, 'login').mockReturnValue(loginRequest);

	component.loginForm.setValue({
		username: 'candidate',
		password: 'angular',
	});

	component.submit();
	
	expect(component.loading).toBe(true);
	expect(component.signedIn).toBe(false);
	
	loginRequest.next(); // simulate success

	expect(component.loading).toBe(false);
	expect(component.signedIn).toBe(true);
  });

  it('shows the service error and allows another login attempt', () => {
	const component = fixture.componentInstance;
	const authService = TestBed.inject(AuthService);
	const firstRequest = new Subject<void>();
	const secondRequest = new Subject<void>();

	const loginSpy = vi.spyOn(authService, 'login')
		.mockReturnValueOnce(firstRequest)
		.mockReturnValueOnce(secondRequest);

	component.loginForm.setValue({
		username: 'staff',
		password: 'typescript',
	});

	component.submit();
	
	expect(component.loading).toBe(true);
	expect(component.signedIn).toBe(false);
	
	firstRequest.error(new Error('Invalid username or password')); // simulate error

	expect(component.errorMessage).toBe('Invalid username or password');
	expect(component.loading).toBe(false);

	component.submit();

	expect(component.errorMessage).toBe('');
	expect(component.loading).toBe(true);
	expect(loginSpy).toHaveBeenCalledTimes(2);

	secondRequest.next();

	expect(component.errorMessage).toBe('');
	expect(component.loading).toBe(false);
	expect(component.signedIn).toBe(true);
  });

  it('renders the service error in the alert', () => {
	const component = fixture.componentInstance;

	component.errorMessage = 'Invalid username or password';

	fixture.detectChanges();

	const alert = fixture.nativeElement.querySelector('[role="alert"]');

	expect(alert).not.toBeNull();
	expect(alert.textContent).toContain('Invalid username or password');
  });
});

import { ChangeDetectorRef, Component, inject } from '@angular/core';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { AuthService } from './auth.service';

@Component({
  selector: 'app-root',
  imports: [ReactiveFormsModule],
  templateUrl: './app.html',
  styleUrl: './app.css',
})
export class App {
  private readonly formBuilder = inject(FormBuilder);
  private readonly authService = inject(AuthService);
  private readonly changeDetectorRef = inject(ChangeDetectorRef);

  readonly loginForm = this.formBuilder.nonNullable.group({
    username: ['', [Validators.required]],
    password: ['', [Validators.required]],
  });

  loading = false;
  errorMessage = '';
  signedIn = false;

  submit(): void {
	this.errorMessage = '';
	if (this.loginForm.invalid) {
		this.loginForm.markAllAsTouched();
		return;
	}

	const username = this.loginForm.controls.username.value;
	const password = this.loginForm.controls.password.value;

	this.loading = true;

	this.authService.login(username, password).subscribe({
		next: () => {
			this.signedIn = true;
			this.loading = false;
			this.changeDetectorRef.markForCheck(); // notify Angular of async state changes
		},
		error: (error) => {
			this.errorMessage = error.message;
			this.loading = false;
			this.changeDetectorRef.markForCheck();
		},
	});
  }
}

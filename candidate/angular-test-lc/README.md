# LineCarrier Angular Candidate Exercise

## Context

This is a standalone, one-hour Angular exercise inspired by a teleoperator
DevDesk workflow. It intentionally uses a local mock authentication service.
It does **not** connect to `devdesk.linecarrier.fi`, contain internal
application code, or require production credentials.

**Suggested timebox: 60 minutes.**

## Candidate notes

Completed the required login task and Optional B.

### Optional B root causes

- The status list was displayed without applying the selected filter.
- Refresh errors were not handled, so loading could remain active after a failed request.
- The refresh button had no explicit button type.
- Status was represented by color without an accessible text alternative.
- Empty and error states were not displayed.

Added focused tests for filtering, refresh errors, button behavior, and accessible status text.

### Routing note

The starter project defines the `/status` route and configures the Angular router, but the root application template does not contain a router outlet. I kept the solution scoped to the requested status-page fixes rather than restructuring the application's routing. The status component is tested directly in its focused unit tests.

## Candidate task

Complete the TODO in `src/app/app.ts` and add focused unit tests.

The login screen should:

1. Prevent submission when username or password is empty and show the existing
   validation messages.
2. Set the loading state while the authentication request is pending.
3. Show the existing success state after a successful login.
4. Show the service error in the existing alert and allow another attempt.
5. Avoid logging or persisting the password.
6. Keep the form keyboard accessible and preserve the existing labels and ARIA
   attributes.

The local mock service accepts `candidate` / `angular`. Do not replace it with
real credentials or a production endpoint.

## Optional task A — recreate a page from a screenshot

Use `public/ui-reference.svg` as the only visual reference and create a new
route called `/overview`. Recreate the page with Angular and CSS:

- Match the overall layout, spacing, colors, cards, and activity rows.
- Use semantic HTML and responsive behavior for narrow screens.
- Store the displayed statistics and activities in component data, not repeated
  hard-coded markup.
- Add one focused unit test that verifies the page renders the three summary
  values.

This task is intended to assess visual interpretation, component structure,
responsive CSS, and attention to detail. Pixel-perfect reproduction is not
required.

## Optional task B — fix an existing page

Open `/status` to inspect the intentionally faulty service-status page. It
contains three rows: Network, Authentication, and Notifications. Fix these
issues and add focused tests:

- The status filter does not update the displayed rows.
- The refresh button is inside a form without an explicit button type and can
  submit the form unexpectedly.
- A failed refresh leaves the loading indicator visible because the Observable
  has no error handler.
- The status icon is conveyed only by color and has no accessible text.
- The page has no visible empty or error state if status data cannot be shown.

Keep the fix scoped, explain the root cause briefly, and add tests for the
filter, refresh error, button behavior, and accessible status text. Do not
change the mock login contract.

## Evaluation guide

| Area | Weight |
| --- | ---: |
| Reactive form validation and submission flow | 35% |
| Observable success/error/loading handling | 25% |
| Angular and TypeScript structure | 20% |
| Focused unit tests | 15% |
| Accessibility and secure handling of credentials | 5% |

For a one-hour interview, use the login task as the required task and choose
either Optional task A or Optional task B as a 20–30 minute follow-up.

## Run

```powershell
npm install
npm start
```

Run tests with:

```powershell
npm test
```

## Optional authorized integration testing

An integration test against a live DevDesk environment should be added only
after written authorization and confirmation of a non-production test account.
If that is approved, credentials must be injected at runtime (for example,
through CI secret variables), never committed, printed, or placed in test
fixtures. The test should target a dedicated test environment and use an
explicit opt-in flag so it cannot run accidentally.

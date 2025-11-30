<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ALERT+ Login</title>
   <link rel="stylesheet" href="{{ asset('ccs/login.css') }}">
   <script src="https://accounts.google.com/gsi/client" async defer></script>
  </head>
  <body>
    <div class="auth-container">
      <div class="auth-card">
        <div class="brand">
          ALERT<span style="margin-left: 4px">+</span>
          <sup>Parent Dashboard</sup>
        </div>
        <p class="subtitle">Sign in to access your family's safety dashboard</p>

        <div class="error-message" id="errorMessage" style="display: {{ $errors->any() ? 'block' : 'none' }};">
          @if ($errors->any())
            {{ $errors->first() }}
          @endif
        </div>
        <div class="success-message" id="successMessage"></div>

        <form id="loginForm" method="POST" action="{{ route('login.post') }}">
          @csrf
          <div class="form-group">
            <label for="email">Email Address</label>
            <input
              type="email"
              id="email"
              name="email"
              placeholder="Enter your email"
              required
            />
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <div class="password-toggle">
              <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
                required
              />
              <button type="button" id="togglePassword">👁️</button>
            </div>
          </div>

          <div class="form-group" style="text-align: right">
            <a href="#" style="font-size: 14px; color: var(--blue)">Forgot password?</a>
          </div>

          <button type="submit" class="btn">Sign In</button>
        </form>

        <div class="divider">
          <span>or continue with</span>
        </div>

        @if(config('services.google.client_id'))
        <div id="g_id_onload"
             data-client_id="{{ config('services.google.client_id') }}"
             data-context="signin"
             data-ux_mode="popup"
             data-callback="handleGoogleSignIn"
             data-auto_prompt="true">
        </div>
        <div class="g_id_signin"
             data-type="standard"
             data-shape="rectangular"
             data-theme="outline"
             data-text="signin_with"
             data-size="large"
             data-logo_alignment="left">
        </div>
        @else
        <div style="padding: 12px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; color: #7f1d1d; margin-bottom: 12px; font-size: 14px;">
          Google Sign-In is not configured. Please set GOOGLE_CLIENT_ID in your .env file.
        </div>
        @endif

        <div class="footer">
          Don't have an account? <a href="{{ route('register') }}">Sign up</a>
        </div>
      </div>
    </div>

    <script>
      // Toggle password visibility
      document
        .getElementById("togglePassword")
        .addEventListener("click", function () {
          const passwordInput = document.getElementById("password");
          const button = this;

          if (passwordInput.type === "password") {
            passwordInput.type = "text";
            button.textContent = "🙈";
          } else {
            passwordInput.type = "password";
            button.textContent = "👁️";
          }
        });

      // Form posts to server; client-side helper remains for optional inline validation

      function showMessage(message, type) {
        const errorEl = document.getElementById("errorMessage");
        const successEl = document.getElementById("successMessage");

        if (type === "error") {
          errorEl.textContent = message;
          errorEl.style.display = "block";
          successEl.style.display = "none";
        } else {
          successEl.textContent = message;
          successEl.style.display = "block";
          errorEl.style.display = "none";
        }
      }

      function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
      }

      function handleGoogleSignIn(response) {
        if (response.credential) {
          // Send the credential to the server
          fetch('{{ route("google.token") }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
              credential: response.credential
            })
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              window.location.href = data.redirect;
            } else {
              showMessage(data.error || 'Google sign-in failed', 'error');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showMessage('An error occurred during sign-in', 'error');
          });
        }
      }

      function socialLogin(provider) {
        if (provider === 'google') {
          // Trigger Google Sign-In prompt manually
          if (window.google && window.google.accounts && window.google.accounts.id) {
            window.google.accounts.id.prompt();
          } else {
            showMessage('Google Sign-In is not available. Please refresh the page.', 'error');
          }
        } else {
          showMessage(`Connecting to ${provider}...`, "success");
          setTimeout(() => {
            showMessage(`${provider} login not implemented.`, "error");
          }, 1000);
        }
      }
    </script>
  </body>
 </html>



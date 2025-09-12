<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ALERT+ Login</title>
   <link rel="stylesheet" href="{{ asset('ccs/login.css') }}">
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
            <a href="#" style="font-size: 14px; color: var(--teal)">Forgot password?</a>
          </div>

          <button type="submit" class="btn">Sign In</button>
        </form>

        <div class="divider">
          <span>or continue with</span>
        </div>

        <button class="social-btn" onclick="socialLogin('google')">
          <span>🔍</span>
          Continue with Google
        </button>

        <button
          class="social-btn"
          onclick="socialLogin('apple')"
          style="margin-top: 12px"
        >
          <span>🍎</span>
          Continue with Apple
        </button>

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

      function socialLogin(provider) {
        showMessage(`Connecting to ${provider}...`, "success");
        // In a real app, this would handle OAuth flow
        setTimeout(() => {
          showMessage(`${provider} login not implemented in demo.`, "error");
        }, 1000);
      }
    </script>
  </body>
 </html>



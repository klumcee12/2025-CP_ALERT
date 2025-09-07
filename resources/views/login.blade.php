<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ALERT+ Login</title>
    <style>
      :root {
        --teal: #19c3b4; /* brand */
        --teal-700: #0ea5a4;
        --teal-900: #0b7f78;
        --ink: #0f172a; /* slate-900 */
        --muted: #64748b; /* slate-500 */
        --bg: #f6faf9;
        --white: #ffffff;
        --danger: #ef4444;
        --success: #22c55e;
        --warn: #f59e0b;
        --radius: 16px;
        --shadow: 0 10px 30px rgba(2, 8, 23, 0.08);
      }
      * {
        box-sizing: border-box;
      }
      html,
      body {
        height: 100%;
      }
      body {
        margin: 0;
        font: 16px/1.4 ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto,
          Inter, Arial;
        background: var(--bg);
        color: var(--ink);
      }
      a {
        color: inherit;
        text-decoration: none;
      }

      /* Layout */
      .auth-container {
        min-height: 100vh;
        display: grid;
        place-items: center;
        padding: 20px;
        background: linear-gradient(135deg, #f2fbfa 0%, #e4f7f4 100%);
      }

      .auth-card {
        background: var(--white);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 40px;
        width: 100%;
        max-width: 420px;
        text-align: center;
      }

      .brand {
        font-weight: 800;
        font-size: 32px;
        letter-spacing: 0.5px;
        color: var(--teal);
        margin-bottom: 8px;
      }
      .brand sup {
        font-weight: 700;
        font-size: 18px;
        opacity: 0.9;
        margin-left: 4px;
      }

      .subtitle {
        color: var(--muted);
        margin-bottom: 32px;
        font-size: 16px;
      }

      .form-group {
        margin-bottom: 20px;
        text-align: left;
      }

      label {
        font-weight: 700;
        display: block;
        margin-bottom: 8px;
        color: var(--ink);
      }

      input {
        width: 100%;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        font: inherit;
        font-size: 16px;
        transition: border-color 0.2s ease;
      }

      input:focus {
        outline: none;
        border-color: var(--teal);
        box-shadow: 0 0 0 3px rgba(25, 195, 180, 0.1);
      }

      .btn {
        appearance: none;
        border: 0;
        border-radius: 999px;
        padding: 16px 24px;
        background: var(--teal);
        color: var(--white);
        font-weight: 800;
        font-size: 16px;
        cursor: pointer;
        box-shadow: var(--shadow);
        width: 100%;
        transition: all 0.2s ease;
      }

      .btn:hover {
        background: var(--teal-700);
        transform: translateY(-1px);
        box-shadow: 0 15px 35px rgba(2, 8, 23, 0.12);
      }

      .btn:active {
        transform: translateY(0);
      }

      .btn.secondary {
        background: var(--white);
        color: var(--ink);
        border: 2px solid #e2e8f0;
        margin-top: 12px;
      }

      .btn.secondary:hover {
        background: #f8fafc;
        border-color: var(--teal);
      }

      .divider {
        margin: 24px 0;
        display: flex;
        align-items: center;
        color: var(--muted);
        font-size: 14px;
      }

      .divider::before,
      .divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #e2e8f0;
      }

      .divider span {
        padding: 0 16px;
      }

      .social-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        width: 100%;
        padding: 14px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: var(--white);
        color: var(--ink);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
      }

      .social-btn:hover {
        border-color: var(--teal);
        background: #f8fafc;
      }

      .footer {
        margin-top: 24px;
        color: var(--muted);
        font-size: 14px;
      }

      .footer a {
        color: var(--teal);
        font-weight: 600;
      }

      .footer a:hover {
        text-decoration: underline;
      }

      .error-message {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #7f1d1d;
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 20px;
        font-weight: 600;
        display: none;
      }

      .success-message {
        background: #ecfdf5;
        border: 1px solid #bbf7d0;
        color: #065f46;
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 20px;
        font-weight: 600;
        display: none;
      }

      .password-toggle {
        position: relative;
      }

      .password-toggle input {
        padding-right: 50px;
      }

      .password-toggle button {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--muted);
        cursor: pointer;
        font-size: 18px;
        padding: 4px;
      }

      .password-toggle button:hover {
        color: var(--ink);
      }

      @media (max-width: 480px) {
        .auth-card {
          padding: 24px;
        }

        .brand {
          font-size: 28px;
        }

        .brand sup {
          font-size: 16px;
        }
      }
    </style>
  </head>
  <body>
    <div class="auth-container">
      <div class="auth-card">
        <div class="brand">
          ALERT<span style="margin-left: 4px">+</span>
          <sup>Parent Dashboard</sup>
        </div>
        <p class="subtitle">Sign in to access your family's safety dashboard</p>

        <div class="error-message" id="errorMessage"></div>
        <div class="success-message" id="successMessage"></div>

        <form id="loginForm">
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

      // Handle form submission
      document
        .getElementById("loginForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();

          const email = document.getElementById("email").value;
          const password = document.getElementById("password").value;

          // Hide any existing messages
          document.getElementById("errorMessage").style.display = "none";
          document.getElementById("successMessage").style.display = "none";

          // Simple validation
          if (!email || !password) {
            showMessage("Please fill in all fields.", "error");
            return;
          }

          if (!isValidEmail(email)) {
            showMessage("Please enter a valid email address.", "error");
            return;
          }

          // Simulate login process
          showMessage("Signing you in...", "success");

          // Simulate API call delay
          setTimeout(() => {
            // For demo purposes, accept any login
            if (email && password) {
              showMessage("Login successful! Redirecting...", "success");
              setTimeout(() => {
                window.location.href = "{{ route('index') }}";
              }, 1000);
            } else {
              showMessage("Invalid credentials. Please try again.", "error");
            }
          }, 1500);
        });

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



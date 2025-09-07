<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ALERT+ Register</title>
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
        max-width: 480px;
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

      .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
      }

      label {
        font-weight: 700;
        display: block;
        margin-bottom: 8px;
        color: var(--ink);
      }

      input,
      select {
        width: 100%;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        font: inherit;
        font-size: 16px;
        transition: border-color 0.2s ease;
      }

      input:focus,
      select:focus {
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

      .checkbox-group {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 20px;
        text-align: left;
      }

      .checkbox-group input[type="checkbox"] {
        width: auto;
        margin: 0;
        margin-top: 2px;
      }

      .checkbox-group label {
        margin: 0;
        font-size: 14px;
        line-height: 1.4;
        color: var(--muted);
      }

      .checkbox-group label a {
        color: var(--teal);
        font-weight: 600;
      }

      .checkbox-group label a:hover {
        text-decoration: underline;
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

        .form-row {
          grid-template-columns: 1fr;
          gap: 0;
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
        <p class="subtitle">
          Create your account to start protecting your family
        </p>

        <div class="error-message" id="errorMessage"></div>
        <div class="success-message" id="successMessage"></div>

        <form id="registerForm">
          <div class="form-row">
            <div class="form-group">
              <label for="firstName">First Name</label>
              <input
                type="text"
                id="firstName"
                name="firstName"
                placeholder="First name"
                required
              />
            </div>
            <div class="form-group">
              <label for="lastName">Last Name</label>
              <input
                type="text"
                id="lastName"
                name="lastName"
                placeholder="Last name"
                required
              />
            </div>
          </div>

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
            <label for="phone">Phone Number</label>
            <input
              type="tel"
              id="phone"
              name="phone"
              placeholder="+1 (555) 123-4567"
              required
            />
          </div>

          <div class="form-group">
            <label for="familySize">Family Size</label>
            <select id="familySize" name="familySize" required>
              <option value="">Select family size</option>
              <option value="1">1 child</option>
              <option value="2">2 children</option>
              <option value="3">3 children</option>
              <option value="4">4 children</option>
              <option value="5+">5+ children</option>
            </select>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <div class="password-toggle">
              <input
                type="password"
                id="password"
                name="password"
                placeholder="Create a strong password"
                required
              />
              <button type="button" id="togglePassword">👁️</button>
            </div>
          </div>

          <div class="form-group">
            <label for="confirmPassword">Confirm Password</label>
            <div class="password-toggle">
              <input
                type="password"
                id="confirmPassword"
                name="confirmPassword"
                placeholder="Confirm your password"
                required
              />
              <button type="button" id="toggleConfirmPassword">👁️</button>
            </div>
          </div>

          <div class="checkbox-group">
            <input type="checkbox" id="terms" name="terms" required />
            <label for="terms">
              I agree to the <a href="#">Terms of Service</a> and
              <a href="#">Privacy Policy</a>
            </label>
          </div>

          <div class="checkbox-group">
            <input type="checkbox" id="marketing" name="marketing" />
            <label for="marketing">
              I'd like to receive updates about new features and safety tips
              (optional)
            </label>
          </div>

          <button type="submit" class="btn">Create Account</button>
        </form>

        <div class="divider">
          <span>or sign up with</span>
        </div>

        <button class="social-btn" onclick="socialRegister('google')">
          <span>🔍</span>
          Continue with Google
        </button>

        <button
          class="social-btn"
          onclick="socialRegister('apple')"
          style="margin-top: 12px"
        >
          <span>🍎</span>
          Continue with Apple
        </button>

        <div class="footer">
          Already have an account? <a href="{{ route('login') }}">Sign in</a>
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

      document
        .getElementById("toggleConfirmPassword")
        .addEventListener("click", function () {
          const passwordInput = document.getElementById("confirmPassword");
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
        .getElementById("registerForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();

          const firstName = document.getElementById("firstName").value;
          const lastName = document.getElementById("lastName").value;
          const email = document.getElementById("email").value;
          const phone = document.getElementById("phone").value;
          const familySize = document.getElementById("familySize").value;
          const password = document.getElementById("password").value;
          const confirmPassword =
            document.getElementById("confirmPassword").value;
          const terms = document.getElementById("terms").checked;

          // Hide any existing messages
          document.getElementById("errorMessage").style.display = "none";
          document.getElementById("successMessage").style.display = "none";

          // Validation
          if (
            !firstName ||
            !lastName ||
            !email ||
            !phone ||
            !familySize ||
            !password ||
            !confirmPassword
          ) {
            showMessage("Please fill in all required fields.", "error");
            return;
          }

          if (!isValidEmail(email)) {
            showMessage("Please enter a valid email address.", "error");
            return;
          }

          if (password.length < 8) {
            showMessage(
              "Password must be at least 8 characters long.",
              "error"
            );
            return;
          }

          if (password !== confirmPassword) {
            showMessage("Passwords do not match.", "error");
            return;
          }

          if (!terms) {
            showMessage(
              "You must agree to the Terms of Service to continue.",
              "error"
            );
            return;
          }

          // Simulate registration process
          showMessage("Creating your account...", "success");

          // Simulate API call delay
          setTimeout(() => {
            showMessage(
              "Account created successfully! Redirecting to login...",
              "success"
            );
            setTimeout(() => {
              window.location.href = "{{ route('login') }}";
            }, 1500);
          }, 2000);
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

      function socialRegister(provider) {
        showMessage(`Connecting to ${provider}...`, "success");
        // In a real app, this would handle OAuth flow
        setTimeout(() => {
          showMessage(
            `${provider} registration not implemented in demo.`,
            "error"
          );
        }, 1000);
      }
    </script>
  </body>
 </html>



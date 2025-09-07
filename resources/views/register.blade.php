<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ALERT+ Register</title>
    <link rel="stylesheet" href="ccs/register.css">
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



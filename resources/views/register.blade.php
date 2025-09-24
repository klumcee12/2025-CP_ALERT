<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ALERT+ Register</title>
    <link rel="stylesheet" href="{{ asset('ccs/register.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/emailjs-com@3/dist/email.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        if (window.emailjs && emailjs.init) {
          emailjs.init('SwKUulQ_A_AkT71KD');
        }
      });
    </script>
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

        <div class="error-message" id="errorMessage" style="display: {{ $errors->any() ? 'block' : 'none' }};">
          @if ($errors->any())
            {{ $errors->first() }}
          @endif
        </div>
        <div class="success-message" id="successMessage"></div>

        <form id="registerForm" method="POST" action="{{ route('register.post') }}">
          @csrf
          <div class="form-row">
            <div class="form-group">
              <label for="firstName">First Name</label>
              <input
                type="text"
                id="firstName"
                name="first_name"
                placeholder="First name"
                required
              />
            </div>
            <div class="form-group">
              <label for="middleName">Middle Name (optional)</label>
              <input
                type="text"
                id="middleName"
                name="middle_name"
                placeholder="Middle name"
              />
            </div>
            <div class="form-group full">
              <label for="lastName">Last Name</label>
              <input
                type="text"
                id="lastName"
                name="last_name"
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
                name="password_confirmation"
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

      // Intercept submit: register via AJAX, send EmailJS, then redirect to login
      document.getElementById('registerForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.currentTarget;
        const formData = new FormData(form);
        const csrf = formData.get('_token');

        const email = formData.get('email');
        if (!isValidEmail(email)) {
          showMessage('Please enter a valid email address.', 'error');
          return;
        }

        try {
          const res = await fetch(form.action, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrf,
              'Accept': 'application/json'
            },
            body: formData
          });

          if (!res.ok) {
            const data = await res.json().catch(() => ({}));
            const message = data.message || (data.errors ? Object.values(data.errors)[0][0] : 'Registration failed.');
            showMessage(message, 'error');
            return;
          }

          const data = await res.json();

          if (window.emailjs && emailjs.send) {
            const verifyLink = data.link;
            const timestamp = new Date().toLocaleString();
            await emailjs.send('service_ehcfyd6', 'template_9dseopn', {
              to_email: data.email,
              to_name: data.name || formData.get('first_name'),
              verify_link: verifyLink,
              time: timestamp,
            });
          }

          window.location.href = '{{ route('login') }}';
        } catch (err) {
          showMessage('An unexpected error occurred. Please try again.', 'error');
        }
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



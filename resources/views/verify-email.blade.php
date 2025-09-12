<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Verify your email</title>
    <link rel="stylesheet" href="{{ asset('ccs/login.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
  </head>
  <body>
    <div class="auth-container">
      <div class="auth-card">
        <div class="brand">ALERT<span style="margin-left: 4px">+</span></div>
        <p class="subtitle">Please verify your email address.</p>
        @if (session('status') === 'verification-link-sent')
          <div class="success-message" style="display:block;">A new verification link has been sent to your email.</div>
        @endif
        <p>Before continuing, please check your email for a verification link. If you didn't receive the email, you can request another.</p>
        <div class="toolbar" style="margin-top:12px; display:flex; gap:8px;">
          <button id="btnEmailJs" class="btn" type="button">Send Verification Email</button>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn secondary" type="submit">Logout</button>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
    <script>
      const SERVICE_ID = "service_ehcfyd6"; // TODO: replace
      const TEMPLATE_ID = "template_9dseopn"; // TODO: replace
      const PUBLIC_KEY = "SwKUulQ_A_AkT71KD"; // TODO: replace

      document.getElementById('btnEmailJs').addEventListener('click', async () => {
        try {
          // Get signed link from backend
          const res = await fetch("{{ route('verification.link.json') }}", { headers: { Accept: 'application/json' } });
          const data = await res.json();
          const params = {
            to_email: data.email,
            to_name: data.name || data.email,
            verify_link: data.link,
            time: new Date(data.expires_at).toLocaleTimeString(),
          };

          emailjs.init({ publicKey: PUBLIC_KEY });
          await emailjs.send(SERVICE_ID, TEMPLATE_ID, params);
          alert('Verification email sent via EmailJS. Please check your inbox.');
        } catch (e) {
          console.error(e);
          alert('Failed to send via EmailJS. Check your keys/config.');
        }
      });
    </script>
  </body>
 </html>



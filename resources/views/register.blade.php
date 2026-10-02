<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register | Ghar Ka Baker</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      rel="stylesheet"
    />
    <link href="assets/css/style.css" rel="stylesheet" />
  </head>
  <body>
    <main class="auth-page">
      <div class="auth-wrap">
        <section class="auth-welcome">
          <div>
            <a href="/login" class="brand px-0 pb-4"
              ><span class="brand-icon"><i class="bi bi-cake2"></i></span>Ghar
              Ka Baker</a
            >
            <h1>Start managing orders with confidence.</h1>
            <p class="opacity-75 mt-3">
              Keep your customers, cakes, payments and delivery schedule in one
              cheerful place.
            </p>
          </div>
          <div class="auth-features">
            <div class="auth-feature">
              <i class="bi bi-phone me-2"></i>Built for your phone and your
              business
            </div>
            <div class="auth-feature">
              <i class="bi bi-currency-exchange me-2"></i>All amounts shown in
              Pakistani Rupees
            </div>
          </div>
          <small class="opacity-75"
            >No complicated tools. Just your business, organised.</small
          >
        </section>
        <section class="auth-panel">
          <div class="d-flex justify-content-end mb-3">
            <button
              class="theme-button"
              type="button"
              aria-label="Change theme"
            ></button>
          </div>
          <h2>Create your account</h2>
          <p class="text-muted-custom mb-4">
            Set up your bakery in less than a minute.
          </p>
          <form class="demo-form" action="/register" method="POST">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Your name</label
                ><input
                  class="form-control"
                  name="owner_name"
                  placeholder="Ayesha Khan"
                  required
                />
              </div>
              <div class="col-md-6">
                <label class="form-label">Business name</label
                ><input
                  class="form-control"
                  name="business_name"
                  placeholder="Ayesha's Bakes"
                  required
                />
              </div>
              <div class="col-12">
                <label class="form-label">WhatsApp number</label
                ><input
                  class="form-control"
                   name="whatsapp_number"
                  placeholder="0300 1234567"
                  required
                />
              </div>
              <div class="col-12">
                <label class="form-label">Email address</label
                ><input
                  type="email"
                  name="email"
                  class="form-control"
                  placeholder="you@example.com"
                  required
                />
              </div>
              <div class="col-12">
                <label class="form-label">Create password</label
                ><input
                  type="password"
                    name="password"
                  class="form-control"
                  placeholder="At least 6 characters"
                  required
                />
              </div>
            </div>
           <button type="submit" class="btn btn-gradient w-100 py-2 mt-4">
            Create my account <i class="bi bi-arrow-right ms-1"></i>
           </button>
          </form>
          <p class="text-center text-muted-custom mt-4 mb-0">
            Already have an account?
            <a href="/login" class="small-link">Login</a>
          </p>
        </section>
      </div>
    </main>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
      <div id="appToast" class="toast border-0">
        <div class="toast-body bg-dark text-white rounded">
          <span id="toastText"></span>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
  </body>
</html>

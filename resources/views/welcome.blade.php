<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ghar Ka Baker</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      rel="stylesheet"
    />
    <link href="/assets/css/style.css" rel="stylesheet" />
    <style>
      .landing-hero {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: linear-gradient(165deg, #5136d8, #784eff 50%, #ef5da8 135%);
      }
      .landing-card {
        background: var(--surface);
        border-radius: 24px;
        box-shadow: var(--shadow);
        padding: 48px 40px;
        max-width: 460px;
        width: 100%;
        text-align: center;
      }
      .landing-brand-icon {
        width: 64px;
        height: 64px;
        display: grid;
        place-items: center;
        margin: 0 auto 20px;
        border-radius: 18px;
        background: linear-gradient(165deg, #5136d8, #784eff 50%, #ef5da8 135%);
        color: #fff;
        font-size: 1.75rem;
      }
    </style>
  </head>
  <body>
    <div class="landing-hero">
      <div class="landing-card">
        <div class="landing-brand-icon">
          <i class="bi bi-cake2"></i>
        </div>
        <h1 class="h3 fw-bold mb-2">Ghar Ka Baker</h1>
        <p class="text-muted-custom mb-4">
          Manage your orders, customers and payments — all in one simple place for your home bakery.
        </p>

       @if(session('bakery_id'))
  <a href="{{ url('/dashboard') }}" class="btn btn-gradient w-100 mb-2">
    <i class="bi bi-grid-1x2"></i> Go to Dashboard
  </a>
@else
  <a href="{{ route('login') }}" class="btn btn-gradient w-100 mb-2">
    <i class="bi bi-box-arrow-in-right"></i> Log in
  </a>
  <a href="{{ route('register') }}" class="btn btn-soft w-100">
    <i class="bi bi-person-plus"></i> Create an account
  </a>
@endif

        <p class="small text-muted-custom mt-4 mb-0">
          Made for home bakers across Pakistan 🇵🇰
        </p>
      </div>
    </div>
  </body>
</html>
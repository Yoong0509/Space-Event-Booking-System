<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Spacing Booking - About</title>
  <!-- FONTAWESOME ICONS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Segoe+UI:wght@400;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/about.css'])
</head>
<body>

  @include('layouts.navigation')
<br>
  <!-- Main Banner -->
  <main class="main-banner" style="background-image: url('{{ asset('images/events/about.jpg') }}')">
    <p>About Us<br>Spacing Booking</p>
  </main>

  <!-- About Booking System -->
  <section class="section">
    
    <div class="about-container">
      <div class="about-image">
        <img src="/images/events/about1.jpg" alt="Event Booking">
      </div>
      <div class="text">
        <p>
          Spacing Booking is your trusted event reservation platform. 
          Whether it’s concerts, conferences, or exhibitions, we make booking 
          seamless, secure, and enjoyable.
        </p>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="about-container reverse">
      <div class="about-image">
        <img src="/images/events/about2.jpg" alt="Event Management">
      </div>
      <div class="text">
        <h3>Global Reach, Local Experience</h3>
        <p>
          Originating in Malaysia, Spacing Booking now connects audiences 
          across Asia. Our platform supports events in Japan, Singapore, and Malaysia, 
          ensuring you can discover and book experiences wherever you are.
        </p>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="about-container">
      <div class="about-image">
        <img src="/images/events/about3.jpg" alt="Booking Features">
      </div>
      <div class="text">
        <h3>Smart Booking Features</h3>
        <p>
          From real-time seat selection to secure payment gateways, 
          our system blends technology with convenience. 
          Enjoy a smooth booking journey with instant confirmations 
          and personalized recommendations.
        </p>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="about-container reverse">
      <div class="about-image">
        <img src="/images/events/about4.jpg" alt="Event Insights">
      </div>
      <div class="text">
        <h3>Insights & Transparency</h3>
        <p>
          Every event listing includes detailed information—venue, schedule, 
          ticket categories, and organizer details. We believe in transparency, 
          so you can book with confidence.
        </p>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="about-container">
      <div class="about-image">
        <img src="/images/events/about5.jpg" alt="Beyond Booking">
      </div>
      <div class="text">
        <h3>Beyond Booking</h3>
        <p>
          Spacing Booking is more than a ticketing system—it’s a gateway 
          to experiences. Discover trending events, explore curated collections, 
          and make memories that last.
        </p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  @include('components.footer')

</body>
</html>
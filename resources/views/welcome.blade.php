<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PROJECT CONNECT</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="icon" type="image/x-icon" href="{{ asset('images/city_of_general_trias_seal.png') }}">
  <style>
    :root {
      --blue:       #1a6ec7;
      --blue-dark:  #154f96;
      --sky:        #d9f2fc;
      --sky-mid:    #a8dff5;
      --green:      #3a7d44;
      --green-dark: #2c5f35;
      --soft-blue:  #d4f4ff;
      --off-white:  #f4f8fb;
      --text:       #1a2433;
      --text-light: #4b5e72;
      --border:     rgba(26,110,199,0.12);
      --gold:       #e8b84b;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'DM Sans', sans-serif;
      color: var(--text);
      background: var(--soft-blue);
      overflow-x: hidden;
      line-height: 1.6;
    }

    .floatingPopup {
      width: 400px;
      position: fixed;
      top: 50%;
      left: 50%;
      font-family: 'DM Sans', sans-serif;
      transform: translate(-50%, -50%);
      background: white;
      backdrop-filter: blur(100px);
      padding: 25px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.3);
      border-radius: 10px;
      z-index: 9999;
    }

    header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      background: rgba(255,255,255,0.96);
      backdrop-filter: blur(14px);
      border-bottom: 1px solid var(--border);
      box-shadow: 0 2px 20px rgba(26,110,199,0.08);
    }

    .header-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 28px;
      height: 72px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .logo-group {
      display: flex;
      align-items: center;
      gap: 14px;
      text-decoration: none;
      flex-shrink: 0;
    }

    .logo-group img {
      width: 48px;
      height: 48px;
      object-fit: contain;
      mix-blend-mode: multiply;
      filter: drop-shadow(0 1px 3px rgba(0,0,0,0.15));
    }

    .logo-text { line-height: 1.2; }
    .logo-text strong { display: block; font-family: 'DM Sans', sans-serif; font-size: 1rem; color: var(--blue-dark); letter-spacing: 0.01em; font-weight: 700; }
    .logo-text span { display: block; font-size: 0.72rem; color: var(--green); font-weight: 500; letter-spacing: 0.04em; text-transform: uppercase; }

    nav, .nav-menu { display: flex; align-items: center; gap: 6px; list-style: none; }
    nav a, .nav-item a { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; font-size: 0.875rem; font-weight: 500; color: var(--text-light); text-decoration: none; border-radius: 8px; transition: all .2s; }
    nav a:hover, .nav-item a:hover, nav a.active, .nav-item a.active { color: var(--blue); background: var(--sky); }

    .btn-primary, .btn-outline, .btn-white { text-decoration: none; }
    .btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; background: var(--blue); color: var(--soft-blue); border: none; border-radius: 10px; font-family: 'DM Sans', sans-serif; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all .25s; box-shadow: 0 3px 14px rgba(26,110,199,0.3); }
    .btn-primary:hover { background: var(--blue-dark); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(26,110,199,0.38); }

    .hero { margin-top: 72px; background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue) 45%, #1a8fc7 100%); color: white; padding: 100px 32px; position: relative; overflow: hidden; }
    .hero::before { content: ''; position: absolute; top: -50%; right: -20%; width: 800px; height: 800px; background: radial-gradient(circle, rgba(255, 255, 255, 0.1), transparent); border-radius: 50%; }
    .hero-inner { max-width: 1200px; margin: 0 auto; text-align: center; position: relative; z-index: 2; }
    .hero-title { font-family: 'DM Sans', sans-serif; font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 700; line-height: 1.2; margin-bottom: 16px; }
    .hero-subtitle { font-size: clamp(1.1rem, 2vw, 1.4rem); font-weight: 600; color: #fbbf24; margin-bottom: 20px; }
    .hero-description { font-size: 1.1rem; line-height: 1.8; max-width: 800px; margin: 0 auto 40px; opacity: 0.95; }
    .hero-ctas { display: flex; justify-content: center; gap: 16px; flex-wrap: wrap; margin-bottom: 60px; }
    .btn-white { padding: 14px 28px; background: white; color: var(--blue-dark); border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; border: 2px solid transparent; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-white:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); }
    .btn-outline { padding: 14px 28px; background: transparent; color: white; border: 2px solid rgba(255, 255, 255, 0.5); border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; }
    .btn-outline:hover { background: rgba(255, 255, 255, 0.1); border-color: white; }

    .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 32px; max-width: 900px; margin: 0 auto; }
    .stat-card { text-align: center; padding: 24px; background: rgba(255, 255, 255, 0.1); border-radius: 12px; backdrop-filter: blur(10px); }
    .stat-value { font-size: 2.5rem; font-weight: 700; margin-bottom: 8px; }
    .stat-label { font-size: 0.95rem; opacity: 0.9; }

    .about { margin-top: 0px; padding: 10px 32px; background: var(--soft-blue); }
    .about-inner { max-width: 1200px; margin: 0 auto; }
    .about-card { padding: 40px 32px; background: var(--off-white); border-radius: 16px; text-align: center; border: 2px solid transparent; transition: all 0.3s; }
    .about-card:hover { border-color: var(--blue); box-shadow: 0 8px 24px rgba(26,110,199,0.1); transform: translateY(-4px); }
    .about-icon { font-size: 3rem; margin-bottom: 20px; }
    .about-card h3 { font-size: 1.3rem; color: var(--text); margin-bottom: 12px; font-weight: 700; }
    .about-card p { font-size: 1rem; color: var(--text-light); line-height: 1.7; }

    .city-banner { margin-top: 50px; margin-bottom: 50px; padding: 30px; background: linear-gradient(135deg, var(--green), var(--green)); border-radius: 16px; text-align: center; color: white; }
    .city-banner h3 { font-size: 1.8rem; margin-bottom: 8px; }
    .city-banner p { text-align: left; font-size: 1.1rem; display: inline-block; text-decoration: none; margin-top: 12px; color:white; }
    .city-hall { width: 50%; border: 1px solid rgba(255, 255, 255, 0.5); padding: 12px 28px; border-radius: 8px; margin: 0 auto 10px; text-align: left; line-height: 1.6; text-decoration: none; color: white; transition: all 0.2s; }
    .city-hall:hover { border: 1px solid var(--soft-blue); background: var(--soft-blue); color: var(--blue-dark); }

    .section-header { text-align: center; margin-bottom: 20px; }
    .section-label { display: inline-block; font-size: 0.85rem; font-weight: 600; color: var(--blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0px; }
    .section-title { font-family: 'DM Sans', sans-serif; font-size: clamp(2rem, 2vw, .5rem); color: var(--green); margin-bottom: 5px; line-height: 1.3; }
    .section-description { font-size: 1.1rem; color: var(--text-light); max-width: 700px; margin: 0 auto; line-height: 1.8; }

    .about-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 32px; margin-top: 60px; }
    .services { padding: 100px 32px; background: var(--off-white); }
    .services-inner { max-width: 1200px; margin: 0 auto; }
    .services-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 32px; }
    .service-card { background: white; padding: 40px 32px; border-radius: 16px; border: 1px solid var(--border); transition: all 0.3s; position: relative; overflow: hidden; }
    .service-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, var(--blue), #1a8fc7); transform: scaleX(0); transition: transform 0.3s; }
    .service-card:hover::before { transform: scaleX(1); }
    .service-card:hover { box-shadow: 0 12px 32px rgba(26,110,199,0.15); transform: translateY(-6px); border-color: var(--sky-mid); }
    .service-icon { font-size: 3rem; margin-bottom: 20px; display: block; }
    .service-card h3 { font-size: 1.4rem; color: var(--text); margin-bottom: 12px; font-weight: 700; }
    .service-card p { font-size: 1rem; color: var(--text-light); line-height: 1.7; margin-bottom: 20px; }
    .service-link { display: inline-flex; align-items: center; gap: 6px; color: var(--blue); font-weight: 600; text-decoration: none; font-size: 0.95rem; transition: all 0.2s; }
    .service-link:hover { gap: 10px; color: var(--blue-dark); }

    .announcements { padding: 100px 32px; background: white; }
    .announcements-inner { max-width: 1200px; margin: 0 auto; }
    .announcements-grid { display: grid; gap: 24px; }
    .announcement-card { display: grid; grid-template-columns: 80px 1fr; gap: 24px; padding: 32px; background: var(--off-white); border-radius: 12px; border-left: 4px solid var(--blue); transition: all 0.3s; }
    .announcement-card:hover { box-shadow: 0 6px 20px rgba(26,110,199,0.1); transform: translateX(4px); }
    .announcement-date { text-align: center; padding: 16px 12px; background: white; border-radius: 8px; border: 2px solid var(--border); }
    .announcement-day { font-size: 2rem; font-weight: 700; color: var(--blue); display: block; line-height: 1; }
    .announcement-month { font-size: 0.85rem; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 4px; display: block; }
    .announcement-content h3 { font-size: 1.3rem; color: var(--text); margin-bottom: 8px; font-weight: 700; }
    .announcement-category { display: inline-block; padding: 4px 12px; background: var(--blue); color: white; border-radius: 20px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px; }
    .announcement-category.health { background: #059669; }
    .announcement-category.civil { background: #7c3aed; }
    .announcement-category.event { background: #dc2626; }
    .announcement-category.advisory { background: #ea580c; }
    .announcement-content p { font-size: 1rem; color: var(--text-light); line-height: 1.7; }

    .quick-links { padding: 80px 32px; background: var(--blue-dark); color: white; }
    .quick-links-inner { max-width: 1200px; margin: 0 auto; }
    .quick-links h2 { text-align: center; font-size: 2rem; margin-bottom: 40px; font-weight: 700; }
    .links-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
    .quick-link-card { padding: 24px; background: rgba(255, 255, 255, 0.1); border-radius: 12px; text-align: center; text-decoration: none; color: white; transition: all 0.3s; border: 2px solid transparent; }
    .quick-link-card:hover { background: rgba(255, 255, 255, 0.15); border-color: rgba(255, 255, 255, 0.3); transform: translateY(-4px); }
    .quick-link-icon { font-size: 2.5rem; margin-bottom: 12px; display: block; }
    .quick-link-card span { font-size: 1rem; font-weight: 600; display: block; }

    .contact { padding: 100px 32px; background: var(--off-white); }
    .contact-inner { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1.2fr; gap: 60px; }
    .contact-info h2 { font-family: 'Playfair Display', serif; font-size: 2.5rem; color: var(--text); margin-bottom: 20px; line-height: 1.3; }
    .contact-info p { font-size: 1.05rem; color: var(--text-light); line-height: 1.8; margin-bottom: 40px; }
    .contact-details { display: grid; gap: 24px; }
    .contact-item { display: flex; gap: 16px; }
    .contact-item-icon { font-size: 1.5rem; color: var(--blue); flex-shrink: 0; }
    .contact-item h4 { font-size: 0.9rem; color: var(--text-light); margin-bottom: 4px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .contact-item p { font-size: 1.05rem; color: var(--text); margin: 0; line-height: 1.6; }
    .contact-form { background: white; padding: 48px; border-radius: 16px; box-shadow: 0 4px 24px rgba(26,110,199,0.08); }
    .contact-form h3 { font-size: 1.6rem; color: var(--text); margin-bottom: 24px; font-weight: 700; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 0.9rem; font-weight: 600; color: var(--text); margin-bottom: 8px; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 16px; border: 2px solid var(--border); border-radius: 8px; font-family: 'DM Sans', sans-serif; font-size: 1rem; color: var(--text); transition: all 0.3s; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 3px rgba(26,110,199,0.1); }
    .form-group textarea { resize: vertical; min-height: 120px; }
    .btn-submit { width: 100%; padding: 14px 32px; background: var(--blue); color: white; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .btn-submit:hover { background: var(--blue-dark); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(26,110,199,0.3); }

    footer { background: var(--text); color: rgba(255, 255, 255, 0.8); padding: 60px 32px 24px; }
    .footer-inner { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 48px; margin-bottom: 48px; }
    .footer-brand h3 { color: white; font-size: 1.4rem; margin-bottom: 12px; font-weight: 700; }
    .footer-brand p { font-size: 0.95rem; line-height: 1.7; margin-bottom: 20px; }
    .footer-section h4 { color: white; font-size: 1.1rem; margin-bottom: 16px; font-weight: 700; }
    .footer-section ul { list-style: none; padding: 0; }
    .footer-section ul li { margin-bottom: 12px; }
    .footer-section a { color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: color 0.2s; font-size: 0.95rem; }
    .footer-section a:hover { color: white; }
    .footer-bottom { border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 24px; text-align: center; font-size: 0.9rem; }

    @media (max-width: 768px) {
      .floatingPopup { width: 300px; }
      .header-inner { padding: 0 20px; }
      nav, .nav-menu { display: none; }
      .logo-text span { display: none; }
      .hero { padding: 60px 20px; }
      .city-hall { width: 95%; padding: 12px 28px; }
      .stats { grid-template-columns: 1fr; }
      .about, .services, .announcements, .contact { padding: 20px; }
      .contact-inner { grid-template-columns: 1fr; gap: 40px; }
      .contact-form { padding: 32px 24px; }
      .form-row { grid-template-columns: 1fr; }
      .footer-inner { grid-template-columns: 1fr; gap: 32px; }
      .announcement-card { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <div id="floatingPopup" class="floatingPopup">
    <h4>Data Privacy Consent Form</h4>
    <p>I hereby give my consent and acknowledge the authority of Barangay San Juan I to process my personal information in accordance with the Data Privacy Act of 2012.</p>
    <button class="btn btn-primary" onclick="closePopup()" style="float:right;">OK</button>
  </div>
  <header>
    <div class="header-inner">
      <a href="{{ url('/') }}" class="logo-group">
        <img src="{{ asset('images/city_of_general_trias_seal.png') }}" alt="General Trias Seal" />
        <div class="logo-text">
          <strong>PROJECT CONNECT</strong>
          <span>Brgy. San Juan I</span>
        </div>
      </a>
      <nav>
        <a href="{{ url('/') }}">Home</a>
        <a href="#about">About</a>
        <a href="#city-banner">Contact</a>
      </nav>
      <div>
        @guest
          <a href="{{ route('login') }}" class="btn-outline" style="margin-right:10px;">Log In</a>
          @if(Route::has('register'))
            <a href="{{ route('register') }}" class="btn-white">Register</a>
          @endif
        @else
          <a href="{{ url('/resident/dashboard') }}" class="btn-white">Dashboard</a>
        @endguest
      </div>
    </div>
  </header>

  <section class="hero">
    <div class="hero-inner">
      <h1 class="hero-title">PROJECT CONNECT</h1>
      <p class="hero-subtitle">Serving the Community of General Trias with Excellence</p>
      <p class="hero-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      <div class="hero-ctas">
        <a href="{{ route('register') }}" class="btn-white">Register</a>
        <a href="{{ route('login') }}" class="btn-outline">Log In</a>
      </div>
      <div class="stats">
        <div class="stat-card"><span class="stat-value">100K</span><span class="stat-label">Registered Residents</span></div>
        <div class="stat-card"><span class="stat-value">24/7</span><span class="stat-label">Free Wi-Fi Access</span></div>
      </div>
    </div>
  </section>

  <section class="about" id="about">
    <div class="about-inner">
      <div class="about-grid">
        <div class="about-card"><div class="about-icon">📝</div><h3>REGISTER</h3><p>Enter your personal information</p></div>
        <div class="about-card"><div class="about-icon">✅</div><h3>VERIFICATION</h3><p>Wait for approval</p></div>
        <div class="about-card"><div class="about-icon">🆓</div><h3>FREE WI-FI ACCESS</h3><p>Get 1-day free wi-fi access</p></div>
      </div>

      <div class="city-banner" id="city-banner">
        <div class="about-icon" style="font-size: 4rem; margin-bottom: 16px; text-align:center;">🏛️</div>
        <h3 style="text-align: center; margin-bottom: 50px;">San Juan I Barangay Hall</h3>
        <a href="https://www.facebook.com/profile.php?id=61577772153879"><div class="city-hall" title="Redirect to Facebook">Facebook<br>Barangay San Juan I - City of General Trias Cavite</div></a>
        <a href="https://maps.app.goo.gl/jb8Hb745vhcvAAjD9"><div class="city-hall" title="Redirect to Google Maps">Google Maps<br>Block 6 Lot 4, Pennsylvania Executive Village, City of General Trias, Cavite, 4107</div></a>
      </div>
    </div>
  </section>

  <footer>
    <div class="footer-inner">
      <div class="footer-brand">
        <h3>PROJECT CONNECT</h3>
        <p>Providing transparent, efficient, and accessible government services to all residents of Barangay San Juan I, City of General Trias City, Cavite.</p>
        <p style="margin-top: 20px; font-size: 0.85rem;">© 2026 PROJECT CONNECT. All rights reserved.</p>
      </div>
      <div class="footer-section"><h4>Quick Access</h4><ul><li><a href="{{ route('register') }}">Register</a></li><li><a href="#about">Services</a></li></ul></div>
      <div class="footer-section"><h4>Connect</h4><ul><li><a href="https://www.facebook.com/profile.php?id=61577772153879">Facebook</a></li><li><a href="https://maps.app.goo.gl/jb8Hb745vhcvAAjD9">Google Maps</a></li></ul></div>
    </div>
  </footer>

  <script>
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
      });
    });

    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('nav a[href^="#"]');
    window.addEventListener('scroll', () => {
      let current = '';
      sections.forEach(section => {
        const sectionTop = section.offsetTop - 120;
        if (window.scrollY >= sectionTop) { current = section.getAttribute('id'); }
      });
      navLinks.forEach(link => {
        link.style.background = '';
        link.style.color = '';
        if (link.getAttribute('href') === '#' + current) {
          link.style.background = 'var(--sky)';
          link.style.color = 'var(--blue)';
        }
      });
    });

    function closePopup() {
      document.getElementById('floatingPopup').style.display = 'none';
      document.body.style.overflow = 'auto';
    }

    document.addEventListener('DOMContentLoaded', function () {
      document.getElementById('floatingPopup').style.display = 'block';
      document.body.style.overflow = 'hidden';
    });
  </script>
</body>
</html>

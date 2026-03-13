<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Official Government System – Barangay Management System</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --blue:       #1a6ec7;
      --blue-dark:  #154f96;
      --sky:        #d9f2fc;
      --sky-mid:    #a8dff5;
      --green:      #3a7d44;
      --green-dark: #2c5f35;
      --white:      #ffffff;
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
      background: var(--white);
      overflow-x: hidden;
      line-height: 1.6;
    }

    /* ══════════════════════════════════════════════════════════════════
       HEADER
    ══════════════════════════════════════════════════════════════════ */
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

    .top-banner {
      background: linear-gradient(90deg, var(--blue-dark), var(--blue));
      color: white;
      text-align: center;
      padding: 8px 20px;
      font-size: 0.875rem;
      font-weight: 500;
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

    .logo-text {
      line-height: 1.2;
    }

    .logo-text strong {
      display: block;
      font-family: 'Playfair Display', serif;
      font-size: 1rem;
      color: var(--blue-dark);
      letter-spacing: 0.01em;
      font-weight: 700;
    }

    .logo-text span {
      font-size: 0.72rem;
      color: var(--green);
      font-weight: 500;
      letter-spacing: 0.04em;
      text-transform: uppercase;
    }

    nav, .nav-menu {
      display: flex;
      align-items: center;
      gap: 6px;
      list-style: none;
    }

    nav a, .nav-item a {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 14px;
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--text-light);
      text-decoration: none;
      border-radius: 8px;
      transition: all .2s;
    }

    nav a:hover, .nav-item a:hover,
    nav a.active, .nav-item a.active {
      color: var(--blue);
      background: var(--sky);
    }

    .nav-icon {
      font-size: 1.1rem;
    }

    .btn-primary {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 22px;
      background: var(--blue);
      color: var(--white);
      border: none;
      border-radius: 10px;
      font-family: 'DM Sans', sans-serif;
      font-size: 0.875rem;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: all .25s;
      box-shadow: 0 3px 14px rgba(26,110,199,0.3);
    }

    .btn-primary:hover {
      background: var(--blue-dark);
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(26,110,199,0.38);
    }

    /* ══════════════════════════════════════════════════════════════════
       HERO SECTION
    ══════════════════════════════════════════════════════════════════ */
    .hero {
      margin-top: 110px;
      background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue) 45%, #1a8fc7 100%);
      color: white;
      padding: 80px 32px;
      position: relative;
      overflow: hidden;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -20%;
      width: 800px;
      height: 800px;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.1), transparent);
      border-radius: 50%;
    }

    .hero-inner {
      max-width: 1200px;
      margin: 0 auto;
      text-align: center;
      position: relative;
      z-index: 2;
    }

    .hero-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2rem, 5vw, 3.5rem);
      font-weight: 700;
      line-height: 1.2;
      margin-bottom: 16px;
    }

    .hero-subtitle {
      font-size: clamp(1.1rem, 2vw, 1.4rem);
      font-weight: 600;
      color: #fbbf24;
      margin-bottom: 20px;
    }

    .hero-description {
      font-size: 1.1rem;
      line-height: 1.8;
      max-width: 800px;
      margin: 0 auto 40px;
      opacity: 0.95;
    }

    .hero-ctas {
      display: flex;
      justify-content: center;
      gap: 16px;
      flex-wrap: wrap;
      margin-bottom: 60px;
    }

    .btn-white {
      padding: 14px 28px;
      background: white;
      color: var(--blue-dark);
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.3s;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-white:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .btn-outline {
      padding: 14px 28px;
      background: transparent;
      color: white;
      border: 2px solid rgba(255, 255, 255, 0.5);
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.3s;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-outline:hover {
      background: rgba(255, 255, 255, 0.1);
      border-color: white;
    }

    .stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 32px;
      max-width: 900px;
      margin: 0 auto;
    }

    .stat-card {
      text-align: center;
      padding: 24px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      backdrop-filter: blur(10px);
    }

    .stat-value {
      font-size: 2.5rem;
      font-weight: 700;
      display: block;
      margin-bottom: 8px;
    }

    .stat-label {
      font-size: 0.95rem;
      opacity: 0.9;
    }

    .stat-icon {
      width: 60px;
      height: 60px;
      margin: 0 auto 12px;
      opacity: 0.3;
    }

    /* ══════════════════════════════════════════════════════════════════
       ABOUT SECTION
    ══════════════════════════════════════════════════════════════════ */
    .about {
      padding: 100px 32px;
      background: var(--white);
    }

    .about-inner {
      max-width: 1200px;
      margin: 0 auto;
    }

    .section-header {
      text-align: center;
      margin-bottom: 60px;
    }

    .section-label {
      display: inline-block;
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--blue);
      text-transform: uppercase;
      letter-spacing: 0.1em;
      margin-bottom: 12px;
    }

    .section-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2rem, 4vw, 2.8rem);
      color: var(--text);
      margin-bottom: 16px;
      line-height: 1.3;
    }

    .section-description {
      font-size: 1.1rem;
      color: var(--text-light);
      max-width: 700px;
      margin: 0 auto;
      line-height: 1.8;
    }

    .about-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 32px;
      margin-top: 60px;
    }

    .about-card {
      padding: 40px 32px;
      background: var(--off-white);
      border-radius: 16px;
      text-align: center;
      border: 2px solid transparent;
      transition: all 0.3s;
    }

    .about-card:hover {
      border-color: var(--blue);
      box-shadow: 0 8px 24px rgba(26,110,199,0.1);
      transform: translateY(-4px);
    }

    .about-icon {
      font-size: 3rem;
      margin-bottom: 20px;
    }

    .about-card h3 {
      font-size: 1.3rem;
      color: var(--text);
      margin-bottom: 12px;
      font-weight: 700;
    }

    .about-card p {
      font-size: 1rem;
      color: var(--text-light);
      line-height: 1.7;
    }

    .city-banner {
      margin-top: 60px;
      padding: 40px;
      background: linear-gradient(135deg, var(--green-dark), var(--green));
      border-radius: 16px;
      text-align: center;
      color: white;
    }

    .city-banner h3 {
      font-size: 1.8rem;
      margin-bottom: 8px;
    }

    .city-banner p {
      font-size: 1.1rem;
      opacity: 0.95;
    }

    /* ══════════════════════════════════════════════════════════════════
       SERVICES SECTION
    ══════════════════════════════════════════════════════════════════ */
    .services {
      padding: 100px 32px;
      background: var(--off-white);
    }

    .services-inner {
      max-width: 1200px;
      margin: 0 auto;
    }

    .services-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 32px;
    }

    .service-card {
      background: white;
      padding: 40px 32px;
      border-radius: 16px;
      border: 1px solid var(--border);
      transition: all 0.3s;
      position: relative;
      overflow: hidden;
    }

    .service-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, var(--blue), #1a8fc7);
      transform: scaleX(0);
      transition: transform 0.3s;
    }

    .service-card:hover::before {
      transform: scaleX(1);
    }

    .service-card:hover {
      box-shadow: 0 12px 32px rgba(26,110,199,0.15);
      transform: translateY(-6px);
      border-color: var(--sky-mid);
    }

    .service-icon {
      font-size: 3rem;
      margin-bottom: 20px;
      display: block;
    }

    .service-card h3 {
      font-size: 1.4rem;
      color: var(--text);
      margin-bottom: 12px;
      font-weight: 700;
    }

    .service-card p {
      font-size: 1rem;
      color: var(--text-light);
      line-height: 1.7;
      margin-bottom: 20px;
    }

    .service-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: var(--blue);
      font-weight: 600;
      text-decoration: none;
      font-size: 0.95rem;
      transition: all 0.2s;
    }

    .service-link:hover {
      gap: 10px;
      color: var(--blue-dark);
    }

    /* ══════════════════════════════════════════════════════════════════
       ANNOUNCEMENTS SECTION
    ══════════════════════════════════════════════════════════════════ */
    .announcements {
      padding: 100px 32px;
      background: white;
    }

    .announcements-inner {
      max-width: 1200px;
      margin: 0 auto;
    }

    .announcements-grid {
      display: grid;
      gap: 24px;
    }

    .announcement-card {
      display: grid;
      grid-template-columns: 80px 1fr;
      gap: 24px;
      padding: 32px;
      background: var(--off-white);
      border-radius: 12px;
      border-left: 4px solid var(--blue);
      transition: all 0.3s;
    }

    .announcement-card:hover {
      box-shadow: 0 6px 20px rgba(26,110,199,0.1);
      transform: translateX(4px);
    }

    .announcement-date {
      text-align: center;
      padding: 16px 12px;
      background: white;
      border-radius: 8px;
      border: 2px solid var(--border);
    }

    .announcement-day {
      font-size: 2rem;
      font-weight: 700;
      color: var(--blue);
      display: block;
      line-height: 1;
    }

    .announcement-month {
      font-size: 0.85rem;
      color: var(--text-light);
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-top: 4px;
      display: block;
    }

    .announcement-content h3 {
      font-size: 1.3rem;
      color: var(--text);
      margin-bottom: 8px;
      font-weight: 700;
    }

    .announcement-category {
      display: inline-block;
      padding: 4px 12px;
      background: var(--blue);
      color: white;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 12px;
    }

    .announcement-category.health { background: #059669; }
    .announcement-category.civil { background: #7c3aed; }
    .announcement-category.event { background: #dc2626; }
    .announcement-category.advisory { background: #ea580c; }

    .announcement-content p {
      font-size: 1rem;
      color: var(--text-light);
      line-height: 1.7;
    }

    /* ══════════════════════════════════════════════════════════════════
       QUICK LINKS SECTION
    ══════════════════════════════════════════════════════════════════ */
    .quick-links {
      padding: 80px 32px;
      background: var(--blue-dark);
      color: white;
    }

    .quick-links-inner {
      max-width: 1200px;
      margin: 0 auto;
    }

    .quick-links h2 {
      text-align: center;
      font-size: 2rem;
      margin-bottom: 40px;
      font-weight: 700;
    }

    .links-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
    }

    .quick-link-card {
      padding: 24px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      text-align: center;
      text-decoration: none;
      color: white;
      transition: all 0.3s;
      border: 2px solid transparent;
    }

    .quick-link-card:hover {
      background: rgba(255, 255, 255, 0.15);
      border-color: rgba(255, 255, 255, 0.3);
      transform: translateY(-4px);
    }

    .quick-link-icon {
      font-size: 2.5rem;
      margin-bottom: 12px;
      display: block;
    }

    .quick-link-card span {
      font-size: 1rem;
      font-weight: 600;
      display: block;
    }

    /* ══════════════════════════════════════════════════════════════════
       CONTACT SECTION
    ══════════════════════════════════════════════════════════════════ */
    .contact {
      padding: 100px 32px;
      background: var(--off-white);
    }

    .contact-inner {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1.2fr;
      gap: 60px;
    }

    .contact-info h2 {
      font-family: 'Playfair Display', serif;
      font-size: 2.5rem;
      color: var(--text);
      margin-bottom: 20px;
      line-height: 1.3;
    }

    .contact-info p {
      font-size: 1.05rem;
      color: var(--text-light);
      line-height: 1.8;
      margin-bottom: 40px;
    }

    .contact-details {
      display: grid;
      gap: 24px;
    }

    .contact-item {
      display: flex;
      gap: 16px;
    }

    .contact-item-icon {
      font-size: 1.5rem;
      color: var(--blue);
      flex-shrink: 0;
    }

    .contact-item h4 {
      font-size: 0.9rem;
      color: var(--text-light);
      margin-bottom: 4px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .contact-item p {
      font-size: 1.05rem;
      color: var(--text);
      margin: 0;
      line-height: 1.6;
    }

    .contact-form {
      background: white;
      padding: 48px;
      border-radius: 16px;
      box-shadow: 0 4px 24px rgba(26,110,199,0.08);
    }

    .contact-form h3 {
      font-size: 1.6rem;
      color: var(--text);
      margin-bottom: 24px;
      font-weight: 700;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-size: 0.9rem;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 8px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid var(--border);
      border-radius: 8px;
      font-family: 'DM Sans', sans-serif;
      font-size: 1rem;
      color: var(--text);
      transition: all 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: var(--blue);
      box-shadow: 0 0 0 3px rgba(26,110,199,0.1);
    }

    .form-group textarea {
      resize: vertical;
      min-height: 120px;
    }

    .btn-submit {
      width: 100%;
      padding: 14px 32px;
      background: var(--blue);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .btn-submit:hover {
      background: var(--blue-dark);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(26,110,199,0.3);
    }

    /* ══════════════════════════════════════════════════════════════════
       FOOTER
    ══════════════════════════════════════════════════════════════════ */
    footer {
      background: var(--text);
      color: rgba(255, 255, 255, 0.8);
      padding: 60px 32px 24px;
    }

    .footer-inner {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1fr;
      gap: 48px;
      margin-bottom: 48px;
    }

    .footer-brand h3 {
      color: white;
      font-size: 1.4rem;
      margin-bottom: 12px;
      font-weight: 700;
    }

    .footer-brand p {
      font-size: 0.95rem;
      line-height: 1.7;
      margin-bottom: 20px;
    }

    .footer-section h4 {
      color: white;
      font-size: 1.1rem;
      margin-bottom: 16px;
      font-weight: 700;
    }

    .footer-section ul {
      list-style: none;
    }

    .footer-section ul li {
      margin-bottom: 12px;
    }

    .footer-section a {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      transition: color 0.2s;
      font-size: 0.95rem;
    }

    .footer-section a:hover {
      color: white;
    }

    .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding-top: 24px;
      text-align: center;
      font-size: 0.9rem;
    }

    /* ══════════════════════════════════════════════════════════════════
       RESPONSIVE DESIGN
    ══════════════════════════════════════════════════════════════════ */
    @media (max-width: 768px) {
      header {
        position: relative;
      }

      .header-inner {
        padding: 0 16px;
        height: 60px;
      }

      .top-banner {
        padding: 6px 16px;
        font-size: 0.75rem;
      }

      nav, .nav-menu {
        display: none;
      }

      nav a {
        padding: 8px 10px;
        font-size: 0.8rem;
      }

      .logo-group img {
        width: 40px;
        height: 40px;
      }

      .logo-text strong {
        font-size: 0.9rem;
      }

      .logo-text span {
        font-size: 0.6rem;
      }

      .btn-primary {
        padding: 8px 16px;
        font-size: 0.8rem;
      }

      .hero {
        margin-top: 60px;
        padding: 50px 16px;
      }

      .hero-title {
        font-size: clamp(1.5rem, 4vw, 2.5rem);
        margin-bottom: 12px;
      }

      .hero-subtitle {
        font-size: clamp(0.95rem, 3vw, 1.2rem);
        margin-bottom: 16px;
      }

      .hero-description {
        font-size: 0.95rem;
        margin: 0 auto 30px;
        line-height: 1.6;
      }

      .hero-ctas {
        gap: 12px;
        margin-bottom: 40px;
      }

      .btn-white,
      .btn-outline {
        padding: 12px 20px;
        font-size: 0.85rem;
        min-height: 44px;
      }

      .stats {
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 16px;
      }

      .stat-card {
        padding: 16px;
        border-radius: 8px;
      }

      .stat-value {
        font-size: 1.8rem;
      }

      .stat-label {
        font-size: 0.8rem;
      }

      .stat-icon {
        width: 48px;
        height: 48px;
      }

      .about,
      .services,
      .announcements,
      .contact {
        padding: 50px 16px;
      }

      .about-grid {
        gap: 20px;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      }

      .about-card {
        padding: 28px 20px;
      }

      .about-card h3 {
        font-size: 1.1rem;
      }

      .about-icon {
        font-size: 2.2rem;
      }

      .section-title {
        font-size: clamp(1.6rem, 3vw, 2.2rem);
      }

      .section-description {
        font-size: 0.95rem;
      }

      .services-grid {
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
      }

      .service-card {
        padding: 28px 20px;
      }

      .service-card h3 {
        font-size: 1.1rem;
      }

      .service-icon {
        font-size: 2.2rem;
      }

      .announcement-card {
        grid-template-columns: 70px 1fr;
        gap: 16px;
        padding: 20px;
      }

      .announcement-date {
        padding: 12px 8px;
        min-width: 70px;
      }

      .announcement-day {
        font-size: 1.6rem;
      }

      .announcement-month {
        font-size: 0.75rem;
      }

      .announcement-content h3 {
        font-size: 1.05rem;
      }

      .announcement-content p {
        font-size: 0.9rem;
      }

      .quick-links {
        padding: 50px 16px;
      }

      .quick-links h2 {
        font-size: 1.6rem;
      }

      .links-grid {
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
      }

      .quick-link-card {
        padding: 16px;
      }

      .quick-link-icon {
        font-size: 2rem;
      }

      .quick-link-card span {
        font-size: 0.85rem;
      }

      .contact-inner {
        grid-template-columns: 1fr;
        gap: 40px;
      }

      .contact-info h2 {
        font-size: 1.8rem;
      }

      .contact-form {
        padding: 28px 20px;
      }

      .contact-form h3 {
        font-size: 1.3rem;
      }

      .form-row {
        grid-template-columns: 1fr;
        gap: 16px;
      }

      .form-group {
        margin-bottom: 16px;
      }

      .form-group label {
        font-size: 0.85rem;
      }

      .form-group input,
      .form-group select,
      .form-group textarea {
        padding: 10px 12px;
        font-size: 0.95rem;
      }

      .form-group textarea {
        min-height: 100px;
      }

      .btn-submit {
        min-height: 44px;
        font-size: 0.9rem;
      }

      .city-banner {
        margin-top: 40px;
        padding: 28px 20px;
      }

      .city-banner h3 {
        font-size: 1.3rem;
      }

      .city-banner p {
        font-size: 0.95rem;
      }

      .contact-item {
        gap: 12px;
      }

      .contact-item-icon {
        font-size: 1.3rem;
      }

      .contact-item h4 {
        font-size: 0.8rem;
        margin-bottom: 2px;
      }

      .contact-item p {
        font-size: 0.95rem;
      }

      .footer-inner {
        grid-template-columns: 1fr;
        gap: 32px;
      }

      .footer-brand h3 {
        font-size: 1.2rem;
      }

      .footer-section h4 {
        font-size: 0.95rem;
      }

      .footer-section a {
        font-size: 0.9rem;
      }

      .footer-bottom {
        padding-top: 20px;
        font-size: 0.85rem;
      }
    }

    @media (max-width: 480px) {
      .header-inner {
        padding: 0 12px;
        height: 56px;
      }

      .top-banner {
        padding: 4px 12px;
        font-size: 0.7rem;
      }

      .logo-group img {
        width: 36px;
        height: 36px;
      }

      .logo-text strong {
        font-size: 0.8rem;
      }

      .logo-text span {
        display: none;
      }

      .btn-primary {
        padding: 6px 12px;
        font-size: 0.75rem;
      }

      .hero {
        margin-top: 56px;
        padding: 40px 12px;
      }

      .hero-title {
        font-size: clamp(1.3rem, 3vw, 2rem);
        margin-bottom: 8px;
      }

      .hero-subtitle {
        font-size: clamp(0.85rem, 2vw, 1rem);
        margin-bottom: 12px;
      }

      .hero-description {
        font-size: 0.9rem;
        margin: 0 auto 24px;
        line-height: 1.5;
      }

      .hero-ctas {
        gap: 8px;
        margin-bottom: 32px;
        flex-direction: column;
      }

      .btn-white,
      .btn-outline {
        width: 100%;
        padding: 12px 16px;
        font-size: 0.8rem;
        justify-content: center;
        min-height: 44px;
      }

      .stats {
        grid-template-columns: 1fr;
        gap: 12px;
      }

      .stat-card {
        padding: 12px;
      }

      .stat-value {
        font-size: 1.6rem;
      }

      .stat-label {
        font-size: 0.75rem;
      }

      .about,
      .services,
      .announcements,
      .contact {
        padding: 40px 12px;
      }

      .about-grid {
        gap: 16px;
        grid-template-columns: 1fr;
      }

      .about-card {
        padding: 20px 16px;
      }

      .about-card h3 {
        font-size: 1rem;
      }

      .about-icon {
        font-size: 2rem;
      }

      .section-title {
        font-size: clamp(1.4rem, 2.5vw, 1.8rem);
      }

      .section-description {
        font-size: 0.9rem;
      }

      .services-grid {
        grid-template-columns: 1fr;
        gap: 16px;
      }

      .service-card {
        padding: 20px 16px;
      }

      .service-card h3 {
        font-size: 1rem;
      }

      .service-icon {
        font-size: 2rem;
      }

      .service-card p {
        font-size: 0.9rem;
      }

      .service-link {
        font-size: 0.9rem;
      }

      .announcement-card {
        grid-template-columns: 1fr;
        gap: 12px;
        padding: 16px;
      }

      .announcement-date {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0;
        border: none;
        background: transparent;
      }

      .announcement-day {
        font-size: 1.3rem;
      }

      .announcement-month {
        font-size: 0.7rem;
      }

      .announcement-content h3 {
        font-size: 0.95rem;
      }

      .announcement-content p {
        font-size: 0.85rem;
      }

      .quick-links {
        padding: 40px 12px;
      }

      .quick-links h2 {
        font-size: 1.4rem;
        margin-bottom: 12px;
      }

      .quick-links-inner > p {
        font-size: 0.9rem;
        margin-bottom: 24px;
      }

      .links-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
      }

      .quick-link-card {
        padding: 12px;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
      }

      .quick-link-icon {
        font-size: 1.6rem;
        margin-bottom: 8px;
      }

      .quick-link-card span {
        font-size: 0.75rem;
        text-align: center;
      }

      .contact-inner {
        gap: 32px;
      }

      .contact-info h2 {
        font-size: 1.5rem;
      }

      .contact-form {
        padding: 20px 16px;
      }

      .contact-form h3 {
        font-size: 1.1rem;
        margin-bottom: 16px;
      }

      .form-group label {
        font-size: 0.8rem;
        margin-bottom: 6px;
      }

      .form-group input,
      .form-group select,
      .form-group textarea {
        padding: 8px 10px;
        font-size: 0.9rem;
        border-radius: 6px;
      }

      .form-group textarea {
        min-height: 80px;
      }

      .btn-submit {
        min-height: 44px;
        font-size: 0.85rem;
      }

      .city-banner {
        margin-top: 32px;
        padding: 20px 16px;
      }

      .city-banner h3 {
        font-size: 1.1rem;
      }

      .footer-inner {
        gap: 24px;
      }

      .footer-brand h3 {
        font-size: 1.05rem;
      }

      .footer-brand p {
        font-size: 0.85rem;
      }

      .footer-section h4 {
        font-size: 0.85rem;
        margin-bottom: 12px;
      }

      .footer-section a {
        font-size: 0.85rem;
        margin-bottom: 6px;
      }

      .footer-bottom {
        font-size: 0.75rem;
        padding-top: 16px;
      }
    }

    /* Touch device optimization */
    @media (hover: none) and (pointer: coarse) {
      .btn-white,
      .btn-outline,
      .btn-primary,
      .btn-submit,
      nav a,
      .service-link,
      .quick-link-card {
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      button, a[class*="btn"] {
        -webkit-tap-highlight-color: transparent;
      }
    }
  </style>
</head>
<body>

  <!-- ══════════════════════════════════════════════════════════════════
       HEADER
  ══════════════════════════════════════════════════════════════════ -->
  <header>
    <div class="top-banner">
      Official Government System  ·  © 2026
    </div>
    <div class="header-inner">
      <a href="/" class="logo-group">
        <img src="{{ asset('images/city_of_general_trias_seal.png') }}" alt="General Trias Seal" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2248%22 height=%2248%22%3E%3Ccircle cx=%2224%22 cy=%2224%22 r=%2220%22 fill=%22%231a6ec7%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22white%22 font-size=%2216%22 font-weight=%22bold%22%3EGT%3C/text%3E%3C/svg%3E'"/>
        <div class="logo-text">
          <strong>Barangay Management System</strong>
          <span>City of General Trias</span>
        </div>
      </a>
      <nav>
        <a href="/">
          <span class="nav-icon">🏠</span>
          <span>Home</span>
        </a>
        <a href="#about">
          <span class="nav-icon">ℹ️</span>
          <span>About</span>
        </a>
        <a href="#services">
          <span class="nav-icon">📋</span>
          <span>Services</span>
        </a>
        <a href="#announcements">
          <span class="nav-icon">📢</span>
          <span>Announcements</span>
        </a>
        <a href="#contact">
          <span class="nav-icon">📞</span>
          <span>Contact</span>
        </a>
        <a href="{{ route('login') }}" class="btn-primary">Login</a>
      </nav>
    </div>
  </header>

  <!-- ══════════════════════════════════════════════════════════════════
       HERO SECTION
  ══════════════════════════════════════════════════════════════════ -->
  <section class="hero">
    <div class="hero-inner">
      <h1 class="hero-title">Official Government System</h1>
      <p class="hero-subtitle">Serving the Community of General Trias with Excellence</p>
      <p class="hero-description">
        A modern and transparent barangay management platform providing efficient public services, 
        real-time updates, and seamless access to government documents for every resident.
      </p>

      <div class="hero-ctas">
        <a href="#services" class="btn-white">
          🗂 Explore Services
        </a>
        <a href="#contact" class="btn-outline">
          📞 Contact Us
        </a>
      </div>

      <div class="stats">
        <div class="stat-card">
          <span class="stat-value">145+</span>
          <span class="stat-label">Barangays Served</span>
        </div>
        <div class="stat-card">
          <span class="stat-value">400K</span>
          <span class="stat-label">Residents</span>
        </div>
        <div class="stat-card">
          <span class="stat-value">24/7</span>
          <span class="stat-label">Online Access</span>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════════════════════════════
       ABOUT SECTION
  ══════════════════════════════════════════════════════════════════ -->
  <section class="about" id="about">
    <div class="about-inner">
      <div class="section-header">
        <span class="section-label">Who We Are</span>
        <h2 class="section-title">About Barangay Management System</h2>
        <p class="section-description">
          Empowering communities through transparent governance and digital accessibility.
        </p>
      </div>

      <p style="text-align: center; max-width: 800px; margin: 0 auto 60px; font-size: 1.05rem; color: var(--text-light); line-height: 1.8;">
        The Barangay Management System (BMS) is a modern government portal designed to streamline administrative 
        processes and bring government services closer to every resident. Our mission is to make public services 
        more accessible, transparent, and efficient.
      </p>

      <p style="text-align: center; max-width: 800px; margin: 0 auto 60px; font-size: 1.05rem; color: var(--text-light); line-height: 1.8;">
        With an integrated digital infrastructure, we enable residents to access vital services, track documents, 
        and communicate with officials seamlessly—anytime, anywhere.
      </p>

      <div class="about-grid">
        <div class="about-card">
          <div class="about-icon">🎯</div>
          <h3>Mission</h3>
          <p>Deliver outstanding public services through technology and innovation</p>
        </div>

        <div class="about-card">
          <div class="about-icon">👁️</div>
          <h3>Vision</h3>
          <p>A connected community with transparent and responsive governance</p>
        </div>

        <div class="about-card">
          <div class="about-icon">📢</div>
          <h3>Communication</h3>
          <p>Open channels between residents and government officials</p>
        </div>

        <div class="about-card">
          <div class="about-icon">⚡</div>
          <h3>Excellence</h3>
          <p>Continuous improvement in service delivery and efficiency</p>
        </div>
      </div>

      <div class="city-banner">
        <div class="about-icon" style="font-size: 4rem; margin-bottom: 16px;">🏛️</div>
        <h3>City Hall</h3>
        <p>General Trias</p>
        <p style="margin-top: 12px; font-size: 1rem; opacity: 0.9;">System Online</p>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════════════════════════════
       SERVICES SECTION
  ══════════════════════════════════════════════════════════════════ -->
  <section class="services" id="services">
    <div class="services-inner">
      <div class="section-header">
        <span class="section-label">Our Services</span>
        <h2 class="section-title">What We Offer</h2>
        <p class="section-description">
          Comprehensive digital services tailored to meet the needs of our community
        </p>
      </div>

      <div class="services-grid">
        <div class="service-card">
          <span class="service-icon">📋</span>
          <h3>Document Services</h3>
          <p>Request and track important documents such as barangay clearance, residency, and business permits online.</p>
          <a href="{{ route('login') }}" class="service-link">Get Started ›</a>
        </div>

        <div class="service-card">
          <span class="service-icon">🗳️</span>
          <h3>Government Programs</h3>
          <p>Explore and apply for various government programs and benefits available to residents.</p>
          <a href="{{ route('login') }}" class="service-link">Learn More ›</a>
        </div>

        <div class="service-card">
          <span class="service-icon">💬</span>
          <h3>Online Assistance</h3>
          <p>Connect with government officials through our chat system for inquiries and assistance.</p>
          <a href="#contact" class="service-link">Contact Us ›</a>
        </div>

        <div class="service-card">
          <span class="service-icon">📱</span>
          <h3>Online ID Services</h3>
          <p>Generate and manage your online ID for quick verification and access to services.</p>
          <a href="{{ route('login') }}" class="service-link">Apply Now ›</a>
        </div>

        <div class="service-card">
          <span class="service-icon">📢</span>
          <h3>Announcements</h3>
          <p>Stay updated with latest news, advisories, and community announcements.</p>
          <a href="#announcements" class="service-link">View News ›</a>
        </div>

        <div class="service-card">
          <span class="service-icon">🔔</span>
          <h3>Notifications</h3>
          <p>Receive real-time notifications about important updates and events.</p>
          <a href="{{ route('login') }}" class="service-link">Enable Alerts ›</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════════════════════════════
       ANNOUNCEMENTS SECTION
  ══════════════════════════════════════════════════════════════════ -->
  <section class="announcements" id="announcements">
    <div class="announcements-inner">
      <div class="section-header">
        <span class="section-label">Latest Updates</span>
        <h2 class="section-title">News & Announcements</h2>
        <p class="section-description">
          Stay informed about important community events and government updates
        </p>
      </div>

      <div class="announcements-grid">
        <div class="announcement-card">
          <div class="announcement-date">
            <span class="announcement-day">10</span>
            <span class="announcement-month">Mar</span>
          </div>
          <div class="announcement-content">
            <span class="announcement-category health">Health</span>
            <h3>Free Medical Mission – Barangay Buenavista</h3>
            <p>
              The City Health Office will conduct a free medical mission offering consultation, 
              blood pressure monitoring, and medicine distribution for all residents.
            </p>
          </div>
        </div>

        <div class="announcement-card">
          <div class="announcement-date">
            <span class="announcement-day">22</span>
            <span class="announcement-month">Mar</span>
          </div>
          <div class="announcement-content">
            <span class="announcement-category civil">Civil</span>
            <h3>BMS Online Portal Maintenance Notice</h3>
            <p>
              Scheduled system maintenance from 10PM–2AM on March 22. Online document requests 
              may be temporarily unavailable during this period.
            </p>
          </div>
        </div>

        <div class="announcement-card">
          <div class="announcement-date">
            <span class="announcement-day">28</span>
            <span class="announcement-month">Mar</span>
          </div>
          <div class="announcement-content">
            <span class="announcement-category event">Event</span>
            <h3>Barangay Assembly – 1st Quarter 2026</h3>
            <p>
              All registered residents are invited to attend the 1st Quarter Barangay Assembly. 
              Agenda includes budget presentation and community projects update.
            </p>
          </div>
        </div>

        <div class="announcement-card">
          <div class="announcement-date">
            <span class="announcement-day">05</span>
            <span class="announcement-month">Apr</span>
          </div>
          <div class="announcement-content">
            <span class="announcement-category advisory">Advisory</span>
            <h3>Flood Preparedness Advisory – Rainy Season 2026</h3>
            <p>
              DRRMO General Trias urges all residents to prepare emergency kits and identify 
              nearest evacuation centers ahead of the rainy season.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════════════════════════════
       QUICK LINKS SECTION
  ══════════════════════════════════════════════════════════════════ -->
  <section class="quick-links">
    <div class="quick-links-inner">
      <h2>Quick Links</h2>
      <p style="text-align: center; margin-bottom: 40px; opacity: 0.9;">Frequently accessed portals</p>

      <div class="links-grid">
        <a href="{{ route('login') }}" class="quick-link-card">
          <span class="quick-link-icon">🖥️</span>
          <span>Resident Portal</span>
        </a>

        <a href="{{ route('login') }}" class="quick-link-card">
          <span class="quick-link-icon">📋</span>
          <span>Track Documents</span>
        </a>

        <a href="#contact" class="quick-link-card">
          <span class="quick-link-icon">🗺️</span>
          <span>Contact Info</span>
        </a>

        <a href="#contact" class="quick-link-card">
          <span class="quick-link-icon">📞</span>
          <span>Help & Support</span>
        </a>

        <a href="{{ route('login') }}" class="quick-link-card">
          <span class="quick-link-icon">💬</span>
          <span>Chat with Officials</span>
        </a>

        <a href="#announcements" class="quick-link-card">
          <span class="quick-link-icon">📰</span>
          <span>Latest News</span>
        </a>

        <a href="{{ route('register') }}" class="quick-link-card">
          <span class="quick-link-icon">✍️</span>
          <span>Register Now</span>
        </a>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════════════════════════════
       CONTACT SECTION
  ══════════════════════════════════════════════════════════════════ -->
  <section class="contact" id="contact">
    <div class="contact-inner">
      <div class="contact-info">
        <h2>Get in Touch</h2>
        <p style="font-size: 1.1rem; color: var(--text); font-weight: 600; margin-bottom: 12px;">Contact Us</p>
        <p>
          Reach out to your barangay officials. We're here to help and listen to your concerns.
        </p>
        <p style="margin-top: 20px;">
          Our offices are open Monday through Friday. For urgent matters, please use the emergency 
          hotlines or visit your nearest barangay hall during office hours.
        </p>

        <div class="contact-details">
          <div class="contact-item">
            <div class="contact-item-icon">📍</div>
            <div>
              <h4>City Hall Address</h4>
              <p>Gen. Trias Drive, General Trias City,<br>Cavite 4116, Philippines</p>
            </div>
          </div>

          <div class="contact-item">
            <div class="contact-item-icon">📞</div>
            <div>
              <h4>Office Hotline</h4>
              <p>(046) 437-0000 · (046) 437-0001</p>
            </div>
          </div>

          <div class="contact-item">
            <div class="contact-item-icon">✉️</div>
            <div>
              <h4>Email Address</h4>
              <p>bms@generaltrias.gov.ph</p>
            </div>
          </div>

          <div class="contact-item">
            <div class="contact-item-icon">🕐</div>
            <div>
              <h4>Office Hours</h4>
              <p>Monday – Friday: 8:00 AM – 5:00 PM<br>Closed on weekends and holidays</p>
            </div>
          </div>
        </div>
      </div>

      <div class="contact-form">
        <h3>Send a Message</h3>
        <form action="#" method="POST">
          @csrf
          <div class="form-row">
            <div class="form-group">
              <label for="first_name">First Name</label>
              <input type="text" id="first_name" name="first_name" placeholder="Juan" required>
            </div>
            <div class="form-group">
              <label for="last_name">Last Name</label>
              <input type="text" id="last_name" name="last_name" placeholder="Dela Cruz" required>
            </div>
          </div>

          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="juan@email.com" required>
          </div>

          <div class="form-group">
            <label for="concern_type">Concern Type</label>
            <select id="concern_type" name="concern_type" required>
              <option value="">Select a concern</option>
              <option value="document">Document Request</option>
              <option value="assistance">General Assistance</option>
              <option value="complaint">Complaint</option>
              <option value="inquiry">Inquiry</option>
              <option value="feedback">Feedback</option>
            </select>
          </div>

          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" placeholder="Describe your concern or inquiry..." required></textarea>
          </div>

          <button type="submit" class="btn-submit">
            📨 Submit Message
          </button>
        </form>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════════════════════════════
       FOOTER
  ══════════════════════════════════════════════════════════════════ -->
  <footer>
    <div class="footer-inner">
      <div class="footer-brand">
        <h3>Barangay Management System</h3>
        <p>
          Providing transparent, efficient, and accessible government services to all residents 
          of General Trias City, Cavite.
        </p>
        <p style="margin-top: 20px; font-size: 0.85rem;">
          © 2026 City Government of General Trias. All rights reserved.
        </p>
      </div>

      <div class="footer-section">
        <h4>Quick Access</h4>
        <ul>
          <li><a href="{{ route('login') }}">Resident Portal</a></li>
          <li><a href="{{ route('register') }}">Register</a></li>
          <li><a href="#services">Services</a></li>
          <li><a href="#announcements">Announcements</a></li>
        </ul>
      </div>

      <div class="footer-section">
        <h4>About</h4>
        <ul>
          <li><a href="#about">About BMS</a></li>
          <li><a href="#contact">Contact Us</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms of Service</a></li>
        </ul>
      </div>

      <div class="footer-section">
        <h4>Connect</h4>
        <ul>
          <li><a href="#">Facebook</a></li>
          <li><a href="#">Twitter</a></li>
          <li><a href="#">Email</a></li>
          <li><a href="#">Hotline</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>Official Government System · Barangay Management System · General Trias, Cavite</p>
    </div>
  </footer>

  <script>
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });

    // Active navigation highlighting
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('nav a[href^="#"]');
    
    window.addEventListener('scroll', () => {
      let current = '';
      sections.forEach(section => {
        const sectionTop = section.offsetTop - 100;
        if (window.scrollY >= sectionTop) {
          current = section.getAttribute('id');
        }
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
  </script>
</body>
</html>

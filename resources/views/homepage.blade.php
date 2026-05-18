<!DOCTYPE html>
<html lang="en">
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
      background: var(--soft-blue);
      overflow-x: hidden;
      line-height: 1.6;
    }


    /* ══════════════════════════════════════════════════════════════════
       MODAL - DATA PRIVACY NOTICE
    ══════════════════════════════════════════════════════════════════ */
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
	.service-card {
  display: block;
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
      font-family: 'DM Sans', sans-serif;
      font-size: 1rem;
      color: var(--blue-dark);
      letter-spacing: 0.01em;
      font-weight: 700;
    }

    .logo-text span {
      display: block;
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
      color: var(--soft-blue);
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
      margin-top: 50px;
      background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue) 45%, #1a8fc7 100%);
      color: white;
      padding: 96px 32px 80px;
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

    .hero::after {
      content: '';
      position: absolute;
      left: -14%;
      bottom: -36%;
      width: 580px;
      height: 580px;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.14), transparent 68%);
      border-radius: 50%;
      pointer-events: none;
    }

    .hero-inner {
      max-width: 1240px;
      margin: 0 auto;
      position: relative;
      z-index: 2;
    }

    .hero-layout {
      display: grid;
      grid-template-columns: minmax(300px, 1fr) minmax(420px, 1.08fr);
      align-items: center;
      gap: clamp(28px, 4vw, 52px);
      margin-bottom: 30px;
    }

    .hero-copy {
      text-align: left;
    }

    .hero-media {
      width: 100%;
    }

    .hero-title {
      font-family: 'DM Sans', sans-serif;
      font-size: clamp(2rem, 5vw, 3.5rem);
      font-weight: 700;
      line-height: 1.2;
      margin-bottom: 16px;
      letter-spacing: -0.02em;
    }

    .hero-subtitle {
      font-size: clamp(1.1rem, 2vw, 1.4rem);
      font-weight: 600;
      color: #fbbf24;
      margin-bottom: 20px;
    }

    .hero-description {
      font-size: 1.1rem;
      line-height: 1.75;
      max-width: 640px;
      margin: 0 0 34px;
      opacity: 0.94;
    }

    .hero-welcome {
      width: fit-content;
      margin: 0 0 24px;
      padding: 10px 16px;
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 999px;
      font-size: 0.95rem;
      font-weight: 600;
      letter-spacing: 0.01em;
    }

    .hero-carousel {
      max-width: 1040px;
      margin: 0 0 18px;
      position: relative;
      border-radius: 20px;
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.22);
      box-shadow: 0 30px 70px rgba(4, 16, 35, 0.38);
      background: #0b223d;
      isolation: isolate;
    }

    .hero-carousel::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at 18% 20%, rgba(255, 255, 255, 0.14), transparent 55%);
      z-index: 1;
      pointer-events: none;
    }

    .hero-carousel-track {
      display: flex;
      height: clamp(240px, 40vw, 500px);
      transition: transform 0.7s cubic-bezier(0.22, 0.61, 0.36, 1);
      will-change: transform;
      position: relative;
      z-index: 0;
    }

    .hero-slide {
      position: relative;
      min-width: 100%;
      height: 100%;
      user-select: none;
    }

    .hero-slide::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, rgba(5, 16, 34, 0.1), rgba(5, 16, 34, 0.58));
      pointer-events: none;
    }

    .hero-slide img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      user-select: none;
      pointer-events: none;
      transform: scale(1);
      transition: transform 7s cubic-bezier(0.22, 0.61, 0.36, 1);
    }

    .hero-slide.active img {
      transform: scale(1.06);
    }

    .hero-slide-caption {
      position: absolute;
      left: 20px;
      bottom: 20px;
      width: min(450px, calc(100% - 40px));
      padding: 16px 18px;
      border-radius: 14px;
      border: 1px solid rgba(255, 255, 255, 0.22);
      background: linear-gradient(145deg, rgba(10, 30, 55, 0.8), rgba(11, 39, 70, 0.62));
      backdrop-filter: blur(10px);
      text-align: left;
      z-index: 2;
    }

    .hero-slide-caption h3 {
      margin: 0;
      font-size: clamp(1rem, 2vw, 1.2rem);
      font-weight: 700;
      line-height: 1.3;
      color: #ffffff;
    }

    .hero-slide-caption p {
      margin: 6px 0 0;
      color: rgba(255, 255, 255, 0.94);
      font-size: 0.95rem;
      line-height: 1.45;
    }

    .hero-carousel-controls {
      position: absolute;
      right: 16px;
      bottom: 16px;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
      z-index: 3;
    }

    /* carousel indicators removed */

    .hero-carousel-btn {
      width: 48px;
      height: 48px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border: 1px solid rgba(255, 255, 255, 0.45);
      background: rgba(10, 34, 63, 0.64);
      color: white;
      padding: 0;
      border-radius: 999px;
      cursor: pointer;
      font-size: 1.15rem;
      font-weight: 700;
      transition: all 0.2s ease;
      backdrop-filter: blur(8px);
    }

    .hero-carousel-btn:hover {
      background: rgba(255, 255, 255, 0.22);
      border-color: white;
      transform: translateY(-1px);
    }

    .hero-carousel-btn:disabled {
      opacity: 0.45;
      cursor: not-allowed;
      transform: none;
    }

    /* counter chip removed */

    .hero-carousel-progress {
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 3;
      height: 4px;
      background: rgba(255, 255, 255, 0.2);
      overflow: hidden;
    }

    .hero-carousel-progress-fill {
      width: 100%;
      height: 100%;
      transform: scaleX(0);
      transform-origin: left center;
      background: linear-gradient(90deg, #fbbf24, #ffffff);
    }

    @keyframes heroProgress {
      from {
        transform: scaleX(0);
      }
      to {
        transform: scaleX(1);
      }
    }

    @media (max-width: 768px) {
      .hero-carousel {
        border-radius: 14px;
      }

      .hero-carousel-track {
        height: clamp(220px, 58vw, 340px);
      }

      .hero-slide-caption {
        right: 12px;
        bottom: 58px;
        width: 230px;
        max-height: 25%;
        overflow-y: auto;
        padding: 12px 14px;
        box-sizing: border-box;
      }

      .hero-carousel-controls {
        right: 12px;
        bottom: 10px;
        gap: 8px;
      }

      .hero-carousel-btn {
        width: 40px;
        height: 40px;
      }

      /* mobile counter chip removed */
    }

    .hero-ctas {
      display: flex;
      justify-content: flex-start;
      gap: 16px;
      flex-wrap: wrap;
      margin-bottom: 0;
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
      line-height: 1.6;
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
      gap: 20px;
      max-width: 100%;
      margin: 0;
    }

    .stat-card {
      width: 100%;
      margin: 0;
      text-align: center;
      padding: 24px;
      background: rgba(255, 255, 255, 0.14);
      border-radius: 16px;
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 10px 24px rgba(6, 20, 40, 0.2);
    }

    .stat-value {
      font-size: 2.5rem;
      font-weight: 700;
      display: block;
      margin-bottom: 8px;
      /* color: var(--green); */
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
      margin-top: 0px;
      padding: 40px 32px 60px;
      background:
        radial-gradient(80% 120% at 15% 10%, rgba(26, 110, 199, 0.08), transparent 55%),
        var(--soft-blue);
    }

    .about-inner {
      max-width: 1200px;
      margin: 0 auto;
    }

    .about-card {
      padding: 40px 32px;
      background: linear-gradient(160deg, #ffffff, #f7fbff);
      border-radius: 16px;
      text-align: center;
      border: 1px solid rgba(26, 110, 199, 0.16);
      transition: all 0.3s;
      box-shadow: 0 12px 30px rgba(15, 55, 98, 0.08);
    }

    .about-card:hover {
      border-color: var(--blue);
      box-shadow: 0 8px 24px rgba(26,110,199,0.1);
      transform: translateY(-4px);
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
      margin-top: 50px;
      margin-bottom: 50px;
      padding: 36px;
      background: linear-gradient(135deg, var(--green), #2f8a48);
      border-radius: 16px;
      color: white;
    }

    .city-banner-inner {
      max-width: 1100px;
      margin: 0 auto;
      display: grid;
      gap: 18px;
    }

    .city-banner-heading {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .city-banner-heading h3 {
      font-size: 1.6rem;
      margin: 0;
      font-weight: 800;
      color: #ffffff;
    }

    .city-banner-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 16px;
      margin-top: 6px;
    }

    .city-card {
      display: flex;
      gap: 14px;
      align-items: center;
      padding: 16px 18px;
      background: rgba(255,255,255,0.05);
      border-radius: 12px;
      border: 1px solid rgba(255,255,255,0.08);
      transition: transform 0.18s ease, background 0.18s ease, box-shadow 0.18s ease;
      text-decoration: none;
      color: inherit;
    }

    .city-card:hover {
      transform: translateY(-6px);
      background: rgba(255,255,255,0.08);
      box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    }

    .city-card-icon {
      width: 56px;
      height: 56px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      background: rgba(255,255,255,0.12);
    }

    .city-card-title { font-weight: 700; font-size: 1rem; margin-bottom: 4px; }
    .city-card-text { font-size: 0.95rem; color: rgba(255,255,255,0.95); }

    @media (max-width: 768px) {
      .city-banner-inner { display: block; }
      .city-banner-heading { text-align: center; }
      .city-banner-grid { grid-template-columns: 1fr; }
      .city-card { justify-content: center; text-align: center; }
      .city-card-icon { width: 48px; height: 48px; }
    }

    .city-banner p {
      text-align: left;
      font-size: 1.1rem;
      display: inline-block;
      text-decoration: none;
      margin-top: 12px; 
      color:white;
    }

    .city-hall {
      width: 50%;
      border: 1px solid rgba(255, 255, 255, 0.5); 
      padding: 12px 28px; 
      border-radius: 8px; 
      margin: 0 auto;
      margin-bottom: 10px; 
      text-align: left; 
      line-height: 1.6;
      text-decoration: none;
      color: white;

    }

     .city-hall:hover {
      width: 50%;
      border: 1px solid var(--soft-blue); 
      background: var(--soft-blue);
      color: var(--blue-dark);

    }
    a {
      text-decoration: none;
    }

    .section-header {
      text-align: center;
      margin-bottom: 20px;
    }

    .section-label {
      display: inline-block;
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--blue);
      text-transform: uppercase;
      letter-spacing: 0.1em;
      margin-bottom: 0px;
    }

    .section-title {
      font-family: 'DM Sans', sans-serif;
      font-size: clamp(2rem, 2vw, .5rem);
      color: var(--green);
      margin-bottom: 5px;
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

    



    /* ══════════════════════════════════════════════════════════════════
       SERVICES SECTION
    ══════════════════════════════════════════════════════════════════ */
    .services {
      padding: 100px 32px;
      background: linear-gradient(180deg, #f8fcff 0%, #ffffff 100%);
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
      border: 1px solid rgba(26, 110, 199, 0.12);
      transition: all 0.3s;
      position: relative;
      overflow: hidden;
      box-shadow: 0 10px 24px rgba(13, 55, 99, 0.08);
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
      background: linear-gradient(180deg, #ffffff 0%, #f6fbff 100%);
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
      background: #ffffff;
      border-radius: 12px;
      border-left: 4px solid var(--blue);
      border: 1px solid rgba(26, 110, 199, 0.11);
      transition: all 0.3s;
      box-shadow: 0 12px 24px rgba(13, 55, 99, 0.07);
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
      background: linear-gradient(135deg, #0f3d74 0%, #1a6ec7 55%, #228cb8 100%);
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
      background: rgba(255, 255, 255, 0.14);
      border-radius: 12px;
      text-align: center;
      text-decoration: none;
      color: white;
      transition: all 0.3s;
      border: 1px solid rgba(255, 255, 255, 0.28);
      backdrop-filter: blur(6px);
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
      background:
        radial-gradient(70% 120% at 85% 0%, rgba(26, 110, 199, 0.08), transparent 60%),
        var(--off-white);
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
       RESPONSIVE
    ══════════════════════════════════════════════════════════════════ */
    @media (max-width: 768px) {



      .floatingPopup {
      width: 300px;
      position: fixed;
      top: 50%;
      left: 50%;
      padding: 25px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.3);
      border-radius: 10px;
      z-index: 9999;
      
    }
      .header-inner {
        padding: 0 20px;
      }

      nav, .nav-menu {
        display: none;
      }

      .logo-text span {
        display: none;
      }

      .hero {
        padding: 60px 20px;
      
      }

      .hero-layout {
        grid-template-columns: 1fr;
        gap: 22px;
        margin-bottom: 20px;
      }

      .hero-copy {
        text-align: center;
      }

      .hero-welcome {
        font-size: 0.95rem;
        margin-left: auto;
        margin-right: auto;
      }

      .hero-description {
        margin-left: auto;
        margin-right: auto;
      }

      .hero-ctas {
        justify-content: center;
      }

      .hero-carousel-controls {
        right: 12px;
        bottom: 10px;
      }

      .hero-carousel-btn {
        width: 40px;
        height: 40px;
      }

      .stat-card {
      width: 350px;
      margin: 0 auto;
      text-align: center;
      padding: 24px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      backdrop-filter: blur(10px);
    }

      .city-hall {
      width: 95%;
      padding: 12px 28px; 


    }

      .stats {
        grid-template-columns: 1fr;
        max-width: 420px;
        margin: 0 auto;
      }

      .about,
      .services,
      .announcements,
      .contact {
        padding: 20px 20px;
      }

      .contact-inner {
        grid-template-columns: 1fr;
        gap: 40px;
      }

      .contact-form {
        padding: 32px 24px;
      }

      .form-row {
        grid-template-columns: 1fr;
      }

      .footer-inner {
        grid-template-columns: 1fr;
        gap: 32px;
      }

      .announcement-card {
        grid-template-columns: 1fr;
      }
    }

    /* ══════════════════════════════════════════════════════════════════
       RESPONSIVE - SMALL MOBILE (479px and below)
    ══════════════════════════════════════════════════════════════════ */
    @media (max-width: 479px) {
      .floatingPopup {
        width: 90%;
        padding: 20px;
      }

      .header-inner {
        padding: 0 12px;
      }

      .hero {
        padding: 40px 12px;
      }

      .hero-title {
        font-size: 1.75rem;
      }

      .hero-subtitle {
        font-size: 0.95rem;
      }

      .hero-welcome {
        font-size: 0.85rem;
      }

      .hero-description {
        font-size: 0.9rem;
        line-height: 1.6;
      }

      .hero-ctas {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
      }

      .hero-ctas a {
        flex: 0 1 48%;
        max-width: 220px;
      }

      .btn-white,
      .btn-outline {
        width: auto;
        padding: 12px 16px;
        font-size: 0.95rem;
        display: inline-flex;
        justify-content: center;
      }

      .hero-carousel-controls {
        right: 8px;
        bottom: 8px;
        gap: 4px;
      }

      .hero-carousel-btn {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
      }

      .stat-card {
        width: 100%;
        max-width: 280px;
        padding: 16px 20px;
      }

      .stat-value {
        font-size: 1.75rem;
      }

      .stat-label {
        font-size: 0.85rem;
      }

      .about,
      .services,
      .announcements,
      .contact {
        padding: 40px 12px;
      }

      .section-title {
        font-size: 1.5rem;
      }

      .about-grid {
        gap: 16px;
      }

      .about-card {
        padding: 16px;
      }

      .about-card h3 {
        font-size: 1rem;
      }

      .about-card p {
        font-size: 0.9rem;
      }

      .city-banner-heading h3 {
        font-size: 1.4rem;
      }

      .city-banner-grid {
        gap: 12px;
      }

      .city-card {
        padding: 12px;
        gap: 12px;
      }

      .city-card-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
      }

      .city-card-title {
        font-size: 0.9rem;
      }

      .city-card-text {
        font-size: 0.8rem;
      }

      .service-card {
        padding: 16px;
      }

      .service-icon {
        font-size: 2rem;
      }

      .service-card h3 {
        font-size: 1rem;
      }

      .service-card p {
        font-size: 0.85rem;
      }

      .announcements-grid {
        gap: 12px;
      }

      .announcement-card {
        padding: 12px;
        gap: 12px;
      }

      .announcement-date {
        padding: 8px;
        min-width: 60px;
      }

      .announcement-day {
        font-size: 1.5rem;
      }

      .announcement-month {
        font-size: 0.7rem;
      }

      .announcement-content h3 {
        font-size: 1rem;
      }

      .announcement-content p {
        font-size: 0.85rem;
      }

      .quick-links {
        padding: 40px 12px;
      }

      .quick-links h2 {
        font-size: 1.5rem;
        margin-bottom: 24px;
      }

      .links-grid {
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 12px;
      }

      .quick-link-card {
        padding: 12px;
      }

      .quick-link-icon {
        font-size: 1.8rem;
        margin-bottom: 8px;
      }

      .quick-link-card span {
        font-size: 0.85rem;
      }

      .contact-inner {
        gap: 24px;
      }

      .contact-info h2 {
        font-size: 1.6rem;
      }

      .contact-info p {
        font-size: 0.95rem;
      }

      .contact-form {
        padding: 20px 12px;
      }

      .contact-form h3 {
        font-size: 1.3rem;
      }

      .form-group input,
      .form-group select,
      .form-group textarea {
        padding: 10px 12px;
        font-size: 1rem;
      }

      .form-row {
        gap: 12px;
      }

      .btn-submit {
        padding: 12px 20px;
        font-size: 0.95rem;
      }

      .footer-inner {
        gap: 20px;
        margin-bottom: 20px;
      }

      .footer-brand h3 {
        font-size: 1.2rem;
      }

      .footer-brand p {
        font-size: 0.85rem;
      }

      .footer-section h4 {
        font-size: 1rem;
      }

      .footer-section a {
        font-size: 0.85rem;
      }

      .floatingPopup h4 {
        font-size: 1rem;
      }

      .floatingPopup p {
        font-size: 0.9rem;
      }

      .hero-layout {
        grid-template-columns: 1fr;
        gap: 14px;
      }

      .hero-copy {
        order: 1;
        text-align: center;
      }

      .hero-media {
        order: 2;
      }

      .hero-ctas {
        justify-content: center;
        width: 100%;
      }

      .hero-carousel-track {
        height: clamp(160px, 50vw, 260px);
      }

      .hero-slide-caption {
        font-size: 0.9rem;
        right: 8px;
        bottom: 10px;
        width: 250px  ;
        transform: none;
        max-height: 25%;
        overflow-y: auto;
        padding: 10px 12px;
        box-sizing: border-box;
        z-index: 4;
      }

      .hero-carousel-controls {
        right: 8px;
        top: auto;
        bottom: 12px;
        transform: none;
        gap: 6px;
        z-index: 5;
      }

      .hero-carousel-progress {
        bottom: 0;
        height: 4px;
      }
    }
  </style>
</head>
<body>

  @php
    $carouselSlides = collect($slides ?? [])->keyBy('slot');

    $carouselSlides = collect(range(1, 7))->map(function (int $slot) use ($carouselSlides): array {
      $slide = $carouselSlides->get($slot);

      $fallback = asset('images/carousel/slide' . $slot . '.svg');

      $imageUrl = $fallback;
      $title = null;
      $description = null;
      $enabled = true;
      $linkUrl = null;
      $openInNewTab = false;

      if ($slide) {
        $enabled = $slide['enabled'] ?? true;
        $linkUrl = $slide['link_url'] ?? null;
        $openInNewTab = $slide['open_in_new_tab'] ?? false;
        if ($enabled && !empty($slide['image_url'])) {
          $imageUrl = $slide['image_url'];
        }
        $title = $slide['title'] ?? null;
        $description = $slide['description'] ?? null;
      }

      return [
        'slot' => $slot,
        'image_url' => $imageUrl,
        'title' => $title,
        'description' => $description,
        'enabled' => $enabled,
        'link_url' => $linkUrl,
        'open_in_new_tab' => $openInNewTab,
      ];
    })->filter(fn($s) => (bool) ($s['enabled'] ?? true))->values();
  @endphp

  <!-- ══════════════════════════════════════════════════════════════════
       MODAL - DATA PRIVACY NOTICE
  ══════════════════════════════════════════════════════════════════ -->
    <div id="floatingPopup" class="floatingPopup">
      <h4>Data Privacy Consent Form</h4> <br>
      <p>
        I hereby give my consent and acknowledge the authority of Barangay San Juan I to process my personal
        information in accordance with the Data Privacy Act of 2012.
      </p> <br>
      <button class="btn btn-primary" onclick="closePopup()" style="float:right;">OK</button>
    </div>



  <!-- ══════════════════════════════════════════════════════════════════
       HEADER
  ══════════════════════════════════════════════════════════════════ -->
  <header>

    <div class="header-inner">
      <a href="/" class="logo-group">
        <img src="{{ asset('images/city_of_general_trias_seal.png') }}" alt="General Trias Seal" />
        <div class="logo-text">
          <strong>PROJECT CONNECT</strong>
          
            <span style="display: block;">Brgy. San Juan I</span>
          
        </div>
      </a>
      <nav>
        <a href="/">
          <span>Home</span>
        </a>
        <a href="#about">
          <span>About</span>
        </a>
        <a href="#city-banner">
          <span>Contact</span>
        </a>
      </nav>
    </div>
  </header>

  <!-- ══════════════════════════════════════════════════════════════════
       HERO SECTION
  ══════════════════════════════════════════════════════════════════ -->
  <section class="hero">
    <div class="hero-inner">
      <div class="hero-layout">
        <div class="hero-copy">
          <h1 class="hero-title">PROJECT CONNECT</h1>
          <p class="hero-subtitle">Serving the Community of General Trias with Excellence</p>
          <p class="hero-welcome">Welcome to San Juan I Barangay Information System.</p>
          <p class="hero-description">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, 
            sunt in culpa qui officia deserunt mollit anim id est laborum.
          </p>

          <div class="hero-ctas">
            <a href="{{ route('register') }}" class="btn-white">
              Register
            </a>

            <a href="{{ route('login') }}" class="btn-outline">
              Log In
            </a>
          </div>
        </div>

        <div class="hero-media">
          <div class="hero-carousel" id="heroCarousel">
            <div class="hero-carousel-track">
              @foreach ($carouselSlides as $index => $slide)
                <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}" aria-hidden="{{ $index === 0 ? 'false' : 'true' }}">
                  @if (!empty($slide['link_url']))
                    <a href="{{ $slide['link_url'] }}" {{ !empty($slide['open_in_new_tab']) ? 'target="_blank" rel="noopener noreferrer"' : '' }}>
                      <img src="{{ $slide['image_url'] }}" alt="Homepage slide {{ $index + 1 }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                    </a>
                  @else
                    <img src="{{ $slide['image_url'] }}" alt="Homepage slide {{ $index + 1 }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                  @endif
                  @if (!empty($slide['title']) || !empty($slide['description']))
                    <div class="hero-slide-caption">
                      @if (!empty($slide['title']))
                        <h3>{{ $slide['title'] }}</h3>
                      @endif
                      @if (!empty($slide['description']))
                        <p>{{ $slide['description'] }}</p>
                      @endif
                    </div>
                  @endif
                </div>
              @endforeach
            </div>
            <div class="hero-carousel-controls" aria-label="Carousel navigation">
              <button type="button" class="hero-carousel-btn" data-carousel="prev" aria-label="Previous slide">&#10094;</button>
              <button type="button" class="hero-carousel-btn" data-carousel="next" aria-label="Next slide">&#10095;</button>
            </div>
            <div class="hero-carousel-progress" aria-hidden="true">
              <span class="hero-carousel-progress-fill" id="heroCarouselProgress"></span>
            </div>
          </div>

          <div class="stats">
            <div class="stat-card">
              <span class="stat-value">100K</span>
              <span class="stat-label">Registered Residents</span>
            </div>
            <!-- <div class="stat-card">
              <span class="stat-value">24/7</span>
              <span class="stat-label">Free Wi-Fi Access</span>
            </div> -->
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════════════════════════════
       ABOUT SECTION
  ══════════════════════════════════════════════════════════════════ -->
  <section class="about" id="about">
    <div class="about-inner">

      <div class="about-grid">
        <div class="about-card" >
          <h3>REGISTER </h3>
          <p>Submit your personal information to create your resident account.</p>
        </div>

        <div class="about-card">
          <h3>VERIFICATION</h3>
          <p>All accounts are subject to review and approval by barangay administrators.</p>
        </div>

        <div class="about-card">
          <h3>FREE WI-FI ACCESS</h3>
          <p>Approved users are entitled to one-day free Wi-Fi access.</p>
        </div>
      </div>

      <div class="city-banner" id="city-banner">
        <div class="city-banner-inner">
          <div class="city-banner-heading">
            <h3>San Juan I Barangay Hall</h3>
            <p class="muted" style="margin:0; opacity:0.95;">Connect with us and find our location</p>
          </div>

          <div class="city-banner-grid">
            <a class="city-card" href="https://www.facebook.com/profile.php?id=61577772153879" target="_blank" rel="noopener noreferrer" title="Barangay San Juan I Facebook">
              <div class="city-card-icon" style="background:#1877F2;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg"><path d="M22 12.073C22 6.507 17.523 2 12 2S2 6.507 2 12.073C2 17.09 5.657 21.128 10.438 21.951v-6.99H7.898v-2.96h2.54V9.845c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.242 0-1.63.772-1.63 1.562v1.875h2.773l-.443 2.96h-2.33v6.99C18.343 21.128 22 17.09 22 12.073z"/></svg>
              </div>
              <div>
                <div class="city-card-title">Facebook</div>
                <div class="city-card-text">Barangay San Juan I - City of General Trias Cavite</div>
              </div>
            </a>

            <a class="city-card" href="https://maps.app.goo.gl/jb8Hb745vhcvAAjD9" target="_blank" rel="noopener noreferrer" title="Open Google Maps">
              <div class="city-card-icon" style="background: linear-gradient(180deg,#fbbf24,#ff7a5a);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C8.14 2 5 5.14 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.86-3.14-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg>
              </div>
              <div>
                <div class="city-card-title">Google Maps</div>
                <div class="city-card-text">Block 6 Lot 4, Pennsylvania Executive Village, City of General Trias, Cavite, 4107</div>
              </div>
            </a>
          </div>
        </div>
      </div>

        
      </div>
    </div>
  </section>

      <!-- ══════════════════════════════════════════════════════════════════
       DESCRIPTION SECTION
      ═════════════════════════════════════════════════════════════════ -->
      
        <!-- <p style="margin-top: 12px; font-size: 1rem; opacity: 0.9;">System Online</p> -->
      

      <!-- <div class="section-header"> -->
        <!-- <span class="section-label">Who We Are</span> -->
        <!-- <h2 class="section-title">Register now access Free Wi-Fi!</h2> -->
        <!-- <p class="section-description">
          Empowering communities through transparent governance and digital accessibility.
        </p> -->
      <!-- </div>

      <p style="text-align: center; max-width: 800px; margin: 0 auto 60px; font-size: 1.05rem; color: var(--text-light); line-height: 1.8;">
        The Barangay Management System (BMS) is a modern government portal designed to streamline administrative 
        processes and bring government services closer to every resident. Our mission is to make public services 
        more accessible, transparent, and efficient.
      </p>

      <p style="text-align: center; max-width: 800px; margin: 0 auto 60px; font-size: 1.05rem; color: var(--text-light); line-height: 1.8;">
        With an integrated digital infrastructure, we enable residents to access vital services, track documents, 
        and communicate with officials seamlessly—anytime, anywhere.
      </p>

    
    </div> -->
      </section>




  

  <!-- ══════════════════════════════════════════════════════════════════
       FOOTER
  ══════════════════════════════════════════════════════════════════ -->
  <footer>
    <div class="footer-inner">
      <div class="footer-brand">
        <h3>PROJECT CONNECT</h3>
        <p>
          Providing transparent, efficient, and accessible government services to all residents 
          of Barangay San Juan I, City of General Trias City, Cavite.
        </p>
        <p style="margin-top: 20px; font-size: 0.85rem;">
          © 2026 PROJECT CONNECT. All rights reserved.
        </p>
      </div>

      <div class="footer-section">
        <h4>Quick Access</h4>
        <ul>
          <li><a href="{{ route('register') }}">Register</a></li>
          <li><a href="#about">Services</a></li>
          <li><a href="{{ route('contacts.page') }}">Verifier</a></li>
          <li><a href="{{ route('captive.homepage') }}">Captive Portal</a></li>
            
        </ul>
      </div>


      <div class="footer-section">
        <h4>Connect</h4>
        <ul>
          <li><a href="https://www.facebook.com/profile.php?id=61577772153879">Facebook</a></li>
          <li><a href="https://maps.app.goo.gl/jb8Hb745vhcvAAjD9">Google Maps</a></li>

        </ul>
      </div>

    </div>

  </footer>


  
  <script>
    // If this page is restored from BFCache, force a fresh request so
    // authenticated users are redirected by the server to their dashboard.
    window.addEventListener('pageshow', function (event) {
      if (event.persisted) {
        window.location.reload();
      }
    });

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

    function closePopup() {
    document.getElementById('floatingPopup').style.display = 'none';
    document.body.style.overflow = 'auto'; // enable scroll
    }

    // Show popup on page load
    document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('floatingPopup').style.display = 'block';
    document.body.style.overflow = 'hidden';

    });

    (function () {
      const carousel = document.getElementById('heroCarousel');
      if (!carousel) {
        return;
      }

      const track = carousel.querySelector('.hero-carousel-track');
      const slides = Array.from(carousel.querySelectorAll('.hero-slide'));
      const progressFill = carousel.querySelector('#heroCarouselProgress');
      if (slides.length === 0) {
        return;
      }

      let currentIndex = 0;
      let autoplayTimer = null;
      let startX = 0;
      let isDragging = false;
      const totalSlides = slides.length;

      // Carousel settings from server
      const carouselSettings = @json($carouselSettings ?? null);
      const autoplayEnabled = carouselSettings ? Boolean(carouselSettings.autoplay_enabled) : true;
      const autoplaySpeed = carouselSettings ? Number(carouselSettings.autoplay_speed) || 4000 : 4000;
      const pauseOnHover = carouselSettings ? Boolean(carouselSettings.pause_on_hover) : true;
      const loop = carouselSettings ? Boolean(carouselSettings.loop) : true;
      carousel.style.setProperty('--hero-progress-duration', `${autoplaySpeed}ms`);


      const updateSlides = () => {
        slides.forEach((slide, index) => {
          const isActive = index === currentIndex;
          slide.classList.toggle('active', isActive);
          slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
        });
      };


      const syncControlState = () => {
        if (loop) {
          return;
        }
        if (prevButton) {
          prevButton.disabled = currentIndex === 0;
        }
        if (nextButton) {
          nextButton.disabled = currentIndex === slides.length - 1;
        }
      };

      const restartProgress = (shouldRun = true) => {
        if (!progressFill) {
          return;
        }
        progressFill.style.animation = 'none';
        void progressFill.offsetWidth;

        if (!shouldRun) {
          progressFill.style.transform = 'scaleX(0)';
          return;
        }

        progressFill.style.animation = `heroProgress ${autoplaySpeed}ms linear forwards`;
      };

      const goToSlide = (index) => {
        if (loop) {
          currentIndex = (index + slides.length) % slides.length;
        } else {
          currentIndex = Math.max(0, Math.min(index, slides.length - 1));
        }
        if (track) {
          track.style.transform = `translateX(-${currentIndex * 100}%)`;
        }
        updateSlides();
        syncControlState();
      };

      const nextSlide = () => goToSlide(currentIndex + 1);
      const prevSlide = () => goToSlide(currentIndex - 1);

      const restartAutoplay = () => {
        if (autoplayTimer) {
          clearInterval(autoplayTimer);
        }
        if (!autoplayEnabled) {
          restartProgress(false);
          return;
        }
        autoplayTimer = setInterval(() => {
          nextSlide();
          restartProgress();
        }, autoplaySpeed);
        restartProgress();
      };

      const nextButton = carousel.querySelector('[data-carousel="next"]');
      const prevButton = carousel.querySelector('[data-carousel="prev"]');
      carousel.tabIndex = 0;

      if (nextButton) {
        nextButton.addEventListener('click', () => {
          nextSlide();
          restartAutoplay();
        });
      }

      if (prevButton) {
        prevButton.addEventListener('click', () => {
          prevSlide();
          restartAutoplay();
        });
      }

      // indicators removed: direct indicator click handlers are no longer needed

      carousel.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowRight') {
          event.preventDefault();
          nextSlide();
          restartAutoplay();
        }

        if (event.key === 'ArrowLeft') {
          event.preventDefault();
          prevSlide();
          restartAutoplay();
        }
      });

      carousel.addEventListener('touchstart', (event) => {
        startX = event.touches[0].clientX;
        isDragging = true;
      }, { passive: true });

      carousel.addEventListener('touchend', (event) => {
        if (!isDragging) {
          return;
        }

        const endX = event.changedTouches[0].clientX;
        const deltaX = endX - startX;
        isDragging = false;

        if (Math.abs(deltaX) > 40) {
          if (deltaX < 0) {
            nextSlide();
          } else {
            prevSlide();
          }
          restartAutoplay();
        }
      }, { passive: true });

      if (pauseOnHover) {
        carousel.addEventListener('mouseenter', () => {
          clearInterval(autoplayTimer);
          if (progressFill) {
            progressFill.style.animationPlayState = 'paused';
          }
        });

        carousel.addEventListener('mouseleave', () => {
          if (progressFill) {
            progressFill.style.animationPlayState = 'running';
          }
          restartAutoplay();
        });
      }

      goToSlide(0);
      restartAutoplay();
    })();
  </script>
</body>
</html>

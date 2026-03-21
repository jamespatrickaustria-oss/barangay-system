<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> PROJECT CONNECT - @yield('title') </title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/city_of_general_trias_seal.png') }}">
    <!-- Google Fonts -->
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

            /* Backward compatibility with old variable names */
            --primary: #1a6ec7;
            --primary-dark: #154f96;
            --primary-light: #d9f2fc;
            --secondary: #3a7d44;
            --secondary-dark: #2c5f35;
            --secondary-light: rgba(58,125,68,0.12);
            --accent: #e8b84b;
            --accent-light: #fef3c7;
            --gray-50: #f4f8fb;
            --gray-100: #eff2f5;
            --gray-200: #dfe5eb;
            --gray-300: #c8d5e0;
            --gray-400: #9ca3af;
            --gray-500: #4b5e72;
            --gray-600: #4b5e72;
            --gray-700: #1a2433;
            --gray-800: #1a2433;
            --gray-900: #1a2433;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            background: var(--white);
            overflow-x: hidden;
        }

        /* ── SCROLL PROGRESS BAR ──────────────────── */
        .scroll-progress {
            height: 3px;
            background: linear-gradient(90deg, var(--blue), var(--green));
            width: 0%;
            transition: width 0.1s linear;
            position: relative;
            z-index: 2;
        }

        /* ── HEADER ──────────────────────────────── */
        .top-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .nav-bar {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 2px 20px rgba(26,110,199,0.06);
            transition: all 0.3s ease;
        }

        .top-nav.scrolled .nav-bar {
            background: rgba(255,255,255,0.98);
            box-shadow: 0 4px 30px rgba(26,110,199,0.14);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 28px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            transition: height 0.3s ease;
        }

        .top-nav.scrolled .nav-container {
            height: 60px;
        }

        /* ── BRAND ──────────────────────────────── */
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .brand-icon img {
            width: 46px;
            height: 46px;
            object-fit: contain;
            filter: drop-shadow(0 1px 3px rgba(0,0,0,0.15));
            transition: all 0.3s ease;
        }

        .top-nav.scrolled .brand-icon img {
            width: 40px;
            height: 40px;
        }

        .brand-text {
            line-height: 1.2;
            display: flex;
            flex-direction: column;
        }

        .brand-title {
            display: block;
            font-family: 'Playfair Display', serif;
            font-size: 1rem;
            color: var(--blue-dark);
            letter-spacing: 0.01em;
            font-weight: 700;
            line-height: 1.1;
        }

        .brand-subtitle {
            font-size: 0.72rem;
            color: var(--green);
            font-weight: 500;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        /* ── NAV MENU ───────────────────────────── */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 2px;
            list-style: none;
        }

        .nav-item a {
            position: relative;
            padding: 8px 15px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-light);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .nav-item a .nav-icon {
            font-size: 0.9rem;
            opacity: 0.65;
            transition: opacity 0.2s, transform 0.2s;
        }

        .nav-item a:hover {
            color: var(--blue);
            background: var(--sky);
        }

        .nav-item a:hover .nav-icon {
            opacity: 1;
            transform: translateY(-1px);
        }

        .nav-item a.active {
            color: var(--blue);
            background: rgba(26,110,199,0.09);
            font-weight: 600;
        }

        .nav-item a.active .nav-icon { opacity: 1; }

        .nav-item a.active::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
            width: 18px;
            height: 2px;
            background: linear-gradient(90deg, var(--blue), var(--green));
            border-radius: 2px;
        }

        /* ── NAV ACTIONS ────────────────────────── */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }

        /* Notification */
        .notif-wrapper { position: relative; }

        .notif-button {
            width: 40px;
            height: 40px;
            background: transparent;
            border: 1px solid transparent;
            border-radius: 12px;
            font-size: 1.1rem;
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            color: var(--text-light);
        }

        .notif-button:hover {
            background: var(--sky);
            border-color: var(--border);
        }

        .notif-button.has-unread {
            animation: bell-ring 3.5s ease-in-out infinite;
        }

        @keyframes bell-ring {
            0%, 80%, 100% { transform: rotate(0deg); }
            83%  { transform: rotate(14deg); }
            86%  { transform: rotate(-11deg); }
            89%  { transform: rotate(9deg); }
            92%  { transform: rotate(-6deg); }
            95%  { transform: rotate(3deg); }
        }

        .notif-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            font-size: 9px;
            font-weight: 800;
            min-width: 16px;
            height: 16px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            border: 2px solid var(--white);
            line-height: 1;
        }

        /* Notification Panel */
        .notif-panel {
            display: none;
            position: absolute;
            top: calc(100% + 10px);
            right: -8px;
            width: 340px;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(26,110,199,0.18);
            overflow: hidden;
            z-index: 200;
        }

        .notif-panel.show {
            display: block;
            animation: dropIn 0.2s ease;
        }

        @keyframes dropIn {
            from { opacity: 0; transform: translateY(-8px) scale(0.98); }
            to   { opacity: 1; transform: translateY(0)   scale(1);    }
        }

        .notif-panel-header {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, rgba(26,110,199,0.04), rgba(58,125,68,0.02));
        }

        .notif-panel-title {
            font-weight: 700;
            font-size: 13px;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .notif-count-tag {
            background: var(--blue);
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
        }

        .notif-panel-link {
            font-size: 12px;
            color: var(--blue);
            text-decoration: none;
            font-weight: 500;
        }

        .notif-panel-link:hover { text-decoration: underline; }

        .notif-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .notif-list::-webkit-scrollbar { width: 4px; }
        .notif-list::-webkit-scrollbar-track { background: transparent; }
        .notif-list::-webkit-scrollbar-thumb { background: var(--sky-mid); border-radius: 4px; }

        .notif-list-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 13px 18px;
            border-bottom: 1px solid rgba(26,110,199,0.05);
            transition: background 0.15s;
            text-decoration: none;
            cursor: pointer;
        }

        .notif-list-item:hover { background: var(--off-white); }
        .notif-list-item.unread-item { background: rgba(26,110,199,0.03); }

        .notif-item-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--blue);
            margin-top: 4px;
            flex-shrink: 0;
        }

        .notif-list-item:not(.unread-item) .notif-item-dot {
            background: transparent;
            border: 1.5px solid var(--gray-400);
        }

        .notif-item-content { flex: 1; min-width: 0; }

        .notif-item-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notif-item-desc {
            font-size: 11px;
            color: var(--text-light);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }

        .notif-item-time {
            font-size: 10px;
            color: var(--gray-400);
            flex-shrink: 0;
            margin-top: 3px;
            white-space: nowrap;
        }

        .notif-empty {
            padding: 30px 20px;
            text-align: center;
            color: var(--text-light);
            font-size: 13px;
        }

        .notif-empty-icon {
            font-size: 2rem;
            display: block;
            margin-bottom: 8px;
            opacity: 0.45;
        }

        .notif-panel-footer {
            padding: 11px 18px;
            border-top: 1px solid var(--border);
            text-align: center;
            background: rgba(26,110,199,0.02);
        }

        .notif-panel-footer a {
            font-size: 13px;
            color: var(--blue);
            text-decoration: none;
            font-weight: 600;
        }

        /* ── USER MENU ──────────────────────────── */
        .user-menu { position: relative; }

        .user-button {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 12px 5px 5px;
            border-radius: 30px;
            background: var(--sky);
            border: 1.5px solid rgba(26,110,199,0.12);
            cursor: pointer;
            transition: all 0.2s;
        }

        .user-button:hover {
            background: var(--sky-mid);
            border-color: rgba(26,110,199,0.3);
            box-shadow: 0 4px 14px rgba(26,110,199,0.14);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, var(--blue), var(--green));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12px;
            border: 2px solid white;
            box-shadow: 0 2px 6px rgba(26,110,199,0.2);
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        .user-chevron {
            font-size: 10px;
            color: var(--text-light);
            transition: transform 0.25s;
            display: inline-block;
            margin-left: 1px;
        }

        .user-menu.open .user-chevron { transform: rotate(180deg); }

        .user-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            min-width: 248px;
            box-shadow: 0 20px 60px rgba(26,110,199,0.18);
            overflow: hidden;
            z-index: 200;
        }

        .user-dropdown.show {
            display: block;
            animation: dropIn 0.2s ease;
        }

        .dropdown-header {
            padding: 16px 16px 12px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, rgba(26,110,199,0.05), rgba(58,125,68,0.03));
        }

        .dropdown-avatar-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .dropdown-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, var(--blue), var(--green));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            border: 2.5px solid white;
            box-shadow: 0 3px 10px rgba(26,110,199,0.2);
            flex-shrink: 0;
        }

        .dropdown-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .dropdown-user-info .user-full-name {
            font-weight: 700;
            font-size: 14px;
            color: var(--text);
            line-height: 1.2;
        }

        .user-role-badge {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            color: var(--green-dark);
            background: rgba(58,125,68,0.12);
            border-radius: 20px;
            padding: 2px 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 3px;
        }

        .dropdown-user-info .user-email {
            font-size: 11px;
            color: var(--text-light);
            margin-top: 2px;
        }

        .dropdown-nav { padding: 6px 0; }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 10px 16px;
            border: none;
            background: none;
            cursor: pointer;
            text-align: left;
            font-family: inherit;
            font-size: 13.5px;
            color: var(--text);
            transition: background 0.15s;
            text-decoration: none;
        }

        .dropdown-item:hover { background: var(--off-white); }

        .dropdown-item-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: var(--sky);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
            transition: background 0.15s;
        }

        .dropdown-item:hover .dropdown-item-icon { background: rgba(26,110,199,0.15); }

        .dropdown-divider {
            height: 1px;
            background: var(--border);
            margin: 4px 8px;
        }

        .dropdown-item.logout { color: #dc2626; }
        .dropdown-item.logout .dropdown-item-icon { background: rgba(220,38,38,0.08); }
        .dropdown-item.logout:hover { background: rgba(220,38,38,0.05); }

        /* ── HAMBURGER ──────────────────────────── */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 8px;
            border-radius: 10px;
            border: none;
            background: none;
            transition: background 0.2s;
        }

        .hamburger:hover { background: var(--sky); }

        .hamburger span {
            display: block;
            width: 22px;
            height: 2px;
            background: var(--text);
            border-radius: 2px;
            transition: all 0.3s;
        }

        .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        /* ── MOBILE OVERLAY & DRAWER ────────────── */
        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(10,20,40,0.45);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 1001;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .mobile-overlay.show { display: block; opacity: 1; }

        .mobile-drawer {
            position: fixed;
            top: 0;
            right: -320px;
            width: 300px;
            max-width: calc(100vw - 40px);
            height: 100vh;
            background: var(--white);
            box-shadow: -10px 0 60px rgba(0,0,0,0.12);
            z-index: 1002;
            overflow-y: auto;
            transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        .mobile-drawer.open { right: 0; }

        .mobile-drawer-header {
            padding: 24px 20px 18px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, rgba(26,110,199,0.05), rgba(58,125,68,0.02));
            position: relative;
        }

        .mobile-drawer-close {
            position: absolute;
            top: 18px;
            right: 16px;
            width: 30px;
            height: 30px;
            background: var(--off-white);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            transition: background 0.2s;
        }

        .mobile-drawer-close:hover { background: var(--sky); }

        .mobile-user-row {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .mobile-user-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue), var(--green));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            border: 3px solid white;
            box-shadow: 0 4px 12px rgba(26,110,199,0.25);
            overflow: hidden;
            flex-shrink: 0;
        }

        .mobile-user-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .mobile-user-name {
            font-weight: 700;
            font-size: 15px;
            color: var(--text);
            line-height: 1.2;
        }

        .mobile-role-badge {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            color: var(--green-dark);
            background: rgba(58,125,68,0.12);
            border-radius: 20px;
            padding: 2px 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 3px;
        }

        .mobile-nav-body { padding: 12px; flex: 1; }

        .mobile-section-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--gray-400);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 10px 12px 4px;
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 14px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 2px;
            transition: all 0.2s;
        }

        .mobile-nav-link:hover, .mobile-nav-link.active {
            background: var(--sky);
            color: var(--blue);
        }

        .mobile-nav-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--off-white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            transition: background 0.2s;
            flex-shrink: 0;
        }

        .mobile-nav-link:hover .mobile-nav-icon,
        .mobile-nav-link.active .mobile-nav-icon { background: rgba(26,110,199,0.12); }

        .mobile-drawer-footer {
            padding: 12px;
            border-top: 1px solid var(--border);
        }

        .mobile-logout-btn {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 14px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: #dc2626;
            background: none;
            width: 100%;
            border: none;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.2s;
        }

        .mobile-logout-btn:hover { background: rgba(220,38,38,0.06); }

        .mobile-logout-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(220,38,38,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        /* MAIN CONTENT */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 28px 100px;
            margin-top: 72px;
        }

        /* ALERT MESSAGES */
        .alert {
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(58,125,68,0.12);
            border: 1px solid var(--green);
            color: var(--green-dark);
        }

        .alert-error {
            background: rgba(220,38,38,0.12);
            border: 1px solid #dc2626;
            color: #b91c1c;
        }

        .alert-icon {
            font-size: 20px;
            flex-shrink: 0;
        }

        /* RESPONSIVE DESIGN */
        @media (max-width: 768px) {
            .nav-menu   { display: none; }
            .hamburger  { display: flex; }
            .user-name  { display: none; }
            .brand-subtitle { display: none; }

            .nav-container {
                padding: 0 16px;
                height: 62px;
            }

            .top-nav.scrolled .nav-container { height: 56px; }

            .brand-icon img { width: 40px; height: 40px; }
            .brand-title    { font-size: 0.9rem; }

            .main-container {
                padding: 20px 16px 100px;
                margin-top: 62px;
            }

            .notif-panel {
                width: calc(100vw - 32px);
                right: -8px;
            }

            .user-button { padding: 4px 10px 4px 4px; }

            .user-avatar {
                width: 30px;
                height: 30px;
                font-size: 11px;
            }

            .alert {
                flex-direction: column;
                gap: 8px;
                padding: 12px 16px;
            }
        }

        @media (max-width: 480px) {
            .nav-container {
                padding: 0 12px;
                height: 58px;
                gap: 10px;
            }

            .main-container {
                padding: 16px 12px 100px;
                margin-top: 58px;
            }

            .brand-icon img  { width: 36px; height: 36px; }
            .brand-title     { font-size: 0.85rem; }
            .brand-subtitle  { font-size: 0.65rem; }

            .notif-panel {
                width: calc(100vw - 24px);
                right: -4px;
            }

            .user-dropdown {
                right: 0;
                width: calc(100vw - 32px);
                max-width: 280px;
            }

            .alert {
                font-size: 12px;
                padding: 10px 12px;
            }

            .alert-icon { font-size: 16px; }
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


        /* FOOTER RESPONSIVE */
        @media (max-width: 768px) {
            .footer-inner {
                grid-template-columns: 1fr;
                gap: 32px;
            }
        }

        

        /* BUTTON AND INPUT RESPONSIVENESS */
        @media (hover: none) and (pointer: coarse) {
            .notif-button, .user-button { min-height: 44px; }

            .nav-item a {
                min-height: 44px;
                display: flex;
                align-items: center;
            }
        }
    </style>

    <!-- Google Fonts for Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
</head>
<body>
    @php
        $sealLogo = file_exists(public_path('images/city_of_general_trias_seal.png'))
            ? asset('images/city_of_general_trias_seal.png')
            : (file_exists(public_path('images/city_of_general trias.png'))
                ? asset('images/city_of_general trias.png')
                : asset('images/city_of_general_trias.png'));
        $residentPhotoUrl = auth()->user()->profile_photo_url;
    @endphp
    <!-- MOBILE OVERLAY + DRAWER -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="closeMobileMenu()"></div>
    <div class="mobile-drawer" id="mobileDrawer">
        <div class="mobile-drawer-header">
            <button class="mobile-drawer-close" onclick="closeMobileMenu()" aria-label="Close menu">✕</button>
            <div class="mobile-user-row">
                <div class="mobile-user-avatar">
                    @if($residentPhotoUrl)
                        <img src="{{ $residentPhotoUrl }}" alt="{{ auth()->user()->getFullName() }}">
                    @else
                        {{ strtoupper(substr(auth()->user()->getFullName(), 0, 1)) }}
                    @endif
                </div>
                <div>
                    <div class="mobile-user-name">{{ auth()->user()->getFullName() }}</div>
                    <div class="mobile-role-badge">Resident</div>
                </div>
            </div>
        </div>
        <div class="mobile-nav-body">
            <div class="mobile-section-label">Navigation</div>
            <a href="{{ route('resident.dashboard') }}" class="mobile-nav-link {{ request()->is('resident/dashboard') ? 'active' : '' }}">
                Home
            </a>
            <a href="{{ route('resident.online-id') }}" class="mobile-nav-link {{ request()->is('resident/online-id') ? 'active' : '' }}">
                My Online ID
            </a>
            <a href="{{ route('resident.notifications') }}" class="mobile-nav-link {{ request()->is('resident/notifications') ? 'active' : '' }}">
                Announcements
            </a>
            <div class="mobile-section-label">Account</div>
            <a href="{{ route('resident.profile') }}" class="mobile-nav-link {{ request()->is('resident/profile') ? 'active' : '' }}">
                My Profile
            </a>
        </div>
        <div class="mobile-drawer-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="mobile-logout-btn">
                    <span class="mobile-logout-icon">🚪</span>
                    Log Out
                </button>
            </form>
        </div>
    </div>

    <!-- TOP NAVIGATION -->
    <nav class="top-nav" id="topNav">
        <div class="scroll-progress" id="scrollProgress"></div>
        <div class="nav-bar">
            <div class="nav-container">
                <a href="{{ route('resident.dashboard') }}" class="nav-brand">
                    <div class="brand-icon">
                        <img src="{{ $sealLogo }}" alt="City of General Trias Seal">
                    </div>
                    <div class="brand-text">
                        <div class="brand-title">PROJECT CONNECT</div>
                        <div class="brand-subtitle">Brgy. San Juan I</div>
                    </div>
                </a>

                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="{{ route('resident.dashboard') }}" class="{{ request()->is('resident/dashboard') ? 'active' : '' }}">
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('resident.online-id') }}" class="{{ request()->is('resident/online-id') ? 'active' : '' }}">
                            Digital ID
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('resident.notifications') }}" class="{{ request()->is('resident/notifications') ? 'active' : '' }}">
                            Announcements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('resident.profile') }}" class="{{ request()->is('resident/profile') ? 'active' : '' }}">
                            Profile
                        </a>
                    </li>
                </ul>

                <div class="nav-actions">
                    @php
                        $philippineTz = 'Asia/Manila';
                        $unreadCount = App\Models\Notification::where('user_id', auth()->id())
                            ->where('is_read', false)
                            ->where('title', '!=', 'Account Approved')
                            ->count();
                        $recentNotifs = App\Models\Notification::where('user_id', auth()->id())
                            ->where('title', '!=', 'Account Approved')
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
                    @endphp

                    <!-- Notification Bell -->
                    <div class="notif-wrapper">
                        <button class="notif-button {{ $unreadCount > 0 ? 'has-unread' : '' }}"
                                onclick="toggleNotifPanel(event)"
                                aria-label="Notifications">
                            🔔
                            @if($unreadCount > 0)
                                <span class="notif-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                            @endif
                        </button>
                        <div class="notif-panel" id="notifPanel">
                            <div class="notif-panel-header">
                                <span class="notif-panel-title">
                                    Notifications
                                    @if($unreadCount > 0)
                                        <span class="notif-count-tag">{{ $unreadCount }}</span>
                                    @endif
                                </span>
                                <a href="{{ route('resident.notifications') }}" class="notif-panel-link">View all</a>
                            </div>
                            <div class="notif-list">
                                @forelse($recentNotifs as $notif)
                                    <a href="{{ route('resident.notifications') }}" class="notif-list-item {{ !$notif->is_read ? 'unread-item' : '' }}">
                                        <div class="notif-item-dot"></div>
                                        <div class="notif-item-content">
                                            <div class="notif-item-title">{{ $notif->title }}</div>
                                            <div class="notif-item-desc">{{ Str::limit($notif->message, 80) }}</div>
                                        </div>
                                        <div class="notif-item-time">{{ $notif->created_at->timezone($philippineTz)->diffForHumans(now($philippineTz), true, true) }}</div>
                                    </a>
                                @empty
                                    <div class="notif-empty">
                                        <span class="notif-empty-icon">🔔</span>
                                        No notifications yet
                                    </div>
                                @endforelse
                            </div>
                            <div class="notif-panel-footer">
                                <a href="{{ route('resident.notifications') }}">See all notifications →</a>
                            </div>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="user-menu" id="userMenuEl">
                        <button class="user-button" onclick="toggleUserMenu()" aria-label="User menu">
                            <div class="user-avatar">
                                @if($residentPhotoUrl)
                                    <img src="{{ $residentPhotoUrl }}" alt="{{ auth()->user()->getFullName() }}">
                                @else
                                    {{ strtoupper(substr(auth()->user()->getFullName(), 0, 1)) }}
                                @endif
                            </div>
                            <span class="user-name">{{ auth()->user()->getFullName() }}</span>
                            <span class="user-chevron">▼</span>
                        </button>

                        <div class="user-dropdown" id="userDropdown">
                            <div class="dropdown-header">
                                <div class="dropdown-avatar-row">
                                    <div class="dropdown-avatar">
                                        @if($residentPhotoUrl)
                                            <img src="{{ $residentPhotoUrl }}" alt="{{ auth()->user()->getFullName() }}">
                                        @else
                                            {{ strtoupper(substr(auth()->user()->getFullName(), 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="dropdown-user-info">
                                        <div class="user-full-name">{{ auth()->user()->getFullName() }}</div>
                                        <div class="user-role-badge">Resident</div>
                                        <div class="user-email">{{ auth()->user()->email }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-nav">
                                <a href="{{ route('resident.profile') }}" class="dropdown-item">
                                    <span class="dropdown-item-icon">👤</span>
                                    My Profile
                                </a>
                                <a href="{{ route('resident.online-id') }}" class="dropdown-item">
                                    <span class="dropdown-item-icon">🪪</span>
                                    My Online ID
                                </a>
                                <a href="{{ route('resident.notifications') }}" class="dropdown-item">
                                    <span class="dropdown-item-icon">🔔</span>
                                    Notifications
                                    @if($unreadCount > 0)
                                        <span style="margin-left:auto;background:var(--blue);color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;">{{ $unreadCount }}</span>
                                    @endif
                                </a>
                            </div>
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-nav">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout" style="width:100%">
                                        <span class="dropdown-item-icon">🚪</span>
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Hamburger -->
                    <button class="hamburger" id="hamburger" onclick="toggleMobileMenu()" aria-label="Toggle menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="main-container">
        <!-- Flash Messages -->
        @if($errors->any())
            <div class="alert alert-error">
                <span class="alert-icon">⚠️</span>
                <div>
                    <strong>Please correct the following errors:</strong>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <span class="alert-icon">✓</span>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <span class="alert-icon">✕</span>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </main>

    @include('resident.partials.chat-widget')

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
        // ── Scroll effects ─────────────────────────────────────
        const topNav        = document.getElementById('topNav');
        const scrollProg    = document.getElementById('scrollProgress');

        window.addEventListener('scroll', () => {
            const scrollTop  = window.scrollY;
            const docHeight  = document.documentElement.scrollHeight - window.innerHeight;
            const progress   = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
            if (scrollProg) scrollProg.style.width = progress + '%';
            if (topNav)     topNav.classList.toggle('scrolled', scrollTop > 30);
        }, { passive: true });

        // ── Notification panel ──────────────────────────────────
        function toggleNotifPanel(e) {
            e.stopPropagation();
            const panel    = document.getElementById('notifPanel');
            const isShown  = panel.classList.contains('show');
            closeAllDropdowns();
            if (!isShown) panel.classList.add('show');
        }

        // ── User dropdown ───────────────────────────────────────
        const userMenuEl = document.getElementById('userMenuEl');

        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            const isShown  = dropdown.classList.contains('show');
            closeAllDropdowns();
            if (!isShown) {
                dropdown.classList.add('show');
                userMenuEl && userMenuEl.classList.add('open');
            }
        }

        function closeAllDropdowns() {
            document.getElementById('notifPanel')?.classList.remove('show');
            document.getElementById('userDropdown')?.classList.remove('show');
            userMenuEl?.classList.remove('open');
        }

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.notif-wrapper') && !e.target.closest('.user-menu')) {
                closeAllDropdowns();
            }
        });

        // Close on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeAllDropdowns();
                closeMobileMenu();
            }
        });

        // ── Mobile drawer ───────────────────────────────────────
        function toggleMobileMenu() {
            const drawer     = document.getElementById('mobileDrawer');
            drawer.classList.contains('open') ? closeMobileMenu() : openMobileMenu();
        }

        function openMobileMenu() {
            document.getElementById('mobileDrawer').classList.add('open');
            document.getElementById('mobileOverlay').classList.add('show');
            document.getElementById('hamburger').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            document.getElementById('mobileDrawer')?.classList.remove('open');
            document.getElementById('mobileOverlay')?.classList.remove('show');
            document.getElementById('hamburger')?.classList.remove('open');
            document.body.style.overflow = '';
        }
    </script>
</body>
</html>

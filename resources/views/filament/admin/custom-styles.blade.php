{{-- Admin Panel Custom Responsive Styles --}}
<div style="position: fixed; bottom: 10px; right: 10px; background: #f59e0b; color: white; padding: 10px 15px; border-radius: 8px; font-size: 13px; font-weight: bold; z-index: 99999; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
    📱 Admin Responsive CSS Loaded
</div>

<style>
    /* Admin Panel Custom Responsive Styles */
    
    /* Enhanced mobile responsiveness */
    @media (max-width: 768px) {
        /* Main content padding */
        .fi-main {
            padding: 1rem !important;
        }

        /* Improve touch targets */
        .fi-sidebar-nav-item,
        .fi-btn {
            min-height: 44px !important;
        }

        /* Table horizontal scroll */
        .fi-ta-table {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Stats widgets - single column on mobile */
        .fi-wi-stats-overview {
            grid-template-columns: 1fr !important;
            gap: 0.75rem !important;
        }

        /* Better spacing for sections */
        .fi-section-content {
            padding: 1rem !important;
        }

        /* Form fields stack on mobile */
        .fi-fo-component-ctn {
            grid-template-columns: 1fr !important;
        }

        /* Header adjustments */
        .fi-header {
            padding: 1rem !important;
        }

        .fi-header-heading {
            font-size: 1.25rem !important;
        }

        /* Action buttons full width */
        .fi-ac-btn-action {
            width: 100%;
            justify-content: center;
        }

        /* Topbar improvements */
        .fi-topbar {
            padding: 0.75rem 1rem !important;
        }

        /* Logo size adjustment */
        .fi-logo img {
            max-height: 2rem !important;
        }
    }

    /* Tablet styles */
    @media (max-width: 1024px) and (min-width: 769px) {
        .fi-main {
            padding: 1.5rem !important;
        }

        /* Two column layout for stats */
        .fi-wi-stats-overview {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }

    /* Desktop - 3 columns for stats */
    @media (min-width: 1025px) and (max-width: 1440px) {
        .fi-wi-stats-overview {
            grid-template-columns: repeat(3, 1fr) !important;
        }
    }

    /* Large desktop - 4 columns */
    @media (min-width: 1441px) {
        .fi-wi-stats-overview {
            grid-template-columns: repeat(4, 1fr) !important;
        }
    }

    /* Smooth transitions */
    .fi-sidebar,
    .fi-sidebar-nav,
    .fi-topbar {
        transition: all 0.3s ease;
    }

    /* Improve mobile menu overlay */
    @media (max-width: 768px) {
        .fi-sidebar-open .fi-sidebar-overlay {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
        }
    }
</style>

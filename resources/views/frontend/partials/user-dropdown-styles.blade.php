<style>
/* ==========================================================================
   Modern TailAdmin-Consistent Profile Dropdown for Frontend Storefront
   ========================================================================== */
.js-user-dropdown {
    position: relative !important;
    display: inline-flex !important;
    align-items: center !important;
}

/* User Trigger Capsule Pill Button */
.ta-frontend-trigger {
    display: inline-flex !important;
    align-items: center !important;
    gap: 9px !important;
    padding: 3px 12px 3px 4px !important;
    border-radius: 999px !important;
    border: 1px solid #e2e8f0 !important;
    background-color: #ffffff !important;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05) !important;
    transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1) !important;
    cursor: pointer !important;
    text-decoration: none !important;
    user-select: none !important;
    outline: none !important;
}

.ta-frontend-trigger:hover,
.js-user-dropdown.show .ta-frontend-trigger {
    background-color: #f8fafc !important;
    border-color: #cbd5e1 !important;
    box-shadow: 0 3px 8px rgba(15, 23, 42, 0.08) !important;
    transform: translateY(-0.5px);
}

/* Avatar Wrap & Online Status Dot */
.ta-frontend-avatar-wrap {
    position: relative !important;
    flex-shrink: 0 !important;
    width: 32px !important;
    height: 32px !important;
}

.ta-frontend-avatar-img {
    width: 32px !important;
    height: 32px !important;
    border-radius: 50% !important;
    object-fit: cover !important;
    display: block !important;
    border: 1px solid #e2e8f0 !important;
}

.ta-frontend-avatar-fallback {
    width: 32px !important;
    height: 32px !important;
    border-radius: 50% !important;
    background: linear-gradient(135deg, #465fff 0%, #6366f1 100%) !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    font-size: 13px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    text-transform: uppercase !important;
    box-shadow: 0 2px 4px rgba(70, 95, 255, 0.25) !important;
}

.ta-frontend-status-dot {
    position: absolute !important;
    bottom: -1px !important;
    right: -1px !important;
    width: 9.5px !important;
    height: 9.5px !important;
    border-radius: 50% !important;
    border: 2px solid #ffffff !important;
    background-color: #10b981 !important; /* Emerald green matching backend */
}

/* User Name & Role Meta */
.ta-frontend-user-meta {
    text-align: left !important;
    line-height: 1.2 !important;
}

.ta-frontend-user-name {
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #0f172a !important;
    max-width: 120px !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
    letter-spacing: -0.01em !important;
}

.ta-frontend-user-role {
    font-size: 11px !important;
    color: #64748b !important;
    font-weight: 400 !important;
    letter-spacing: 0.01em !important;
}

/* Down Chevron */
.ta-frontend-chevron {
    font-size: 10px !important;
    color: #94a3b8 !important;
    transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), color 0.15s ease !important;
    margin-left: 2px !important;
}

.js-user-dropdown.show .ta-frontend-chevron {
    transform: rotate(180deg) !important;
    color: #465fff !important;
}

/* Dropdown Menu Container Card */
.js-user-dropdown .user-dropdown-menu,
.ta-frontend-dropdown-menu {
    display: none;
    position: absolute !important;
    top: calc(100% + 10px) !important;
    right: 0 !important;
    left: auto !important;
    min-width: 250px !important;
    background-color: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 16px !important; /* rounded-2xl consistent with backend */
    box-shadow: 0 14px 34px -4px rgba(15, 23, 42, 0.12), 0 6px 12px -3px rgba(15, 23, 42, 0.05) !important;
    padding: 8px !important;
    z-index: 1200 !important;
    transform: none !important;
    margin: 0 !important;
}

.js-user-dropdown.show > .user-dropdown-menu,
.js-user-dropdown.show > .ta-frontend-dropdown-menu {
    display: block !important;
    animation: taFadeInMenu 0.16s ease-out !important;
}

@keyframes taFadeInMenu {
    from {
        opacity: 0;
        transform: translateY(-5px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Header Inside Dropdown Menu */
.ta-frontend-dropdown-header {
    padding: 10px 12px 10px !important;
    border-bottom: 1px solid #f1f5f9 !important;
    margin-bottom: 5px !important;
    text-align: left !important;
}

.ta-frontend-dropdown-title {
    font-size: 13.5px !important;
    font-weight: 600 !important;
    color: #0f172a !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
    line-height: 1.3 !important;
}

.ta-frontend-dropdown-sub {
    font-size: 11.5px !important;
    color: #64748b !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
    line-height: 1.3 !important;
    margin-top: 2px !important;
}

.ta-frontend-role-badge {
    display: inline-block !important;
    padding: 2px 8px !important;
    border-radius: 999px !important;
    font-size: 10.5px !important;
    font-weight: 600 !important;
    margin-top: 6px !important;
    line-height: 1.3 !important;
}

.ta-frontend-role-badge.badge-admin {
    background-color: #fee2e2 !important;
    color: #dc2626 !important;
}

.ta-frontend-role-badge.badge-member {
    background-color: #eef2ff !important;
    color: #465fff !important;
}

/* Dropdown Menu Item Links */
.ta-frontend-dropdown-item {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    padding: 8px 12px !important;
    border-radius: 10px !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    color: #334155 !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
    cursor: pointer !important;
    line-height: 1.4 !important;
}

.ta-frontend-dropdown-item:hover,
.ta-frontend-dropdown-item.active {
    background-color: #f1f5f9 !important;
    color: #0f172a !important;
}

.ta-frontend-dropdown-item i {
    width: 16px !important;
    font-size: 13px !important;
    color: #64748b !important;
    text-align: center !important;
    transition: color 0.15s ease !important;
    flex-shrink: 0 !important;
}

.ta-frontend-dropdown-item:hover i {
    color: #465fff !important;
}

/* Dropdown Divider */
.ta-frontend-dropdown-divider {
    height: 1px !important;
    background-color: #f1f5f9 !important;
    margin: 4px 0 !important;
}

/* Dropdown Logout Item */
.ta-frontend-dropdown-logout {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    padding: 8px 12px !important;
    border-radius: 10px !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    color: #dc2626 !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
    cursor: pointer !important;
    line-height: 1.4 !important;
}

.ta-frontend-dropdown-logout:hover {
    background-color: #fef2f2 !important;
    color: #b91c1c !important;
}

.ta-frontend-dropdown-logout i {
    width: 16px !important;
    font-size: 13px !important;
    color: #dc2626 !important;
    text-align: center !important;
    flex-shrink: 0 !important;
}

/* Mobile Specific Adjustments */
@media (max-width: 767.98px) {
    .ta-frontend-trigger {
        padding: 3px 6px 3px 3px !important;
        gap: 6px !important;
    }
    .ta-frontend-user-meta {
        display: none !important;
    }
    .js-user-dropdown .user-dropdown-menu,
    .ta-frontend-dropdown-menu {
        right: 0 !important;
        left: auto !important;
        max-width: calc(100vw - 24px) !important;
        min-width: 220px !important;
    }
}
</style>


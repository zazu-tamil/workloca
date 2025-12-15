<?php include_once(VIEWPATH . '/inc/header.php'); ?>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --card-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        --card-shadow-hover: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .dashboard-header {
        background: var(--primary-gradient);
        padding: 40px 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: var(--card-shadow);
        position: relative;
        overflow: hidden;
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .dashboard-header h1 {
        color: #fff;
        margin: 0;
        font-size: 32px;
        font-weight: 700;
        position: relative;
        z-index: 1;
    }

    .dashboard-header .subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 16px;
        margin-top: 8px;
        position: relative;
        z-index: 1;
    }

    /* Modern Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        box-shadow: var(--card-shadow);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--card-color);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--card-shadow-hover);
    }

    .stat-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: #fff;
        background: var(--card-color);
        box-shadow: 0 4px 12px var(--card-shadow-color);
    }

    .stat-info {
        flex: 1;
        padding-left: 20px;
    }

    .stat-value {
        font-size: 36px;
        font-weight: 700;
        color: #2d3748;
        line-height: 1;
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 15px;
        color: #718096;
        font-weight: 500;
        /* text-transform: uppercase; */
        letter-spacing: 0.5px;
    }

    .stat-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 15px;
        border-top: 1px solid #e2e8f0;
    }

    .stat-link {
        color: var(--card-color);
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    .stat-link:hover {
        color: var(--card-color);
        transform: translateX(5px);
    }

    .stat-link i {
        margin-left: 8px;
        font-size: 12px;
    }

    /* Color Variations */
    .stat-card.aqua {
        --card-color: #00c0ef;
        --card-shadow-color: rgba(0, 192, 239, 0.3);
    }

    .stat-card.green {
        --card-color: #00a65a;
        --card-shadow-color: rgba(0, 166, 90, 0.3);
    }

    .stat-card.yellow {
        --card-color: #f39c12;
        --card-shadow-color: rgba(243, 156, 18, 0.3);
    }

    .stat-card.red {
        --card-color: #dd4b39;
        --card-shadow-color: rgba(221, 75, 57, 0.3);
    }

    .stat-card.purple {
        --card-color: #605ca8;
        --card-shadow-color: rgba(96, 92, 168, 0.3);
    }

    .stat-card.maroon {
        --card-color: #d81b60;
        --card-shadow-color: rgba(216, 27, 96, 0.3);
    }

    .stat-card.orange {
        --card-color: #ff6b6b;
        --card-shadow-color: rgba(255, 107, 107, 0.3);
    }

    .stat-card.teal {
        --card-color: #26a69a;
        --card-shadow-color: rgba(38, 166, 154, 0.3);
    }

    .stat-card.indigo {
        --card-color: #5c6bc0;
        --card-shadow-color: rgba(92, 107, 192, 0.3);
    }

    .stat-card.gradient {
        --card-color: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --card-shadow-color: rgba(240, 147, 251, 0.3);
    }

    .stat-card.gradient .stat-icon {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-card.gradient .stat-link {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* System Status Card */
    .system-status {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: #fff;
    }

    .system-status .stat-value,
    .system-status .stat-label {
        color: #fff;
    }

    .system-status .stat-footer {
        border-top-color: rgba(255, 255, 255, 0.2);
    }

    .system-status .stat-link {
        color: #fff;
    }

    /* Quick Stats Section */
    .quick-stats {
        background: #fff;
        border-radius: 15px;
        padding: 30px;
        box-shadow: var(--card-shadow);
        margin-top: 30px;
    }

    .quick-stats h3 {
        font-size: 22px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
    }

    .quick-stats h3 i {
        margin-right: 12px;
        color: #667eea;
    }

    .stats-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
    }

    .summary-item {
        text-align: center;
        padding: 20px;
        border-radius: 10px;
        background: #f7fafc;
        transition: all 0.3s ease;
    }

    .summary-item:hover {
        background: #edf2f7;
        transform: scale(1.05);
    }

    .summary-item i {
        font-size: 36px;
        margin-bottom: 12px;
        display: block;
    }

    .summary-item .value {
        font-size: 28px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 5px;
    }

    .summary-item .label {
        font-size: 13px;
        color: #718096;
        font-weight: 600;
        text-transform: uppercase;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .dashboard-header {
            padding: 30px 20px;
        }

        .dashboard-header h1 {
            font-size: 24px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .stat-card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .stat-info {
            padding-left: 0;
            padding-top: 15px;
        }

        .stat-value {
            font-size: 28px;
        }
    }

    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stat-card {
        animation: fadeInUp 0.6s ease-out;
    }

    .stat-card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .stat-card:nth-child(2) {
        animation-delay: 0.2s;
    }

    .stat-card:nth-child(3) {
        animation-delay: 0.3s;
    }

    .stat-card:nth-child(4) {
        animation-delay: 0.4s;
    }

    .stat-card:nth-child(5) {
        animation-delay: 0.5s;
    }

    .stat-card:nth-child(6) {
        animation-delay: 0.6s;
    }

    .stat-card:nth-child(7) {
        animation-delay: 0.7s;
    }

    .stat-card:nth-child(8) {
        animation-delay: 0.8s;
    }

    .stat-card:nth-child(9) {
        animation-delay: 0.9s;
    }
</style>

<section class="content">
    <!-- Modern Header -->
    <div class="dashboard-header">
        <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
        <div class="subtitle">Welcome back! Here's what's happening with your store today.</div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <!-- Booking Paid -->
        <div class="stat-card aqua">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fa fa-calendar"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?= !empty($booking_paid) ? $booking_paid : 0; ?></div>
                    <div class="stat-label">Booking Paid</div>
                </div>
            </div>
            <div class="stat-footer">
                <a href="<?= base_url('brand-list'); ?>" class="stat-link">
                    View All <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Booking Pending -->
        <div class="stat-card green">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fa fa-list"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?= !empty($booking_pending) ? $booking_pending : 0; ?></div>
                    <div class="stat-label">Booking Pending</div>
                </div>
            </div>
            <div class="stat-footer">
                <a href="<?= base_url('category-list'); ?>" class="stat-link">
                    View All <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Bookings -->
        <div class="stat-card yellow">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fa fa-ticket"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?= !empty($total_bookings) ? $total_bookings : 0; ?></div>
                    <div class="stat-label">Total Bookings</div>
                </div>
            </div>
            <div class="stat-footer">
                <a href="<?= base_url('items-list'); ?>" class="stat-link">
                    View All <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Amount Paid -->
        <div class="stat-card green">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fa fa-inr"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?= !empty($total_amount_paid) ? $total_amount_paid : 0; ?></div>
                    <div class="stat-label">Total Amount (Paid)</div>
                </div>
            </div>
            <div class="stat-footer">
                <a href="<?= base_url('user-list'); ?>" class="stat-link">
                    View All <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Amount Pending -->
        <div class="stat-card orange">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fa fa-inr"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?= !empty($total_amount_pending) ? $total_amount_pending : 0; ?></div>
                    <div class="stat-label">Total Amount (Pending)</div>
                </div>
            </div>
            <div class="stat-footer">
                <a href="<?= base_url('user-list'); ?>" class="stat-link">
                    View All <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>


        <style>
            .today {
                font-size: 15px !important;
            }
        </style>

    </div>
</section>

<?php include_once(VIEWPATH . 'inc/footer.php'); ?>
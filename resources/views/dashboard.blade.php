<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Plateforme de Paiement</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #F3DE2C;
            --primary-dark: #e2cd24;
            --secondary-color: #111;
            --text-color: #333;
            --light-gray: #f5f5f5;
            --border-color: #ddd;
            --card-bg: #ffffff;
            --sidebar-bg: #1a1a2e;
            --sidebar-text: #e2e8f0;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #3498db;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: var(--text-color);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Layout principal */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            padding: 30px 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transform: translateX(0);
            transition: var(--transition);
            z-index: 1000;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: var(--primary-color);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary-color);
            font-size: 24px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .logo-text h1 {
            font-size: 20px;
            font-weight: 700;
            color: white;
            margin-bottom: 5px;
        }

        .logo-text p {
            font-size: 12px;
            opacity: 0.8;
        }

        .nav-menu {
            list-style: none;
            margin-top: 30px;
        }

        .nav-item {
            margin-bottom: 10px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 10px;
            transition: var(--transition);
            font-weight: 500;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 18px;
        }

        .badge {
            background: var(--primary-color);
            color: var(--secondary-color);
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: auto;
        }

        /* Contenu principal */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            transition: var(--transition);
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: var(--card-bg);
            border-radius: 15px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header-left h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--secondary-color);
        }

        .header-left p {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 12px 40px 12px 15px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            width: 300px;
            transition: var(--transition);
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(243, 222, 44, 0.1);
        }

        .search-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
            cursor: pointer;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary-color);
            font-weight: 600;
            font-size: 20px;
        }

        .toggle-sidebar {
            display: none;
            background: var(--primary-color);
            color: var(--secondary-color);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 20px;
            transition: var(--transition);
        }

        .toggle-sidebar:hover {
            transform: rotate(90deg);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
            animation-fill-mode: both;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: white;
        }

        .stat-card:nth-child(1) .stat-icon { background: linear-gradient(135deg, #667eea, #764ba2); }
        .stat-card:nth-child(2) .stat-icon { background: linear-gradient(135deg, #f093fb, #f5576c); }
        .stat-card:nth-child(3) .stat-icon { background: linear-gradient(135deg, #4facfe, #00f2fe); }
        .stat-card:nth-child(4) .stat-icon { background: linear-gradient(135deg, #43e97b, #38f9d7); }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--secondary-color);
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            font-weight: 500;
        }

        .trend-up {
            color: var(--success-color);
        }

        .trend-down {
            color: var(--danger-color);
        }

        /* Charts Section */
        .charts-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .chart-container {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--shadow);
            animation: slideUp 0.8s ease-out;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .chart-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .chart-options select {
            padding: 8px 15px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            background: white;
            cursor: pointer;
        }

        .chart-placeholder {
            height: 300px;
            background: linear-gradient(135deg, rgba(243, 222, 44, 0.1), rgba(226, 205, 36, 0.05));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 14px;
        }

        /* Recent Activity */
        .activity-container {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--shadow);
            animation: slideUp 0.9s ease-out;
        }

        .activity-list {
            margin-top: 20px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border-radius: 10px;
            transition: var(--transition);
            margin-bottom: 10px;
        }

        .activity-item:hover {
            background: var(--light-gray);
            transform: translateX(5px);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .activity-icon.success { background: var(--success-color); }
        .activity-icon.warning { background: var(--warning-color); }
        .activity-icon.danger { background: var(--danger-color); }
        .activity-icon.info { background: var(--info-color); }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .activity-time {
            font-size: 12px;
            color: #999;
        }

        /* Quick Actions */
        .quick-actions {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--shadow);
            animation: slideUp 1s ease-out;
            margin-bottom: 40px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 25px;
            background: var(--light-gray);
            border-radius: 12px;
            text-decoration: none;
            color: var(--text-color);
            transition: var(--transition);
        }

        .action-btn:hover {
            background: var(--primary-color);
            color: var(--secondary-color);
            transform: translateY(-5px);
        }

        .action-btn i {
            font-size: 32px;
            margin-bottom: 15px;
        }

        .action-btn span {
            font-weight: 500;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 14px;
            border-top: 1px solid var(--border-color);
            margin-top: 40px;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .toggle-sidebar {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .search-box input {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            
            .header-right {
                flex-direction: column;
                width: 100%;
            }
            
            .search-box input {
                width: 100%;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .actions-grid {
                grid-template-columns: 1fr;
            }
            
            .main-content {
                padding: 15px;
            }
            
            .header-left h2 {
                font-size: 20px;
            }
        }

        /* Dark mode */
        @media (prefers-color-scheme: dark) {
            :root {
                --card-bg: #2d3748;
                --text-color: #e2e8f0;
                --light-gray: #4a5568;
                --border-color: #718096;
            }
            
            body {
                background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            }
            
            .search-box input {
                background: #4a5568;
                color: white;
                border-color: #718096;
            }
            
            .activity-item:hover {
                background: #4a5568;
            }
            
            .action-btn {
                background: #4a5568;
                color: #e2e8f0;
            }
        }

        /* Animations supplémentaires */
        .dashboard-container {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .welcome-message {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--secondary-color);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            animation: slideInLeft 0.7s ease-out;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .welcome-message h3 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .welcome-message p {
            opacity: 0.9;
        }

        /* Notifications */
        .notification-badge {
            position: relative;
        }

        .notification-badge::after {
            content: '3';
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Loading animation */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            100% {
                left: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="logo-text">
                    <h1>Soutien opheliant</h1>
                    <p>Tableau de bord</p>
                </div>
            </div>

            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('admin.payments.dashboard') }}" class="nav-link active">
                        <i class="fas fa-home"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-credit-card"></i>
                        <span>Transactions</span>
                        <span class="badge">12</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <span>Analytiques</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span>Clients</span>
                        <span class="badge">5</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <span>Paramètres</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-question-circle"></i>
                        <span>Aide & Support</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Déconnexion</span>
                    </a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </ul>
        </aside>

        <!-- Contenu principal -->
        <main class="main-content" id="mainContent">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="toggle-sidebar" id="toggleSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2>Bonjour, {{ Auth::user()->name ?? 'Utilisateur' }} 👋</h2>
                    <p>Voici votre tableau de bord personnel</p>
                </div>
                
                <div class="header-right">
                    <div class="search-box">
                        <input type="text" placeholder="Rechercher...">
                        <i class="fas fa-search"></i>
                    </div>
                    
                    <div class="notification-badge">
                        <i class="fas fa-bell" style="font-size: 20px; color: #666; cursor: pointer;"></i>
                    </div>
                    
                    <div class="user-menu">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 500;">{{ Auth::user()->name ?? 'Utilisateur' }}</div>
                            <div style="font-size: 12px; color: #666;">Administrateur</div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Message de bienvenue -->
            <div class="welcome-message">
                <h3>🎉 Bienvenue sur votre espace !</h3>
                <p>Vous avez 3 nouvelles notifications et 12 transactions en attente</p>
            </div>

            <!-- Statistiques -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-value">1,245,000 F</div>
                    <div class="stat-label">Chiffre d'affaires</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-arrow-up"></i>
                        <span>12.5% ce mois</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-value">1,248</div>
                    <div class="stat-label">Clients actifs</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-arrow-up"></i>
                        <span>8.2% ce mois</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-value">324</div>
                    <div class="stat-label">Transactions</div>
                    <div class="stat-trend trend-down">
                        <i class="fas fa-arrow-down"></i>
                        <span>3.1% ce mois</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="stat-value">98.2%</div>
                    <div class="stat-label">Taux de réussite</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-arrow-up"></i>
                        <span>1.5% ce mois</span>
                    </div>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="charts-grid">
                <div class="chart-container">
                    <div class="chart-header">
                        <h3>Performances mensuelles</h3>
                        <div class="chart-options">
                            <select>
                                <option>Ce mois</option>
                                <option>Ce trimestre</option>
                                <option>Cette année</option>
                            </select>
                        </div>
                    </div>
                    <div class="chart-placeholder">
                        <i class="fas fa-chart-line" style="font-size: 48px; margin-right: 15px;"></i>
                        Graphique des performances
                    </div>
                </div>
                
                <div class="chart-container">
                    <div class="chart-header">
                        <h3>Répartition</h3>
                        <div class="chart-options">
                            <select>
                                <option>Par catégorie</option>
                                <option>Par région</option>
                            </select>
                        </div>
                    </div>
                    <div class="chart-placeholder">
                        <i class="fas fa-chart-pie" style="font-size: 48px; margin-right: 15px;"></i>
                        Graphique circulaire
                    </div>
                </div>
            </div>

            <!-- Activité récente -->
            <div class="activity-container">
                <h3 style="font-size: 18px; font-weight: 600; color: var(--secondary-color); margin-bottom: 20px;">
                    Activité récente
                </h3>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon success">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Paiement réussi - Jean Dupont</div>
                            <div class="activity-time">Il y a 5 minutes</div>
                        </div>
                        <div class="activity-amount">25,000 F</div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Paiement en attente - Marie Curie</div>
                            <div class="activity-time">Il y a 15 minutes</div>
                        </div>
                        <div class="activity-amount">50,000 F</div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon danger">
                            <i class="fas fa-times"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Paiement échoué - Pierre Martin</div>
                            <div class="activity-time">Il y a 1 heure</div>
                        </div>
                        <div class="activity-amount">15,000 F</div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon info">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Nouveau client - Sarah Johnson</div>
                            <div class="activity-time">Il y a 2 heures</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="quick-actions">
                <h3 style="font-size: 18px; font-weight: 600; color: var(--secondary-color); margin-bottom: 20px;">
                    Actions rapides
                </h3>
                <div class="actions-grid">
                    <a href="#" class="action-btn">
                        <i class="fas fa-plus-circle"></i>
                        <span>Nouveau paiement</span>
                    </a>
                    
                    <a href="#" class="action-btn">
                        <i class="fas fa-file-invoice"></i>
                        <span>Créer facture</span>
                    </a>
                    
                    <a href="#" class="action-btn">
                        <i class="fas fa-chart-bar"></i>
                        <span>Voir rapports</span>
                    </a>
                    
                    <a href="#" class="action-btn">
                        <i class="fas fa-cog"></i>
                        <span>Paramètres</span>
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>© 2024 FinancePro. Tous droits réservés.</p>
                <p style="margin-top: 5px; font-size: 12px; opacity: 0.8;">
                    Dernière mise à jour : Aujourd'hui à 14:30
                </p>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleSidebar = document.getElementById('toggleSidebar');
            
            // Toggle sidebar
            toggleSidebar.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                mainContent.classList.toggle('expanded');
            });
            
            // Fermer le sidebar en cliquant à l'extérieur (sur mobile)
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 992) {
                    if (!sidebar.contains(event.target) && !toggleSidebar.contains(event.target)) {
                        sidebar.classList.remove('active');
                        mainContent.classList.remove('expanded');
                    }
                }
            });
            
            // Animation des cartes de statistiques au survol
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
            
            // Animation des boutons d'action
            const actionBtns = document.querySelectorAll('.action-btn');
            actionBtns.forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    const icon = this.querySelector('i');
                    icon.style.transform = 'scale(1.2) rotate(5deg)';
                });
                
                btn.addEventListener('mouseleave', function() {
                    const icon = this.querySelector('i');
                    icon.style.transform = 'scale(1) rotate(0deg)';
                });
            });
            
            // Simulation de chargement des données
            function simulateLoading() {
                const loadingElements = document.querySelectorAll('.stat-card, .chart-container, .activity-container');
                loadingElements.forEach(el => {
                    el.classList.add('loading');
                });
                
                setTimeout(() => {
                    loadingElements.forEach(el => {
                        el.classList.remove('loading');
                    });
                }, 1500);
            }
            
            // Simuler le chargement au démarrage
            setTimeout(simulateLoading, 1000);
            
            // Mettre à jour l'heure en temps réel
            function updateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('fr-FR', { 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });
                const dateString = now.toLocaleDateString('fr-FR', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                
                // Mettre à jour le header
                const headerSubtitle = document.querySelector('.header-left p');
                if (headerSubtitle) {
                    headerSubtitle.textContent = `Voici votre tableau de bord - ${dateString}`;
                }
            }
            
            updateTime();
            setInterval(updateTime, 60000); // Mettre à jour chaque minute
            
            // Effet de notification
            const notificationBell = document.querySelector('.fa-bell');
            notificationBell.addEventListener('click', function() {
                this.style.transform = 'rotate(15deg)';
                setTimeout(() => {
                    this.style.transform = 'rotate(-15deg)';
                }, 100);
                setTimeout(() => {
                    this.style.transform = 'rotate(0deg)';
                }, 200);
                
                // Afficher une notification toast
                showToast('Nouvelle notification reçue !');
            });
            
            function showToast(message) {
                const toast = document.createElement('div');
                toast.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: var(--primary-color);
                    color: var(--secondary-color);
                    padding: 15px 25px;
                    border-radius: 10px;
                    box-shadow: var(--shadow);
                    z-index: 9999;
                    animation: slideInRight 0.3s ease-out;
                `;
                
                toast.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-bell"></i>
                        <span>${message}</span>
                    </div>
                `;
                
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.style.animation = 'slideOutRight 0.3s ease-out';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }
            
            // Ajouter les styles d'animation pour le toast
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideInRight {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                
                @keyframes slideOutRight {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
            
            // Effet de recherche
            const searchInput = document.querySelector('.search-box input');
            searchInput.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.05)';
            });
            
            searchInput.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
            
            // Animation du logo
            const logoIcon = document.querySelector('.logo-icon');
            setInterval(() => {
                logoIcon.style.transform = 'rotate(5deg)';
                setTimeout(() => {
                    logoIcon.style.transform = 'rotate(-5deg)';
                }, 300);
                setTimeout(() => {
                    logoIcon.style.transform = 'rotate(0deg)';
                }, 600);
            }, 5000);
        });
    </script>
</body>
</html>
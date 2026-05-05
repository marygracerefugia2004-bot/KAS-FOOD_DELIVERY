<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAS Delivery — Fast Food Delivery Anywhere</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{
            --orange:#FF6B2C;--orange2:#FF8B00;
            --navy:#0D1B4B;
            --bg:#080D1A;--text:#fff;--muted:#94A3B8;
        }
        body{
            font-family:'Plus Jakarta Sans',sans-serif;
            background:var(--bg);
            color:var(--text);
            min-height:100vh;
            overflow-x:hidden;
        }
        
        /* Background pattern */
        .bg-pattern{
            position:fixed;inset:0;z-index:0;pointer-events:none;
            background:linear-gradient(135deg,#080D1A 0%,#0F172A 100%);
        }
        .bg-pattern::before{
            content:'';position:absolute;top:-50%;right:-30%;width:800px;height:800px;
            background:radial-gradient(circle,rgba(255,107,44,.12) 0%,transparent 70%);
            animation:float 10s ease-in-out infinite;
        }
        .bg-pattern::after{
            content:'';position:absolute;bottom:-40%;left:-20%;width:600px;height:600px;
            background:radial-gradient(circle,rgba(30,51,153,.15) 0%,transparent 70%);
            animation:float 12s ease-in-out infinite reverse;
        }
        @keyframes float{0%,100%{transform:translate(0,0)}50%{transform:translate(30px,-30px)}}
        
        /* Navbar — no menu items */
        nav{
            position:fixed;top:0;left:0;right:0;z-index:100;
            display:flex;align-items:center;justify-content:space-between;
            padding:1rem 2rem;
            background:rgba(8,13,26,.8);
            backdrop-filter:blur(20px);
            border-bottom:1px solid rgba(255,255,255,.05);
        }
        .logo{display:flex;align-items:center;gap:.6rem;font-family:'Space Grotesk',sans-serif;font-weight:800;font-size:1.3rem}
        .logo-icon{width:36px;height:36px;background:linear-gradient(135deg,var(--orange),var(--orange2));border-radius:10px;display:flex;align-items:center;justify-content:center}
        .logo span{color:var(--orange)}
        .nav-buttons{display:flex;gap:.8rem}
        
        /* Buttons */
        .btn{text-decoration:none;font-weight:700;font-size:.85rem;padding:.5rem 1.2rem;border-radius:40px;transition:.25s;cursor:pointer;display:inline-flex;align-items:center;gap:.5rem}
        .btn-outline{background:transparent;border:1.5px solid rgba(255,255,255,.2);color:#fff}
        .btn-outline:hover{border-color:var(--orange);color:var(--orange)}
        .btn-primary{background:linear-gradient(135deg,var(--orange),var(--orange2));color:#fff;box-shadow:0 4px 12px rgba(255,107,44,.3)}
        .btn-primary:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(255,107,44,.4)}
        
        /* Main container — everything centered & boxed */
        .main-container{
            max-width:1300px;
            margin:0 auto;
            padding:0 1.5rem;
        }
        
        /* Hero Section — inside a neat box to minimize space */
        .hero{
            position:relative;
            z-index:1;
            min-height:90vh;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:2rem;
            padding:6rem 2rem 3rem;
            background:rgba(15,23,42,.4);
            backdrop-filter:blur(2px);
            border-radius:2rem;
            margin-top:5rem;
            border:1px solid rgba(255,255,255,.05);
        }
        .hero-content{
            flex:1;
            max-width:550px;
        }
        .hero-visual{
            flex:1;
            display:flex;
            justify-content:center;
            align-items:center;
        }
        .hero-img{
            width:100%;
            max-width:400px;
            animation:floatImg 4s ease-in-out infinite;
            filter:drop-shadow(0 20px 35px rgba(255,107,44,.2));
        }
        @keyframes floatImg{0%,100%{transform:translateY(0)}50%{transform:translateY(-15px)}}
        
        .badge{
            display:inline-flex;align-items:center;gap:.5rem;
            background:rgba(255,107,44,.1);border:1px solid rgba(255,107,44,.2);
            padding:.3rem .9rem;border-radius:99px;margin-bottom:1.2rem;
        }
        .badge-dot{width:6px;height:6px;background:var(--orange);border-radius:50%;animation:pulse 2s infinite}
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:.5}}
        .badge-text{color:var(--orange);font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase}
        
        h1{
            font-family:'Space Grotesk',sans-serif;
            font-size:clamp(2.2rem,4.5vw,3.6rem);
            font-weight:800;line-height:1.15;margin-bottom:1rem;
        }
        h1 span{color:var(--orange)}
        
        .hero-desc{color:var(--muted);font-size:1rem;line-height:1.6;margin-bottom:1.8rem;max-width:450px}
        
        .hero-cta{display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:2rem}
        
        .hero-stats{display:flex;gap:2rem;padding-top:1.5rem;border-top:1px solid rgba(255,255,255,.06)}
        .stat-num{font-family:'Space Grotesk',sans-serif;font-size:1.6rem;font-weight:800;color:var(--orange)}
        .stat-label{font-size:.7rem;color:var(--muted);margin-top:.2rem}
        
        /* Other sections also boxed */
        section{
            padding:4rem 0;
            position:relative;
            z-index:1;
        }
        .container{
            max-width:1200px;
            margin:0 auto;
            padding:0 1rem;
        }
        .section-title{
            font-family:'Space Grotesk',sans-serif;
            font-size:1.8rem;
            font-weight:800;
            text-align:center;
            margin-bottom:2.5rem;
        }
        .features-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
            gap:1.5rem;
        }
        .feature-card{
            background:linear-gradient(135deg,rgba(15,23,42,.6),rgba(15,23,42,.3));
            border:1px solid rgba(255,255,255,.06);
            border-radius:1.5rem;
            padding:1.5rem;
            transition:.3s;
        }
        .feature-card:hover{border-color:rgba(255,107,44,.3);transform:translateY(-4px)}
        .feature-icon{
            width:50px;height:50px;border-radius:12px;
            background:linear-gradient(135deg,rgba(255,107,44,.15),rgba(255,107,44,.05));
            color:var(--orange);display:flex;align-items:center;justify-content:center;
            font-size:1.3rem;margin-bottom:1rem;
        }
        .feature-title{font-family:'Space Grotesk',sans-serif;font-size:1.1rem;font-weight:700;margin-bottom:.5rem}
        .feature-desc{font-size:.85rem;color:var(--muted);line-height:1.5}
        
        .steps-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
            gap:1.5rem;
            max-width:900px;
            margin:0 auto;
        }
        .step-card{text-align:center;background:rgba(15,23,42,.3);padding:1.2rem;border-radius:1.5rem;}
        .step-num{
            width:40px;height:40px;border-radius:50%;
            background:linear-gradient(135deg,var(--orange),var(--orange2));
            display:flex;align-items:center;justify-content:center;
            margin:0 auto 0.8rem;font-weight:800;
        }
        .step-title{font-weight:700;margin-bottom:.3rem;font-size:.95rem}
        .step-desc{font-size:.75rem;color:var(--muted)}
        
        .testimonial-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
            gap:1.5rem;
        }
        .testimonial-card{
            background:rgba(15,23,42,.5);
            border:1px solid rgba(255,255,255,.05);
            border-radius:1.5rem;
            padding:1.5rem;
        }
        .stars{color:#FFB800;margin-bottom:0.8rem;font-size:.8rem}
        .testimonial-text{color:var(--text);line-height:1.5;margin-bottom:1.2rem;font-style:italic;font-size:.9rem}
        .testimonial-author{display:flex;align-items:center;gap:.7rem}
        .author-avatar{
            width:38px;height:38px;border-radius:50%;
            background:linear-gradient(135deg,var(--orange),var(--orange2));
            display:flex;align-items:center;justify-content:center;
        }
        .author-name{font-weight:700;font-size:.85rem}
        .author-title{font-size:.7rem;color:var(--muted)}
        
        .cta-box{
            max-width:650px;
            margin:0 auto;
            background:linear-gradient(135deg,rgba(255,107,44,.1),rgba(15,23,42,.8));
            border:1px solid rgba(255,107,44,.2);
            border-radius:1.8rem;
            padding:2rem;
            text-align:center;
        }
        .cta-title{font-family:'Space Grotesk',sans-serif;font-size:1.6rem;font-weight:800;margin-bottom:.6rem}
        .cta-desc{color:var(--muted);margin-bottom:1.5rem;font-size:.9rem}
        
        footer{background:#050A12;padding:2rem 1.5rem 1rem;margin-top:2rem}
        .footer-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
            gap:1.5rem;
            max-width:1100px;
            margin:0 auto 1.5rem;
        }
        .footer-col h4{color:#fff;margin-bottom:0.8rem;font-size:.9rem}
        .footer-col a{display:block;color:var(--muted);text-decoration:none;margin-bottom:.4rem;font-size:.75rem;transition:.2s}
        .footer-col a:hover{color:var(--orange)}
        .social-links{display:flex;gap:.8rem;margin-top:0.8rem}
        .social-links a{background:rgba(255,255,255,.05);width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;transition:.2s}
        .social-links a:hover{background:var(--orange);color:#fff}
        .footer-bottom{text-align:center;padding-top:1.5rem;border-top:1px solid rgba(255,255,255,.05);color:var(--muted);font-size:.7rem}
        
        /* Responsive */
        @media(max-width:900px){
            nav{padding:0.8rem 1.2rem}
            .main-container{padding:0 1rem}
            .hero{
                flex-direction:column;
                text-align:center;
                padding:4rem 1.5rem;
                margin-top:4rem;
            }
            .hero-content{max-width:100%;text-align:center}
            .hero-desc{margin-left:auto;margin-right:auto}
            .hero-cta{justify-content:center}
            .hero-stats{justify-content:center}
            .hero-visual{max-width:280px;margin-top:1rem}
            section{padding:2.5rem 0}
            .section-title{font-size:1.5rem}
        }
        
        .fade-up{opacity:0;transform:translateY(20px);transition:opacity .5s ease,transform .5s ease}
        .fade-up.reveal{opacity:1;transform:none}
    </style>
</head>
<body>
<div class="bg-pattern"></div>

<nav>
    <div class="logo">
        <div class="logo-icon"><i class="fas fa-motorcycle"></i></div>
        KAS<span>Delivery</span>
    </div>
    <div class="nav-buttons">
        <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isDriver() ? route('driver.dashboard') : route('user.dashboard'))); ?>" class="btn btn-primary">Go to Dashboard</a>
            <form action="<?php echo e(route('logout')); ?>" method="POST" style="display:inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-outline">Logout</button>
            </form>
        <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" class="btn btn-outline">Sign In</a>
            <a href="<?php echo e(route('register')); ?>" class="btn btn-primary">Get Started</a>
        <?php endif; ?>
    </div>
</nav>

<div class="main-container">
    <!-- Hero Section inside a box to minimize space -->
    <div class="hero">
        <div class="hero-content">
            <div class="badge">
                <span class="badge-dot"></span>
                <span class="badge-text">Fastest Delivery App</span>
            </div>
            <h1>Kahit Ano Sayo,<br><span>Delivered Fast</span></h1>
            <p class="hero-desc">
                Get food, groceries, and essentials delivered to your doorstep in minutes. 
                Join thousands of happy customers across the city.
            </p>
            <div class="hero-cta">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isDriver() ? route('driver.dashboard') : route('user.dashboard'))); ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i> Open Dashboard
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i> Start Ordering
                    </a>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-outline">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </a>
                <?php endif; ?>
            </div>
            <div class="hero-stats">
                <div><div class="stat-num">15K+</div><div class="stat-label">Happy Customers</div></div>
                <div><div class="stat-num">200+</div><div class="stat-label">Partner Stores</div></div>
                <div><div class="stat-num">25min</div><div class="stat-label">Avg Delivery</div></div>
            </div>
        </div>
        <div class="hero-visual">
            <!-- Logo picture kept, lightning fast card removed -->
            <img src="<?php echo e(asset('images/hero-delivery.png')); ?>" alt="KAS Delivery" class="hero-img" onerror="this.onerror=null;this.src='https://placehold.co/450x450/0F172A/FF6B2C?text=KAS+Delivery';">
        </div>
    </div>

    <!-- Features Section -->
    <section id="features">
        <div class="container">
            <h2 class="section-title fade-up">Why Choose KAS Delivery</h2>
            <div class="features-grid">
                <div class="feature-card fade-up"><div class="feature-icon"><i class="fas fa-motorcycle"></i></div><h3 class="feature-title">Lightning Delivery</h3><p class="feature-desc">Your orders arrive in 25 minutes or less. Our riders are always nearby.</p></div>
                <div class="feature-card fade-up"><div class="feature-icon"><i class="fas fa-store"></i></div><h3 class="feature-title">200+ Partners</h3><p class="feature-desc">From fast food to gourmet meals — every craving covered.</p></div>
                <div class="feature-card fade-up"><div class="feature-icon"><i class="fas fa-shield-alt"></i></div><h3 class="feature-title">Secure Payments</h3><p class="feature-desc">Pay with GCash, card, or cash on delivery. Safe and easy.</p></div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="howitworks">
        <div class="container">
            <h2 class="section-title fade-up">How It Works</h2>
            <div class="steps-grid">
                <div class="step-card fade-up"><div class="step-num">1</div><h4 class="step-title">Browse</h4><p class="step-desc">Explore restaurants & stores</p></div>
                <div class="step-card fade-up"><div class="step-num">2</div><h4 class="step-title">Order</h4><p class="step-desc">Add items & checkout</p></div>
                <div class="step-card fade-up"><div class="step-num">3</div><h4 class="step-title">Track</h4><p class="step-desc">Follow your driver live</p></div>
                <div class="step-card fade-up"><div class="step-num">4</div><h4 class="step-title">Enjoy</h4><p class="step-desc">Receive at your doorstep</p></div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials">
        <div class="container">
            <h2 class="section-title fade-up">What Our Customers Say</h2>
            <div class="testimonial-grid">
                <div class="testimonial-card fade-up"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><p class="testimonial-text">"Super fast delivery! My food was still hot and the driver was very polite. Highly recommended!"</p><div class="testimonial-author"><div class="author-avatar"><i class="fas fa-user"></i></div><div><div class="author-name">Maria R.</div><div class="author-title">Makati</div></div></div></div>
                <div class="testimonial-card fade-up"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div><p class="testimonial-text">"Best delivery app in the city. Real-time tracking is accurate and the customer support is great."</p><div class="testimonial-author"><div class="author-avatar"><i class="fas fa-user"></i></div><div><div class="author-name">John C.</div><div class="author-title">BGC</div></div></div></div>
                <div class="testimonial-card fade-up"><div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></div><p class="testimonial-text">"Wide selection of restaurants and the app is very easy to use. Delivery always on time."</p><div class="testimonial-author"><div class="author-avatar"><i class="fas fa-user"></i></div><div><div class="author-name">Lisa T.</div><div class="author-title">Ortigas</div></div></div></div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section id="contact">
        <div class="container">
            <div class="cta-box fade-up">
                <h2 class="cta-title">Ready to Try KAS Delivery?</h2>
                <p class="cta-desc">Join thousands of satisfied customers. Get ₱50 off your first order!</p>
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isDriver() ? route('driver.dashboard') : route('user.dashboard'))); ?>" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Open Dashboard</a>
                <?php else: ?>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-primary"><i class="fas fa-user-plus"></i> Create Account</a>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<footer>
    <div class="footer-grid">
        <div class="footer-col"><h4>KAS Delivery</h4><a href="#">About Us</a><a href="#">Careers</a><a href="#">Press</a></div>
        <div class="footer-col"><h4>Support</h4><a href="#">Help Center</a><a href="#">Safety</a><a href="#">Contact Us</a></div>
        <div class="footer-col"><h4>Legal</h4><a href="#">Privacy Policy</a><a href="#">Terms of Service</a><a href="#">Cookie Policy</a></div>
        <div class="footer-col"><h4>Follow Us</h4><div class="social-links"><a href="#"><i class="fab fa-facebook-f"></i></a><a href="#"><i class="fab fa-instagram"></i></a><a href="#"><i class="fab fa-twitter"></i></a><a href="#"><i class="fab fa-tiktok"></i></a></div></div>
    </div>
    <div class="footer-bottom">© 2025 KAS Delivery. All rights reserved.</div>
</footer>

<script>
    const faders = document.querySelectorAll('.fade-up');
    const appearOptions = { threshold: 0.2, rootMargin: "0px 0px -30px 0px" };
    const appearOnScroll = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('reveal');
        });
    }, appearOptions);
    faders.forEach(fader => appearOnScroll.observe(fader));

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth' });
        });
    });
</script>
</body>
</html><?php /**PATH C:\xampp\htdocs\KAS-food-deliveryY\KAS-food-delivery\resources\views\welcome.blade.php ENDPATH**/ ?>
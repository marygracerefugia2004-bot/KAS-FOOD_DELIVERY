<!DOCTYPE html>
<html lang="en" class="{{ auth()->user()?->dark_mode ? 'dark' : '' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','KAS Delivery') — KAS</title>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* ── DESIGN TOKENS ───────────────────────────────────────── */
:root {
    --orange: #FF6B2C;
    --orange2: #FF8B00;
    --orange-glow: rgba(255,107,44,.13);
    --orange-glow2: rgba(255,107,44,.22);
    --navy: #0D1B4B;
    --navy2: #15277A;
    --navy3: #1E3399;
    --bg: #F2F5FB;
    --surface: #FFFFFF;
    --border: #E3E8F0;
    --border2: #CAD3E3;
    --text: #111827;
    --muted: #6B7280;
    --hint: #9CA3AF;
    --success: #10B981;
    --danger: #EF4444;
    --warning: #F59E0B;
    --info: #3B82F6;
    --r: 12px;
    --r2: 18px;
    --r3: 8px;
    --sidebar-w: 252px;
    --topbar-h: 60px;
    --font: 'Plus Jakarta Sans', sans-serif;
    --font2: 'Space Grotesk', sans-serif;
    --transition: .18s cubic-bezier(.4,0,.2,1);
}
.dark {
    --bg: #080D1A;
    --surface: #0F172A;
    --border: #1E2D4A;
    --border2: #263354;
    --text: #E2E8F0;
    --muted: #94A3B8;
    --hint: #475569;
}

/* ── RESET ───────────────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body { font-family: var(--font); background: var(--bg); color: var(--text); min-height: 100vh; display: flex; flex-direction: column; }
a { color: inherit; text-decoration: none; }
img { max-width: 100%; display: block; }
::-webkit-scrollbar { width: 4px; }
::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 4px; }

/* ── TOPBAR ──────────────────────────────────────────────── */
.topbar {
    height: var(--topbar-h);
    background: var(--navy);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1.25rem 0 0;
    position: sticky;
    top: 0;
    z-index: 300;
    flex-shrink: 0;
    border-bottom: 1px solid rgba(255,255,255,.06);
}
.topbar-brand {
    width: var(--sidebar-w);
    flex-shrink: 0;
    display: flex;
    align-items: center;
    gap: .65rem;
    padding: 0 1.25rem;
    font-family: var(--font2);
    font-weight: 700;
    font-size: 1.15rem;
    color: #fff;
    border-right: 1px solid rgba(255,255,255,.07);
    height: 100%;
    text-decoration: none;
    transition: background var(--transition);
}
.topbar-brand:hover { background: rgba(255,255,255,.04); }
.brand-logo {
    width: 33px;
    height: 33px;
    border-radius: 9px;
    background: linear-gradient(135deg, var(--orange), var(--orange2));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(255,107,44,.35);
}
.brand-name { color: #fff; letter-spacing: -.01em; }
.brand-name span { color: var(--orange); }

.topbar-center { flex: 1; padding: 0 1.5rem; }
.topbar-page-title {
    font-family: var(--font2);
    font-weight: 600;
    font-size: .88rem;
    color: rgba(255,255,255,.5);
    letter-spacing: .01em;
}

.topbar-right { display: flex; align-items: center; gap: .35rem; }

/* Topbar icon buttons */
.t-btn {
    width: 36px;
    height: 36px;
    border-radius: 9px;
    border: 1px solid rgba(255,255,255,.1);
    background: none;
    color: rgba(255,255,255,.6);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .82rem;
    transition: background var(--transition), color var(--transition), border-color var(--transition);
    position: relative;
    text-decoration: none;
}
.t-btn:hover { background: rgba(255,255,255,.1); color: #fff; border-color: rgba(255,255,255,.2); }

/* Badge on icon buttons */
.t-btn-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: var(--orange);
    color: #fff;
    border-radius: 99px;
    padding: 1px 5px;
    font-size: .58rem;
    font-weight: 800;
    min-width: 16px;
    text-align: center;
    line-height: 1.5;
    display: none;
    border: 2px solid var(--navy);
    pointer-events: none;
}

/* Divider between topbar sections */
.t-divider {
    width: 1px;
    height: 20px;
    background: rgba(255,255,255,.1);
    margin: 0 .15rem;
    flex-shrink: 0;
}

/* User pill in topbar */
.t-user-pill {
    display: flex;
    align-items: center;
    gap: .5rem;
    padding: .3rem .6rem .3rem .3rem;
    border-radius: 99px;
    border: 1px solid rgba(255,255,255,.1);
    cursor: pointer;
    transition: background var(--transition);
    text-decoration: none;
}
.t-user-pill:hover { background: rgba(255,255,255,.08); }
.t-initials {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--orange), var(--orange2));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 800;
    font-size: .68rem;
    flex-shrink: 0;
}
.t-user-name {
    font-size: .78rem;
    font-weight: 700;
    color: rgba(255,255,255,.85);
    max-width: 110px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.t-role-badge {
    padding: .18rem .55rem;
    border-radius: 99px;
    font-size: .62rem;
    font-weight: 800;
    letter-spacing: .03em;
    background: rgba(255,107,44,.2);
    color: var(--orange);
    border: 1px solid rgba(255,107,44,.3);
    white-space: nowrap;
}

/* ── APP SHELL ───────────────────────────────────────────── */
.app-shell { display: flex; flex: 1; min-height: calc(100vh - var(--topbar-h)); }

/* ── SIDEBAR ─────────────────────────────────────────────── */
.sidebar {
    width: var(--sidebar-w);
    flex-shrink: 0;
    background: var(--navy);
    display: flex;
    flex-direction: column;
    position: sticky;
    top: var(--topbar-h);
    height: calc(100vh - var(--topbar-h));
    overflow-y: auto;
    overflow-x: hidden;
    border-right: 1px solid rgba(255,255,255,.06);
    transition: transform .25s cubic-bezier(.4,0,.2,1);
}
.sidebar::-webkit-scrollbar { width: 0; }

.sidebar-section {
    font-size: .62rem;
    font-weight: 800;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: rgba(255,255,255,.25);
    padding: 1rem 1.3rem .3rem;
}

.sidebar-link {
    display: flex;
    align-items: center;
    gap: .65rem;
    padding: .52rem 1.3rem;
    font-size: .82rem;
    font-weight: 600;
    color: rgba(255,255,255,.5);
    transition: color var(--transition), background var(--transition), border-color var(--transition);
    border-left: 3px solid transparent;
    position: relative;
    cursor: pointer;
    text-decoration: none;
    background: none;
    border-top: none;
    border-right: none;
    border-bottom: none;
    width: 100%;
    text-align: left;
    font-family: var(--font);
}
.sidebar-link:hover {
    color: rgba(255,255,255,.88);
    background: rgba(255,255,255,.05);
}
.sidebar-link.active {
    color: #fff;
    background: rgba(255,107,44,.14);
    border-left-color: var(--orange);
}
.sidebar-link .sl-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .78rem;
    flex-shrink: 0;
    transition: background var(--transition), color var(--transition);
    color: rgba(255,255,255,.4);
}
.sidebar-link:hover .sl-icon { background: rgba(255,255,255,.08); color: rgba(255,255,255,.75); }
.sidebar-link.active .sl-icon { background: rgba(255,107,44,.22); color: var(--orange); }

.sidebar-badge {
    margin-left: auto;
    background: var(--orange);
    color: #fff;
    border-radius: 99px;
    padding: 1px 7px;
    font-size: .62rem;
    font-weight: 800;
    flex-shrink: 0;
    animation: badge-pulse 2s infinite;
}
@keyframes badge-pulse { 0%,100% { box-shadow: 0 0 0 0 rgba(255,107,44,.4); } 50% { box-shadow: 0 0 0 4px rgba(255,107,44,0); } }

.sidebar-divider { height: 1px; background: rgba(255,255,255,.06); margin: .4rem 1.3rem; }

/* Sidebar bottom user card */
.sidebar-user-card {
    margin: auto 1rem 1rem;
    padding: .85rem 1rem;
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.08);
    border-radius: var(--r);
    display: flex;
    align-items: center;
    gap: .6rem;
}
.suc-initials {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--orange), var(--orange2));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 800;
    font-size: .7rem;
    flex-shrink: 0;
}
.suc-info { flex: 1; min-width: 0; }
.suc-name { font-size: .8rem; font-weight: 700; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.suc-role { font-size: .67rem; color: rgba(255,255,255,.38); text-transform: capitalize; margin-top: 1px; }
.suc-logout {
    background: none;
    border: none;
    color: rgba(255,255,255,.35);
    cursor: pointer;
    font-size: .8rem;
    padding: .25rem;
    border-radius: 6px;
    transition: color var(--transition), background var(--transition);
    flex-shrink: 0;
}
.suc-logout:hover { color: var(--danger); background: rgba(239,68,68,.1); }

/* ── SIDEBAR MOBILE OVERLAY ──────────────────────────────── */
.sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.5);
    z-index: 249;
    backdrop-filter: blur(2px);
    -webkit-backdrop-filter: blur(2px);
    animation: fadeIn .2s ease;
}
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
.sidebar-overlay.visible { display: block; }

/* ── MAIN CONTENT ────────────────────────────────────────── */
.main { flex: 1; padding: 1.75rem; overflow-y: auto; max-width: 100%; }

/* ── PAGE HEADER ─────────────────────────────────────────── */
.page-hdr { margin-bottom: 1.75rem; display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
.page-hdr-left h1 { font-family: var(--font2); font-size: 1.35rem; font-weight: 700; line-height: 1.2; }
.page-hdr-left p { color: var(--muted); font-size: .83rem; margin-top: .25rem; }
.page-breadcrumb { display: flex; align-items: center; gap: .4rem; font-size: .75rem; color: var(--muted); margin-bottom: .4rem; }
.page-breadcrumb a { color: var(--orange); }
.page-breadcrumb span { opacity: .45; }

/* ── CARDS ───────────────────────────────────────────────── */
.card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r2); padding: 1.5rem; }
.card-sm { padding: 1rem 1.2rem; }
.card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.2rem; }
.card-title { font-family: var(--font2); font-size: .92rem; font-weight: 700; display: flex; align-items: center; gap: .5rem; color: var(--text); }
.card-icon { width: 28px; height: 28px; border-radius: 7px; display: flex; align-items: center; justify-content: center; font-size: .75rem; background: var(--orange-glow); color: var(--orange); flex-shrink: 0; }

/* ── STATS ───────────────────────────────────────────────── */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(145px, 1fr)); gap: 1rem; margin-bottom: 1.75rem; }
.stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 1.1rem 1.2rem;
    transition: border-color var(--transition), transform var(--transition), box-shadow var(--transition);
    position: relative;
    overflow: hidden;
    cursor: default;
}
.stat-card::after { content: ''; position: absolute; top: -15px; right: -15px; width: 60px; height: 60px; border-radius: 50%; background: var(--orange-glow); pointer-events: none; }
.stat-card:hover { border-color: rgba(255,107,44,.4); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,107,44,.08); }
.stat-ico { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: .85rem; margin-bottom: .7rem; flex-shrink: 0; }
.ico-orange { background: #FFF1EB; color: var(--orange); } .dark .ico-orange { background: rgba(255,107,44,.15); }
.ico-navy   { background: #EEF2FF; color: var(--navy); }  .dark .ico-navy   { background: rgba(30,51,153,.3); color: #93C5FD; }
.ico-green  { background: #D1FAE5; color: #059669; }      .dark .ico-green  { background: rgba(16,185,129,.12); color: #34D399; }
.ico-red    { background: #FEE2E2; color: #DC2626; }      .dark .ico-red    { background: rgba(239,68,68,.12); color: #F87171; }
.stat-val { font-family: var(--font2); font-size: 1.55rem; font-weight: 700; line-height: 1; color: var(--text); }
.stat-lbl { font-size: .72rem; font-weight: 600; color: var(--muted); margin-top: .3rem; text-transform: uppercase; letter-spacing: .04em; }

/* ── BUTTONS ─────────────────────────────────────────────── */
.btn { display: inline-flex; align-items: center; justify-content: center; gap: .42rem; padding: .52rem 1.15rem; border-radius: var(--r3); font-family: var(--font); font-weight: 700; font-size: .83rem; cursor: pointer; border: none; transition: all var(--transition); line-height: 1; white-space: nowrap; text-decoration: none; }
.btn:active { transform: scale(.96); }
.btn-primary { background: linear-gradient(135deg, var(--orange) 0%, var(--orange2) 100%); color: #fff; box-shadow: 0 3px 10px rgba(255,107,44,.3); }
.btn-primary:hover { box-shadow: 0 5px 18px rgba(255,107,44,.4); filter: brightness(1.05); }
.btn-navy { background: var(--navy); color: #fff; }
.btn-navy:hover { background: var(--navy2); }
.btn-outline { background: transparent; border: 1.5px solid var(--border2); color: var(--text); }
.btn-outline:hover { border-color: var(--orange); color: var(--orange); background: var(--orange-glow); }
.btn-success { background: #10B981; color: #fff; } .btn-success:hover { background: #059669; }
.btn-danger  { background: #EF4444; color: #fff; } .btn-danger:hover  { background: #DC2626; }
.btn-sm  { padding: .35rem .8rem; font-size: .76rem; border-radius: 6px; }
.btn-lg  { padding: .72rem 1.6rem; font-size: .92rem; border-radius: 10px; }
.btn-block { width: 100%; }
.btn-icon { width: 34px; height: 34px; padding: 0; border-radius: 8px; flex-shrink: 0; }

/* ── FORMS ───────────────────────────────────────────────── */
.form-group { margin-bottom: 1.1rem; }
.form-label { display: block; font-weight: 700; font-size: .75rem; margin-bottom: .4rem; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; }
.form-control { width: 100%; padding: .6rem .9rem; border: 1.5px solid var(--border); border-radius: 8px; font-size: .87rem; background: var(--surface); color: var(--text); transition: border-color var(--transition), box-shadow var(--transition); font-family: var(--font); }
.form-control:focus { outline: none; border-color: var(--orange); box-shadow: 0 0 0 3px rgba(255,107,44,.1); }
.form-control::placeholder { color: var(--hint); }
.form-error { color: var(--danger); font-size: .76rem; margin-top: .3rem; font-weight: 600; }

/* ── TABLE ───────────────────────────────────────────────── */
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
thead th { background: var(--bg); padding: .65rem 1rem; font-size: .71rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); text-align: left; border-bottom: 1px solid var(--border); }
tbody td { padding: .78rem 1rem; border-bottom: 1px solid var(--border); font-size: .84rem; vertical-align: middle; }
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover { background: rgba(0,0,0,.015); }
.dark tbody tr:hover { background: rgba(255,255,255,.015); }

/* ── BADGES ──────────────────────────────────────────────── */
.badge { display: inline-flex; align-items: center; gap: .28rem; padding: .2rem .6rem; border-radius: 99px; font-size: .68rem; font-weight: 800; text-transform: capitalize; letter-spacing: .02em; }
.badge-pending         { background: #FEF3C7; color: #92400E; }
.badge-assigned        { background: #DBEAFE; color: #1E40AF; }
.badge-out_for_delivery { background: #FEF9C3; color: #854D0E; }
.badge-delivered       { background: #D1FAE5; color: #065F46; }
.badge-cancelled       { background: #FEE2E2; color: #991B1B; }
.badge-live { animation: badge-pulse 1.5s infinite; }
.badge-user   { background: #FFF7ED; color: #C2410C; }
.badge-driver { background: #EEF2FF; color: #3730A3; }
.badge-admin  { background: #F3E8FF; color: #7E22CE; }

/* ── STATUS TIMELINE ─────────────────────────────────────── */
.timeline { display: flex; align-items: center; gap: .2rem; margin: 1rem 0; }
.tl-step { display: flex; flex-direction: column; align-items: center; gap: .35rem; flex: 1; min-width: 0; }
.tl-dot { width: 34px; height: 34px; border-radius: 50%; border: 2px solid var(--border2); display: flex; align-items: center; justify-content: center; font-size: .75rem; color: var(--muted); background: var(--bg); transition: .3s; flex-shrink: 0; }
.tl-dot.done { border-color: var(--orange); background: var(--orange); color: #fff; box-shadow: 0 0 0 3px rgba(255,107,44,.15); }
.tl-dot.active { border-color: var(--orange); color: var(--orange); animation: tl-pulse 1.8s infinite; }
@keyframes tl-pulse { 0%,100% { box-shadow: 0 0 0 0 rgba(255,107,44,.4); } 60% { box-shadow: 0 0 0 7px rgba(255,107,44,0); } }
.tl-label { font-size: .64rem; font-weight: 700; color: var(--muted); text-align: center; line-height: 1.3; }
.tl-line { flex: 1; height: 2px; background: var(--border); border-radius: 1px; min-width: 8px; }
.tl-line.done { background: var(--orange); }

/* ── COUNTDOWN ───────────────────────────────────────────── */
.countdown { display: flex; gap: .75rem; justify-content: center; margin: 1rem 0; }
.cd-block { text-align: center; background: var(--navy); color: #fff; border-radius: 10px; padding: .65rem 1rem; min-width: 64px; }
.dark .cd-block { background: rgba(255,107,44,.12); border: 1px solid rgba(255,107,44,.2); }
.cd-num { font-family: var(--font2); font-size: 1.75rem; font-weight: 700; color: var(--orange); line-height: 1; }
.cd-lbl { font-size: .6rem; font-weight: 700; opacity: .6; text-transform: uppercase; margin-top: .2rem; letter-spacing: .05em; }

/* ── MAP BOX ─────────────────────────────────────────────── */
.map-box { width: 100%; height: 360px; border-radius: var(--r); overflow: hidden; border: 1px solid var(--border); }

/* ── FOOD GRID ───────────────────────────────────────────── */
.food-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.25rem; }
.food-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r2); overflow: hidden; transition: border-color var(--transition), transform var(--transition), box-shadow var(--transition); cursor: pointer; }
.food-card:hover { border-color: rgba(255,107,44,.4); transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.07); }
.food-img { width: 100%; height: 160px; object-fit: cover; transition: transform .3s ease; }
.food-card:hover .food-img { transform: scale(1.04); }
.food-img-ph { width: 100%; height: 160px; background: linear-gradient(135deg, var(--navy), var(--navy2)); display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,.12); font-size: 2.5rem; }
.food-body { padding: .95rem 1rem; }
.food-name { font-family: var(--font2); font-weight: 700; font-size: .88rem; margin-bottom: .25rem; line-height: 1.3; }
.food-desc { font-size: .75rem; color: var(--muted); margin-bottom: .5rem; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.food-footer { display: flex; align-items: center; justify-content: space-between; margin-top: .6rem; }
.food-price { font-family: var(--font2); font-size: 1rem; font-weight: 700; color: var(--orange); }
.food-actions { display: flex; gap: .4rem; margin-top: .6rem; }
.fav-btn { width: 32px; height: 32px; padding: 0; border-radius: 7px; border: 1.5px solid var(--border); background: transparent; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all var(--transition); color: var(--muted); }
.fav-btn:hover, .fav-btn.active { border-color: var(--orange); color: var(--orange); background: var(--orange-glow); }

/* ── CHAT ────────────────────────────────────────────────── */
.chat-wrap { display: flex; height: calc(100vh - var(--topbar-h) - 3.5rem); gap: 0; background: var(--surface); border: 1px solid var(--border); border-radius: var(--r2); overflow: hidden; }
.chat-sidebar { width: 260px; border-right: 1px solid var(--border); display: flex; flex-direction: column; flex-shrink: 0; }
.chat-sidebar-hdr { padding: .9rem 1rem; border-bottom: 1px solid var(--border); font-family: var(--font2); font-weight: 700; font-size: .9rem; }
.chat-contacts { flex: 1; overflow-y: auto; }
.chat-contact { display: flex; align-items: center; gap: .65rem; padding: .7rem 1rem; cursor: pointer; transition: background var(--transition); border-left: 3px solid transparent; }
.chat-contact:hover { background: var(--bg); }
.chat-contact.active { background: var(--orange-glow); border-left-color: var(--orange); }
.chat-contact-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--orange), var(--orange2)); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 800; font-size: .78rem; flex-shrink: 0; }
.chat-contact-name { font-size: .83rem; font-weight: 700; line-height: 1.2; }
.chat-contact-role { font-size: .7rem; color: var(--muted); }
.chat-contact-unread { background: var(--orange); color: #fff; border-radius: 99px; padding: 1px 6px; font-size: .62rem; font-weight: 800; margin-left: auto; }
.chat-main { flex: 1; display: flex; flex-direction: column; min-width: 0; }
.chat-hdr { padding: .9rem 1.2rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: .65rem; }
.chat-hdr-avatar { width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, var(--navy), var(--navy2)); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 800; font-size: .82rem; flex-shrink: 0; }
.chat-hdr-name { font-weight: 700; font-size: .9rem; }
.chat-hdr-status { font-size: .72rem; color: var(--muted); }
.chat-messages { flex: 1; overflow-y: auto; padding: 1rem 1.2rem; display: flex; flex-direction: column; gap: .6rem; }
.msg-row { display: flex; gap: .5rem; align-items: flex-end; }
.msg-row.mine { flex-direction: row-reverse; }
.msg-avatar { width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: .65rem; font-weight: 800; color: #fff; background: var(--navy); }
.msg-row.mine .msg-avatar { background: var(--orange); }
.msg-bubble { max-width: 68%; padding: .55rem .9rem; border-radius: 14px; font-size: .83rem; line-height: 1.5; }
.msg-row.mine .msg-bubble { background: var(--orange); color: #fff; border-bottom-right-radius: 4px; }
.msg-row:not(.mine) .msg-bubble { background: var(--bg); border: 1px solid var(--border); color: var(--text); border-bottom-left-radius: 4px; }
.dark .msg-row:not(.mine) .msg-bubble { background: rgba(255,255,255,.05); border-color: var(--border); }
.msg-time { font-size: .65rem; color: var(--muted); margin-top: .15rem; }
.msg-row.mine .msg-time { text-align: right; }
.chat-input-area { padding: .8rem 1.2rem; border-top: 1px solid var(--border); display: flex; gap: .5rem; align-items: center; }
.chat-input { flex: 1; padding: .6rem .9rem; border: 1.5px solid var(--border); border-radius: 99px; font-size: .84rem; background: var(--bg); color: var(--text); font-family: var(--font); transition: border-color var(--transition); }
.chat-input:focus { outline: none; border-color: var(--orange); }
.chat-send-btn { width: 38px; height: 38px; border-radius: 50%; background: var(--orange); color: #fff; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: .85rem; transition: background var(--transition); flex-shrink: 0; }
.chat-send-btn:hover { background: var(--orange2); }
.chat-send-btn:active { transform: scale(.93); }
.chat-empty { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--muted); gap: .5rem; font-size: .85rem; }
.chat-empty-icon { font-size: 2.5rem; color: var(--border2); }

/* ── ALERT ───────────────────────────────────────────────── */
.alert { padding: .75rem 1rem; border-radius: var(--r3); font-weight: 600; font-size: .84rem; margin-bottom: 1rem; display: flex; align-items: center; gap: .55rem; border-width: 1px; border-style: solid; }
.alert-success { background: #D1FAE5; color: #065F46; border-color: #A7F3D0; }
.alert-danger  { background: #FEE2E2; color: #991B1B; border-color: #FECACA; }
.alert-info    { background: #DBEAFE; color: #1E40AF; border-color: #BFDBFE; }
.alert-warning { background: #FEF3C7; color: #92400E; border-color: #FDE68A; }

/* ── DRIVER ORDER CARD ───────────────────────────────────── */
.order-delivery-card { border: 1px solid var(--border); border-radius: var(--r2); overflow: hidden; transition: border-color var(--transition), box-shadow var(--transition); background: var(--surface); }
.order-delivery-card.active-delivery { border-color: rgba(255,107,44,.4); box-shadow: 0 4px 16px rgba(255,107,44,.1); }
.odc-header { background: linear-gradient(135deg, var(--navy), var(--navy2)); padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; }
.odc-body { padding: 1.1rem 1.25rem; }
.odc-footer { padding: .75rem 1.25rem; border-top: 1px solid var(--border); display: flex; gap: .5rem; flex-wrap: wrap; background: var(--bg); }

/* ── ANIMATIONS ──────────────────────────────────────────── */
@keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: none; } }
.fade-up   { animation: fadeUp .35s ease forwards; }
.fade-up-1 { animation: fadeUp .35s ease .05s forwards; opacity: 0; }
.fade-up-2 { animation: fadeUp .35s ease .10s forwards; opacity: 0; }
.fade-up-3 { animation: fadeUp .35s ease .15s forwards; opacity: 0; }
.fade-up-4 { animation: fadeUp .35s ease .20s forwards; opacity: 0; }

/* ── TOAST ───────────────────────────────────────────────── */
#toast-area {
    position: fixed;
    top: calc(var(--topbar-h) + 12px);
    right: 1.25rem;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: .5rem;
    pointer-events: none;
}
.toast {
    padding: .7rem 1.1rem;
    border-radius: 10px;
    font-size: .83rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: .55rem;
    max-width: 320px;
    pointer-events: auto;
    animation: toastIn .3s cubic-bezier(.4,0,.2,1);
    border: 1px solid;
    box-shadow: 0 4px 16px rgba(0,0,0,.1);
}
@keyframes toastIn { from { opacity: 0; transform: translateX(24px); } to { opacity: 1; transform: none; } }
.toast-success { background: #D1FAE5; color: #065F46; border-color: #A7F3D0; }
.toast-danger  { background: #FEE2E2; color: #991B1B; border-color: #FECACA; }

/* ── MOBILE ──────────────────────────────────────────────── */
@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        left: 0;
        top: var(--topbar-h);
        height: calc(100vh - var(--topbar-h));
        transform: translateX(-100%);
        z-index: 250;
    }
    .sidebar.open { transform: translateX(0); }
    .topbar-brand { width: auto; border-right: none; }
    .brand-name { display: none; }
    .topbar-page-title { display: none; }
    .t-user-name { display: none; }
    .main { padding: 1rem; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .food-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); }
    .chat-sidebar { display: none; }
    .chat-wrap { height: 70vh; }
}
</style>
@stack('styles')

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
</head>
<body>

@if(!isset($hideTopbar))
{{-- ── TOPBAR ──────────────────────────────────────────────── --}}
<header class="topbar">

    {{-- Brand --}}
    <a href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isDriver() ? route('driver.dashboard') : route('user.dashboard'))) : '/' }}" class="topbar-brand">
        <div class="brand-logo"><i class="fas fa-motorcycle"></i></div>
        <span class="brand-name">KAS<span>Delivery</span></span>
    </a>

    {{-- Page title (desktop) --}}
    <div class="topbar-center">
        <span class="topbar-page-title">@yield('page-title', '')</span>
    </div>

    {{-- Right actions --}}
    <div class="topbar-right">
        @auth

        {{-- Cart (users only) --}}
        @if(auth()->user()->isUser())
        <a href="{{ route('user.cart') }}" class="t-btn" title="Cart" style="position:relative">
            <i class="fas fa-shopping-cart"></i>
            <span class="t-btn-badge" id="cart-count">0</span>
        </a>
        @endif

        {{-- Messages --}}
        <a href="{{ route('messages.index') }}" class="t-btn" title="Messages">
            <i class="fas fa-comments"></i>
        </a>

        {{-- Notifications --}}
        <a href="{{ route('notifications.index') }}" class="t-btn" title="Notifications" style="position:relative">
            <i class="fas fa-bell"></i>
            <span class="t-btn-badge" id="notif-count">0</span>
        </a>

        <div class="t-divider"></div>

        {{-- Role badge --}}
        <span class="t-role-badge">{{ ucfirst(auth()->user()->role) }}</span>

        {{-- User pill --}}
        <a href="{{ auth()->user()->isAdmin() ? '#' : (auth()->user()->isDriver() ? route('driver.profile') : route('user.profile')) }}" class="t-user-pill" title="Profile">
            <div class="t-initials">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <span class="t-user-name">{{ auth()->user()->name }}</span>
        </a>

        {{-- Mobile hamburger --}}
        <button class="t-btn" id="mob-menu-btn" onclick="toggleMobSidebar()" title="Menu" aria-label="Toggle menu">
            <i class="fas fa-bars" id="mob-menu-icon"></i>
        </button>

        @endauth
    </div>
</header>
@endif

{{-- ── TOAST AREA ──────────────────────────────────────────── --}}
<div id="toast-area">
    @if(session('success'))
        <div class="toast toast-success fade-up"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="toast toast-danger fade-up"><i class="fas fa-exclamation-circle"></i>{{ session('error') }}</div>
    @endif
</div>

@auth

{{-- Mobile sidebar overlay --}}
<div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleMobSidebar()"></div>

{{-- ── APP SHELL ───────────────────────────────────────────── --}}
<div class="app-shell">

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">

        {{-- ── USER SIDEBAR ── --}}
        @if(auth()->user()->isUser())
        <div class="sidebar-section">Menu</div>
        <a href="{{ route('user.dashboard') }}"       class="sidebar-link @yield('sl-dashboard')"><span class="sl-icon"><i class="fas fa-home"></i></span>Dashboard</a>
        <a href="{{ route('user.foods') }}"           class="sidebar-link @yield('sl-foods')"><span class="sl-icon"><i class="fas fa-utensils"></i></span>Browse Food</a>
        <a href="{{ route('user.orders.history') }}"  class="sidebar-link @yield('sl-orders')"><span class="sl-icon"><i class="fas fa-box"></i></span>My Orders</a>
        <a href="{{ route('user.favorites') }}"       class="sidebar-link @yield('sl-favs')"><span class="sl-icon"><i class="fas fa-heart"></i></span>Favorites</a>

        <div class="sidebar-divider"></div>
        <div class="sidebar-section">Account</div>

        <a href="{{ route('messages.index') }}" class="sidebar-link @yield('sl-messages')">
            <span class="sl-icon"><i class="fas fa-comments"></i></span>Messages
            @php
                try { $unreadMsgs = auth()->user()->recvMessages()->where('is_read', false)->count(); }
                catch (\Exception $e) { $unreadMsgs = 0; }
            @endphp
            @if($unreadMsgs > 0)<span class="sidebar-badge">{{ $unreadMsgs }}</span>@endif
        </a>
        <a href="{{ route('notifications.index') }}"  class="sidebar-link @yield('sl-notifs')"><span class="sl-icon"><i class="fas fa-bell"></i></span>Notifications</a>
        <a href="{{ route('user.profile') }}"         class="sidebar-link @yield('sl-profile')"><span class="sl-icon"><i class="fas fa-user"></i></span>Profile</a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-link"><span class="sl-icon"><i class="fas fa-sign-out-alt"></i></span>Logout</button>
        </form>

        {{-- ── DRIVER SIDEBAR ── --}}
        @elseif(auth()->user()->isDriver())
        <div class="sidebar-section">Work</div>
        <a href="{{ route('driver.dashboard') }}" class="sidebar-link @yield('sl-dashboard')"><span class="sl-icon"><i class="fas fa-motorcycle"></i></span>Dashboard</a>
        <a href="{{ route('driver.earnings') }}"  class="sidebar-link @yield('sl-earnings')"><span class="sl-icon"><i class="fas fa-money-bill-wave"></i></span>Earnings</a>
        <a href="{{ route('driver.stats') }}"     class="sidebar-link @yield('sl-stats')"><span class="sl-icon"><i class="fas fa-chart-line"></i></span>Statistics</a>

        <div class="sidebar-divider"></div>
        <div class="sidebar-section">Vehicle & Profile</div>
        <a href="{{ route('driver.profile') }}"       class="sidebar-link @yield('sl-profile')"><span class="sl-icon"><i class="fas fa-id-card"></i></span>Profile</a>
        <a href="{{ route('driver.vehicle.index') }}" class="sidebar-link @yield('sl-vehicle')"><span class="sl-icon"><i class="fas fa-wrench"></i></span>Maintenance</a>

        <div class="sidebar-divider"></div>

        <a href="{{ route('messages.index') }}"   class="sidebar-link @yield('sl-messages')"><span class="sl-icon"><i class="fas fa-comments"></i></span>Messages</a>
        <a href="{{ route('notifications.index') }}" class="sidebar-link @yield('sl-notifs')"><span class="sl-icon"><i class="fas fa-bell"></i></span>Notifications</a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-link"><span class="sl-icon"><i class="fas fa-sign-out-alt"></i></span>Logout</button>
        </form>

        {{-- ── ADMIN SIDEBAR ── --}}
        @elseif(auth()->user()->isAdmin())
        @php $pendingCount = \App\Models\DriverRequest::where('status', 'pending')->count(); @endphp

        <div class="sidebar-section">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link @yield('sl-dashboard')"><span class="sl-icon"><i class="fas fa-chart-pie"></i></span>Dashboard</a>
        <a href="{{ route('admin.monitor') }}"   class="sidebar-link @yield('sl-monitor')"><span class="sl-icon"><i class="fas fa-satellite-dish"></i></span>Live Monitor</a>

        <div class="sidebar-divider"></div>
        <div class="sidebar-section">Manage</div>
        <a href="{{ route('admin.foods.index') }}"  class="sidebar-link @yield('sl-foods')"><span class="sl-icon"><i class="fas fa-hamburger"></i></span>Foods</a>
        <a href="{{ route('admin.orders.index') }}" class="sidebar-link @yield('sl-orders')"><span class="sl-icon"><i class="fas fa-box"></i></span>Orders</a>
        <a href="{{ route('admin.users') }}"        class="sidebar-link @yield('sl-users')"><span class="sl-icon"><i class="fas fa-users"></i></span>Users</a>
        <a href="{{ route('admin.drivers') }}"      class="sidebar-link @yield('sl-drivers')"><span class="sl-icon"><i class="fas fa-motorcycle"></i></span>Drivers</a>
        
            

        <div class="sidebar-divider"></div>
        <div class="sidebar-section">System</div>
        <a href="{{ route('admin.audit-logs') }}"    class="sidebar-link @yield('sl-audit')"><span class="sl-icon"><i class="fas fa-history"></i></span>Audit Logs</a>
        <a href="{{ route('admin.suspicious') }}"    class="sidebar-link @yield('sl-security')"><span class="sl-icon"><i class="fas fa-shield-alt"></i></span>Security</a>
        <a href="{{ route('admin.reports') }}"       class="sidebar-link @yield('sl-reports')"><span class="sl-icon"><i class="fas fa-chart-line"></i></span>Reports</a>
        <a href="{{ route('admin.promos.index') }}"  class="sidebar-link @yield('sl-promos')"><span class="sl-icon"><i class="fas fa-ticket-alt"></i></span>Promo Codes</a>
        <a href="{{ route('messages.index') }}"      class="sidebar-link @yield('sl-messages')"><span class="sl-icon"><i class="fas fa-comments"></i></span>Messages</a>
    
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-link"><span class="sl-icon"><i class="fas fa-sign-out-alt"></i></span>Logout</button>
        </form>
        @endif

        {{-- Spacer --}}
        <div style="flex: 1; min-height: 1rem;"></div>

        {{-- User card --}}
        <div class="sidebar-user-card">
            <div class="suc-initials">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div class="suc-info">
                <div class="suc-name">{{ auth()->user()->name }}</div>
                <div class="suc-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="suc-logout" title="Logout"><i class="fas fa-sign-out-alt"></i></button>
            </form>
        </div>

    </aside>

    {{-- MAIN CONTENT --}}
    <main class="main" id="main-content">
        @yield('content')
    </main>
</div>

@else
{{-- Content for non-authenticated users --}}
@yield('content')
@endauth

<script>
function toggleDark() {
    fetch('{{ route("dark-mode") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify({ dark_mode: !document.documentElement.classList.contains('dark') })
    }).then(() => location.reload());
}

function toggleMobSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const icon    = document.getElementById('mob-menu-icon');
    const isOpen  = sidebar.classList.toggle('open');
    overlay.classList.toggle('visible', isOpen);
    icon.className = isOpen ? 'fas fa-times' : 'fas fa-bars';
    document.body.style.overflow = isOpen ? 'hidden' : '';
}

// Auto-dismiss toasts
document.querySelectorAll('.toast').forEach(t => {
    setTimeout(() => {
        t.style.transition = 'opacity .3s ease, transform .3s ease';
        t.style.opacity    = '0';
        t.style.transform  = 'translateX(20px)';
        setTimeout(() => t.remove(), 320);
    }, 3500);
});

@auth

// Notification polling
(function pollNotifs() {
    fetch('{{ route("notifications.count") }}', { credentials: 'same-origin' })
        .then(r => { if (r.status === 419) return window.location.reload(); return r.json(); })
        .then(d => {
            const el = document.getElementById('notif-count');
            if (el) { el.style.display = d.count > 0 ? 'flex' : 'none'; el.textContent = d.count; }
        })
        .catch(() => {});
    setTimeout(pollNotifs, 30000);
})();

@if(auth()->user()->isUser())
// Cart count polling (users only)
(function pollCart() {
    fetch('{{ route("user.cart.count") }}', { credentials: 'same-origin' })
        .then(r => { if (r.status === 419) return window.location.reload(); return r.json(); })
        .then(d => {
            const el = document.getElementById('cart-count');
            if (el) { el.style.display = d.count > 0 ? 'flex' : 'none'; el.textContent = d.count; }
        })
        .catch(() => {});
    setTimeout(pollCart, 10000);
})();
@endif

@endauth
</script>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" style="display: none; position: fixed; inset: 0; z-index: 1000; align-items: center; justify-content: center; background: rgba(0,0,0,0.5);">
    <div style="background: var(--surface); border-radius: 12px; padding: 1.5rem; max-width: 400px; width: 90%; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <h3 style="margin-bottom: 0.5rem; font-size: 1.25rem;">Confirm Logout</h3>
        <p style="color: var(--muted); margin-bottom: 1.5rem;">Are you sure you want to log out?</p>
        <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
            <button type="button" onclick="closeLogoutModal()" class="btn btn-outline">Cancel</button>
            <button type="button" onclick="document.getElementById('logoutForm').submit()" class="btn btn-danger">Logout</button>
        </div>
    </div>
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>

<script>
function showLogoutModal(e) {
    e.preventDefault();
    document.getElementById('logoutModal').style.display = 'flex';
}

function closeLogoutModal() {
    document.getElementById('logoutModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('logoutModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeLogoutModal();
    }
});

// Add click handlers to all logout buttons/links
document.querySelectorAll('form[action*="logout"] button[type="submit"]').forEach(function(btn) {
    btn.onclick = showLogoutModal;
});

document.querySelectorAll('.suc-logout').forEach(function(btn) {
    btn.onclick = showLogoutModal;
});
</script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

@stack('scripts')
</body>
</html>
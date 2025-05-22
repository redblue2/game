<?php
// Gerekirse oturum kontrolü eklenebilir
if (!isset($_SESSION)) session_start();
?>

<style>
  .dashboard-welcome {
    max-width: 700px;
    margin: 30px auto;
    background: linear-gradient(135deg, #1f1c2c, #928dab);
    padding: 30px 40px;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.4);
    color: #f0e9ff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    text-align: center;
    position: relative;
    overflow: hidden;
  }

  .dashboard-welcome h2 {
    font-size: 2.8rem;
    margin-bottom: 15px;
    text-shadow: 0 0 10px #f39c12;
  }

  .dashboard-welcome p {
    font-size: 1.2rem;
    margin-bottom: 15px;
    text-shadow: 0 0 5px #00000070;
  }

  .dashboard-welcome .highlight {
    color: #f39c12;
    font-weight: 700;
    font-size: 1.3rem;
  }

  /* Alt bölüm - menü ipucu */
  .dashboard-welcome .tip {
    font-style: italic;
    font-size: 1rem;
    color: #d1c4e9cc;
  }

  /* Arka planda hafif hareket eden soyut şekiller */
  .dashboard-welcome::before,
  .dashboard-welcome::after {
    content: "";
    position: absolute;
    border-radius: 50%;
    opacity: 0.15;
    filter: blur(60px);
    animation: floaty 20s infinite alternate ease-in-out;
  }

  .dashboard-welcome::before {
    width: 220px;
    height: 220px;
    background: #f39c12;
    top: -60px;
    left: -60px;
  }

  .dashboard-welcome::after {
    width: 300px;
    height: 300px;
    background: #6a1b9a;
    bottom: -80px;
    right: -80px;
    animation-delay: 10s;
  }

  @keyframes floaty {
    0% { transform: translateY(0) translateX(0) }
    100% { transform: translateY(30px) translateX(30px) }
  }
</style>

<div class="dashboard-welcome" role="main" aria-label="Hoşgeldiniz mesajı">
    <h2>MK Evrenine <span class="highlight">Hoş Geldin!</span></h2>
    <p>Senin gibi <span class="highlight">cesur bir savaşçıya</span> ihtiyacımız vardı!</p>
    <p>Soldaki menüden <strong>karakterini yönetebilir</strong>, <strong>görev alabilir</strong> ya da <strong>haritaları keşfedebilirsin.</strong></p>
    <p class="tip">İpucu: Yeniysen, önce <em>Rehber</em> kısmını incelemeyi unutma!</p>
</div>

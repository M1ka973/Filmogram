<?php
/**
 * admin_login.php — Connexion admin en 2 étapes
 * Étape 1 : email + mot de passe
 * Étape 2 : question secrète
 */
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

// Déjà connecté → dashboard
if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: admin.php');
    exit();
}

require_once '../db_connect.php';

$step     = $_SESSION['admin_step'] ?? 1;
$error    = '';
$question = '';

// ─── ÉTAPE 1 : vérifier email + mdp ────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['step1'])) {
    $email = trim($_POST['email'] ?? '');
    $mdp   = $_POST['password'] ?? '';

    if (empty($email) || empty($mdp)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        $stmt = $pdo->prepare("SELECT iduser, password, role FROM user WHERE email = :e");
        $stmt->execute([':e' => $email]);
        $user = $stmt->fetch();

        if ($user && $user['role'] === 'admin' && password_verify($mdp, $user['password'])) {
            $_SESSION['admin_step']      = 2;
            $_SESSION['admin_id_tmp']    = $user['iduser'];
            $step = 2;
        } else {
            $error = 'Email ou mot de passe incorrect, ou compte non administrateur.';
        }
    }
}

// ─── ÉTAPE 2 : vérifier la réponse secrète ─────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['step2'])) {
    $answer   = trim(strtolower($_POST['answer'] ?? ''));
    $adminId  = $_SESSION['admin_id_tmp'] ?? 0;

    $stmt = $pdo->prepare("SELECT question, answer_hash FROM admin_security WHERE admin_id = ?");
    $stmt->execute([$adminId]);
    $sec = $stmt->fetch();

    if ($sec && password_verify($answer, $sec['answer_hash'])) {
        // Connexion réussie
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id']        = $adminId;

        // Redirection conditionnelle (Simple vs Complet)
        $stmtEmail = $pdo->prepare("SELECT email FROM user WHERE iduser = ?");
        $stmtEmail->execute([$adminId]);
        $adm = $stmtEmail->fetch();
        
        $targetPage = ($adm && $adm['email'] === 'admin@filmogram.test') ? 'admin_simple.php' : 'admin.php';

        unset($_SESSION['admin_step'], $_SESSION['admin_id_tmp']);
        header("Location: $targetPage");
        exit();
    } else {
        $error = 'Réponse incorrecte. Réessayez.';
        $step  = 2;
    }
}

// Charger la question si on est à l'étape 2
if ($step === 2) {
    $adminId = $_SESSION['admin_id_tmp'] ?? 0;
    $stmt    = $pdo->prepare("SELECT question FROM admin_security WHERE admin_id = ?");
    $stmt->execute([$adminId]);
    $sec      = $stmt->fetch();
    $question = $sec ? $sec['question'] : 'Quelle est la réponse secrète ?';
}

// Annuler / retour à l'étape 1
if (isset($_GET['reset'])) {
    unset($_SESSION['admin_step'], $_SESSION['admin_id_tmp']);
    header('Location: admin_login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Connexion | FILMOGRAM</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --bg:       #0a0a1a;
    --card:     rgba(255,255,255,0.04);
    --border:   rgba(255,255,255,0.1);
    --accent:   #e94560;
    --accent2:  #667eea;
    --text:     #e8e8f0;
    --muted:    #888899;
    --input-bg: rgba(255,255,255,0.06);
    --glow:     0 0 40px rgba(233,69,96,0.25);
  }

  body {
    font-family: 'Inter', sans-serif;
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
  }

  /* Animated background */
  body::before {
    content: '';
    position: fixed; inset: 0;
    background:
      radial-gradient(ellipse at 20% 50%, rgba(102,126,234,0.12) 0%, transparent 50%),
      radial-gradient(ellipse at 80% 20%, rgba(233,69,96,0.12) 0%, transparent 50%),
      radial-gradient(ellipse at 50% 80%, rgba(118,75,162,0.08) 0%, transparent 50%);
    pointer-events: none;
    z-index: 0;
  }

  /* Floating particles */
  .particle {
    position: fixed;
    border-radius: 50%;
    background: var(--accent);
    opacity: 0.15;
    animation: float linear infinite;
    z-index: 0;
  }

  @keyframes float {
    0%   { transform: translateY(100vh) rotate(0deg); opacity: 0; }
    10%  { opacity: 0.15; }
    90%  { opacity: 0.15; }
    100% { transform: translateY(-100px) rotate(720deg); opacity: 0; }
  }

  .login-wrapper {
    position: relative; z-index: 1;
    width: 100%; max-width: 460px;
    padding: 20px;
  }

  .login-card {
    background: var(--card);
    backdrop-filter: blur(20px);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 48px 40px;
    box-shadow: 0 25px 60px rgba(0,0,0,0.5), var(--glow);
    animation: slideUp 0.5s cubic-bezier(0.16,1,0.3,1);
  }

  @keyframes slideUp {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .logo {
    text-align: center;
    margin-bottom: 32px;
  }

  .logo-icon {
    width: 64px; height: 64px;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 28px;
    margin: 0 auto 16px;
    box-shadow: 0 8px 24px rgba(233,69,96,0.4);
  }

  .logo h1 {
    font-size: 22px; font-weight: 800;
    background: linear-gradient(135deg, #fff, var(--accent));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    letter-spacing: 3px;
  }

  .logo p {
    color: var(--muted); font-size: 13px; margin-top: 4px;
  }

  /* Steps indicator */
  .steps {
    display: flex; gap: 8px; margin-bottom: 32px; align-items: center;
  }
  .step-dot {
    flex: 1; height: 3px; border-radius: 2px;
    background: var(--border);
    transition: background 0.3s;
  }
  .step-dot.active { background: var(--accent); }
  .step-dot.done   { background: var(--accent2); }
  .step-label {
    font-size: 11px; color: var(--muted); white-space: nowrap;
  }

  h2 {
    font-size: 20px; font-weight: 700; margin-bottom: 6px;
  }
  .subtitle {
    color: var(--muted); font-size: 13px; margin-bottom: 28px;
  }

  .form-group {
    margin-bottom: 20px;
  }
  .form-group label {
    display: block; font-size: 13px; font-weight: 600;
    color: var(--muted); margin-bottom: 8px; letter-spacing: 0.5px;
  }
  .form-group .input-wrap {
    position: relative;
  }
  .form-group .input-wrap i {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    color: var(--muted); font-size: 15px;
    transition: color 0.2s;
  }
  .form-group input {
    width: 100%; padding: 14px 16px 14px 44px;
    background: var(--input-bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    color: var(--text); font-size: 15px; font-family: inherit;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .form-group input:focus {
    border-color: var(--accent2);
    box-shadow: 0 0 0 3px rgba(102,126,234,0.15);
  }
  .form-group input:focus ~ i,
  .form-group .input-wrap:focus-within i { color: var(--accent2); }

  .question-box {
    background: rgba(102,126,234,0.1);
    border: 1px solid rgba(102,126,234,0.3);
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 24px;
    font-size: 14px;
    color: #a0aaff;
    display: flex; align-items: center; gap: 12px;
  }
  .question-box i { font-size: 18px; color: var(--accent2); flex-shrink: 0; }

  .btn-submit {
    width: 100%; padding: 15px;
    background: linear-gradient(135deg, var(--accent), #c13558);
    color: #fff; border: none; border-radius: 12px;
    font-size: 15px; font-weight: 700; font-family: inherit;
    cursor: pointer; letter-spacing: 0.5px;
    transition: transform 0.2s, box-shadow 0.2s, opacity 0.2s;
    box-shadow: 0 4px 20px rgba(233,69,96,0.4);
    display: flex; align-items: center; justify-content: center; gap: 8px;
  }
  .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(233,69,96,0.5);
  }
  .btn-submit:active { transform: translateY(0); }

  .error-msg {
    background: rgba(233,69,96,0.12);
    border: 1px solid rgba(233,69,96,0.35);
    color: #ff7b93;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 13px;
    margin-bottom: 20px;
    display: flex; align-items: center; gap: 8px;
    animation: shake 0.4s ease;
  }
  @keyframes shake {
    0%,100% { transform: translateX(0); }
    20%,60%  { transform: translateX(-6px); }
    40%,80%  { transform: translateX(6px); }
  }

  .back-link {
    display: block; text-align: center;
    margin-top: 20px; font-size: 13px; color: var(--muted);
    text-decoration: none; transition: color 0.2s;
  }
  .back-link:hover { color: var(--text); }

  .home-link {
    display: block; text-align: center;
    margin-top: 12px; font-size: 13px; color: var(--muted);
    text-decoration: none;
  }
  .home-link:hover { color: var(--accent2); }

  .toggle-pwd {
    position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
    cursor: pointer; color: var(--muted);
    background: none; border: none; font-size: 15px;
    transition: color 0.2s;
  }
  .toggle-pwd:hover { color: var(--text); }
</style>
</head>
<body>
<!-- Floating particles -->
<script>
  (function(){
    const colors = ['#e94560','#667eea','#764ba2'];
    for(let i=0;i<15;i++){
      const p=document.createElement('div');
      p.className='particle';
      const size=Math.random()*6+2;
      p.style.cssText=`width:${size}px;height:${size}px;left:${Math.random()*100}%;background:${colors[Math.floor(Math.random()*3)]};animation-duration:${Math.random()*15+10}s;animation-delay:${Math.random()*10}s;`;
      document.body.appendChild(p);
    }
  })();
</script>

<div class="login-wrapper">
  <div class="login-card">

    <div class="logo">
      <div class="logo-icon"><i class="fas fa-film"></i></div>
      <h1>FILMOGRAM</h1>
      <p>Panneau d'Administration</p>
    </div>

    <!-- Steps -->
    <div class="steps">
      <span class="step-label" style="color:<?= $step===1?'#fff':'var(--accent2)' ?>">Identifiants</span>
      <div class="step-dot <?= $step===1?'active':($step===2?'done':'') ?>"></div>
      <div class="step-dot <?= $step===2?'active':'' ?>"></div>
      <span class="step-label" style="color:<?= $step===2?'#fff':'var(--muted)' ?>">Vérification</span>
    </div>

    <?php if (!empty($error)): ?>
    <div class="error-msg"><i class="fas fa-exclamation-triangle"></i><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($step === 1): ?>
    <!-- ── ÉTAPE 1 ── -->
    <h2>Connexion Admin</h2>
    <p class="subtitle">Entrez vos identifiants administrateur</p>

    <form method="post" action="admin_login.php">
      <div class="form-group">
        <label for="email">ADRESSE EMAIL</label>
        <div class="input-wrap">
          <input type="email" id="email" name="email" placeholder="admin@filmogram.com" required autocomplete="email">
          <i class="fas fa-envelope"></i>
        </div>
      </div>
      <div class="form-group">
        <label for="password">MOT DE PASSE</label>
        <div class="input-wrap">
          <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
          <i class="fas fa-lock"></i>
          <button type="button" class="toggle-pwd" onclick="togglePwd()" id="eyeBtn"><i class="fas fa-eye" id="eyeIcon"></i></button>
        </div>
      </div>
      <button type="submit" name="step1" class="btn-submit">
        <i class="fas fa-arrow-right"></i> Continuer
      </button>
    </form>

    <a href="../index.html" class="home-link"><i class="fas fa-home"></i> Retour au site</a>

    <?php else: ?>
    <!-- ── ÉTAPE 2 ── -->
    <h2>Vérification</h2>
    <p class="subtitle">Double authentification — répondez à la question secrète</p>

    <div class="question-box">
      <i class="fas fa-shield-alt"></i>
      <span><?= htmlspecialchars($question) ?></span>
    </div>

    <form method="post" action="admin_login.php">
      <div class="form-group">
        <label for="answer">VOTRE RÉPONSE</label>
        <div class="input-wrap">
          <input type="text" id="answer" name="answer" placeholder="Votre réponse secrète..." required autocomplete="off" autofocus>
          <i class="fas fa-key"></i>
        </div>
      </div>
      <button type="submit" name="step2" class="btn-submit">
        <i class="fas fa-unlock"></i> Accéder au panneau
      </button>
    </form>

    <a href="admin_login.php?reset=1" class="back-link">← Retour à l'étape précédente</a>
    <?php endif; ?>

  </div>
</div>

<script>
function togglePwd() {
  const inp = document.getElementById('password');
  const ico = document.getElementById('eyeIcon');
  if (inp.type === 'password') {
    inp.type = 'text';
    ico.className = 'fas fa-eye-slash';
  } else {
    inp.type = 'password';
    ico.className = 'fas fa-eye';
  }
}
</script>
</body>
</html>

<?php
require __DIR__ . '/flag.inc.php';
header('Content-Type: text/html; charset=utf-8');

// 身份完全由客户端的 Cookie 决定 —— 这就是「不要相信任何递过来的东西」
$role = isset($_COOKIE['role']) ? $_COOKIE['role'] : 'guest';
$user = isset($_COOKIE['user']) ? $_COOKIE['user'] : 'guest';
?>
<!DOCTYPE html>
<html lang="zh"><head><meta charset="UTF-8"><title>个人中心</title>
<style>body{font-family:system-ui,sans-serif;display:flex;justify-content:center;align-items:center;min-height:100vh;margin:0;background:#0b0f14;color:#d7e0ea}
.card{background:#151c26;padding:36px 44px;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.5);max-width:560px;width:100%}
h1{margin-top:0;font-size:22px;color:#8ee6c1}code{background:#0b0f14;padding:2px 6px;border-radius:4px;color:#ffd479}
pre{background:#0b0f14;padding:14px;border-radius:8px;color:#8ee6c1;overflow-x:auto}
.badge{display:inline-block;padding:3px 10px;border-radius:999px;background:#26303c;font-size:13px}
.warn{color:#8a97a6;font-size:14px;line-height:1.7}</style></head>
<body><div class="card">
<h1>个人中心</h1>
<p>当前身份：<b><?php echo htmlspecialchars($user, ENT_QUOTES, 'UTF-8'); ?></b>
   <span class="badge"><?php echo htmlspecialchars($role, ENT_QUOTES, 'UTF-8'); ?></span></p>
<hr>
<?php if ($role === 'admin'): ?>
  <h2>牌没有问题</h2>
  <p class="warn">验完了，<b>牌没有问题</b> —— 管理员请进。</p>
  <pre><?php echo htmlspecialchars(ctf_flag(), ENT_QUOTES, 'UTF-8'); ?></pre>
<?php else: ?>
  <p class="warn">普通用户只能看到自己的名字。<br>
  你说你是管理员？<b>我要验牌</b> —— 可服务器手上根本没有你的档案。</p>
  <p class="warn">它唯一的判断依据，就是<b>你发给它的 <code>Cookie</code></b>。
  打开看看里面写了什么。</p>
<?php endif; ?>
</div></body></html>

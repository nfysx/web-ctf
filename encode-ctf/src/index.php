<?php
header('Content-Type: text/html; charset=utf-8');

// 静态谜面：编码链是固定的，flag 依然是动态的
$PUZZLE = 'NzU2ZTZjNmY2MzZiMmU3MDY4NzAzZjYzNmY2NDY1M2Q2MjZjNzU2NTYyNjk3MjY0';
?>
<!DOCTYPE html>
<html lang="zh"><head><meta charset="UTF-8"><title>两层门</title>
<style>body{font-family:system-ui,sans-serif;display:flex;justify-content:center;align-items:center;min-height:100vh;margin:0;background:#0e1512;color:#d6e8dd}
.card{background:#16211b;padding:36px 44px;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.5);max-width:680px;width:100%}
h1{margin-top:0;font-size:22px;color:#6ee7a8}code{background:#0e1512;padding:2px 6px;border-radius:4px;color:#fde68a}
pre{background:#0e1512;padding:14px;border-radius:8px;color:#6ee7a8;overflow-x:auto;word-break:break-all;white-space:pre-wrap}
p{line-height:1.8}.tip{color:#8aa595;font-size:13px;line-height:1.8}
</style></head>
<body><div class="card">
<h1>两层门</h1>
<p>下面这串东西打开了两层锁，锁后面是一条路径。</p>
<pre><?php echo htmlspecialchars($PUZZLE, ENT_QUOTES, 'UTF-8'); ?></pre>
<p class="tip">
第一层：<code>base64</code><br>
第二层：解开后得到的是 <code>16 进制</code> 字符串，再转回文本<br>
转完之后你会看到一条路径 —— 访问它就能拿 flag。<br>
两层都是<b>预制</b>的编码，顺序别乱，一层层剥就是了。
</p>
</div></body></html>

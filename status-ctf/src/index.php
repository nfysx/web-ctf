<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="zh"><head><meta charset="UTF-8"><title>跳转测试台</title>
<style>body{font-family:system-ui,sans-serif;display:flex;justify-content:center;align-items:center;min-height:100vh;margin:0;background:#0d1117;color:#c9d1d9}
.card{background:#161b22;padding:36px 44px;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.6);max-width:600px}
h1{margin-top:0;font-size:22px;color:#58a6ff}code{background:#0d1117;padding:2px 6px;border-radius:4px;color:#f0b429}
p{line-height:1.8}.tip{color:#8b949e;font-size:14px}
</style></head>
<body><div class="card">
<h1>跳转测试台</h1>
<p>这个站点只会做一件事：把你从 <code>/jump.php</code> 跳到 <code>/landing.php</code>。</p>
<p>据说有一次跳转的<b>响应头</b>里多了点东西。</p>
<hr>
<p class="tip">
提示：浏览器会自动跟着 3xx 走，所以你「看不见」那一次响应。<br>
想看见它，就别跟着跳 —— 让工具把原始响应留给你。
</p>
<p><a href="/jump.php" style="color:#58a6ff">开始跳转</a></p>
</div></body></html>

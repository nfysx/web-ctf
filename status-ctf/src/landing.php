<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="zh"><head><meta charset="UTF-8"><title>落地页</title>
<style>body{font-family:system-ui,sans-serif;display:flex;justify-content:center;align-items:center;min-height:100vh;margin:0;background:#0d1117;color:#c9d1d9}
.card{background:#161b22;padding:36px 44px;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.6);max-width:560px}
h1{margin-top:0;font-size:22px;color:#58a6ff}p{line-height:1.8}.tip{color:#8b949e;font-size:14px}code{background:#0d1117;padding:2px 6px;border-radius:4px;color:#f0b429}
</style></head>
<body><div class="card">
<h1>你到了</h1>
<p>这里是 <code>/landing.php</code>，页面本身什么都没有。</p>
<p class="tip">跳转那一步的响应你看到了吗？<br>
如果没有，回去看 <code>/jump.php</code> 的<b>原始响应</b>（302 那一个）。</p>
</div></body></html>

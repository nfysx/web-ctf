<?php
require __DIR__ . '/flag.inc.php';
header('Content-Type: text/html; charset=utf-8');

// 两个条件都靠请求头判断 —— 而请求头是客户端说了算
$xff = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? trim((string)$_SERVER['HTTP_X_FORWARDED_FOR']) : '';
$ua  = isset($_SERVER['HTTP_USER_AGENT']) ? (string)$_SERVER['HTTP_USER_AGENT'] : '';

$cond1 = (strpos($xff, '127.0.0.1') !== false);          // 来源 IP
$cond2 = (stripos($ua, 'AdminBot') !== false);            // 采集机器人

$ok = $cond1 && $cond2;
?>
<!DOCTYPE html>
<html lang="zh"><head><meta charset="UTF-8"><title>内网管理入口</title>
<style>body{font-family:system-ui,sans-serif;display:flex;justify-content:center;align-items:center;min-height:100vh;margin:0;background:#101820;color:#d9e6ef}
.card{background:#1a2733;padding:36px 44px;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.5);max-width:600px;width:100%}
h1{margin-top:0;font-size:22px;color:#7fd1ff}code{background:#101820;padding:2px 6px;border-radius:4px;color:#ffd479}
pre{background:#101820;padding:14px;border-radius:8px;color:#7fd1ff;overflow-x:auto}
ul{line-height:2}li.no{color:#f38ba8}li.yes{color:#a6e3a1}.tip{color:#8aa0b2;font-size:13px;line-height:1.7}
</style></head>
<body><div class="card">
<h1>内网管理入口</h1>
<p class="tip">本页面仅允许「来自内网 <code>127.0.0.1</code> 的管理机器人」访问。<br>
它连你是不是<b>真人</b>都没验过。</p>
<ul>
  <li class="<?php echo $cond1 ? 'yes' : 'no'; ?>">
      来源 IP 判定（<code>X-Forwarded-For</code>）：<?php echo $cond1 ? '通过' : '不通过'; ?>
      　当前值：<code><?php echo $xff === '' ? '(未提供)' : htmlspecialchars($xff, ENT_QUOTES, 'UTF-8'); ?></code>
  </li>
  <li class="<?php echo $cond2 ? 'yes' : 'no'; ?>">
      客户端标识判定（机器人名 <code>AdminBot</code>）：<?php echo $cond2 ? '通过' : '不通过'; ?>
      　当前值：<code><?php echo htmlspecialchars(substr($ua, 0, 60), ENT_QUOTES, 'UTF-8'); ?></code>
  </li>
</ul>
<?php if ($ok): ?>
  <h2>验证通过</h2>
  <pre><?php echo htmlspecialchars(ctf_flag(), ENT_QUOTES, 'UTF-8'); ?></pre>
<?php else: ?>
  <p class="tip">两个条件都满足才给 flag。<br>
  它判断身份的全部依据，就是你亲手写下的两个请求头 —— 这就是它眼里的<b>含人量</b>。<br>
  页面上显示的两项，都是<b>你发过去的</b>，不是服务器亲自测出来的。</p>
<?php endif; ?>
</div></body></html>

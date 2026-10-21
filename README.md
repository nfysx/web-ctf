# Web-CTF 题目集

> 一个帮朋友出的 CTF Web 入门题集。
> 每道题的题解写在各自文件夹的 `README.md` 里。

题目都围绕一套「Web 安全入门」课程的知识点设计：**从信息泄露开始，到协议、注入、越权、上传**。
难度控制在 1–2，适合刚接触 CTF 的新手。

---

## 题目一览（10 题）

| 目录 | 题名 | 考点 | 难度 | 端口 | 对应课件 |
|---|---|---|---|---|---|
| `http-message-ctf/` | 预言家，我知道你为何而来 | HTTP 协议、Cookie、请求方法、URL 参数、Base64 | 2 | **8080** | p17 请求详解 · p48 Burp |
| `xss-reflect-ctf/` | 你好啊，预言家 | 反射型 XSS、读 Cookie、Base64 | 1 | **8081** | p33 XSS |
| `robots-ctf/` | 秘密档案室 | `robots.txt` 信息泄露 | 1 | **8082** | p31 信息泄露 |
| `backup-ctf/` | 内部通知系统 | 备份文件泄露、源码审计 | 1 | **8083** | p31 信息泄露 |
| `sqli-ctf/` | 青鸟管理系统 | SQL 注入、注释符、登录绕过 | 2 | **8084** | p32 SQL 注入 |
| `cookie-ctf/` | 个人中心 | Cookie 伪造、水平越权 | 1 | **8085** | p20 Cookie · p35 越权 |
| `upload-ctf/` | 头像上传 | 文件上传、`Content-Type` 可伪造 | 2 | **8086** | p36 文件上传 |
| `header-ctf/` | 内网管理入口 | 请求头伪造、`X-Forwarded-For` | 1 | **8087** | p17 请求详解 |
| `status-ctf/` | 跳转测试台 | 状态码、302 重定向、响应头 | 1 | **8088** | p24 状态码 |
| `encode-ctf/` | 两层门 | Base64、十六进制、编码≠加密 | 1 | **8089** | p48 编码 |

端口连续（8080–8089），**可以同时启动互不冲突**。

---

## 教学示例站（不参与题目编号）

| 目录 | 站名 | 内容 | 难度 | 端口 |
|---|---|---|---|---|
| `infoleak-demo/` | 星记博客（二次元个人博客） | **信息泄露总示范** —— 把 PPT p31 清单上的 10 种泄露点全摆在一个站里 | 1 | **8090** |

它不算一道单点题，是**课堂演示用的靶子**：站点本体是真实博客的构建产物（视觉、交互都保持原样，
所以看着就是个正常的二次元个人博客），在此基础上故意加了一批运维常犯的错。

用法是「照着清单让学生一条条找」：`robots.txt` / `.git` / 备份 `.bak` / 打包 `www.zip` /
`.DS_Store` / `phpinfo.php` / 响应头 / HTML 注释 / 报错页 / 目录扫描，十条全在。
找齐之后再讲怎么把几条串成一条攻击链。

题解与演示脚本见 `infoleak-demo/README.md`。

---

## 动态 Flag 机制

**仓库里没有任何可用的固定 flag。**

每道题的 `flag.inc.php` 都实现了同一套逻辑：

```
1) 优先读环境变量 FLAG（平台 / 运维注入，填【明文】flag）
2) 没注入时，首次请求自动生成一个随机 flag，
   并缓存到容器 /tmp/.web_ctf_flag，保证同一实例内多次请求一致
```

生成的随机 flag 形如 `flag{40fd67b7-344e-4edd-9afd-707be0ffc3a7}`（UUID v4）。

**注入方式**——`docker-compose.yml` 里已经准备好这一行：

```yaml
    environment:
      - FLAG=${FLAG:-}
      - TZ=Asia/Shanghai
```

所以平台只要在启动时带上环境变量即可，**不用改任何文件**：

```bash
FLAG=flag{your-dynamic-flag-here} docker compose up -d
```

（Windows PowerShell：`$env:FLAG="flag{...}"; docker compose up -d`）

不设 `FLAG` 就自动随机，两者都已实测。

> 注意：`FLAG` 里填**明文**。至于页面输出的是明文还是编码后的形式，由各题决定
> （`http-message-ctf` 和 `xss-reflect-ctf` 会额外做一次 Base64，题解里有写）。

---

## 部署

每道题的目录里都有 `Dockerfile` + `docker-compose.yml`，彼此独立：

```bash
cd robots-ctf
docker compose up -d --build
# 打开 http://127.0.0.1:8082/
docker compose down -v          # 用完清理
```

依赖：Docker + Docker Compose v2（`docker compose`，不是老的 `docker-compose`）。

| 题 | 基础镜像 | 说明 |
|---|---|---|
| 9 题 | `php:7.4-apache` | 单容器 |
| `sqli-ctf` | `php:7.4-apache` + `pdo_mysql`，另有 `mariadb:10.11` | 双容器，首次启动自动执行 `init.sql` 建表 |

**为什么 sqli 用 MariaDB 而不是 SQLite**：PHP 7.4 起不再内置 SQLite，`pdo_sqlite` 需要系统
`libsqlite3-dev`，构建时必须联网 `apt-get`。改用 `pdo_mysql`（走内置 mysqlnd）可以**完全离线构建**。

没有 Docker 也可以直接用 PHP 内置服务器跑（仅限单容器的那 9 题）：

```bash
cd robots-ctf
php -S 127.0.0.1:8082 -t src
```

---

## 项目结构

```
web-ctf/
├── http-message-ctf/
│   ├── src/                  # 站点根目录
│   │   ├── index.php
│   │   ├── flag.inc.php      # 动态 flag 读取器
│   │   └── 1.jpg
│   ├── Dockerfile
│   ├── docker-compose.yml
│   └── README.md             # 题解
├── sqli-ctf/                 # 唯一带数据库的一题
│   ├── init.sql              # 建表 + 种子数据
│   ├── src/
│   ├── Dockerfile
│   ├── docker-compose.yml    # web + db 两个服务
│   └── README.md
├── robots-ctf/
│   └── ...                   # 同 http-message-ctf 结构
├── infoleak-demo/            # 教学示例站（不参与题目编号）
│   ├── src/                  # 真实博客构建产物 + 后加的漏洞点
│   ├── dotgit/               # 构建时 COPY 成 /var/www/html/.git，用于演示 .git 泄露
│   ├── Dockerfile
│   ├── docker-compose.yml
│   └── README.md
├── ...（共 10 道题 + 1 个示例站）
├── .gitignore
└── README.md
```

约定：

- 站点源码一律放 `src/`（全部题目统一为 `src/` 结构）
- 每道题自带一份 `flag.inc.php`，**不共享**，方便单独打包部署
- 每题一个独立端口

---

## 验证情况

已在真实 Docker 环境（Docker Desktop 29.6.1）逐题跑过：

- **11 个镜像**（10 题 + 1 示例站）全部构建成功
- 10 题同时启动，8080–8089 端口全部就绪；示例站单独占 8090
- **10 题解题路径校验 32/32 通过**（含反例：错误口令 / 错误类型 / 只补一个请求头等都不给 flag，
  以及 10 项「梗已写入」和 7 项「关键线索未丢失」断言）
- **示例站泄露点校验 34/34 通过**（p31 那 10 条泄露点逐条实测，含 `.git`、备份 zip 内容、`.DS_Store` 文件名）
- **每个实例生成互不相同的动态 flag**，且与响应里的一致
- `FLAG=flag{...} docker compose up -d` 注入实测生效
- 所有容器日志无 PHP Fatal / Parse / Warning

---

## 新增一题的步骤

1. 建目录 `xxx-ctf/`，源码放 `src/`
2. 复制一份 `flag.inc.php` 到 `src/`
3. 在需要的页面 `require __DIR__ . '/flag.inc.php';` 然后 `echo ctf_flag();`
4. `Dockerfile`：`FROM php:7.4-apache` + `COPY src/ /var/www/html/`
5. `docker-compose.yml`：分配一个没被占用的端口，`environment` 里留 `- FLAG=${FLAG:-}` 和 `TZ`
6. 写 `README.md` 题解，跑一遍确认能打通

---

## ⚠️ 安全提醒

这些应用**故意包含漏洞**：注入、上传木马、伪造身份等等。

- 只在**本机或受控内网**运行
- 默认 `ports` 映射是 `0.0.0.0`，暴露到公网前请改成 `"127.0.0.1:8082:80"`
- `upload-ctf` 传上去的木马能执行任意 PHP，别放在有真实数据的机器上

---

## 已知待办

- `xss-reflect-ctf/my-challenge.tar`（156 MB）已从仓库索引移除、文件移到仓库外
  （`D:\web-ctf-unused-data\`），但 **git 历史里仍然存在**。
  要彻底减小仓库体积需要用 `git filter-repo` 或 `BFG` 重写历史。
- 本地还留着几个旧镜像（`my-challenge:v1.0`、`your-challenge-name:v1.0`、`web-ctf-web`、
  `192.168.17.130:30310/my-challenge:v1.0`），各约 648 MB，确认无用后可以
  `docker rmi` 清掉约 2.6 GB。
- 未覆盖 CSRF / 存储型 XSS：这两类需要「受害者主动访问」，诚实出题要额外的无头浏览器 bot。

# CTF 题解：HTTP协议

## 题目概述

这是一个简单的HTTP协议题目，需要抓包改包 **依次修改 Cookie、请求方法 + POST 数据、URL 参数**，才能拿到最终的 Flag。
- **考点**：HTTP协议，Base64 解码
- **难度**：2
- **Flag 格式**：`flag{...}`

---

## 环境部署

题目提供了 Docker 环境，使用 `docker-compose` 一键启动：

```BASH
# 进入项目目录
cd http-ctf
# 构建并启动容器
docker-compose up -d --build
#访问 
http://localhost:8080 即可看到题目页面

```
## 解题过程

### 1.Cookie
在 Burp 中开启拦截，刷新页面，捕获到类似如下的 GET 请求:
```
GET / HTTP/1.1
Host: 127.0.0.1:8080
User-Agent: Mozilla/5.0 ...
...
```
第一关中提示
`‘预言家,没有饼干了,我需要...’`及一道数学题可知需要添加Cookie及其对应值。Cookie值由数学题解出为**78**，随后在在请求头中添加
```
Cookie: cookie=78
```
构造成类似如下请求：
```
GET / HTTP/1.1
Host: 127.0.0.1:8080
...
Cookie: cookie=78
...
```
发送后，页面则会进入第二关
### 2.修改请求方法 + POST 数据
由提示
>‘预言家,现在的交流方法不太安全,改成一个安全的方式吧。而且你是知道女祭司和预言家是一对的,别忘用英语写在数据里哦’

可知需要将请求头中的`GET`改为`POST`并在 Body 中写入 `priestess=prophet`

构造成类似如下请求：
```
POST / HTTP/1.1
Host: 127.0.0.1:8080
...
Cookie: cookie=78

priestess=prophet
```
发送后，页面提示将变为第三关

### 3.URL 参数
由提示
>预言家,你的链接里缺少了一个参数,管理员的值不为一哦。

在请求行中路径后添加 ?admin=1，变为 POST /?admin=1

构造成类似如下请求：
```
POST /?admin=1 HTTP/1.1
Host: 127.0.0.1:8888
...
Cookie: cookie=78

priestess=prophet
```
点击 发送，此时三关全部通过，响应中会显示包含 Base64 字符串的 Flag

---
### 解题完毕
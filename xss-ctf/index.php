<?php
/**
 * 反射型XSS
 * Flag 从环境变量 FLAG 读取，并写入Cookie
 */
$flag = getenv('FLAG') ?: 'flag{58F627F0-4607-A3C2-3BB5-67A51F400A29}'; // 如果环境变量中没有设置 FLAG，则使用默认值
// 将 Flag 编码为 Base64 并设置为 Cookie 
$flag_encoded = base64_encode($flag);
// 将Flag写入Cookie（有效期1小时）
setcookie("flag", $flag_encoded, time() + 3600, "/");
// 禁用浏览器XSS防护
header("X-XSS-Protection: 0");

$greeting = '';
if (array_key_exists("name", $_GET) && $_GET['name'] !== '') {
    // 漏洞：直接拼接用户输入，无过滤
    $greeting = '<pre>' . $_GET['name'] . ' 不是你的名字</pre>';
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>你好啊，预言家</title>  
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: sans-serif;
            background-image: url('1.jpg'); 
            background-size: cover;          
            background-position: center;    
            background-repeat: no-repeat;   
            background-attachment: fixed;   
            background-blend-mode: overlay; 
        }
        .container {
            background: rgba(255, 255, 255, 0.35);  
            backdrop-filter: blur(4px);  
            padding: 2rem 3rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        h2 {
            margin-top: 0;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        input[type="text"] {
            width: 80%;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"] {
            padding: 8px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background: #0056b3;
        }

        #result {
            margin-top: 20px;
            padding: 10px;
            background: rgba(255,255,255,0.7);
            border-radius: 4px;
            min-height: 40px;
        }

        .hint {
            margin-top: 20px;
            color: #333;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>输入你的名字</h2>
        <form method="GET" action="">
            <input type="text" name="name" value="<?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : ''; ?>">
            <input type="submit" value="完成">
        </form>
        <div id="result">
            <?php echo $greeting; ?>
         <!-- 后端输出显示在这里 -->
        </div>
        <p class="hint">tips: 不妨试试其他一些字符呢，预言家</p>
    </div>
</body>
</html>
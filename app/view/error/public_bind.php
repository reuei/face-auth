<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>部署错误 - 森码云实人认证系统</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:-apple-system,BlinkMacSystemFont,sans-serif;background:linear-gradient(135deg,#0F172A,#1E293B);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;color:#fff}
.e{max-width:600px;text-align:center}
.e-i{width:80px;height:80px;margin:0 auto 32px;background:linear-gradient(135deg,#EF4444,#DC2626);border-radius:24px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 40px rgba(239,68,68,.3)}
.e-i svg{width:40px;height:40px}
.e h1{font-size:28px;font-weight:700;margin-bottom:16px}
.e p{font-size:16px;color:rgba(255,255,255,.6);line-height:1.7;margin-bottom:32px}
.e-s{text-align:left;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:16px;padding:32px;margin-bottom:32px}
.e-s h3{font-size:18px;margin-bottom:20px}
.e-s ol{padding-left:24px}
.e-s li{font-size:14px;color:rgba(255,255,255,.7);line-height:1.8;margin-bottom:12px}
.e-s code{background:rgba(255,255,255,.1);padding:4px 8px;border-radius:4px;font-family:monospace;font-size:13px;color:#3B82F6}
</style>
</head>
<body>
<div class="e">
<div class="e-i"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
<h1>部署配置错误</h1>
<p>检测到站点根目录未绑定到 <code>public</code> 文件夹。<br>请将网站根目录设置为 <code>public</code> 文件夹后重试。</p>
<div class="e-s"><h3>解决步骤</h3><ol>
<li>登录虚拟主机控制面板（cPanel、Plesk等）</li>
<li>找到「域名管理」或「网站设置」</li>
<li>将网站根目录修改为项目目录下的 <code>public</code> 文件夹</li>
<li>保存设置并等待生效</li>
<li>刷新此页面</li></ol></div>
<p style="color:rgba(255,255,255,.4);font-size:14px">森码云实人认证系统</p>
</div>
</body>
</html>
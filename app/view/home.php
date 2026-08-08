<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>森码云实人认证系统 - 金融级AI人脸识别平台</title>
<meta name="description" content="基于AI人脸识别技术，3步完成实名认证。支持活体检测、人脸比对，为您的业务提供金融级安全保障。">
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 40 40'%3E%3Crect width='40' height='40' rx='8' fill='%232563EB'/%3E%3Cpath d='M20 10c-5.5 0-10 4.5-10 10s4.5 10 10 10 10-4.5 10-10-4.5-10-10-10zm0 18c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8z' fill='white'/%3E%3C/svg%3E">
<style>
:root{--p:#2563EB;--s:#7C3AED;--g:#10B981;--r:#EF4444;--bg:#F8FAFC;--t:#1E293B;--tl:#64748B;--b:#E2E8F0;--w:#fff;--sh:0 4px 24px rgba(0,0,0,.08);--r16:16px;--tn:.3s ease}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;-webkit-font-smoothing:antialiased}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;font-size:16px;line-height:1.6;color:var(--t);background:var(--bg)}
a{color:inherit;text-decoration:none}
.container{max-width:1200px;margin:0 auto;padding:0 24px}
.btn{display:inline-flex;align-items:center;gap:8px;padding:12px 28px;font-size:14px;font-weight:600;border-radius:var(--r16);cursor:pointer;border:none;transition:all var(--tn)}
.btn-p{background:linear-gradient(135deg,var(--p),var(--s));color:var(--w);box-shadow:0 4px 12px rgba(37,99,235,.3)}
.btn-p:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(37,99,235,.4)}
.btn-o{background:transparent;border:1px solid var(--b);color:var(--t)}
.btn-o:hover{border-color:var(--p);color:var(--p)}
.btn-l{padding:16px 36px;font-size:16px}
/* Nav */
.nav{position:fixed;top:0;left:0;right:0;z-index:100;background:rgba(255,255,255,.85);backdrop-filter:blur(12px);border-bottom:1px solid rgba(226,232,240,.6)}
.nav-c{max-width:1200px;margin:0 auto;padding:0 24px;height:64px;display:flex;align-items:center;justify-content:space-between}
.nav-l{display:flex;align-items:center;gap:10px;font-weight:700;font-size:18px}
.nav-l svg{width:36px;height:36px}
.nav-l span{background:linear-gradient(135deg,var(--p),var(--s));-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.nav-xs{display:flex;align-items:center;gap:32px}
.nav-x{font-size:14px;font-weight:500;color:var(--tl);transition:color var(--tn)}
.nav-x:hover{color:var(--t)}
.nav-ax{display:flex;align-items:center;gap:12px}
/* Hero */
.hero{position:relative;min-height:100vh;display:flex;align-items:center;padding-top:64px;background:linear-gradient(180deg,#F8FAFC 0%,#EFF6FF 50%,#F5F3FF 100%);overflow:hidden}
.hero-bg{position:absolute;inset:0;pointer-events:none}
.hero-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(37,99,235,.03)1px,transparent 1px),linear-gradient(90deg,rgba(37,99,235,.03)1px,transparent 1px);background-size:60px 60px}
.hero-glow{position:absolute;top:20%;right:10%;width:500px;height:500px;background:radial-gradient(circle,rgba(124,58,237,.08)0%,transparent 70%);border-radius:50%}
.hero-c{display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;position:relative;z-index:1}
.hero-t{max-width:560px}
.hero-badge{display:inline-flex;align-items:center;gap:8px;padding:6px 16px;background:rgba(37,99,235,.08);border:1px solid rgba(37,99,235,.15);border-radius:100px;font-size:13px;font-weight:500;color:var(--p);margin-bottom:24px}
.hero-badge span{width:8px;height:8px;background:var(--g);border-radius:50%;animation:pulse 2s ease-in-out infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.5}}
.hero h1{font-size:52px;font-weight:800;line-height:1.15;margin-bottom:20px;letter-spacing:-.02em}
.hero h1 span{background:linear-gradient(135deg,var(--p),var(--s));-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.hero p{font-size:18px;line-height:1.7;color:var(--tl);margin-bottom:32px}
.hero-actions{display:flex;gap:16px;margin-bottom:48px;flex-wrap:wrap}
.hero-visual{display:flex;justify-content:center}
.hero-visual svg{max-width:400px;filter:drop-shadow(0 20px 40px rgba(37,99,235,.15))}
/* Features */
.features{padding:120px 0;background:var(--w)}
.sec-h{text-align:center;max-width:600px;margin:0 auto 64px}
.sec-tag{display:inline-block;padding:6px 16px;background:linear-gradient(135deg,rgba(37,99,235,.08),rgba(124,58,237,.08));border:1px solid rgba(37,99,235,.15);border-radius:100px;font-size:13px;font-weight:600;color:var(--p);margin-bottom:16px}
.sec-h h2{font-size:36px;font-weight:700;margin-bottom:16px}
.sec-h p{font-size:16px;color:var(--tl)}
.f-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.f-card{padding:32px;background:var(--w);border:1px solid var(--b);border-radius:var(--r16);transition:all var(--tn)}
.f-card:hover{transform:translateY(-4px);box-shadow:var(--sh);border-color:transparent}
.f-icon{width:48px;height:48px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,rgba(37,99,235,.1),rgba(124,58,237,.1));border-radius:12px;margin-bottom:20px;color:var(--p)}
.f-icon svg{width:24px;height:24px}
.f-card h3{font-size:18px;font-weight:600;margin-bottom:8px}
.f-card p{font-size:14px;color:var(--tl);line-height:1.6}
/* Process */
.process{padding:120px 0;background:linear-gradient(180deg,#F8FAFC 0%,var(--w) 100%)}
.p-steps{display:flex;align-items:center;justify-content:center;gap:0;flex-wrap:wrap}
.p-step{text-align:center;padding:32px 24px;max-width:220px;position:relative}
.p-num{position:absolute;top:8px;right:8px;font-size:48px;font-weight:800;color:rgba(37,99,235,.06);line-height:1;pointer-events:none}
.p-icon{width:64px;height:64px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--p),var(--s));border-radius:16px;margin:0 auto 20px;color:var(--w);box-shadow:0 4px 12px rgba(37,99,235,.3)}
.p-icon svg{width:28px;height:28px}
.p-step h3{font-size:18px;font-weight:600;margin-bottom:8px}
.p-step p{font-size:14px;color:var(--tl)}
.p-arrow{color:var(--tl);margin-top:-40px}
.p-arrow svg{width:24px;height:24px}
/* CTA */
.cta{padding:100px 0;background:linear-gradient(135deg,var(--p),var(--s));text-align:center;color:var(--w)}
.cta h2{font-size:36px;font-weight:700;margin-bottom:16px}
.cta p{font-size:18px;color:rgba(255,255,255,.8);margin-bottom:32px}
.cta .btn-p{background:var(--w);color:var(--p)}
.cta .btn-o{border-color:rgba(255,255,255,.4);color:var(--w)}
.cta .btn-o:hover{background:rgba(255,255,255,.1);border-color:var(--w)}
/* Footer */
.footer{padding:80px 0 40px;background:#0F172A;color:var(--w)}
.footer-c{max-width:1200px;margin:0 auto;padding:0 24px;display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:48px;margin-bottom:64px}
.footer-b{max-width:300px}
.footer-l{font-weight:700;font-size:18px;margin-bottom:16px}
.footer-d{font-size:14px;color:rgba(255,255,255,.6);line-height:1.7}
.footer-ls h4{font-size:14px;font-weight:600;margin-bottom:20px}
.footer-ls a{display:block;font-size:14px;color:rgba(255,255,255,.6);padding:6px 0;transition:color var(--tn)}
.footer-ls a:hover{color:var(--w)}
.footer-btm{max-width:1200px;margin:0 auto;padding:32px 24px 0;border-top:1px solid rgba(255,255,255,.1);text-align:center;font-size:13px;color:rgba(255,255,255,.5)}
@media(max-width:1023px){.hero-c{grid-template-columns:1fr;text-align:center}.hero h1{font-size:40px}.hero-t{max-width:100%}.hero-actions{justify-content:center}.f-grid{grid-template-columns:repeat(2,1fr)}.footer-c{grid-template-columns:repeat(2,1fr)}}
@media(max-width:767px){.hero h1{font-size:32px}.f-grid{grid-template-columns:1fr}.p-steps{flex-direction:column;gap:32px}.p-arrow{transform:rotate(90deg)}.footer-c{grid-template-columns:1fr}.nav-xs{display:none}}
</style>
</head>
<body>
<nav class="nav"><div class="nav-c">
<a href="/" class="nav-l"><svg viewBox="0 0 40 40"><rect width="40" height="40" rx="8" fill="url(#lg)"/><path d="M20 10c-5.5 0-10 4.5-10 10s4.5 10 10 10 10-4.5 10-10-4.5-10-10-10zm0 18c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8z" fill="white"/><circle cx="20" cy="20" r="4" fill="white" opacity=".8"/><defs><linearGradient id="lg" x1="0" y1="0" x2="40" y2="40"><stop offset="0%" stop-color="#2563EB"/><stop offset="100%" stop-color="#7C3AED"/></linearGradient></defs></svg><span>森码云</span></a>
<div class="nav-xs"><a href="#features" class="nav-x">产品功能</a><a href="#process" class="nav-x">认证流程</a><a href="/docs" class="nav-x">API文档</a><a href="/pricing" class="nav-x">价格</a><a href="/about" class="nav-x">关于我们</a></div>
<div class="nav-ax"><a href="/admin" class="btn btn-o">登录</a><a href="/verify" class="btn btn-p">免费开始</a></div>
</div></nav>

<section class="hero"><div class="hero-bg"><div class="hero-grid"></div><div class="hero-glow"></div></div>
<div class="container"><div class="hero-c">
<div class="hero-t">
<div class="hero-badge"><span></span>金融级安全认证</div>
<h1>实人认证，<br><span>从未如此简单</span></h1>
<p>基于AI人脸识别技术，3步完成实名认证。支持活体检测、人脸比对，为您的业务提供金融级安全保障。</p>
<div class="hero-actions"><a href="/verify" class="btn btn-p btn-l">立即体验</a><a href="/docs" class="btn btn-o btn-l">查看文档</a></div>
</div>
<div class="hero-visual"><svg viewBox="0 0 300 300"><circle cx="150" cy="150" r="120" fill="none" stroke="url(#sg)" stroke-width="2" opacity=".3"/><circle cx="150" cy="150" r="100" fill="none" stroke="url(#sg)" stroke-width="1" opacity=".2"/><line x1="30" y1="150" x2="270" y2="150" stroke="#2563EB" stroke-width="2" opacity=".8"><animate attributeName="y1" values="30;270;30" dur="3s" repeatCount="indefinite"/><animate attributeName="y2" values="30;270;30" dur="3s" repeatCount="indefinite"/></line><circle cx="150" cy="120" r="4" fill="#2563EB" opacity=".6"><animate attributeName="r" values="4;6;4" dur="2s" repeatCount="indefinite"/></circle><circle cx="130" cy="140" r="3" fill="#7C3AED" opacity=".5"><animate attributeName="r" values="3;5;3" dur="2.5s" repeatCount="indefinite"/></circle><circle cx="170" cy="140" r="3" fill="#7C3AED" opacity=".5"><animate attributeName="r" values="3;5;3" dur="2.5s" repeatCount="indefinite"/></circle><circle cx="150" cy="160" r="3" fill="#2563EB" opacity=".5"><animate attributeName="r" values="3;5;3" dur="2s" repeatCount="indefinite"/></circle><path d="M120 150 L140 170 L180 130" fill="none" stroke="#10B981" stroke-width="3" stroke-linecap="round" opacity="0"><animate attributeName="opacity" values="0;1;1;0" dur="4s" repeatCount="indefinite"/></path><defs><linearGradient id="sg" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#2563EB"/><stop offset="100%" stop-color="#7C3AED"/></linearGradient></defs></svg></div>
</div></div>
</section>

<section class="features" id="features"><div class="container">
<div class="sec-h"><span class="sec-tag">核心能力</span><h2>全方位实人认证解决方案</h2><p>覆盖人脸检测、活体检测、人脸比对全流程</p></div>
<div class="f-grid">
<div class="f-card"><div class="f-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/></svg></div><h3>人脸检测</h3><p>毫秒级人脸检测，支持多人脸、遮挡、侧脸等复杂场景，准确率高达99.8%</p></div>
<div class="f-card"><div class="f-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg></div><h3>活体检测</h3><p>动作活体+静默活体双引擎，有效防御照片、视频、3D面具攻击</p></div>
<div class="f-card"><div class="f-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><path d="M20 8v6M23 11h-6"/></svg></div><h3>人脸比对</h3><p>1:1人脸比对准确率99.8%，支持与身份证信息实时比对</p></div>
<div class="f-card"><div class="f-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/></svg></div><h3>身份证OCR</h3><p>自动识别身份证信息，支持人像提取与结构化输出</p></div>
<div class="f-card"><div class="f-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/></svg></div><h3>多通道冗余</h3><p>同时接入百度、腾讯、Azure、Face++，任一故障自动切换</p></div>
<div class="f-card"><div class="f-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg></div><h3>完整API</h3><p>标准化RESTful API，支持魔方财务等第三方快速对接</p></div>
</div></div>
</section>

<section class="process" id="process"><div class="container">
<div class="sec-h"><span class="sec-tag">认证流程</span><h2>简单四步，完成认证</h2><p>流畅的认证体验，平均完成时间仅需1-2分钟</p></div>
<div class="p-steps">
<div class="p-step"><div class="p-num">01</div><div class="p-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></div><h3>输入身份信息</h3><p>输入姓名和身份证号，无需上传证件</p></div>
<div class="p-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
<div class="p-step"><div class="p-num">02</div><div class="p-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg></div><h3>人脸采集</h3><p>对准摄像头自动捕捉最佳人脸图像</p></div>
<div class="p-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
<div class="p-step"><div class="p-num">03</div><div class="p-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg></div><h3>活体检测</h3><p>根据提示完成眨眼、张嘴等动作</p></div>
<div class="p-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
<div class="p-step"><div class="p-num">04</div><div class="p-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div><h3>认证结果</h3><p>实时返回认证结果，支持下载PDF报告</p></div>
</div></div>
</section>

<section class="cta"><div class="container">
<h2>开始使用森码云实人认证</h2><p>立即注册，免费体验完整的实人认证流程</p>
<div class="hero-actions" style="justify-content:center"><a href="/verify" class="btn btn-p btn-l">免费注册</a><a href="/about" class="btn btn-o btn-l">联系销售</a></div>
</div></section>

<footer class="footer"><div class="footer-c">
<div class="footer-b"><div class="footer-l">森码云</div><p class="footer-d">基于AI人脸识别技术的SaaS平台，为企业提供金融级实人认证服务。</p></div>
<div class="footer-ls"><h4>产品</h4><a href="#">人脸检测</a><a href="#">活体检测</a><a href="#">人脸比对</a></div>
<div class="footer-ls"><h4>开发者</h4><a href="/docs">API文档</a><a href="/docs">SDK下载</a></div>
<div class="footer-ls"><h4>公司</h4><a href="/about">关于我们</a><a href="/privacy">隐私政策</a></div>
</div><div class="footer-btm">森码云实人认证系统 版权所有</div></footer>
</body>
</html>
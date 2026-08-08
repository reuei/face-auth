<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
<title>实人认证 - 森码云</title>
<style>
:root{--p:#2563EB;--s:#7C3AED;--g:#10B981;--r:#EF4444;--w:#fff;--bg:#0F172A;--bg2:#1E293B;--tn:.3s ease}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:-apple-system,BlinkMacSystemFont,sans-serif;background:linear-gradient(180deg,var(--bg),var(--bg2));min-height:100vh;color:var(--w)}
.a{max-width:600px;margin:0 auto;padding:40px 24px;min-height:100vh;display:flex;flex-direction:column}
/* Progress */
.ap{display:flex;align-items:center;justify-content:center;gap:0;margin-bottom:40px;padding:20px 0}
.as{display:flex;flex-direction:column;align-items:center;gap:8px;position:relative;z-index:1}
.ac{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:600;background:rgba(255,255,255,.1);border:2px solid rgba(255,255,255,.2);color:rgba(255,255,255,.6);transition:all var(--tn)}
.as.active .ac{background:linear-gradient(135deg,var(--p),var(--s));border-color:transparent;color:var(--w);box-shadow:0 0 20px rgba(37,99,235,.4)}
.as.done .ac{background:var(--g);border-color:var(--g);color:var(--w)}
.al{font-size:12px;color:rgba(255,255,255,.5);white-space:nowrap}
.as.active .al{color:var(--w)}
.pl{flex:1;height:2px;background:rgba(255,255,255,.1);max-width:60px}
/* Card */
.acard{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:16px;padding:40px;backdrop-filter:blur(10px)}
.ah{text-align:center;margin-bottom:32px}
.ai{width:64px;height:64px;margin:0 auto 20px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,rgba(37,99,235,.2),rgba(124,58,237,.2));border-radius:16px}
.ai svg{width:32px;height:32px}
.ah h2{font-size:24px;font-weight:700;margin-bottom:8px}
.ah p{font-size:14px;color:rgba(255,255,255,.6)}
/* Form */
.af{display:flex;flex-direction:column;gap:20px}
.fg{display:flex;flex-direction:column;gap:8px}
.fg label{font-size:14px;font-weight:500;color:rgba(255,255,255,.8)}
.fg input{width:100%;padding:14px 16px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:12px;color:var(--w);font-size:15px;outline:none;transition:all var(--tn)}
.fg input:focus{border-color:var(--p);box-shadow:0 0 0 3px rgba(37,99,235,.2)}
.fg input::placeholder{color:rgba(255,255,255,.3)}
.cb{display:flex;align-items:flex-start;gap:10px;cursor:pointer;margin-top:8px}
.cb input{position:absolute;opacity:0}
.cb-c{width:20px;height:20px;border:2px solid rgba(255,255,255,.3);border-radius:4px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px}
.cb input:checked+.cb-c{background:var(--p);border-color:var(--p)}
.cb input:checked+.cb-c::after{content:'';width:6px;height:10px;border:solid var(--w);border-width:0 2px 2px 0;transform:rotate(45deg);margin-bottom:2px}
.cb span{font-size:13px;color:rgba(255,255,255,.6)}
.cb a{color:#3B82F6;text-decoration:underline}
.btn{width:100%;padding:14px;background:linear-gradient(135deg,var(--p),var(--s));color:var(--w);border:none;border-radius:12px;font-size:16px;font-weight:600;cursor:pointer;transition:all var(--tn);display:flex;align-items:center;justify-content:center;gap:8px}
.btn:hover{transform:translateY(-2px);box-shadow:0 10px 20px rgba(37,99,235,.3)}
.btn:disabled{opacity:.5;cursor:not-allowed;transform:none}
.btn-o{background:transparent;border:1px solid rgba(255,255,255,.2)}
.btn-o:hover{background:rgba(255,255,255,.1)}
/* Camera */
.cam{position:relative;width:100%;aspect-ratio:4/3;background:#000;border-radius:16px;overflow:hidden;margin-bottom:20px}
.cam video{width:100%;height:100%;object-fit:cover}
.cam canvas{position:absolute;top:0;left:0;width:100%;height:100%;pointer-events:none}
.cam-overlay{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none}
.face-guide{width:200px;height:250px;border:2px dashed rgba(255,255,255,.3);border-radius:50% 50% 45% 45%;display:flex;align-items:center;justify-content:center}
.face-guide-inner{width:180px;height:230px;border:2px solid rgba(37,99,235,.5);border-radius:50% 50% 45% 45%;animation:pulse-g 2s ease-in-out infinite}
@keyframes pulse-g{0%,100%{opacity:.5;transform:scale(1)}50%{opacity:1;transform:scale(1.02)}}
.cam-status{position:absolute;bottom:20px;left:50%;transform:translateX(-50%);display:flex;align-items:center;gap:8px;padding:8px 16px;background:rgba(0,0,0,.6);border-radius:100px;backdrop-filter:blur(10px);font-size:13px}
.cam-status .dot{width:8px;height:8px;background:var(--g);border-radius:50%;animation:pulse-s 2s ease-in-out infinite}
@keyframes pulse-s{0%,100%{opacity:1}50%{opacity:.5}}
/* Liveness */
.lv{display:flex;flex-direction:column;align-items:center;gap:32px;padding:20px 0}
.lv-ring{width:120px;height:120px;position:relative}
.lv-ring svg{width:100%;height:100%;transform:rotate(-90deg)}
.lv-ring-bg{fill:none;stroke:rgba(255,255,255,.1);stroke-width:6}
.lv-ring-fill{fill:none;stroke:url(#pg);stroke-width:6;stroke-linecap:round;stroke-dasharray:339.292;stroke-dashoffset:339.292;transition:stroke-dashoffset .5s ease}
.lv-txt{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);display:flex;align-items:baseline;gap:2px}
.lv-txt .cur{font-size:32px;font-weight:700}
.lv-txt .tot{font-size:16px;color:rgba(255,255,255,.5)}
.lv-action{text-align:center}
.lv-action .a-icon{width:80px;height:80px;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,rgba(37,99,235,.2),rgba(124,58,237,.2));border-radius:24px;animation:bounce 2s ease-in-out infinite}
@keyframes bounce{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
.lv-action .a-icon svg{width:40px;height:40px}
.lv-action h3{font-size:24px;font-weight:700;margin-bottom:8px}
.lv-action p{font-size:14px;color:rgba(255,255,255,.6)}
/* Result */
.result{text-align:center;padding:20px 0}
.r-icon{width:80px;height:80px;margin:0 auto 24px;display:flex;align-items:center;justify-content:center;border-radius:50%;animation:scale-in .5s ease}
.r-icon.ok{background:linear-gradient(135deg,var(--g),#059669);box-shadow:0 0 30px rgba(16,185,129,.3)}
.r-icon.fail{background:linear-gradient(135deg,var(--r),#DC2626);box-shadow:0 0 30px rgba(239,68,68,.3)}
@keyframes scale-in{0%{transform:scale(0)}50%{transform:scale(1.1)}100%{transform:scale(1)}}
.r-icon svg{width:40px;height:40px;color:var(--w)}
.result h2{font-size:28px;font-weight:700;margin-bottom:8px}
.result>p{font-size:14px;color:rgba(255,255,255,.6);margin-bottom:32px}
.r-details{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:20px;margin-bottom:32px;text-align:left}
.r-details dl{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.r-details dl:last-child{border:none}
.r-details dt{font-size:14px;color:rgba(255,255,255,.6)}
.r-details dd{font-size:14px;font-weight:600}
.hidden{display:none!important}
</style>
<svg width="0" height="0"><defs><linearGradient id="pg"><stop offset="0%" stop-color="#2563EB"/><stop offset="100%" stop-color="#7C3AED"/></linearGradient></defs></svg>
</head>
<body>
<div class="a">
<div class="ap">
<div class="as active" data-step="1"><div class="ac">1</div><span class="al">身份验证</span></div>
<div class="pl"></div><div class="as" data-step="2"><div class="ac">2</div><span class="al">人脸采集</span></div>
<div class="pl"></div><div class="as" data-step="3"><div class="ac">3</div><span class="al">活体检测</span></div>
<div class="pl"></div><div class="as" data-step="4"><div class="ac">4</div><span class="al">认证结果</span></div>
</div>

<!-- Step 1 -->
<div class="auth-step" id="step1"><div class="acard">
<div class="ah"><div class="ai"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></div><h2>请输入身份信息</h2><p>请确保信息真实有效，用于实名认证。无需上传证件。</p></div>
<form class="af" id="idForm">
<div class="fg"><label>真实姓名</label><input type="text" id="fullName" placeholder="请输入真实姓名" required></div>
<div class="fg"><label>身份证号</label><input type="text" id="idCard" placeholder="请输入18位身份证号" maxlength="18" required></div>
<label class="cb"><input type="checkbox" id="agreement" required><span class="cb-c"></span><span>我已阅读并同意<a href="/privacy">《实名认证服务协议》</a></span></label>
<button type="submit" class="btn">下一步</button>
</form>
</div></div>

<!-- Step 2 -->
<div class="auth-step hidden" id="step2"><div class="acard">
<div class="ah"><div class="ai"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg></div><h2>人脸采集</h2><p>请将脸部对准摄像头，保持光线充足</p></div>
<div class="cam"><video id="video" autoplay playsinline muted></video><canvas id="canvas"></canvas><div class="cam-overlay"><div class="face-guide"><div class="face-guide-inner"></div></div></div><div class="cam-status" id="camStatus"><span class="dot"></span><span>请将脸部对准框内</span></div></div>
<button class="btn" id="captureBtn" disabled>拍照</button>
</div></div>

<!-- Step 3 -->
<div class="auth-step hidden" id="step3"><div class="acard">
<div class="ah"><div class="ai"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg></div><h2>活体检测</h2><p>请根据提示完成动作，验证真人身份</p></div>
<div class="lv"><div class="lv-ring"><svg viewBox="0 0 120 120"><circle class="lv-ring-bg" cx="60" cy="60" r="54"/><circle class="lv-ring-fill" cx="60" cy="60" r="54" id="ring"/></svg><div class="lv-txt"><span class="cur" id="pc">1</span><span class="tot">/3</span></div></div>
<div class="lv-action"><div class="a-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12h20M12 2v20"/></svg></div><h3 id="actionText">请眨眼</h3><p id="actionHint">请快速眨一下眼睛</p></div></div>
</div></div>

<!-- Step 4 -->
<div class="auth-step hidden" id="step4"><div class="acard"><div class="result">
<div class="r-icon ok" id="rIcon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
<h2 id="rTitle">认证通过</h2><p id="rDesc">您的身份信息已验证成功</p>
<div class="r-details"><dl><dt>认证分数</dt><dd id="rScore">92分</dd></dl><dl><dt>认证时间</dt><dd id="rTime">2026-08-08 10:15</dd></dl></div>
<button class="btn btn-o" id="retryBtn">重新认证</button>
</div></div></div>
</div>

<script>
(function(){
'use strict';
const state={step:1,stream:null,token:new URLSearchParams(location.search).get('token'),userInfo:{name:'',idCard:''},actions:['blink','mouth','head_shake']};

// Audio Manager
const Audio={ctx:null,enabled:true,
init(){try{this.ctx=new(window.AudioContext||window.webkitAudioContext)}catch(e){this.enabled=false}},
play(type){
if(!this.enabled||!this.ctx)return;
if(this.ctx.state==='suspended')this.ctx.resume();
switch(type){case'start':this.tone(440,.2);break;case'capture':this.tone(880,.1);break;case'blink':this.tone(523,.15);this.speak('请眨眼');break;case'mouth':this.tone(587,.15);this.speak('请张嘴');break;case'head_shake':this.tone(659,.15);this.speak('请摇头');break;case'success':this.playMelody([523,659,784,1047],100);this.speak('认证通过');break;case'error':this.playMelody([440,349,294],150);this.speak('认证未通过');break;case'step':this.tone(660,.1);break;}
},
tone(freq,dur){const o=this.ctx.createOscillator(),g=this.ctx.createGain();o.connect(g);g.connect(this.ctx.destination);o.frequency.value=freq;o.type='sine';g.gain.setValueAtTime(.3,this.ctx.currentTime);g.gain.exponentialRampToValueAtTime(.01,this.ctx.currentTime+dur);o.start();o.stop(this.ctx.currentTime+dur);},
playMelody(notes,interval){notes.forEach((f,i)=>setTimeout(()=>this.tone(f,.2),i*interval));},
speak(text){if(!this.enabled||!('speechSynthesis' in window))return;const u=new SpeechSynthesisUtterance(text);u.lang='zh-CN';u.rate=1;u.pitch=1;speechSynthesis.speak(u);}
};

document.addEventListener('DOMContentLoaded',()=>{Audio.init();initFlow();});

function initFlow(){
if(state.token){const p=new URLSearchParams(location.search);const n=p.get('name'),c=p.get('id_card');if(n&&c){state.userInfo={name:n,idCard:c};goToStep(2);initCamera();return;}}
document.getElementById('idForm').addEventListener('submit',function(e){e.preventDefault();
const n=document.getElementById('fullName').value.trim(),c=document.getElementById('idCard').value.trim();
if(!n){alert('请输入真实姓名');return;}
if(!validIdCard(c)){alert('请输入有效的身份证号');return;}
if(!document.getElementById('agreement').checked){alert('请同意认证服务协议');return;}
state.userInfo={name:n,idCard:c};Audio.play('start');Audio.speak('开始实人认证，请将脸部对准摄像头');
setTimeout(()=>{goToStep(2);initCamera();},300);
});
document.getElementById('captureBtn').addEventListener('click',handleCapture);
document.getElementById('retryBtn').addEventListener('click',()=>location.reload());
}

function validIdCard(c){if(!/^\d{17}[\dXx]$/.test(c))return false;const w=[7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2],k=['1','0','X','9','8','7','6','5','4','3','2'];let s=0;for(let i=0;i<17;i++)s+=parseInt(c[i])*w[i];return c[17].toUpperCase()===k[s%11];}

function initCamera(){const v=document.getElementById('video');if(!v)return;if(!navigator.mediaDevices){alert('浏览器不支持摄像头');return;}
navigator.mediaDevices.getUserMedia({video:{width:{ideal:1280},height:{ideal:720},facingMode:'user'}}).then(s=>{state.stream=s;v.srcObject=s;v.play();document.getElementById('captureBtn').disabled=false;Audio.speak('摄像头已开启，请将脸部对准框内');}).catch(()=>alert('无法访问摄像头'));}

function handleCapture(){const v=document.getElementById('video'),c=document.getElementById('canvas');if(!v||!c)return;Audio.play('capture');const ctx=c.getContext('2d');c.width=v.videoWidth;c.height=v.videoHeight;ctx.drawImage(v,0,0);if(state.stream)state.stream.getTracks().forEach(t=>t.stop());setTimeout(()=>{goToStep(3);startLiveness();},300);}

function startLiveness(){const acts=state.actions;let i=0;function next(){if(i>=acts.length){goToStep(4);showResult(true);return;}const a=acts[i];showAction(a,i+1);setTimeout(()=>{i++;next();},3000);}next();}

function showAction(action,step){document.getElementById('actionText').textContent=getActionText(action);document.getElementById('actionHint').textContent=getActionHint(action);document.getElementById('pc').textContent=step;Audio.play(action);updateRing(step/3);}
function getActionText(a){return{blink:'请眨眼',mouth:'请张嘴',head_shake:'请摇头'}[a]||'请完成动作';}
function getActionHint(a){return{blink:'请快速眨一下眼睛',mouth:'请张开嘴巴',head_shake:'请左右摇头'}[a]||'请根据提示完成动作';}
function updateRing(p){const r=document.getElementById('ring');if(!r)return;r.style.strokeDashoffset=339.292*(1-p);}

function showResult(ok){if(ok){Audio.play('success');}else{document.getElementById('rIcon').className='r-icon fail';document.getElementById('rTitle').textContent='认证未通过';document.getElementById('rDesc').textContent='请重新尝试认证';Audio.play('error');}}

function goToStep(s){state.step=s;document.querySelectorAll('.auth-step').forEach(e=>e.classList.add('hidden'));document.getElementById('step'+s).classList.remove('hidden');document.querySelectorAll('.as').forEach((e,i)=>{e.classList.remove('active','done');if(i<s-1)e.classList.add('done');else if(i===s-1)e.classList.add('active');});if(s>1)Audio.play('step');}
})();
</script>
</body>
</html>
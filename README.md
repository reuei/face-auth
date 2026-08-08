# 森码云实人认证系统

> 基于Vue3+PHP的实人认证SaaS平台，支持多API通道人脸识别

## 功能特性
- **人脸检测**：毫秒级检测，支持多人脸、遮挡、侧脸
- **活体检测**：动作活体+静默活体，防御照片/视频攻击
- **人脸比对**：1:1比对，准确率99.8%
- **多通道冗余**：腾讯云/百度/Azure/Face++自动切换
- **魔方财务对接**：完整插件，Token管理
- **提示音系统**：Web Audio API + TTS语音提示

## 技术栈
- 后端：PHP 8.1+ / MySQL 5.7+
- 前端：Vue 3 + Vite + TypeScript + Element Plus + TailwindCSS
- 部署：虚拟主机 (Apache/Nginx + PHP + MySQL)

## 安装步骤
1. 克隆仓库
```bash
git clone https://github.com/reuei/face-auth.git
cd face-auth
```
2. 配置虚拟主机绑定到 `public/` 目录
3. 访问域名自动跳转安装向导
4. 按提示完成数据库和管理员配置
5. 进入后台配置API密钥

## 目录结构
```
senma-face-auth/
├── app/              # 应用核心
│   ├── controller/  # 控制器
│   ├── service/     # 服务层
│   ├── core/        # 核心类
│   ├── middleware/  # 中间件
│   └── view/        # 视图
├── config/          # 配置文件
├── public/          # Web入口
├── database/        # SQL脚本
├── mofang-plugin/   # 魔方财务插件
└── resources/       # Vue前端源码
```

## License
Apache-2.0
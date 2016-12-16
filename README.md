# 如何使用

下面是目录结构

```
.
├── README.md
├── plugins
│   └── Pocket
│       ├── Action.php
│       └── Plugin.php
└── theme
    └── default
        └── pocket.php
```

## 安装
其中 `Pocket` 放到 `usr/plugins` 下面, `pocket.php` 放到主题目录下, 上面的例子是默认主题.

## 开启开发者模式
1. 进入 [Pocket 开发者中心](https://getpocket.com/developer/) 并登陆.
2. 点击左边 `APPS` 下面的 `Create a New App`, 新建 App 其中 `Name`, `Description` 自己随意填写.
3. 权限部分勾选 `Retrieve`
4. 平台部分勾选 `Web`
5. 勾选同意 TOS
6. 提交表单
7. 点击 `My Apps`, 找到刚刚建立的 App, 复制下 `Consumer  Key`, 后面有用.

## 配置
1. 进入后台, 在 `控制台 >> 插件` 找到 `Pocket` 点击 `启用`.
2. 在 `启用的插件` 里找到 `Pocket` 点击后面的设置.
3. 在 `Consumer Key` 输入刚刚申请的 App 的 `Consumer Key` 点击保存设置.
4. 再次进入设置, 点击 `Access Token` 提示里的链接, 页面会跳转到 `Pocket` 的授权页面, 同意后自动跳转回来, 复制页面输出的内容填入到 `Access Token` 里面.

## 启用
1. 进入后台, 在 `管理 >> 独立页面` 点击 `新增`.
2. `标题` 随意填写, 如: `Pocket`.
3. `自定义 url` 随意填写, 如: `pocket`.
4. 内容随意填写, 可留空.
5. 在右边的自定义模板选择 `Pocket`
6. 点击提交.
7. 前台预览.

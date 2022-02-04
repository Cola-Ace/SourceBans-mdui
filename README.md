# SourceBans-mdui
基于PHP和mdui开发的SourceBans网页版

注：libs文件夹里的new_sb.sql文件只适用于之前未使用过sourcebans的用户，若数据库中存在sourcebans的表，则不用替换

# 安装方法
将api/configs/database.php里的host, port, user, pass, db_name字段进行填写即可使用

# 已完成部分

- 夜间模式切换
- 主页布局
- 菜单内容
- 服务器信息获取

# 待办

- 安装界面
- 管理员面板
- 登录/注册
- 禁言列表界面
- 封禁列表界面
- 主页显示最近5个被封禁和被禁言的玩家并通过进度条的方式展现
- 更高的安全性
- 管理员可对服务器进行操作

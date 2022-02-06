<?php
	date_default_timezone_set('PRC'); //Beijing time
    session_start();
    require __DIR__ . "/api/api.php";
	
    $User = new User;
    $Server = new Server;
	$Ban = new Ban;
	$Common = new Common;
	
	if (!isset($_SESSION["login"])){
		$_SESSION["login"] = false;
	}
	
	$list = $Server->getServerList();
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title>SourceBans Powered by Xc_ace</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mdui@1.0.2/dist/css/mdui.min.css">
        <script src="https://cdn.jsdelivr.net/npm/mdui@1.0.2/dist/js/mdui.min.js"></script>
        <script src="https://cdn.staticfile.org/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.staticfile.org/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
        <script>
			function connectToServer(ip, port){
				window.location.href = "steam://connect/" + ip + ":" + port;
			}
			function timestampToTime(timestamp) {
			    var date = new Date(timestamp * 1000);
			    var Y = date.getFullYear() + '-';
			    var M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '-';
			    var D = (date.getDate() < 10 ? '0' + (date.getDate()) : date.getDate()) + ' ';
			    var h = (date.getHours() < 10 ? '0' + (date.getHours()) : date.getHours()) + ':';
			    var m = (date.getMinutes() < 10 ? '0' + (date.getMinutes()) : date.getMinutes()) + ':';
			    var s = (date.getSeconds() < 10 ? '0' + (date.getSeconds()) : date.getSeconds());
			    return Y + M + D + h + m + s;
			}
			function showServerInfo(sid, players){
				if (players <= 0){
					mdui.snackbar({
						message: "当前服务器无玩家",
						position: "top"
					});
					return;
				}
				$.post("api/api.php", 
				{
					"sid": sid
				}, function(data, status){
					let json = $.parseJSON(data);
					let tmp = "";
					for (let i = 0; i < json["count"]; i++){
						tmp += `
						<tr>
							<td>${json["data"][i]["Name"]}</td>
							<td>${json["data"][i]["Frags"]}</td>
							<td>${json["data"][i]["TimeF"]}</td>
						</tr>
						`;
					}
					let content = `
						<div class="mdui-table-fluid">
							<table class="mdui-table">
								<thead>
									<tr>
										<th>玩家</th>
										<th>分数</th>
										<th>时长</th>
									</tr>
								</thead>
								<tbody>`;
					content += tmp + "</tbody></table></div>";
					mdui.dialog({
						content: content
					});
				});
			}
            $(function(){
                //夜间模式切换
                if ($.cookie("night-mode") == 1){
                    $("body").addClass("mdui-theme-layout-dark");
                    $("#night-mode-icon").text("brightness_4");
                } else {
                    $("#night-mode-icon").text("brightness_3");
                }
                $("#night-mode").click(function(){
                    $("body").toggleClass("mdui-theme-layout-dark");
                    $("#night-mode-icon").text( $("#night-mode-icon").text() == "brightness_4" ? "brightness_3":"brightness_4" );
                    $.cookie("night-mode", $.cookie("night-mode") == 1 ? 0:1, {expires: 9999});
                });
				
				var clipboard = new ClipboardJS("#btn_copy");
				clipboard.on("success", function(e){
					mdui.snackbar({
						message:"复制成功",
						position: "top"
					});
				});
            });
        </script>
        <style>
            .mdui-row {
                margin-top:15px;
            }
        </style>
    </head>
    <body class="mdui-appbar-with-toolbar">
        <div class="mdui-appbar mdui-appbar-fixed">
            <div class="mdui-toolbar">
                <!-- Menu define -->
                <ul class="mdui-menu" id="menu_menu">
                    <li class="mdui-menu-item">
                        <a class="mdui-ripple">
                            <i class="mdui-menu-item-icon mdui-icon material-icons">home</i>主页
                        </a>
                    </li>
                    <!-- <li class="mdui-menu-item">
                        <a class="mdui-ripple">
                            <i class="mdui-menu-item-icon mdui-icon material-icons">dns</i>服务器列表
                        </a>
                    </li> -->
                    <li class="mdui-menu-item">
                        <a class="mdui-ripple">
                            <i class="mdui-menu-item-icon mdui-icon material-icons">do_not_disturb_alt</i>封禁列表
                        </a>
                    </li>
                    <li class="mdui-menu-item">
                        <a class="mdui-ripple">
                            <i class="mdui-menu-item-icon mdui-icon material-icons">mic_off</i>禁言列表
                        </a>
                    </li>
                    <li class="mdui-divider"></li>
                    <li class="mdui-menu-item">
                        <a class="mdui-ripple">
                            <i class="mdui-menu-item-icon mdui-icon material-icons">settings</i>管理员面板
                        </a>
                    </li>
                </ul>

                <ul class="mdui-menu" id="menu_user">
					<?php if(!$_SESSION["login"]): //NOTE: DEBUG VERSION ?>
                    <li class="mdui-menu-item">
                        <a class="mdui-ripple"><?php echo $User->getUserNickname(1); ?></a>
                    </li>
					<li class="mdui-divider"></li>
					<li class="mdui-menu-item">
						<a class="mdui-ripple">个人资料</a>
					</li>
					<li class="mdui-menu-item">
						<a class="mdui-ripple">修改密码</a>
					</li>
					<li class="mdui-divider"></li>
					<li class="mdui-menu-item">
						<a class="mdui-ripple">退出登录</a>
					</li>
					<?php else: ?>
					<li class="mdui-menu-item">
						<a class="mdui-ripple">登录</a>
					</li>
					<?php endif; ?>
                </ul>

                <a class="mdui-btn mdui-btn-icon" mdui-tooltip="{content: '菜单'}" mdui-menu="{target: '#menu_menu'}">
                    <i class="mdui-icon material-icons">menu</i>
                </a>
                <div class="mdui-toolbar-spacer"></div>
                <a class="mdui-btn mdui-btn-icon" mdui-tooltip="{content: '夜间模式切换'}" id="night-mode">
                    <i class="mdui-icon material-icons" id="night-mode-icon"></i>
                </a>
                <a class="mdui-btn mdui-btn-icon" mdui-tooltip="{content: '用户'}" mdui-menu="{target: '#menu_user'}">
                    <i class="mdui-icon material-icons">people</i>
                </a>
            </div>
        </div>

        <div class="mdui-container-fluid">
            <!-- info cards -->
            <div class="mdui-row">
                <div class="mdui-col-xs-3">
                    <div class="mdui-card">
                        <div class="mdui-card-primary">
                            <div class="mdui-card-primary-title">
                                <i class="mdui-icon material-icons">dns</i> 服务器数量
                            </div>
                        </div>
                        <div class="mdui-divider"></div>
                        <div class="mdui-card-content" id="server_count"><?php echo $list["data"]["count"]; ?></div>
                    </div>
                </div>
                <div class="mdui-col-xs-3">
                    <div class="mdui-card">
                        <div class="mdui-card-primary">
                            <div class="mdui-card-primary-title">
                                <i class="mdui-icon material-icons" id="online_count">people</i> 在线玩家
                            </div>
                        </div>
                        <div class="mdui-divider"></div>
                        <div class="mdui-card-content"><?php echo $list["data"]["online"]; ?></div>
                    </div>
                </div>
                <div class="mdui-col-xs-3">
                    <div class="mdui-card">
                        <div class="mdui-card-primary">
                            <div class="mdui-card-primary-title">
                                <i class="mdui-icon material-icons" id="ban_count">do_not_disturb_alt</i> 封禁数量
                            </div>
                        </div>
                        <div class="mdui-divider"></div>
                        <div class="mdui-card-content"><?php echo $Ban->getBanCount(); ?></div>
                    </div>
                </div>
                <div class="mdui-col-xs-3">
                    <div class="mdui-card">
                        <div class="mdui-card-primary">
                            <div class="mdui-card-primary-title">
                                <i class="mdui-icon material-icons" id="common_count">mic_off</i> 禁言数量
                            </div>
                        </div>
                        <div class="mdui-divider"></div>
                        <div class="mdui-card-content"><?php echo $Common->getCommonCount(); ?></div>
                    </div>
                </div>
            </div>
            <!-- server list -->
            <div class="mdui-row">
                <div class="mdui-col-xs-12">
                    <div class="mdui-table-fluid">
                        <table class="mdui-table mdui-table-hoverable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>服务器名字</th>
                                    <th>当前地图</th>
                                    <th>玩家数量</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
								<?php
									if ($list["code"] == 0){
										for ($i = 0; $i < $list["data"]["count"]; $i++): ?>
										<tr data-sid="<?php echo $list["data"]["data"][$i]["data"]["sid"] ?>" data-players="<?php echo $list["data"]["data"][$i]["data"]["players"]; ?>">
											<td><?php echo $list["data"]["data"][$i]["data"]["sid"] ?></td>
											<td data="hostname"><?php echo $list["data"]["data"][$i]["data"]["hostname"] ?></td>
											<td data="map"><?php echo $list["data"]["data"][$i]["data"]["map"] ?></td>
											<td>
												<div class="mdui-chip">
													<span class="mdui-chip-title"><?php echo $list["data"]["data"][$i]["data"]["players"] . " / " . $list["data"]["data"][$i]["data"]["maxPlayers"] ?></span>
												</div>
											</td>
											<td>
												<button class="mdui-fab mdui-fab-mini mdui-ripple" id="btn_join" mdui-tooltip="{content: '进入服务器'}" onclick="connectToServer('<?php echo $list["data"]["data"][$i]["data"]["ip"]; ?>', <?php echo $list["data"]["data"][$i]["data"]["port"]; ?>)"><i class="mdui-icon material-icons">flight_takeoff</i></button>
												<button class="mdui-fab mdui-fab-mini mdui-ripple" id="btn_copy" mdui-tooltip="{content: '复制'}" data-clipboard-text="<?php echo "{$list['data']['data'][$i]['data']['ip']}:{$list['data']['data'][$i]['data']['port']}"; ?>"><i class="mdui-icon material-icons">content_copy</i></button>
												<button class="mdui-fab mdui-fab-mini mdui-ripple" id="btn_detail" mdui-tooltip="{content: '详细'}" onclick="showServerInfo($(this).parent().parent().attr('data-sid'), $(this).parent().parent().attr('data-players'))"><i class="mdui-icon material-icons">format_list_bulleted</i></button>
											</td>
										</tr>
										<?php endfor;
									}
								?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- ban & common list -->
            <div class="mdui-row">
                <!-- ban list -->
                <div class="mdui-col-xs-6">
                    <div class="mdui-table-fluid">
                        <table class="mdui-table mdui-table-hoverable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>玩家名称</th>
                                    <th>封禁状态</th>
                                    <th>解封时间</th>
                                </tr>
                            </thead>
                            <tbody>
								<?php
									$list = $Ban->getBanList(5, 1, true);
									if ($list["code"] == 0){
										for ($i = 0; $i < 5; $i++): ?>
										<tr>
											<td><?php echo $list["data"][$i]["bid"]; ?></td>
											<td><?php echo $list["data"][$i]["name"]; ?></td>
											<td>
												<div class="mdui-chip">
													<span class="mdui-chip-icon mdui-color-<?php echo $list["data"][$i]["banned"] == true ? "amber":"green"; ?>">
														<i class="mdui-icon material-icons"><?php echo $list["data"][$i]["banned"] == true ? "do_not_disturb_alt":"check"; ?></i>
													</span>
													<span class="mdui-chip-title"><?php echo $list["data"][$i]["banned"] == true ? "正在封禁":"已解禁"; ?></span>
												</div>
											</td>
											<td><?php echo $list["data"][$i]["length"] == 0 ? "永久封禁":date("Y-m-d H:i:s", $list["data"][$i]["ban_time"] + $list["data"][$i]["length"]); ?></td>
										</tr>
										<?php endfor;
									}
								?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- common list -->
                <div class="mdui-col-xs-6">
                    <div class="mdui-table-fluid">
                        <table class="mdui-table mdui-table-hoverable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>玩家名称</th>
                                    <th>禁言状态</th>
                                    <th>解禁时间</th>
                                </tr>
                            </thead>
                            <tbody>
								
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
			<!-- joke -->
			<!--
			<div class="mdui-row">
				<div class="mdui-col-xs-2">
					<label class="mdui-slider mdui-slider-discrete">
						<input type="range" step="1" min="1" max="100" value="1" id="test">
					</label>
				</div>
				<div class="mdui-col-xs-1">
					<div class="mdui-textfield">
						<input class="mdui-textfield-input" placeholder="Page" type="text" id="test-text">
					</div>
				</div>
				<div class="mdui-col-xs-1">
					<button class="mdui-btn mdui-btn-raised mdui-ripple" id="test-button">点击跳转</button>
				</div>
				<div class="mdui-col-xs-1">
					<button class="mdui-btn mdui-btn-icon mdui-color-theme-accent mdui-ripple" id="test-button-add">
						<i class="mdui-icon material-icons">add</i>
					</button>
				</div>
			</div>
			-->
        </div>
    </body>
</html>
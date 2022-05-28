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
<html>
    <head>
        <meta charset="UTF-8">
 <script language="JavaScript">
setTimeout(function(){location.reload()},20000); //指定1秒刷新一次
</script>
        <link rel="stylesheet" href="https://jsd.kodplay.com/npm/mdui@1.0.2/dist/css/mdui.min.css">
        <script src="https://jsd.kodplay.com/npm/mdui@1.0.2/dist/js/mdui.min.js"></script>
        <script src="https://cdn.staticfile.org/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.staticfile.org/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
		<script src="https://jsd.kodplay.com/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
		  <link rel="stylesheet" href="/layui/css/layui.css"  media="all">

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
										<th>玩家昵称</th>
										<th>回合分数</th>
										<th>在线时长</th>
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
						message:"复制成功，将指令粘贴到CSGO控制台即可！",
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
   
        <div class="mdui-container-fluid">
            <!-- info cards -->
            <div class="mdui-row">
                <div class="mdui-col-xs-3">
                    <div class="mdui-card">
                        <div class="mdui-card-primary">
                            <div class="mdui-card-primary-title">
                                <i class="mdui-icon material-icons">dns</i> 当前在线服务器
                            </div>
                        </div>
                        <div class="mdui-divider"></div>
                        <div class="mdui-card-content" id="server_count"><?php echo $list["count"]; ?></div>
                    </div>
                </div>
                <div class="mdui-col-xs-3">
                    <div class="mdui-card">
                        <div class="mdui-card-primary">
                            <div class="mdui-card-primary-title">
                                <i class="mdui-icon material-icons" id="online_count">people</i> 当前在线玩家
                            </div>
                        </div>
                        <div class="mdui-divider"></div>
                        <div class="mdui-card-content"><?php echo $list["online"]; ?></div>
                    </div>
                </div>
                <div class="mdui-col-xs-3">
                    <div class="mdui-card">
                        <div class="mdui-card-primary">
                            <div class="mdui-card-primary-title">
                                <i class="mdui-icon material-icons" id="ban_count">do_not_disturb_alt</i> 社区总封禁
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
                                <i class="mdui-icon material-icons" id="common_count">mic_off</i> 社区总禁言
                            </div>
                        </div>
                        <div class="mdui-divider"></div>
                        <div class="mdui-card-content"><?php echo $Common->getCommonCount(); ?></div>
                    </div>
                </div>
            </div>
              <table lay-filter="demo">
  <thead>
    <tr>
      <th lay-data="{field:'sid',width:110 ,sort:true,align: 'center'}">服务器ID</th>
      <th lay-data="{field:'name', sort:true}">服务器名字</th>
      <th lay-data="{field:'ditu',sort:true , align: 'center'}">当前地图</th>
      <th lay-data="{field:'user', sort:true, align: 'center'}">玩家数量</th>
      <th lay-data="{field:'sign', align: 'center'}">操作</th>
    </tr> 
  </thead>
  <tbody>
   <?php
									if ($list["code"] == 0){
										for ($i = 0; $i < $list["count"]; $i++){
											if ($list["data"][$i]["code"] == 0): ?>	
											<tr data-sid="<?php echo $list["data"][$i]["sid"]; ?>" data-players="<?php echo $list["data"][$i]["players"]; ?>">
												<td><?php echo $list["data"][$i]["sid"] ?>F</td>
												<td data="hostname"><?php echo $list["data"][$i]["hostname"] ?></td>
												<td data="map"><?php echo $list["data"][$i]["map"] ?></td>
												<td>
<?php echo $list["data"][$i]["players"] . " / " . $list["data"][$i]["maxPlayers"] ?>
												</td>
												<td>
												    
													<button class="mdui-fab mdui-fab-mini mdui-ripple" id="btn_join" mdui-tooltip="{content: '进入服务器'}" onclick="connectToServer('<?php echo $list["data"][$i]["ip"]; ?>', <?php echo $list["data"][$i]["port"]; ?>)"><i class="mdui-icon material-icons">flight_takeoff</i></button>
													<button class="mdui-fab mdui-fab-mini mdui-ripple" id="btn_copy" mdui-tooltip="{content: '进服指令'}" data-clipboard-text="connect <?php echo "{$list['data'][$i]['ip']}:{$list['data'][$i]['port']}"; ?>"><i class="mdui-icon material-icons">content_copy</i></button>
													<button class="mdui-fab mdui-fab-mini mdui-ripple" id="btn_detail" mdui-tooltip="{content: '在线玩家'}" onclick="showServerInfo(<?php echo $list["data"][$i]["sid"]; ?>, <?php echo $list["data"][$i]["players"]; ?>)"><i class="mdui-icon material-icons">format_list_bulleted</i></button>
												</td>
											</tr>
											<?php else: ?>
											<tr>
												<td>Error</td>
												<td><?php echo $list["data"][$i]["msg"]; ?></td>
												<td></td>
												<td></td>
												<td></td>
											</tr>
											<?php endif;
										}
									}
								?>
  </tbody>
</table>


            <!-- server list 
            <div class="mdui-row">
                <div class="mdui-col-xs-12">
                    <div class="mdui-table-fluid">
                        <table class="mdui-table mdui-table-hoverable">
                            <thead>
                                <tr>
                                    <th>服务器ID</th>
                                    <th>服务器名字</th>
                                    <th>当前地图</th>
                                    <th>玩家数量</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
								<?php
									if ($list["code"] == 0){
										for ($i = 0; $i < $list["count"]; $i++){
											if ($list["data"][$i]["code"] == 0): ?>	
											<tr data-sid="<?php echo $list["data"][$i]["sid"]; ?>" data-players="<?php echo $list["data"][$i]["players"]; ?>">
												<td><?php echo $list["data"][$i]["sid"] ?>F</td>
												<td data="hostname"><?php echo $list["data"][$i]["hostname"] ?></td>
												<td data="map"><?php echo $list["data"][$i]["map"] ?></td>
												<td>
													<div class="mdui-chip">
														<span class="mdui-chip-title"><?php echo $list["data"][$i]["players"] . " / " . $list["data"][$i]["maxPlayers"] ?></span>
													</div>
												</td>
												<td>
													<button class="mdui-fab mdui-fab-mini mdui-ripple" id="btn_join" mdui-tooltip="{content: '进入服务器'}" onclick="connectToServer('<?php echo $list["data"][$i]["ip"]; ?>', <?php echo $list["data"][$i]["port"]; ?>)"><i class="mdui-icon material-icons">flight_takeoff</i></button>
													<button class="mdui-fab mdui-fab-mini mdui-ripple" id="btn_copy" mdui-tooltip="{content: '进服指令'}" data-clipboard-text="connect <?php echo "{$list['data'][$i]['ip']}:{$list['data'][$i]['port']}"; ?>"><i class="mdui-icon material-icons">content_copy</i></button>
													<button class="mdui-fab mdui-fab-mini mdui-ripple" id="btn_detail" mdui-tooltip="{content: '在线玩家'}" onclick="showServerInfo($(this).parent().parent().attr('data-sid'), $(this).parent().parent().attr('data-players'))"><i class="mdui-icon material-icons">format_list_bulleted</i></button>
												</td>
											</tr>
											<?php else: ?>
											<tr>
												<td>Error</td>
												<td><?php echo $list["data"][$i]["msg"]; ?></td>
												<td></td>
												<td></td>
												<td></td>
											</tr>
											<?php endif;
										}
									}
								?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>-->
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
													<span class="mdui-chip-title"><?php echo $list["data"][$i]["banned"] == true ? "正在坐牢":"已解封"; ?></span>
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
                            	<?php
                            		$list = $Common->getCommonList(5, 1, true);
                            		if ($list["code"] == 0){
                            			for ($i = 0; $i < 5; $i++): ?>
                            			<tr>
                            				<td><?php echo $list["data"][$i]["cid"]; ?></td>
                            				<td><?php echo $list["data"][$i]["name"]; ?></td>
                            				<td>
                            					<div class="mdui-chip">
                            						<span class="mdui-chip-icon mdui-color-<?php echo $list["data"][$i]["banned"] == true ? "amber":"green"; ?>">
                            							<i class="mdui-icon material-icons"><?php echo $list["data"][$i]["banned"] == true ? "do_not_disturb_alt":"check"; ?></i>
                            						</span>
                            						<span class="mdui-chip-title"><?php echo $list["data"][$i]["banned"] == true ? "正在禁言":"已解禁"; ?></span>
                            					</div>
                            				</td>
                            				<td><?php echo $list["data"][$i]["length"] == 0 ? "永久禁言":date("Y-m-d H:i:s", $list["data"][$i]["ban_time"] + $list["data"][$i]["length"]); ?></td>
                            			</tr>
                            			<?php endfor;
                            		}
                            	?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script src="/layui/layui.js" charset="utf-8"></script>
<script>
layui.use('table', function(){
  var table = layui.table;

//转换静态表格
table.init('demo', {
  //height: 315 //设置高度
  limit: 100 //注意：请务必确保 limit 参数（默认：10）是与你服务端限定的数据条数一致
  //支持所有基础参数
}); 
});
</script>
    </body>
</html>

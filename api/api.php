<?php
	require __DIR__ . "/configs/database.php";
	require __DIR__ . "/../libs/SourceQuery/bootstrap.php";
	use xPaw\SourceQuery\SourceQuery;
	
	if (isset($_POST["sid"])){
		$Server = new Server;
		print_r(json_encode($Server->getServerInfo($_POST["sid"]), JSON_UNESCAPED_UNICODE));
		return;
	}
	
    class Server {
		var $db;
		var $Query;
		function __construct(){
			$this->db = new Database;
			$this->Query = new SourceQuery;
		}
        function getServerList(){
			$result = $this->db->Query("SELECT * FROM sb_servers");
			if ($result->num_rows > 0){
				$list = [];
				$count = 0;
				$online = 0;
				while ($row = $result->fetch_assoc()){
					try {
						$this->Query->Connect($row["ip"], (int)$row["port"], 1, SourceQuery::SOURCE);
						$info = $this->Query->GetInfo();
						$online += $info["Players"];
						$tmp = array("code" => 0, "msg" => "", "data" => array("sid" => (int)$row["sid"], "ip" => $row["ip"], "port" => (int)$row["port"], "hostname" => $info["HostName"], "map" => $info["Map"], "players" => $info["Players"], "maxPlayers" => $info["MaxPlayers"], "bots" => $info["Bots"]));
					} catch (Exception $e){
						$tmp = array("code" => -1, "msg" => $e->getMessage(), "data" => "");
					} finally {
						$this->Query->Disconnect();
					}
					$list[] = $tmp;
					$count++;
				}
				return array("code" => 0, "msg" => "", "data" => array("count" => $count, "online" => $online, "data" => $list));
			}
        }
        function getServerInfo($sid){
            $result = $this->db->Query("SELECT * FROM sb_servers WHERE sid={$sid}");
			if ($result){
				$row = $result->fetch_assoc();
				try {
					$this->Query->Connect($row["ip"], (int)$row["port"], 1, SourceQuery::SOURCE);
					$info = $this->Query->GetPlayers();
				} finally {
					$this->Query->Disconnect();
				}
				return array("code" => 0, "msg" => "", "count" => count($info), "data" => $info);
			} else {
				return "error";
			}
        }
    }
	
	class Ban {
		var $db;
		function __construct(){
			$this->db = new Database;
		}
		function getBanList($list_rows, $page){
			$result = $this->db->Query("SELECT * FROM sb_bans");
			if ($result->num_rows > 0){
				$start = $list_rows * ($page - 1);
				$end = ($list_rows * $page) - 1;
				$rows = 0;
				$info = [];
				while ($row = $result->fetch_assoc()){
					if ($rows > $end){
						break;
					}
					if ($rows >= $start){
						$info[] = array("bid" => (int)$row["bid"], "steamid" => $row["authId"], "name" => $row["name"], "ip" => $row["ip"], "ban_time" => (int)$row["created"], "length" => (int)$row["length"], "reason" => $row["reason"], "remove_user" => $row["RemovedBy"], "remove_time" => (int)$row["RemovedOn"]);
					}
					$rows++;
				}
				return array("code" => 0, "msg" => "", "data" => $info);
			} else {
				return array("code" => -1, "msg" => "无封禁记录", "data" => "");
			}
		}
		function getBanInfo($bid){
			
		}
		function getBanCount(){
			return $this->db->Query("SELECT * FROM sb_bans")->num_rows;
		}
	}
	
	class Common {
		var $db;
		function __construct(){
			$this->db = new Database;
		}
		function getCommonList($list_rows, $page){
			$result = $this->db->Query("SELECT * FROM sb_comms");
			if ($result->num_rows > 0){
				$start = $list_rows * ($page - 1);
				$end = ($list_rows * $page) - 1;
				$rows = 0;
				$info = [];
				while ($row = $result->fetch_assoc()){
					if ($rows > $end){
						break;
					}
					if ($rows >= $start){
						$info[] = array("cid" => (int)$row["bid"], "steamid" => $row["authId"], "name" => $row["name"], "ip" => $row["ip"], "ban_time" => (int)$row["created"], "length" => (int)$row["length"], "reason" => $row["reason"], "remove_user" => $row["RemovedBy"], "remove_time" => (int)$row["RemovedOn"]);
					}
					$rows++;
				}
				return array("code" => 0, "msg" => "", "data" => $info);
			} else {
				return array("code" => -1, "msg" => "无禁言记录", "data" => "");
			}
		}
		function getCommonInfo($cid){
			
		}
		function getCommonCount(){
			return $this->db->Query("SELECT * FROM sb_comms")->num_rows;
		}
	}

    class User {
		var $db;
		function __construct(){
			$this->db = new Database;
		}
        function getUserInfo($uid){

        }
        function getUserNickname($uid){
			return $uid;
			$result = $this->db->Query("SELECT * FROM sb_users WHERE uid={$uid}");
			$row = $result->fetch_assoc();
			return $row["nickname"];
        }
		function getUserPermission($uid){
			$result = $this->db->Query("SELECT * FROM sb_users WHERE uid={$uid}");
			$row = $result->fetch_assoc();
			return $row["permission"];
		}
    }
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
	body{
    background-image: url("./img/space2.jpg");    
    background-size: auto 100%;
    background-repeat: no-repeat;
    background-position: center;
    height: 100vh;
    width: auto;
}
#currentPosi{
    margin-top: 10px;
    font-size: 15px;
    color: azure;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; 
}

/* #memo{
    position: absolute;
    height: 18px;
    width: 78%;
    margin: auto;
    top: 25px;
    right: 55px;
    left: 0;
    outline: none;
    border: none;
    border-radius: 10px;
    padding: 10px;
    font-size: 18px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
} */
/* button{
    position: absolute;
    height: 40px;
    width: 40px;
    margin: auto;
    top: 22px;
    right: 20px;
    cursor: pointer;
    background: azure;
    border: 0;
    border-radius: 10px;
    outline: none;  
    font-size:130%;
} */
#container{
    position: absolute;
    height: 740px;
    width: 600px;
    margin: auto;
    top: 30px;
    left: 0;
    right: 0;
    bottom: 0;
    backdrop-filter: blur(1px);
    box-shadow: 0 0 50px;
}
.jumbotron{
    position: absolute;
    width: 90%;
    height: 200px;
    margin: auto;
    left: 0;
    right: 0;
    bottom: 100px;
    font-size: 20px;
    color: azure;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; 
}

#myMap{
    position:absolute; 
    margin:auto; 
    bottom: 300px;
    right: 0;
    left: 0;
    width:90%; 
    height:380px;
    font-size: 15px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; 
}
#history{
    position:absolute;
    height: 35px;
    width: 95%;
    margin:auto;
    right: 0;
    left: 0;
    bottom: 165px;
    background-color: azure;
    border-radius: 10px;
}
#comment{
    position: absolute;
    margin: auto;
    left: 10px;
    top: 5px;
    font-size: 18px;  
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; 
    color:black
}
#mylist{
    position:absolute;
    height:28%;
    width: 100%;
    margin: auto;
    right: 0;
    left: 0;
    bottom: 5px;
    background-color: transparent; 
    overflow: auto;
    border-radius: 10px;
    font-size: 18px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; 
}
#mylistul{
    margin-right: 30px;
}
li{
    list-style-type: none;
}
a {
    font-weight:bold;
    text-decoration:none;
}
a:link {
    color: azure;
}
a:visited {
    color:#eff702;
}
a:hover {
    color:#ff00ae;
    text-decoration:underline;
}
a:active {
    color:#00ffa6;
}
</style>

<title>Footsteps</title>
<link href="./css/map.css" rel="stylesheet">
</head>
<body>
<div id="container">
<div id="currentPosi"></div>
<div id="input">
	<input type="text" id="memo">
	<button id="input_btn">👣</button>
</div>

<div id="myMap"></div>

<!-- Main[Start] -->
<form method="post" action="insert.php">
  <div class="jumbotron">
   <fieldset>
    <legend>営業履歴</legend>
	   <label>緯度経度：<input id="currentPosi" type="shown" value ="" name="position"></label><br>
     <label>物件名称：<input type="text" name="buildingName"></label><br>
     <label>担当者名：<input type="text" name="name"></label><br>
     <label>コメント：<textArea name="comment" rows="4" cols="40"></textArea></label><br>
     <input type="submit" value="送信">
    </fieldset>
  </div>
</form>

<!-- <div id="history">
<p id="comment"></p>
</div>
<div id="mylist"><ul id="mylistul"></ul></div> -->
</div>
<script src="https://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=Ag6XDobcfjCazLDIYvqssIBNpu-8sfU9kClhtfMYYOJJwSoj3MNoTkKrMXfy8pME" async defer></script>
<script src="./js/BmapQuery.js"></script>
<!-- jQuery&GoogleMapsAPI -->
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<!-- /jQuery&GoogleMapsAPI -->
<script>
//変数の初期化
var map = null;
var lat;
var lon;
/*
 * MAP表示・取得処理
 */	
// 位置情報を取得する処理
function loadmap(){
        navigator.geolocation.watchPosition(   //getCurrentPosition :or: watchPosition
                // 位置情報の取得に成功した時の処理
                function(position){
                        try{
                            lat = position.coords.latitude;   //緯度
                            lon = position.coords.longitude;  //経度
							matSet(lat,lon);
                        }catch(error){            
                            console.log("currentPosi:"+error);
                        }
                        listview();
                },
                // 位置情報の取得に失敗した場合の処理
                function(error){
                		var e = "";
                		if(error.code==1){
                    		e = "位置情報が許可されてません";
                		}
                		if(error.code==2){
                    		e = "現在位置を特定できません";
                		}
                		if(error.code==3){
                    		e = "位置情報を取得する前にタイムアウトになりました";
                		}
                        alert("エラー："+e);
                },
                // 位置情報取得オプション
                {
                        enableHighAccuracy : true,  //より高精度な位置を求める
                        maximumAge : 20000,         //最後の現在地情報取得が20秒以内であればその情報を再利用する設定
                        timeout : 10000             //10秒以内に現在地情報を取得できなければ、処理を終了
                }
        );
}

function matSet(lat,lon){
	// 現在の緯度・経度の値を id="currentPosi"の要素に表示する
	document.getElementById('currentPosi').innerHTML="[緯度："+lat+", 経度："+lon+"]";
	//
    // プッシュピン・ロケーションをセットする
    var loc = new Microsoft.Maps.Location(lat,lon);
    var pin = new Microsoft.Maps.Pushpin(loc);
             
    // MAP 初期化
    map = new Microsoft.Maps.Map(document.getElementById("myMap"),{
    	center: loc,
    	mapTypeId: Microsoft.Maps.MapTypeId.lightscale,
    	zoom: 17,
    	entities: pin
    }); 
    map.entities.push(pin);
}

function map_view(val){
	var g = val.split(',');
	matSet(g[0],g[1]);
	document.getElementById('comment').innerHTML=g[2];
}

//Time
function timeStamp(){
    var date = new Date();
    return (date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate() + " " + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds());
}

//list
function listview(){
	document.getElementById('mylistul').innerHTML=''; //リストを初期化
	var get='';  //””にしないとUndefinedが表示される”var get;”ではNG
	for (var i=0; i<localStorage.length; i++){
		var k = localStorage.key(i);                  //Key取得
		var s = "'"+localStorage.key(i)+"'";          //Key取得(シングルクォーテーションを前後に付与)
		var g = "'"+localStorage.getItem(k)+"'";      //Value取得(シングルクォーテーションを前後に付与)
		get += '<li id="remove_'+i+'">';              //以下３行はHTML文作成処理
		get += '<a href="javascript:void(0);" onclick="historyRemove('+i+','+s+')">♻️</a>　';
		get += '<a href="javascript:void(0);" onclick="map_view('+g+')"> ['+(i+1)+"] "+k+'</a>';
		get += '</li>';
	}
	document.getElementById('mylistul').innerHTML=get;  //上記ループ処理で作成したHTMLを表示
}

//履歴削除
function historyRemove(id,day){
	localStorage.removeItem(day);
	var x = document.getElementById('remove_'+id);
	x.parentNode.removeChild(x);
}

//Map：経度緯度をLocalStorageに登録
document.getElementById('input_btn').onclick = function(){
	var time = timeStamp();
	var txt = document.getElementById('memo').value;
	localStorage.setItem(time,lat+","+lon+","+txt);
	listview();
};

//START
loadmap();
</script>
</body>
</html>
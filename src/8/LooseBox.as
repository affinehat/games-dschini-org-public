package {
	
	import flash.display.*;
	import flash.text.*;
	import flash.events.*;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.net.*;
	import com.adobe.crypto.*;
		
	public class LooseBox extends MovieClip {
	
		public static const READY:String = 'looseBoxReady';
		public static const GO:String = 'looseBoxGo';
		
		public var inTween:Tween;
		public var outTween:Tween;
	
		public var score:TextField;
		public var level:TextField;
		public var startButton:MovieClip;
		
		public var urlRequest:URLRequest;
		public var urlVariables:URLVariables;
		public var urlLoader:URLLoader;
	
		function LooseBox():void
		{
			visible = false;
		}
		
		public function ready():void
		{
			urlVariables = new URLVariables();
			urlVariables.user = game.instance.data.username;
			urlVariables.level = game.instance.data.currentLevel+1;
			urlVariables.score = game.instance.data.score;
			urlVariables.secret = MD5.hash(game.instance.data.score + game.SECRET);
			urlRequest = new URLRequest(game.instance.data.urlSaveHighscore);
			//urlRequest.contentType = "application/x-www-formurlencoded";
			urlRequest.method = URLRequestMethod.POST;
			urlRequest.data = urlVariables;
			urlLoader = new URLLoader();
			//urlLoader.dataFormat = URLLoaderDataFormat.VARIABLES;
			try {
				if(		game.instance.url.host == 'games.localhost' 
					|| 	game.instance.url.host == 'games.dschini.org' 
					|| 	game.instance.url.host == 'gamesdev.dschini.org'){
					urlLoader.load(urlRequest);
				}
			} catch (error:Error) {
				trace("error");
			}
			
			visible = true;
			alpha = 0;

			score.text = "Score: "+game.instance.data.score;
			level.text = "Level: "+(game.instance.data.currentLevel+1);
			inTween = new Tween(this,"alpha",Regular.easeOut,alpha, 1, 20);
			inTween.addEventListener(TweenEvent.MOTION_FINISH,inTweenFinishHandler);
			startButton.mouseChildren = false;
			startButton.buttonMode = true;
			startButton.addEventListener(MouseEvent.CLICK,startButtonClickHandler);
		}
		
		public function inTweenFinishHandler(event:TweenEvent):void{
			inTween.removeEventListener(TweenEvent.MOTION_FINISH,inTweenFinishHandler);
			inTween = null;
			dispatchEvent(new Event(READY));
		}
		
		public function out():void{
			outTween = new Tween(this,"alpha",Regular.easeOut,alpha,0, 10);
			outTween.addEventListener(TweenEvent.MOTION_FINISH,outTweenFinishHandler);
		}
		
		public function outTweenFinishHandler(event:TweenEvent):void{
			outTween.removeEventListener(TweenEvent.MOTION_FINISH,outTweenFinishHandler);
			outTween = null;
			visible = false;
			dispatchEvent(new Event(GO));
		}
		
		private function startButtonClickHandler(event:MouseEvent):void{
			startButton.removeEventListener(MouseEvent.CLICK,startButtonClickHandler);
			out();
		}
		
		private function playMoreButtonClickHandler(event:MouseEvent):void{
			var request:URLRequest = new URLRequest("http://games.dschini.org/");
            try {            
                navigateToURL(request,"_blank");
            }
            catch (e:Error) {
            }
		}
	}
}
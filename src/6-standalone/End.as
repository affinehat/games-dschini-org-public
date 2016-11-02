package {
	
	import flash.display.*;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.events.*;
	import flash.text.*;
	import flash.net.*;
	import flash.errors.*;
	
	import com.adobe.crypto.*;
	
	public class End extends MovieClip {
	
		public static var PLAY_AGAIN:String = 'endPlayAgain';
	
		public var levelText:TextField;
		public var scoreText:TextField;
		public var playAgainButton:MovieClip;
		public var playMoreButton:MovieClip;
		public var copyright:MovieClip;
		public var urlRequest:URLRequest;
		public var urlVariables:URLVariables;
		public var urlLoader:URLLoader;
		
		public var inTween:Tween;
		public var outTween:Tween;
		
		public var data:Object;

		function End():void
		{
			cacheAsBitmap = true;
		}
		
		public function init(data:Object):void
		{
			this.data = data;
			
			/*
			urlVariables = new URLVariables();
			urlVariables.user = data.username;
			urlVariables.level = data.currentLevel+1;
			urlVariables.score = data.score;
			urlVariables.secret = MD5.hash(data.score + game.SECRET);
			urlRequest = new URLRequest(data.urlSaveHighscore);
			//urlRequest.contentType = "application/x-www-formurlencoded";
			urlRequest.method = URLRequestMethod.POST;
			urlRequest.data = urlVariables;
			urlLoader = new URLLoader();
			//urlLoader.dataFormat = URLLoaderDataFormat.VARIABLES;
			try {
				urlLoader.load(urlRequest);
			} catch (error:Error) {
				trace("error");
			}
			*/
			
			levelText.text = data.currentLevel+1;
			scoreText.text = data.score;
			playAgainButton.buttonMode = true;
			playAgainButton.addEventListener(MouseEvent.CLICK,playAgainButtonClickHandler);
			playMoreButton.buttonMode = true;
			playMoreButton.addEventListener(MouseEvent.CLICK,playMoreButtonClickHandler);
			inTween = new Tween(this,"alpha",Regular.easeOut,0, 1, 10);
			inTween.addEventListener(TweenEvent.MOTION_FINISH,inTweenFinishHandler);
		}
		
		public function inTweenFinishHandler(event:TweenEvent):void
		{
			inTween.removeEventListener(TweenEvent.MOTION_FINISH,inTweenFinishHandler);
			inTween = null;
		}
		
		public function outTweenFinishHandler(event:TweenEvent):void
		{
			outTween.removeEventListener(TweenEvent.MOTION_FINISH,outTweenFinishHandler);
			outTween = null;
			dispatchEvent(new Event(PLAY_AGAIN));
		}
		
		public function playAgainButtonClickHandler(event:MouseEvent):void
		{
			playAgainButton.removeEventListener(MouseEvent.CLICK,playAgainButtonClickHandler);
			outTween = new Tween(this,"alpha",Regular.easeIn,1, 0, 10);
			outTween.addEventListener(TweenEvent.MOTION_FINISH,outTweenFinishHandler);
		}
		
		public function playMoreButtonClickHandler(event:MouseEvent):void{
			var request:URLRequest = new URLRequest("http://games.dschini.org/");
            try {            
                navigateToURL(request,"_blank");
            }
            catch (e:Error) {
            }
		}
		
	}
}
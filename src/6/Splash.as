package {
	
	import flash.display.*;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.events.*;
	import flash.text.*;
	
	public class Splash extends MovieClip {
	
		public static var READY:String = 'splashReady';
		public static var GO:String = 'splashGo';
	
		public var title:TextField;
		public var startButton:MovieClip;
		public var fullscreenButton:MovieClip;
		
		public var inTween:Tween;
		public var outTween:Tween;
		
		public var data:Object;

		function Splash():void
		{
			cacheAsBitmap = true;
		}
		
		public function init(data:Object):void
		{
			this.data = data;
			title.text = 'Level ' + (data.currentLevel+1);
			fullscreenButton.buttonMode = true;
			fullscreenButton.addEventListener(MouseEvent.CLICK,fullscreenButtonClickHandler);
			startButton.buttonMode = true;
			startButton.addEventListener(MouseEvent.CLICK,startButtonClickHandler);
			inTween = new Tween(this,"alpha",Regular.easeOut,0, 1, 10);
			inTween.addEventListener(TweenEvent.MOTION_FINISH,inTweenFinishHandler);
		}
		
		public function inTweenFinishHandler(event:TweenEvent):void{
			inTween.removeEventListener(TweenEvent.MOTION_FINISH,inTweenFinishHandler);
			inTween = null;
			dispatchEvent(new Event(READY));
		}

		public function fullscreenButtonClickHandler(event:MouseEvent):void{
			if(game.instance.stage.displayState == StageDisplayState.NORMAL){
				//game.instance.stage.align = "C";
				//game.instance.stage.scaleMode = "exactFit";
				game.instance.stage.scaleMode = "exactFit";
				game.instance.stage.displayState = StageDisplayState.FULL_SCREEN;
				//game.instance.board.top.y = 0;
			}else{
				//game.instance.board.top.y = 0;
				//game.instance.stage.align = "C";
				//game.instance.stage.scaleMode = "noScale";
				game.instance.stage.scaleMode = "exactFit";
				game.instance.stage.displayState = StageDisplayState.NORMAL;
			}
		}
		
		public function outTweenFinishHandler(event:TweenEvent):void{
			outTween.removeEventListener(TweenEvent.MOTION_FINISH,outTweenFinishHandler);
			outTween = null;
			dispatchEvent(new Event(GO));
		}
		
		public function startButtonClickHandler(event:MouseEvent):void{
			startButton.removeEventListener(MouseEvent.CLICK,startButtonClickHandler);
			outTween = new Tween(this,"alpha",Regular.easeIn,1, 0, 10);
			outTween.addEventListener(TweenEvent.MOTION_FINISH,outTweenFinishHandler);
		}
		
	}
}
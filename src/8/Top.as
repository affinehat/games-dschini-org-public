package {
	
	import flash.display.*;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.events.*;
	import flash.text.*;
	import flash.net.*;
		
	public class Top extends Sprite {
	
		public static const READY:String = 'topReady';
		public static const GO:String = 'topGo';
	
		private var inTween:Tween;
		private var outTween:Tween;
		
		public var title:TextField;
		public var subtitle:TextField;
		
		private var factor:Number = 0;
		
		function Top():void
		{
			visible = false;
		}
		
		function ready():void{
			visible = true;
			factor = 150 / game.instance.data.levels[game.instance.data.currentLevel].minScore;
			statusbox.levelscoreMask.width = 0;
			setScore();
			setLevel();
			setFound( 0 );
			setRequired( game.instance.data.levels[game.instance.data.currentLevel].maxTargets );
			statusbox.levelscoreMask.width = 0;
			inTween = new Tween(this,"y",Regular.easeOut,-100, 0, 10);
			inTween.addEventListener(TweenEvent.MOTION_FINISH,inTweenFinishHandler);
		}
		
		public function go():void
		{
		}
		
		public function out():void{
			outTween = new Tween(this,"y",Regular.easeOut,0, -100, 10);
			outTween.addEventListener(TweenEvent.MOTION_FINISH,outTweenFinishHandler);
		}
		
		public function outTweenFinishHandler(event:TweenEvent):void{
			outTween.removeEventListener(TweenEvent.MOTION_FINISH,outTweenFinishHandler);
			outTween = null;
			visible = false;
		}

		public function inTweenFinishHandler(event:TweenEvent):void{
			inTween.removeEventListener(TweenEvent.MOTION_FINISH,inTweenFinishHandler);
			inTween = null;
			dispatchEvent(new Event(READY));
		}
		
		function dispose():void{
		}

		public function setScore():void{
			scorebox.score.text = game.instance.data.score;
		}

		public function setLevel():void{
			scorebox.level.text = game.instance.data.currentLevel+1;
		}

		public function setRequired( value:int ):void{
			statusbox.required.text = value;
		}

		public function setFound( value:int ):void{
			statusbox.found.text = value;
		}
		
		public function setTimer( found:int, required:int ):void{
			var foundWidth:Number = 150/ required * found;
			new Tween(statusbox.levelscoreMask,"width",Regular.easeOut,
						statusbox.levelscoreMask.width,foundWidth,10);
		}
		
	}
}
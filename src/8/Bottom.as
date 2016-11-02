package {
	
	import flash.display.*;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.events.*;
	import flash.text.*;
	import flash.net.*;
		
	public class Bottom extends Sprite {
	
		public static const READY:String = 'bottomReady';
		public static const GO:String = 'bottomGo';
	
		private var inTween:Tween;
		private var outTween:Tween;
		
		public var timerbar:Timerbar;
	
		function Bottom():void
		{
			//visible = false;
		}
		
		public function go():void
		{
			timerbar.go();
		}
		
		public function out():void{
			outTween = new Tween(this,"y",Regular.easeOut,560, 600, 10);
			outTween.addEventListener(TweenEvent.MOTION_FINISH,outTweenFinishHandler);
		}
		
		public function outTweenFinishHandler(event:TweenEvent):void{
			outTween.removeEventListener(TweenEvent.MOTION_FINISH,outTweenFinishHandler);
			outTween = null;
			//visible = false;
		}
		
		public function ready():void{
			//visible = true;
			inTween = new Tween(this,"y",Regular.easeOut,600, 560, 10);
			inTween.addEventListener(TweenEvent.MOTION_FINISH,inTweenFinishHandler);
			timerbar.ready();
		}

		public function inTweenFinishHandler(event:TweenEvent):void{
			inTween.removeEventListener(TweenEvent.MOTION_FINISH,inTweenFinishHandler);
			inTween = null;
			dispatchEvent(new Event(READY));
		}
		
		function dispose():void{
		}
		
	}
}
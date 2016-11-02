package {
	
	import flash.display.*;
	import flash.events.*;
	import flash.utils.*;
	import fl.transitions.*;
	import flash.text.*;
	import fl.transitions.easing.*;
	import flash.geom.ColorTransform;
	
	public class Timerbar extends MovieClip {
	
		public static var TIMER_FINISH:String = "timerFinish";
		public static var TIMER_FREEZE:String = "timerFreeze";
		public static var TOOGLE_SOUND:String = "toogleSound";
	
		public var inTween:Tween;
		public var outTween:Tween;
		
		public var duration:uint;
		public var timer:Timer;
		
		public var title:TextField;
		public var interpret:TextField;
		public var volume:MovieClip;
		public var timerbarBar:MovieClip;
		public var timerbarMask:MovieClip;
		
		public var data:Object;
		
		var colorTransform:ColorTransform ;

		function Timerbar():void
		{
			timer = new Timer(5);
			timer.addEventListener(TimerEvent.TIMER, timerHandler);
			timer.addEventListener(TimerEvent.TIMER_COMPLETE, timerCompleteHandler);
			volume.addEventListener(MouseEvent.CLICK, volumeClickHandler);
			volume.buttonMode = true;
			colorTransform = timerbarBar.transform.colorTransform;
			colorTransform.color = 0x006600;
			timerbarBar.transform.colorTransform = colorTransform;
		}
		
		public function volumeClickHandler(event:MouseEvent):void
		{
			dispatchEvent(new Event(TOOGLE_SOUND));
			volume.alpha = data.volume <=0 ? 0.25 : 0.75;
			interpret.alpha = data.volume <=0 ? 0.25 : 1;
		}
		
		public function init(data:Object):void
		{
			this.data = data;
			volume.alpha = data.volume <=0 ? 0.25 : 0.75;
			interpret.alpha = data.volume <=0 ? 0.25 : 1;
			interpret.text = data.levels[data.currentLevel].loop.title
							+ " by "
							+ data.levels[data.currentLevel].loop.author;
			colorTransform.color = 0x006600;
			timerbarBar.transform.colorTransform = colorTransform;
				
			duration = data.levels[data.currentLevel].maxTime;
			timer.repeatCount = duration/5;
            timer.reset();
            timer.start();
		}
		
		public function freeze():void
		{
			timer.stop();
			dispatchEvent(new Event(TIMER_FREEZE));
		}
		
		public function timerHandler(event:TimerEvent):void
		{
			title.text = Math.round((duration - timer.currentCount * timer.delay)/1000) + " seconds left";
			timerbarMask.scaleX = 1 / duration * (duration - timer.currentCount * timer.delay);
			if(timerbarMask.scaleX<=0.25){
				colorTransform.color = 0x990000;
			} else if(timerbarMask.scaleX<=0.50){
				colorTransform.color = 0x996600;
			} else if(timerbarMask.scaleX<=0.75){
				colorTransform.color = 0x006666;
			} else {
				colorTransform.color = 0x006600;
			}
			timerbarBar.transform.colorTransform = colorTransform;
		}
		
		public function timerCompleteHandler(event:TimerEvent):void
		{
			dispatchEvent(new Event(TIMER_FINISH));
		}
		
		public function dispose():void
		{
			if(volume){
				volume.removeEventListener(MouseEvent.CLICK, volumeClickHandler);
			}
			if(timer){
				timer.stop();
				timer.removeEventListener(TimerEvent.TIMER, timerHandler);
				timer.removeEventListener(TimerEvent.TIMER_COMPLETE, timerCompleteHandler);
				timer = null;
			}
		}
		
	}
}
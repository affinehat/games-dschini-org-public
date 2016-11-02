package {
	
	import flash.display.*;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.events.*;
	import flash.text.*;
	import flash.utils.*;
	import flash.media.*;
	
	public class Infobox extends MovieClip {
	
		public static var NEXT:String = 'infoboxNext';
		public var data:Object;
		
		public var nextButton:MovieClip;
		
		public static var SCALE:Number = 34;
		
		public var title:TextField;
		public var radius:TextField;
		public var total:TextField;
		public var target:TextField;
		public var bonusTime:TextField;
		public var scoreRadius:TextField;
		public var bonusRadius:TextField;
		
		public var nextButtonTimer:Timer;
		
		function Infobox():void
		{
			title.autoSize = TextFieldAutoSize.CENTER;
			radius.autoSize = TextFieldAutoSize.CENTER;
			target.autoSize = TextFieldAutoSize.LEFT;
			total.autoSize = TextFieldAutoSize.LEFT;
			bonusTime.autoSize = TextFieldAutoSize.LEFT;
			scoreRadius.autoSize = TextFieldAutoSize.LEFT;
			bonusRadius.autoSize = TextFieldAutoSize.LEFT;
		}
		
		public function init(data:Object):void
		{
			this.data = data;
			data.soundLoopsChannel.soundTransform = new SoundTransform(
															this.data.volume >= 0.9
															? 0.6
															: 0
															,0);

			title.text = data.levels[data.currentLevel].targets[data.currentLevelTarget].title;
			title.autoSize = TextFieldAutoSize.CENTER;
			var radiusScaled:Number = (data.levels[data.currentLevel].targets[data.currentLevelTarget].radius*SCALE);
			/* make some rand numbers when radiusscaled is not 0*/
			var radiusScaledKM:Number = radiusScaled==0 ? radiusScaled : radiusScaled + (SCALE*(Math.random()-0.5));
			radius.text = (radiusScaledKM<0?0:Math.round(radiusScaledKM)) + " km";
			bonusTime.text = data.levels[data.currentLevel].targets[data.currentLevelTarget].bonusTime;
			scoreRadius.text = data.levels[data.currentLevel].targets[data.currentLevelTarget].scoreRadius;
			bonusRadius.text = "x" + data.levels[data.currentLevel].targets[data.currentLevelTarget].bonusRadius;
			bonusRadius.x = scoreRadius.x + scoreRadius.width;
			total.text = data.levels[data.currentLevel].targets[data.currentLevelTarget].bonusTime
						+ data.levels[data.currentLevel].targets[data.currentLevelTarget].scoreRadius
						* data.levels[data.currentLevel].targets[data.currentLevelTarget].bonusRadius;
			target.text = 	(data.currentLevelTarget+1)
							+ "/"
							+ data.levels[data.currentLevel].maxTargets;
			
			radius.y = title.y + title.height;
			
			nextButton.buttonMode = true;
			nextButton.addEventListener(MouseEvent.CLICK,nextButtonClickHandler);
			nextButtonTimer = new Timer(10,350);
			nextButtonTimer.addEventListener(TimerEvent.TIMER, nextButtonTimerHandler);
			nextButtonTimer.addEventListener(TimerEvent.TIMER_COMPLETE, nextButtonClickHandler);
			nextButtonTimer.start();
		}
		
		public function nextButtonClickHandler(event:Event):void
		{
			data.soundLoopsChannel.soundTransform = new SoundTransform(this.data.volume,0);
			nextButtonTimer.removeEventListener(TimerEvent.TIMER, nextButtonTimerHandler);
			nextButtonTimer.removeEventListener(TimerEvent.TIMER_COMPLETE, nextButtonClickHandler);
			nextButton.removeEventListener(MouseEvent.CLICK,nextButtonClickHandler);
			nextButtonTimer.stop();
			nextButtonTimer = null;
			dispatchEvent(new Event(NEXT));
		}
		
		public function nextButtonTimerHandler(event:TimerEvent):void
		{
			nextButton.nextButtonMask.width -= 160/350; 
		}
		
		public function dispose():void
		{
			data = null;
			nextButtonTimer = null;
		}
		
	}
}
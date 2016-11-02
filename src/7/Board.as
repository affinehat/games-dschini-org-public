package {
	
	import flash.display.*;
	import flash.events.*;
	import flash.text.*;
	import flash.utils.*;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.external.ExternalInterface;
    import flash.ui.Mouse;
	import flash.media.*;
	import flash.filters.*;
	
	import fxs.*;
	
	public class Board extends MovieClip {
	
		public static var START:String = 'boardStart';
		public static var WIN:String = 'boardWin';
		public static var END:String = 'boardEnd';
		public static var CONTINUE:String = 'boardContinue';
	
		public var top:MovieClip;
		public var bottom:MovieClip;
		public var map:Map;
		
		//public var trailerText:TrailerText = new TrailerText();
		public var targetText:TargetText;
		public var timerbar:Timerbar;
		public var infobox:Infobox;
		public var alertbox:Alertbox;
		public var statusbox:Statusbox;
		public var scorebox:Scorebox;
		
		private var inTweenTargetTextY:Tween;
		private var inTweenTargetTextAlpha:Tween;
		private var outTweenTargetTextY:Tween;
		private var inTweenInfobox:Tween;
		private var outTweenInfobox:Tween;
		private var inTweenStatusbox:Tween;
		private var outTweenStatusbox:Tween;
		private var inTweenScorebox:Tween;
		private var outTweenScorebox:Tween;
		private var inTweenAlertbox:Tween;
		private var outTweenAlertbox:Tween;
		private var inTweenTop:Tween;
		private var inTweenBottom:Tween;
		private var outTweenTop:Tween;
		private var outTweenBottom:Tween;
		private var inTweenTimerbar:Tween;
		private var outTweenTimerbar:Tween;
		
		public var data:Object;
		
		public var fx1:Fx1;
		public var fx2:Fx2;
		public var fx3:Fx3;
		public var fx4:Fx4;
		public var fx5:Fx5;
		
		public var fxYeahs:ExtendedArray;
		public var blurTimer:Timer;
		
		function Board():void
		{
		}
		
		public function init(data:Object):void
		{
			this.data = data;
			fx1 = new Fx1();
			fx2 = new Fx2();
			fx3 = new Fx3();
			fx4 = new Fx4();
			fx5 = new Fx5();
			
			fxYeahs = new ExtendedArray();
			fxYeahs.push(fx3);
			fxYeahs.push(fx4);
			fxYeahs.push(fx4);
			
			setTimeout(timerStart,50);
			data.levels[data.currentLevel].targets.shuffle();
			map.init(data);
			map.cacheAsBitmap = true;
			inTween();
		}
		
		public function addMouseListeners():void
		{
			map.addEventListener(Map.MAP_CLICK, mapClickHandler);
		}
		
		public function addTimerListeners():void
		{
			timerbar.addEventListener(Timerbar.TIMER_FINISH, timerbarHandler);
			timerbar.addEventListener(Timerbar.TIMER_FREEZE, timerbarHandler);
			timerbar.addEventListener(Timerbar.TOOGLE_SOUND, toogleSoundHandler);
		}
		
		public function removeMouseListeners():void
		{
			map.removeEventListener(Map.MAP_CLICK, mapClickHandler);
		}
		
		public function removeTimerListeners():void
		{
			timerbar.removeEventListener(Timerbar.TIMER_FINISH, timerbarHandler);
			timerbar.removeEventListener(Timerbar.TIMER_FREEZE, timerbarHandler);
		}
		
		public function timerStart():void
		{
			fxYeahs.shuffle();
			map.addListeners();
			addMouseListeners();
			addTimerListeners();
			statusbox.init(data);
			scorebox.init(data);
			var _title:String = data.levels[data.currentLevel].targets[data.currentLevelTarget].title;

			targetText.init(_title);
			targetText.x = 400;
			inTweenTargetTextY = new Tween(targetText,"y",Regular.easeOut,-25, 50, 10);
			inTweenTargetTextAlpha = new Tween(targetText,"alpha",Regular.easeOut,0, 1, 5);
			addChild(targetText);
			dispatchEvent(new Event(START));
			timerbar.init(data);
		}
		
		public function mapClickHandler(event:Event):void
		{
			removeMouseListeners();
			map.removeListeners();
			timerbar.freeze();
			fx1.play();
			removeTimerListeners();
		}
		
		public function toogleSoundHandler(event:Event):void
		{
			game.instance.volume = data.volume = data.volume <= 0 
					? 0.9 
					: 0;
			data.soundLoopsChannel.soundTransform = new SoundTransform(data.volume,0);
		}
		
		public function blurOn(event:TimerEvent):void
		{
			var blur:BlurFilter = new BlurFilter();
			blur.blurX = 2;
			blur.blurY = 2;
			blur.quality = BitmapFilterQuality.LOW;
			map.world.worldShape.filters = [blur];
			map.world.worldBorders.filters = [blur];
		}        
		 
		public function blurOff(event:TimerEvent):void
		{
			var blur:BlurFilter = new BlurFilter();
			blur.blurX = 0;
			blur.blurY = 0;
			blur.quality = BitmapFilterQuality.HIGH;
			map.world.worldShape.filters = [blur];
			map.world.worldBorders.filters = [blur];
			blurTimer.removeEventListener(TimerEvent.TIMER,blurOn);
			blurTimer.removeEventListener(TimerEvent.TIMER_COMPLETE,blurOff);
			blurTimer.stop();
			blurTimer = null;
		}
		
		public function timerbarHandler(event:Event):void
		{
			var xPos:int;
			var yPos:int;
			
			inTweenTargetTextY = null;
			inTweenTargetTextAlpha = null;

			switch(event.type){
				case Timerbar.TIMER_FINISH:
				
					/* the flags */
					xPos = data.levels[data.currentLevel].targets[data.currentLevelTarget].x;
					yPos = data.levels[data.currentLevel].targets[data.currentLevelTarget].y;
					map.theRedFlag(xPos,yPos);
					
					alertbox = new Alertbox()
					alertbox.init(data);
					inTweenAlertbox = new Tween(alertbox,"y",Strong.easeOut,200, 300, 10);
					if(data.levels[data.currentLevel].targets[data.currentLevelTarget].x>400){
						alertbox.x = 200;
					} else {
						alertbox.x = 600;
					}
					alertbox.addEventListener(Alertbox.CONTINUE,nextHandler);
					addChild(alertbox);
					
					removeMouseListeners();
					map.removeListeners();
					removeTimerListeners();
					fx1.play();
					map.removeChild(map.cursor);
					Mouse.show();
					//map.dispose();
					
					break;
				case Timerbar.TIMER_FREEZE:

					/* the flags */
					xPos = data.levels[data.currentLevel].targets[data.currentLevelTarget].x;
					yPos = data.levels[data.currentLevel].targets[data.currentLevelTarget].y;
					map.theRedFlag(xPos,yPos);
					xPos = map.cursorPositionOnClick.x;
					yPos = map.cursorPositionOnClick.y;
					map.theGreenFlag(xPos,yPos);
					
					var time:int = timerbar.timer.currentCount;
					var radius:int = map.distance.radius;
					var bonusTime:int = Math.round((timerbar.timer.repeatCount-time)/40);
					var scoreRadius:int = 100-radius < 0 ? 0 : 100-radius;
					var bonusRadius:int = 1;
					if(radius<=1){
						blurTimer = new Timer(1,5);
						blurTimer.addEventListener(TimerEvent.TIMER,blurOn);
						blurTimer.addEventListener(TimerEvent.TIMER_COMPLETE,blurOff);
						blurTimer.start();
						bonusRadius = 5;
						data.soundFxsChannel = fx2.play();
						data.soundFxsChannel.soundTransform = new SoundTransform(0.75,0);
					} else if(radius<=6){
						data.soundFxsChannel = fxYeahs[Math.round(Math.random()*2)].play();
						data.soundFxsChannel.soundTransform = new SoundTransform(0.75,0);
						bonusRadius = 3;
					} else {
						bonusRadius = 1;
					}
  
					data.levels[data.currentLevel].targets[data.currentLevelTarget].time = time;
					data.levels[data.currentLevel].targets[data.currentLevelTarget].radius = radius;
					data.levels[data.currentLevel].targets[data.currentLevelTarget].bonusTime = bonusTime;
					data.levels[data.currentLevel].targets[data.currentLevelTarget].scoreRadius = scoreRadius;
					data.levels[data.currentLevel].targets[data.currentLevelTarget].bonusRadius = bonusRadius;
					data.levels[data.currentLevel].score += bonusTime + scoreRadius * bonusRadius;
					data.score += bonusTime + scoreRadius * bonusRadius;
					statusbox.init(data);
					scorebox.init(data);
					infobox = new Infobox()
					infobox.init(data);
					inTweenInfobox = new Tween(infobox,"y",Strong.easeOut,200, 300, 10);
					if(Math.abs(map.cursorPositionOnClick.x - data.levels[data.currentLevel].targets[data.currentLevelTarget].x) > 280) {
						infobox.x = map.cursorPositionOnClick.x > data.levels[data.currentLevel].targets[data.currentLevelTarget].x
							? data.levels[data.currentLevel].targets[data.currentLevelTarget].x 
								+ Math.abs(map.cursorPositionOnClick.x 
								- data.levels[data.currentLevel].targets[data.currentLevelTarget].x)/2
							: map.cursorPositionOnClick.x 
								+ Math.abs(map.cursorPositionOnClick.x 
								- data.levels[data.currentLevel].targets[data.currentLevelTarget].x)/2;
					} else if(
						map.cursorPositionOnClick.x + data.levels[data.currentLevel].targets[data.currentLevelTarget].x < 800
						) {
						infobox.x = 600;
					} else if(
						map.cursorPositionOnClick.x + data.levels[data.currentLevel].targets[data.currentLevelTarget].x > 800
						) {
						infobox.x = 200;
					} else if(data.levels[data.currentLevel].targets[data.currentLevelTarget].x >= 400
						&& map.cursorPositionOnClick.x >= 400 ){
						infobox.x = 200;
					} else if(data.levels[data.currentLevel].targets[data.currentLevelTarget].x <= 400
						&& map.cursorPositionOnClick.x <= 400 ){
						infobox.x = 600;
					} else {
						infobox.x = 0;
					}
					infobox.addEventListener(Infobox.NEXT,nextHandler);
					addChild(infobox);
					
					break;
			}
		}
		
		public function nextHandler(event:Event):void
		{
			data.currentLevelTarget++;
			if(inTweenInfobox){
				inTweenInfobox.stop();
				inTweenInfobox = null;
			}
			if(infobox){
				infobox.removeEventListener(Infobox.NEXT,nextHandler);
				infobox.dispose();
				removeChild(infobox);
				infobox = null;
			}
			if(inTweenAlertbox){
				inTweenAlertbox.stop();
				inTweenAlertbox = null;
			}
			if(alertbox){
				alertbox.removeEventListener(Alertbox.CONTINUE,nextHandler);
				alertbox.dispose();
				removeChild(alertbox);
				alertbox = null;
			}
			map.dispose();
			
			if(data.currentLevelTarget>=data.levels[data.currentLevel].maxTargets){
				outTween();
				if(data.levels[data.currentLevel].minScore <= data.levels[data.currentLevel].score){
					data.currentLevel++;
					data.currentLevelTarget = 0;
					if(data.currentLevel >= data.levels.length){
						setTimeout(dispatchEvent,300,new Event(WIN));
					} else {
						setTimeout(dispatchEvent,300,new Event(CONTINUE));
					}
				}else{
					setTimeout(dispatchEvent,300,new Event(END));
				}
				return;
			}
			
			map.init(data);
			setTimeout(timerStart,50);
		}


		/*
		 * Tweens
		 */
		 
		public function inTween():void
		{
			inTweenStatusbox = new Tween(statusbox,"y",Regular.easeOut,statusbox.y-statusbox.height, statusbox.y, 10);
			inTweenScorebox = new Tween(scorebox,"y",Regular.easeOut,scorebox.y-scorebox.height, scorebox.y, 10);
			inTweenTop = new Tween(top,"y",Regular.easeOut,top.y-top.height, top.y, 10);
			inTweenTimerbar = new Tween(timerbar,"y",Regular.easeOut,timerbar.y+bottom.height, timerbar.y, 10);
			inTweenBottom = new Tween(bottom,"y",Regular.easeOut,bottom.y+bottom.height, bottom.y, 10);
		}
		
		public function outTween():void
		{
			outTweenStatusbox = new Tween(statusbox,"y",Regular.easeOut,statusbox.y, statusbox.y-statusbox.height, 10);
			outTweenScorebox = new Tween(scorebox,"y",Regular.easeOut,scorebox.y, scorebox.y-scorebox.height, 10);
			outTweenTop = new Tween(top,"y",Regular.easeOut,top.y, top.y-top.height, 10);
			outTweenTimerbar = new Tween(timerbar,"y",Regular.easeOut,timerbar.y, timerbar.y+top.height, 10);
			outTweenBottom = new Tween(bottom,"y",Regular.easeOut,bottom.y, bottom.y+bottom.height, 10);
			outTweenTargetTextY = new Tween(targetText,"y",Regular.easeOut,50, -25, 10);
		}
		
		/*
		 * cleanup
		 */
		
		public function dispose():void
		{
			fx1 = null;
			fx2 = null;
			fx3 = null;
			fx4 = null;
			fx5 = null;
			fxYeahs = null;
			inTweenAlertbox = null;
			inTweenStatusbox = null;
			inTweenScorebox = null;
			inTweenTop = null;
			inTweenBottom = null;
			inTweenTimerbar = null;
			outTweenAlertbox = null;
			outTweenStatusbox = null;
			outTweenScorebox = null;
			outTweenTop = null;
			outTweenTimerbar = null;
			outTweenBottom = null;
			inTweenTargetTextY = null;
			inTweenTargetTextAlpha = null;
			outTweenTargetTextY = null;
			timerbar.removeEventListener(Timerbar.TOOGLE_SOUND, toogleSoundHandler);
			timerbar.removeEventListener(Timerbar.TIMER_FINISH, timerbarHandler);
			timerbar.removeEventListener(Timerbar.TIMER_FREEZE, timerbarHandler);
			map.removeEventListener(MouseEvent.CLICK, mapClickHandler);
		}
		 
	}
}
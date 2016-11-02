package {
	
	import flash.display.*;
	import flash.events.*;
	import flash.utils.*;
	import fl.transitions.*;
	import flash.text.*;
	import fl.transitions.easing.*;
	import flash.geom.*;
	import flash.media.*;
	
	public class Timerbar extends MovieClip {
	
		public static const TIMER_FREEZE:String = "timerFreeze";
		public static const TOOGLE_SOUND:String = "toogleSound";
	
		public var inTween:Tween;
		public var outTween:Tween;
		
		public var duration:int;
		public var timer:Timer;
		
		public var title:TextField;
		public var interpret:TextField;
		public var volume:MovieClip;
		public var timerbarBar:MovieClip;
		public var timerbarMask:MovieClip;
		
		public var colorTransform:ColorTransform ;
		public var startDate:Date;
		
		
		/*
		public function timerHandler(event:TimerEvent):void
		{
			title.text = Math.round((duration - timer.currentCount * timer.delay)/1000) + " seconds left";
			timerbarMask.scaleX = 1 / duration * (duration - timer.currentCount * timer.delay);
		}
		
		public function timerCompleteHandler(event:TimerEvent):void
		{
			game.instance.board.status = Board.FAILED;
			game.instance.board.out();
		}
		*/
		
		private function enterFrameHandler( e:Event ):void {
			var currentDate:Date = new Date();
			if( currentDate.time - startDate.time >= duration ){
				startDate = new Date();
				removeEventListener( Event.ENTER_FRAME, enterFrameHandler );
				game.instance.board.status = Board.FAILED;
				game.instance.board.out();
				return;
			}
			title.text = Math.round((duration - (currentDate.time - startDate.time))/1000) + " seconds left";
			timerbarMask.scaleX = 1 / duration * (duration - (currentDate.time - startDate.time));
		}

		function Timerbar():void
		{
			/*
			timer = new Timer(5);
			timer.addEventListener(TimerEvent.TIMER, timerHandler);
			timer.addEventListener(TimerEvent.TIMER_COMPLETE, timerCompleteHandler);
			*/
			volume.addEventListener(MouseEvent.CLICK, volumeClickHandler);
			volume.buttonMode = true;
		}
		
		public function volumeClickHandler(event:MouseEvent):void
		{
			var _check:Number = game.instance.data.loopsChannel.volume <= 0 ? .9 : 0 ;
			game.instance.mute = _check <= 0 ? true : false;
			game.instance.data.loopsChannel.volume = _check;
			volume.alpha = interpret.alpha = ( _check <=.2 ? .2 : .8 );
		}
		
		public function ready():void
		{
			title.text = "";
			volume.alpha = game.instance.data.volume <=0 ? 0.25 : 0.75;
			interpret.alpha = game.instance.data.volume <=0 ? 0.25 : 1;

			interpret.text = game.instance.data.levels[game.instance.data.currentLevel].loop.title
							+ " by "
							+ game.instance.data.levels[game.instance.data.currentLevel].loop.author;
			duration = game.instance.data.levels[game.instance.data.currentLevel].maxTime;
			timerbarMask.scaleX = 1; // / duration * (duration - timer.currentCount * timer.delay);
		}
		
		public function go():void
		{
			startDate = new Date();
			addEventListener( Event.ENTER_FRAME, enterFrameHandler );
			//timer.repeatCount = duration/5;
            //timer.reset();
            //timer.start();
		}
		
		public function freeze():void
		{
			startDate = new Date();
			removeEventListener( Event.ENTER_FRAME, enterFrameHandler );
//game.instance.data.score += Math.round((duration - timer.currentCount * timer.delay)/1000);
			game.instance.top.setScore();
			//timer.stop();
		}
		
		public function dispose():void
		{
			if(volume){
				volume.removeEventListener(MouseEvent.CLICK, volumeClickHandler);
			}
			removeEventListener( Event.ENTER_FRAME, enterFrameHandler );
			/*
			if(timer){
				timer.stop();
				timer.removeEventListener(TimerEvent.TIMER, timerHandler);
				timer.removeEventListener(TimerEvent.TIMER_COMPLETE, timerCompleteHandler);
				timer = null;
			}
			*/
		}
		
	}
}
package {
	
	import flash.display.*;
	import flash.utils.*;
	import flash.events.*;
	
	public class Distance extends MovieClip {
	
		public var radius:Number;
		public var circle:MovieClip;
		public var timer:Timer;
		
		private var _radius:Number;
	
		function Distance():void
		{
			timer = new Timer(10);
			timer.addEventListener(TimerEvent.TIMER, timerHandler);
		}
		
		public function init(data:Object):void
		{
			radius = data.radius;
			if(circle){
				removeChild(circle);
				circle = null;
			}
			_radius = 1;
			circle = new MovieClip();
			addChild(circle);
			timer.start();
		}
		
		public function timerHandler(event:TimerEvent):void
		{
			circle.graphics.clear();
			if(_radius<radius){
				//circle.graphics.beginFill(0xcc0000,1);
				circle.graphics.lineStyle(0.5, 0xcc0000);
				circle.graphics.drawCircle(0,0,_radius);
				//circle.graphics.endFill();
			} else {
				circle.graphics.beginFill(0x00ff00,0.1);
				circle.graphics.lineStyle(0.5, 0x00ff00);
				circle.graphics.drawCircle(0,0,radius);
				circle.graphics.endFill();
				timer.stop();
				timer.removeEventListener(TimerEvent.TIMER, timerHandler);
				timer = null;
			}
			_radius = _radius*1.1;
		}
		
	}
}
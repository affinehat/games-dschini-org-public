package {
	
	import flash.display.*;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.events.*;
	import flash.text.*;
	
	public class Scorebox extends MovieClip {
	
		public var data:Object;
		public var score:TextField;
		public var level:TextField;
		
		function Scorebox():void
		{
		}
		
		public function init(data:Object):void
		{
			this.data = data;
			level.text = data.currentLevel+1;
			score.text = data.score;
		}
		
	}
}
package {
	
	import flash.display.*;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.events.*;
	import flash.text.*;
	
	public class Statusbox extends MovieClip {
	
		public var data:Object;
		public var required:TextField;
		public var yours:TextField;
		public var target:TextField;
		public var levelscoreMask:MovieClip;
		public var tweenScoreMask:Tween;
		
		function Statusbox():void
		{
			levelscoreMask.width = 0;
		}
		
		public function init(data:Object):void
		{
			this.data = data;
			required.text = data.levels[data.currentLevel].minScore;
			yours.text = data.levels[data.currentLevel].score;
			target.text = data.currentLevelTarget+1 + "/" + data.levels[data.currentLevel].maxTargets;
			
			tweenScoreMaskTarget = 150 
				/ data.levels[data.currentLevel].minScore 
				* data.levels[data.currentLevel].score;
				
			tweenScoreMask = new Tween(levelscoreMask,"width",Strong.easeOut,levelscoreMask.width, tweenScoreMaskTarget, 10);
			tweenScoreMask.addEventListener(TweenEvent.MOTION_FINISH, tweenHandler);
			
		}
		
		public function tweenHandler(event:TweenEvent):void
		{
			tweenScoreMask.removeEventListener(TweenEvent.MOTION_FINISH, tweenHandler);
			tweenScoreMask = null;
		}
		
		public function dispose():void
		{
			tweenScoreMask.removeEventListener(TweenEvent.MOTION_FINISH, tweenHandler);
			tweenScoreMask = null;
			data = null;
		}
		
	}
}
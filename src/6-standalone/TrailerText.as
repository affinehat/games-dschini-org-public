package {
	
	import flash.display.*;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.events.*;
	import flash.text.*;
	import flash.utils.*;
	
	public class TrailerText extends MovieClip {
	
		public var title:TextField;
		public var duration:uint;
		
		public var inTweenAlpha:Tween;
		public var outTweenAlpha:Tween;
		public var inTweenX:Tween;
		public var outTweenX:Tween;
		public var inTweenY:Tween;
		public var outTweenY:Tween;
	
	
		function TrailerText():void
		{
			this.cacheAsBitmap = true;
			//title.antiAliasType = AntiAliasType.ADVANCED;
			title.embedFonts = true;
		}
		
		public function init(object:Object):void
		{
			title.text = object.title;
			duration = object.duration;
			inTweenX = new Tween(this,"scaleX",Strong.easeOut,1.5, 1, duration/2/1000, true);
			inTweenY = new Tween(this,"scaleY",Strong.easeOut,1.5, 1, duration/2/1000, true);
			inTweenAlpha = new Tween(this,"alpha",Strong.easeOut,0, 1, duration/2/1000, true);
			setTimeout(inTweenFinishHandler,duration/2);
		}
		
		public function inTweenFinishHandler():void{
			inTweenX = null;
			inTweenY = null;
			inTweenAlpha = null;
			outTweenX = new Tween(this,"scaleX",Strong.easeIn,1, 0.5, duration/2/1000, true);
			outTweenY = new Tween(this,"scaleY",Strong.easeIn,1, 0.5, duration/2/1000, true);
			outTweenAlpha = new Tween(this,"alpha",Strong.easeIn,1, 0, duration/2/1000, true);
			setTimeout(outTweenFinishHandler,duration/2);
		}
		
		public function outTweenFinishHandler():void{
			outTweenX = null;
			outTweenY = null;
			outTweenAlpha = null;
		}
		
	}
}
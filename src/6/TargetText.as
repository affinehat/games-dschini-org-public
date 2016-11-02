package {
	
	import flash.display.*;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.events.*;
	import flash.text.*;
	import flash.utils.*;
	
	public class TargetText extends MovieClip {
	
		public var title:TextField;
	
		function TargetText():void
		{
			this.cacheAsBitmap = true;
			title.antiAliasType = AntiAliasType.ADVANCED;
			title.embedFonts = true;
			title.autoSize = TextFieldAutoSize.CENTER;
		}
		
		public function init(_title:String):void
		{
			title.text = _title;
			title.y = -title.height/2;
		}
		
	}
}
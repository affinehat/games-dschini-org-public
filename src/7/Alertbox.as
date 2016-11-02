package {
	
	import flash.display.*;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.events.*;
	import flash.text.*;
	import flash.media.*;
	
	public class Alertbox extends MovieClip {
	
		public static var CONTINUE:String = 'alertboxContinue';
		
		public var continueButton:MovieClip;
		
		public var title:TextField;
		public var body:TextField;

		public var data:Object;
		
		function Alertbox():void
		{
		}
		
		public function init(data:Object):void
		{
			this.data = data;
			data.soundLoopsChannel.soundTransform = new SoundTransform(
															this.data.volume >= 0.9
															? 0.4
															: 0
															,0);
			title.text = data.levels[data.currentLevel].targets[data.currentLevelTarget].title;
			body.htmlText =	'<b><br><font color="#cc0000">No Score!</font></b>' +
								"<br>" +
								"<br>" +
								"<b>Did you know?</b>" +
								"<br>" +
								"You can also play games in real life!" +
								"<br>" +
								"The game has paused";
			body.y = title.y + title.height;
			continueButton.buttonMode = true;
			continueButton.addEventListener(MouseEvent.CLICK,continueButtonClickHandler);
		}
		
		public function continueButtonClickHandler(event:MouseEvent):void
		{
			data.soundLoopsChannel.soundTransform = new SoundTransform(this.data.volume,0);
			continueButton.removeEventListener(MouseEvent.CLICK,continueButtonClickHandler);
			dispatchEvent(new Event(CONTINUE));
		}
		
		
		public function dispose():void
		{
			data = null;
		}
		
	}
}
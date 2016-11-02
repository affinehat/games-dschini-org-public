package {
	
	import flash.display.*;
	import fl.controls.ProgressBar;
	import fl.containers.UILoader;
	import flash.events.Event;
	import flash.events.ProgressEvent;
	import flash.events.IOErrorEvent;
	import flash.net.URLRequest;

	public dynamic class GameLoader extends MovieClip {

		public var gameLoader:UILoader; //=new UILoader;
		public var gameProgressBar:ProgressBar; //=new ProgressBar;
		public var flashvars:Object;
		public var swf_id:String;
		public var player:String;

		public function GameLoader() {
			flashvars = LoaderInfo(this.loaderInfo).parameters;	
			swf_id = flashvars['swf_id'] ? String(flashvars['swf_id']) : '1';	
			player = flashvars['player'] ? String(flashvars['player']) : 'Undefined';	
			gameLoad();
		}
		public function gameLoad():void {
			gameProgressBar.source=gameLoader;
			gameLoader.source = "/swf/" + swf_id + "/game.swf?player=" + player;
			gameLoader.addEventListener(IOErrorEvent.IO_ERROR,function(event:IOErrorEvent) {gameioErrorHandler()});
			gameProgressBar.addEventListener(ProgressEvent.PROGRESS,gameProgressHandler);
			gameProgressBar.addEventListener(Event.COMPLETE,gameCompleteHandler);
		}
		
		private function gameProgressHandler(event:ProgressEvent):void {
			gameProgressBar.visible=true;
		}
		
		private function gameCompleteHandler(event:Event):void {			
			gameLoader.removeEventListener(IOErrorEvent.IO_ERROR,function(event:IOErrorEvent){});
			gameProgressBar.removeEventListener(ProgressEvent.PROGRESS,gameProgressHandler);
			gameProgressBar.removeEventListener(Event.COMPLETE,gameCompleteHandler);
			gameProgressBar.visible=false;
			gameLoader.scaleContent = false;
		}
		
		private function gameioErrorHandler():void {
			gameProgressBar.visible=false;
		}
	}
}
package {
	
	import flash.display.*;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.events.*;
	import flash.text.*;
	import flash.net.*;
		
	public class Stone extends MovieClip {
	
		public static const READY:String = 'bottomReady';
		public static const GO:String = 'bottomGo';
	
		public var stoneSelectionMask:MovieClip;
		public var col:int;
		public var row:int;
		public var m:int;
		
		public var multiplers:Array;
	
		function Stone():void
		{
			m = 0;
			multiplers = new Array();
			points.selectable = false;
			title.selectable = false;
			stoneSelectionMask.visible = false;
			mouseChildren = false;
			buttonMode = false;
			mouseEnabled = false;
		}
		
		function setMultiplers( value:int ):void
		{
			for( var i:int=1; i<value; i++ ){
				var multipler:Multipler = new Multipler();
				multipler.x = 26;
				multipler.y = -26 + m*12;
				addChild( multipler );
				multiplers.push ( multipler );
			}
			m = value;
		}
		
		function go():void
		{
			mouseChildren = false;
			buttonMode = true;
			mouseEnabled = true;
		}
		
		public function setTitle( value:String ):void
		{
			title.text = value;
			points.text = String( getPoints()*m );
		}
	
		private function getPoints():int
		{
			switch( title.text.toLowerCase() ){
				case "d":
				case "g":
					return 2;
				case "b":
				case "c":
				case "m":
					return 3;
				case "v":
				case "h":
				case "v":
				case "w":
				case "y":
					return 4;
				case "k":
					return 5;
				case "j":
				case "x":
					return 8;
				case "q":
				case "z":
					return 10;
			}
			return 1;
		}
		
	}
}
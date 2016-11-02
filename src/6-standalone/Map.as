package {
	
	import flash.display.*;
	import flash.events.*;
    import flash.ui.Mouse;
	import flash.geom.Point;
	import flash.utils.*;
	import flash.media.*;
	
	public class Map extends MovieClip {

		public var data:Object;
		
		public var distance:Distance;
		public var world:World;
		public var greenFlag:GreenFlag;
		public var redFlag:RedFlag;
		public var cursorPositionOnClick:Point;
		
		public var cursor:MapCursor;
		
		public static var MAP_CLICK:String = "mapClick";
		
		function Map():void
		{
			world.cacheAsBitmap = true;
        }

		public function init(data:Object):void
		{
			this.data = data;
			world.init(data);
			cursor = new MapCursor();
            addChild(cursor);
			distance = new Distance();
			distance.cacheAsBitmap = true;
            Mouse.hide();
		}
		
		public function theGreenFlag(x:Number,y:Number):GreenFlag
		{
			greenFlag = new GreenFlag();
			greenFlag.stop();
			greenFlag.x = x;
			greenFlag.y = y;
			redFlag ? theDistance() : null;
			addChild(greenFlag);
			setTimeout(greenFlag.play,0);
			return greenFlag;
		}
		
		public function theRedFlag(x:Number,y:Number):RedFlag
		{
			redFlag = new RedFlag();
			redFlag.stop();
			redFlag.x = x;
			redFlag.y = y;
			greenFlag ? theDistance() : null;
			addChild(redFlag);
			setTimeout(redFlag.play,250);
			return redFlag;
		}
		
		public function theDistance():Distance
		{
			var radius,dx,dy:Number;
			if(redFlag && greenFlag){
				dx = redFlag.x-greenFlag.x;
				dy = redFlag.y-greenFlag.y;
				radius = Math.sqrt(dx*dx + dy*dy);
			}
			
			distance.init({'radius':radius});
			distance.x = greenFlag.x;
			distance.y = greenFlag.y;
			addChild(distance);
			return distance;
		}
		
        private function mouseClickHandler(event:MouseEvent):void
		{
            cursor.x = event.localX;
            cursor.y = event.localY;
//trace("{title:'"+data.levels[data.currentLevel].targets[data.currentLevelTarget].title+"',x:"+cursor.x+",y:"+cursor.y+"},")
			cursorPositionOnClick = new Point(cursor.x,cursor.y);
			dispatchEvent(new Event(MAP_CLICK));
			removeListeners();
			removeChild(cursor);
			Mouse.show();
        }

        private function mouseOutHandler(event:MouseEvent):void
		{
            Mouse.show();
            cursor.visible = false;
        }

        private function mouseMoveHandler(event:MouseEvent):void
		{
            Mouse.hide();
            cursor.x = event.localX;
            cursor.y = event.localY;
            event.updateAfterEvent();
            cursor.visible = true;
        }
		
		public function addListeners():void
		{
			addEventListener(MouseEvent.MOUSE_OVER, mouseMoveHandler);
            addEventListener(MouseEvent.MOUSE_OUT, mouseOutHandler);
            addEventListener(MouseEvent.CLICK, mouseClickHandler);
            addEventListener(MouseEvent.MOUSE_MOVE, mouseMoveHandler);
		}
		
		public function removeListeners():void
		{
            removeEventListener(MouseEvent.MOUSE_OVER, mouseMoveHandler);
            removeEventListener(MouseEvent.MOUSE_OUT, mouseOutHandler);
            removeEventListener(MouseEvent.MOUSE_MOVE, mouseMoveHandler);
            removeEventListener(MouseEvent.CLICK, mouseClickHandler);
		}
		
		public function dispose():void
		{
			cursorPositionOnClick = null;
			removeListeners();
			Mouse.show();
			data = null;
			if(greenFlag){
				if(contains(greenFlag)){
					removeChild(greenFlag);
				}
				greenFlag = null;
			}
			if(redFlag){
				if(contains(redFlag)){
					removeChild(redFlag);
				}
				redFlag = null;
			}
			if(cursor){
				if(contains(cursor)){
					removeChild(cursor);
				}
				cursor = null;
			}
			if(distance){
				if(contains(distance)){
					removeChild(distance);
				}
				distance = null;
			}
		}
		
	}
}
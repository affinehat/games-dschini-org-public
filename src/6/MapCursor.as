package {
	
	import flash.display.*; 
	
	public class MapCursor extends Shape
	{
		public function MapCursor()
		{
			visible = false;
			draw();
		}
  
		public function draw():void
		{
			graphics.clear();
			graphics.lineStyle(0.25, 0x000000, 0.5);
			//graphics.beginFill(0xeeeeee,0.25);
			graphics.drawCircle(0,0,15);
			//graphics.endFill();
			graphics.lineStyle(0.25, 0xffffff, 0.5);
			graphics.moveTo(0,-20);
			graphics.lineTo(0,-1);
			graphics.moveTo(0,1);
			graphics.lineTo(0,20);
			graphics.moveTo(-20,0);
			graphics.lineTo(-1,0);
			graphics.moveTo(1,0);
			graphics.lineTo(20,0);
		}
	}
}
package {
	
	import flash.display.*;
	import flash.events.*;
    import flash.ui.Mouse;
	import flash.geom.Point;
	import flash.utils.*;
	import flash.filters.ColorMatrixFilter;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.utils.Proxy;
	import flash.geom.ColorTransform;
	
	public class World extends MovieClip {

		public var data:Object;

		public var worldBorders:WorldBorders;
		public var worldBackground:WorldBackground;
		public var worldShape:WorldShape;
	
		function World():void
		{
        }

		public function init(data:Object):void
		{
			this.data = data;
			
			if(!worldShape){
				worldShape = new WorldShape();
				worldShape.cacheAsBitmap = true;
				addChild(worldShape);
			}
			if(!worldBackground){
				worldBackground = new WorldBackground();
				worldBackground.cacheAsBitmap = true;
				addChild(worldBackground);
			}
			if(!worldBorders){
				worldBorders = new WorldBorders();
				worldBorders.cacheAsBitmap = true;
				addChild(worldBorders);
			}
			
			if(data.levels[data.currentLevel].mapBackground){
				var colorTransformWorldBackground:ColorTransform = worldBackground.transform.colorTransform;
				colorTransformWorldBackground.color = data.levels[data.currentLevel].mapBackgroundColor;
				worldBackground.transform.colorTransform = colorTransformWorldBackground;
			} else {
				if(contains(worldBackground)){
					removeChild(worldBackground);
				}
			}
			
			if(data.levels[data.currentLevel].mapBorders){
				var colorTransformWorldBorders:ColorTransform = worldBorders.transform.colorTransform;
				colorTransformWorldBorders.color = data.levels[data.currentLevel].mapBordersColor;
				worldBorders.transform.colorTransform = colorTransformWorldBorders;
			} else {
				if(contains(worldBorders)){
					removeChild(worldBorders);
				}
			}
			
			if(data.levels[data.currentLevel].mapShape){
				var colorTransformWorldShape:ColorTransform = worldShape.transform.colorTransform;
				colorTransformWorldShape.color = data.levels[data.currentLevel].mapShapeColor;
				worldShape.transform.colorTransform = colorTransformWorldShape;
			} else {
				if(contains(worldShape)){
					removeChild(worldShape);
				}
			}
		}
		
		public function dispose():void
		{
			if(contains(worldBorders)){
				removeChild(worldBorders);
			}
			worldBorders = null;
			if(contains(worldBackground)){
				removeChild(worldBackground);
			}
			worldBackground = null;
			if(contains(worldShape)){
				removeChild(worldShape);
			}
			worldShape = null;
		}
		
	}
}
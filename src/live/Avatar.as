package {
	
	import flash.display.*;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.net.*;
	import flash.events.*;
	import flash.utils.setTimeout;
	import com.adobe.crypto.*;
	
	public class Avatar extends MovieClip {
	
		public var updated:uint;
		public var type:String;
		public var username:String;
		public var game:int;
		public var email:String;
		
		private var img:Bitmap;
		private var speed:uint;
		
		private var xTween:Tween;
		private var yTween:Tween;
		private var zTween:Tween;
		
		private var updateEffect:UpdateEffect;
		private var mouseOverEffect:MouseOverEffect;
	
		private var canWalk:Boolean;
		private var alphaBeforeMouseOver:Number;
	
		function Avatar():void{
			canWalk = true;
			//addEventListener(MouseEvent.MOUSE_OVER,mouseOverHandler);
			//addEventListener(MouseEvent.MOUSE_OUT,mouseOutHandler);
		}
		
		public function init(object:Object):Avatar{
			this.buttonMode = true;
			this.update(object);
			//this.cacheAsBitmap = true;
			return this;
		}
		
		public function loadAvatar(email:String):void{
			var ldr:Loader = new Loader();
			var url:String = "/img/avatars/"+MD5.hash(email);
			var urlReq:URLRequest = new URLRequest(url);
			ldr.load(urlReq);
			this.addChild(ldr);
			ldr.x = -15;
			ldr.y = -15;
		}
		
		var icons:Array = new Array();
		var icon:MovieClip;
		
		public function addIcon(object:Object):void{
					
			if(icon){
				icon.markedToDelete = true;
			}

			switch(object.type){
				
				case 'game':
				
					switch(object.id){
						case 1:
							icon = new IconFindTheBug();
							break;
						case 2:
							icon = new IconFindTheBang();
							break;
						case 3:
							icon = new IconFindTheArt();
							break;
						case 4:
							icon = new IconMemoryMania();
							break;
						case 5:
							icon = new IconFindTheSun();
							break;
						case 6:
							icon = new IconGlobetrotterPremium();
							break;
						case 7:
							icon = new IconGlobetrotter();
							break;
						case 8:
							icon = new IconHippopotamus();
							break;
					}
					
					if(icon){
						icons.push(icon);
						addChildAt(icon,0);						
					}
			}
		}
		
		public function update(object:Object):Avatar{
			this.alpha = 1;
			this.updated = new Date().getTime();
			this.speed = object.speed;
			this.type = object.type;
			this.title.text = object.username;
			this.username = object.username;
			this.game = object.game;
			/*if(!this.email && object.email){
				this.loadAvatar(object.email);
			}*/
			if(!updateEffect){
				updateEffect = new UpdateEffect();
				addChildAt(updateEffect,0);
				setTimeout(removeUpdateEffect,2000);
			}
			switch(this.type){
				case 'gamestart':
					this.addIcon({'type':'game','id':this.game});
					break;
				case 'savescore':
					this.addIcon({'type':'game','id':this.game});
					break;
				case 'accountapproved':
					break;
				case 'login':
					break;
			}
			return this;
		}
		
		public function removeUpdateEffect():void{
			if(updateEffect){
				removeChild(updateEffect);
				updateEffect = null;
			}
		}
		
		public function dispose():void{
			//removeEventListener(MouseEvent.MOUSE_OVER,mouseOverHandler);
			//removeEventListener(MouseEvent.MOUSE_OUT,mouseOutHandler);
		}
		
		public function mouseOverHandler(event:MouseEvent):void{
			alphaBeforeMouseOver = this.alpha;
			if(!mouseOverEffect){
				mouseOverEffect = new MouseOverEffect();
				addChildAt(mouseOverEffect,0);
			}
			this.alpha = 1;
			xTween.stop();
			yTween.stop();
			canWalk = false;
		}
		
		public function mouseOutHandler(event:MouseEvent):void{
			if(mouseOverEffect){
				removeChild(mouseOverEffect);
				mouseOverEffect = null;
			}
			xTween.continueTo(xTween.finish,xTween.duration);
			yTween.continueTo(yTween.finish,yTween.duration);
			this.alpha = alphaBeforeMouseOver;
			canWalk = true;
		}
		
		var angle:int = 0;
		public function walk():void{
			if(canWalk){
				var ms:int = new Date().getTime() - this.updated;
				this.alpha = ms%(9000/this.speed)<=30 ? this.alpha-0.01 : this.alpha;
				this.type = this.alpha<=0 ? 'dispose' : this.type;
				if(!xTween || (!xTween.isPlaying && !yTween.isPlaying)){
					var tx:int = Math.round((Math.random()-0.5)*770)+400;
					var ty:int = Math.round((Math.random()-0.5)*170)+100;
					xTween = new Tween(this, "x", Regular.easeInOut, this.x, tx, 1000/this.speed);
					yTween = new Tween(this, "y", Regular.easeInOut, this.y, ty, 1000/this.speed);
				}
				if(icons.length>0){
					for(var i:int=0; i<icons.length; i++){
						if(icons[i]){
							if(icons[i].markedToDelete){
								icons[i].alpha -= 0.002;
							}
							angle = angle%360;
							angle++;
							var rads:Number = angle * Math.PI / 180.00 -(i*45) * speed;
							icons[i].x = Math.cos(rads) * 40;
							icons[i].y = Math.sin(rads) * 40;
							if(icons[i].alpha <= 0){
								removeChild(icons[i]);
								icons[i] = null;
							}
						}
					}
				}
			}
		}		
	}
}
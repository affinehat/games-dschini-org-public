package {
	
	import flash.display.*;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.events.*;
	import flash.text.*;
	import flash.net.*;
		
	public class LevelBox extends Sprite {
	
		public static const READY:String = 'levelBoxReady';
		public static const GO:String = 'levelBoxGo';
	
		public var goal:TextField;
		public var level:TextField;
		public var target:TextField;
		public var startButton:MovieClip;
		public var fullscreenButton:MovieClip;
		public var achievements:Array;
		public var achievementLayer:Sprite;
		//public var playMoreButton:MovieClip;
		
		public var readyTween:Tween;
		public var goTween:Tween;
		
		function LevelBox():void
		{
			visible = false;
			achievementLayer = new Sprite();
			achievementLayer.x = 400;
			achievementLayer.y = 440;
			achievementLayer.buttonMode = true;
			achievementLayer.mouseChildren = false;
			achievementLayer.addEventListener( MouseEvent.CLICK, achievementLayerClickHandler );
		}
		
		private function languageClickHandler(event:MouseEvent):void{
			switch( event.target ){
				case enButton:
					game.instance.language = game.instance.data.lang = "en";
					setLanguage( "en" );
					break;
				case deButton:
					game.instance.language = game.instance.data.lang = "de";
					setLanguage( "de" );
					break;
			}
			game.instance.setDataLanguage();
		}
		
		private function setLanguage( value:String ):void {
			enButton.gotoAndStop( 1 );
			deButton.gotoAndStop( 1 );
			switch( value ){
				case "en":
					enButton.gotoAndStop( 2 );
					break;
				case "de":
					deButton.gotoAndStop( 2 );
					break;
			}
		}
		
		private function achievementLayerClickHandler( event:MouseEvent ):void
		{
			game.instance.data.levels[ game.instance.data.currentLevel ].maxTime += 5000;
			target.text = game.instance.data.levels[game.instance.data.currentLevel].maxTargets + " words in " + (game.instance.data.levels[game.instance.data.currentLevel].maxTime/1000) + " seconds";
			game.instance.data.achievements--;
			removeAchievementLayer();
			makeAchievementLayer();
		}
		
		public function removeAchievementLayer():void
		{
trace( " removeAchievementLayer ");
			for(var i:int=0; i<achievements.length; i++){
				achievements[i].removeEventListener( MouseEvent.CLICK, achievementLayerClickHandler );
				achievementLayer.removeChild( achievements[i] );
			}
			removeChild( achievementLayer );
		}
		
		public function makeAchievementLayer():void
		{
trace( " makeAchievementLayer ");
			achievements = new Array();
			for( var i:int=0; i<game.instance.data.achievements; i++ ){
				var achievement:AchievementIconSmall = new AchievementIconSmall();
				achievement.x = i * 30;
				achievementLayer.addChild( achievement );
				achievements.push( achievement );
			}
			achievementLayer.x = 400 - (achievements.length-1)*15; // -(getBounds(achievementLayer).width/2);
			addChild( achievementLayer );
		}
		
		public function ready():void
		{
			visible = true;
			alpha = 0;

			makeAchievementLayer();
			
			setLanguage( game.instance.language );

			enButton.buttonMode = true;
			enButton.addEventListener(MouseEvent.CLICK,languageClickHandler);
			deButton.buttonMode = true;
			deButton.addEventListener(MouseEvent.CLICK,languageClickHandler);

			fullscreenButton.buttonMode = true;
			fullscreenButton.addEventListener(MouseEvent.CLICK,fullscreenButtonClickHandler);
			level.text = "Level: "+(game.instance.data.currentLevel+1);
			target.text = game.instance.data.levels[game.instance.data.currentLevel].maxTargets + " words in " + (game.instance.data.levels[game.instance.data.currentLevel].maxTime/1000) + " seconds";
			startButton.mouseChildren = false;
			startButton.buttonMode = true;
			startButton.addEventListener(MouseEvent.CLICK,startButtonClickHandler);
			//playMoreButton.buttonMode = true;
			//playMoreButton.addEventListener(MouseEvent.CLICK,playMoreButtonClickHandler);
			readyTween = new Tween(this,"alpha",Regular.easeOut,alpha, 1, 10);
			readyTween.addEventListener(TweenEvent.MOTION_FINISH,readyTweenFinishHandler);
		}
		
		public function go():void
		{
			goTween = new Tween(this,"alpha",Regular.easeIn,this.alpha, 0, 10);
			goTween.addEventListener(TweenEvent.MOTION_FINISH,goTweenFinishHandler);
		}
		
		function dispose():void{
			removeAchievementLayer();
			startButton.removeEventListener(MouseEvent.CLICK,startButtonClickHandler);
			visible = false;
		}
		
		public function readyTweenFinishHandler(event:TweenEvent):void{
			readyTween.removeEventListener(TweenEvent.MOTION_FINISH,readyTweenFinishHandler);
			readyTween = null;
			dispatchEvent(new Event(READY));
		}
		
		public function goTweenFinishHandler(event:TweenEvent):void{
			goTween.removeEventListener(TweenEvent.MOTION_FINISH,goTweenFinishHandler);
			goTween = null;
			dispatchEvent(new Event(GO));
		}

		public function fullscreenButtonClickHandler(event:MouseEvent):void{
			if(game.instance.stage.displayState == StageDisplayState.NORMAL){
				game.instance.stage.scaleMode = "exactFit";
				game.instance.stage.displayState = StageDisplayState.FULL_SCREEN;
			}else{
				game.instance.stage.scaleMode = "exactFit";
				game.instance.stage.displayState = StageDisplayState.NORMAL;
			}
		}
		
		private function startButtonClickHandler(event:MouseEvent):void{
			startButton.removeEventListener(MouseEvent.CLICK,startButtonClickHandler);
			go();
		}
		
	}
}
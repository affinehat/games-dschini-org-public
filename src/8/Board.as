package {
	
	import flash.display.*;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.events.*;
	import flash.text.*;
	import flash.net.*;
	import flash.geom.*;
	import flash.media.*;
	import flash.utils.*;
	import flash.filters.*;

	import caurina.*;
	import caurina.transitions.*;
	
	import fxs.*;
		
	public class Board extends Sprite {
	
		public static const READY:String = 'boardReady';
		public static const COMPLETE:String = "boardComplete";
		public static const FAILED:String = "boardFailed";
		
		public var animations:Array;
		
//public var achievement:AchievementIcon;
		public var inTween:Tween;
		public var outTween:Tween;
		public var possibleDirections:ExtendedArray = new ExtendedArray( 1,2,3,4 );
	
		public var lastDirection:int = 0;
		public var cols:int;
		public var rows:int;
		public var scale:Number;
		
		public var lastSelectedWord:String = "";
		public var stones:Array;
		public var backgroundStones:Array;
		public var additionalStones:Array;
		public var selectedStones:Array;
		public var wordStones:Array;
		public var multiplerWords:Array;
		public var status:String;

		private var nextPosition:int;
		private var mouseIsDown:Boolean = false;
		
		public var containerStones:Array;
	
		public var fxOver:Fx14 = new Fx14();
		public var fxWrong:Fx15 = new Fx15();
		public var fxGood:Fx1 = new Fx1();
		public var fxYeahLow:Fx5 = new Fx5();
		public var fxYeahMiddle:Fx4 = new Fx4();
		public var fxYeahHigh:Fx2 = new Fx2();
		
		public var blurTimer:Timer;
	
		public function Board():void
		{
			visible = false;
			animations = new ExtendedArray();
			animations[0] = new Array( [0,0],[0,1],[0,2],[0,3],[0,4],[8,0],[8,1],[8,2],[8,3],[8,4],[4,0],[4,1],[4,2],[4,3],[4,4],[2,0],[2,1],[2,2],[2,3],[2,4],[6,0],[6,1],[6,2],[6,3],[6,4],[1,0],[1,1],[1,2],[1,3],[1,4],[5,0],[5,1],[5,2],[5,3],[5,4],[3,0],[3,1],[3,2],[3,3],[3,4],[7,0],[7,1],[7,2],[7,3],[7,4] );
			animations[1] = new Array( [4,4],[4,3],[4,2],[4,1],[4,0],[3,0],[5,0],[3,1],[5,1],[3,2],[5,2],[3,3],[5,3],[3,4],[5,4],[2,4],[6,4],[2,3],[6,3],[2,2],[6,2],[2,1],[6,1],[2,0],[6,0],[1,0],[7,0],[1,1],[7,1],[1,2],[7,2],[1,3],[7,3],[1,4],[7,4],[0,4],[8,4],[0,3],[8,3],[0,2],[8,2],[0,1],[8,1],[0,0],[8,0] );
			animations[2] = new Array( [0,0],[8,4],[0,1],[8,3],[0,2],[8,2],[0,3],[8,1],[0,4],[8,0],[1,4],[7,0],[1,3],[7,1],[1,2],[7,2],[1,1],[7,3],[1,0],[7,4],[2,0],[6,4],[2,1],[6,3],[2,2],[6,2],[2,3],[6,1],[2,4],[6,0],[3,4],[5,0],[3,3],[5,1],[3,2],[5,2],[3,1],[5,3],[3,0],[5,4],[4,0],[4,4],[4,1],[4,3],[4,2] );
			animations[3] = new Array( [4,2],[5,2],[3,2],[6,2],[2,2],[7,2],[1,2],[8,2],[0,2],[8,3],[0,1],[7,3],[1,1],[6,3],[2,1],[5,3],[3,1],[4,3],[4,1],[3,3],[5,1],[2,3],[6,1],[1,3],[7,1],[0,3],[8,1],[0,4],[8,0],[1,4],[7,0],[2,4],[6,0],[3,4],[5,0],[4,4],[4,0],[5,4],[3,0],[6,4],[2,0],[7,4],[1,0],[8,4],[0,0] );
			animations[4] = new Array( [4,2],[5,2],[5,1],[4,1],[3,1],[3,2],[3,3],[4,3],[5,3],[6,3],[6,2],[6,1],[6,0],[5,0],[4,0],[3,0],[2,0],[2,1],[2,2],[2,3],[2,4],[3,4],[4,4],[5,4],[6,4],[7,4],[1,0],[7,3],[1,1],[7,2],[1,2],[7,1],[1,3],[7,0],[1,4],[8,0],[0,4],[8,1],[0,3],[8,2],[0,2],[8,3],[0,1],[8,4],[0,0] );
			animations[5] = new Array( [0,0],[8,4],[1,0],[7,4],[2,0],[6,4],[3,0],[5,4],[4,0],[4,4],[5,0],[3,4],[6,0],[2,4],[7,0],[1,4],[8,0],[0,4],[8,1],[0,3],[8,2],[0,2],[8,3],[0,1],[7,3],[1,1],[6,3],[2,1],[5,3],[3,1],[4,3],[4,1],[3,3],[5,1],[2,3],[6,1],[1,3],[7,1],[1,2],[7,2],[2,2],[6,2],[3,2],[5,2],[4,2] );
			animations[6] = new Array( [0,0],[1,0],[2,0],[3,0],[4,0],[5,0],[6,0],[7,0],[8,0],[8,1],[8,2],[8,3],[8,4],[7,4],[6,4],[5,4],[4,4],[3,4],[2,4],[1,4],[0,4],[0,3],[0,2],[0,1],[1,1],[2,1],[3,1],[4,1],[5,1],[6,1],[7,1],[7,2],[7,3],[6,3],[5,3],[4,3],[3,3],[2,3],[1,3],[1,2],[2,2],[3,2],[4,2],[5,2],[6,2] );
		}
		
		public function ready():void
		{
			animations.shuffle();
			visible = true;
			alpha = 1;
			
			cols = game.instance.data.levels[game.instance.data.currentLevel].cols;
			rows = game.instance.data.levels[game.instance.data.currentLevel].rows;
			scale = game.instance.data.levels[game.instance.data.currentLevel].scale;
			stones = new Array();
			containerStones = new Array();
			additionalStones = new Array();
			backgroundStones = new Array();
			wordStones = new Array();
			selectedStones = new Array();
			lastDirection = 0;
			nextPosition = 0;
			multiplerWords = new Array();
//achievement = new AchievementIcon();
//achievement.x = 400;
//achievement.y = 310;

			game.instance.data.words.shuffle();
			updateTitle( game.instance.data.words[ nextPosition ] );
			
			for(var _c:int=0; _c<cols; _c++){
				containerStones[_c] = new Array();
				for(var _r:int=0; _r<rows; _r++){
					containerStones[_c][_r] = getContainerStone( _c,_r );
					addChild( containerStones[_c][_r] );
				}
			}
			for(var c:int=0; c<cols; c++){
				game.instance.data.allowedChars.shuffle();
				for(var r:int=0; r<rows; r++){
					var backgroundStone:BackgroundStone = getBackgroundStone( c,r );
					backgroundStones.push( backgroundStone );
					containerStones[c][r].addChild( backgroundStone );
					
					var additionalStone:AdditionalStone = getAdditionalStone( c,r,game.instance.data.allowedChars[r] );
					//additionalStone.increaseMultipler();
					additionalStones.push( additionalStone );
					containerStones[c][r].addChild( additionalStone );
				}
			}

			for(var i:int=game.instance.data.levels[game.instance.data.currentLevel].maxTargets-1; i>=0; i--){
				var letters:Array = getNewWordArray( game.instance.data.words[i] );
				var _wordStones:Array = new Array();
				for(var j:int=0; j<letters.length; j++){
					var stone:Stone = getNewStone( letters[j].x,letters[j].y,letters[j].title );
					stones.push( stone );
					_wordStones.push( stone );
					containerStones[letters[j].x][letters[j].y].addChild( stone );
				}
				wordStones.push( _wordStones );
			}
			
			var _tc:Number = .1;
			for( var a:int=0; a<animations[0].length; a++ ){
				containerStones[animations[0][a][0]][animations[0][a][1]].scaleX = 0;
				containerStones[animations[0][a][0]][animations[0][a][1]].scaleY = 0;
				_tc+=.04;
				if( a+1 >= animations[0].length ){
					Tweener.addTween(containerStones[animations[0][a][0]][animations[0][a][1]],
							{scaleX:1, scaleY:1, time:.1, delay:a*.04, onComplete:inTweenFinishHandler}
					);
				} else {
					Tweener.addTween(containerStones[animations[0][a][0]][animations[0][a][1]],
							{scaleX:1, scaleY:1, time:.1, delay:a*.04}
					);
				}
			}
			mouseEnabled = false;
			mouseIsDown = false;
		}
		
		public function go():void
		{
			mouseEnabled = true;
game.instance.addEventListener( MouseEvent.MOUSE_UP, stoneUpHandler );
			for(var i:int=0; i<stones.length; i++){
				stones[i].go();
			}
			for(var j:int=0; j<additionalStones.length; j++){
				additionalStones[j].go();
			}
		}
		
		public function inTweenFinishHandler():void{
			Tweener.removeAllTweens();
			//inTween.removeEventListener(TweenEvent.MOTION_FINISH,inTweenFinishHandler);
			//inTween = null;
			dispatchEvent(new Event(READY));
		}
		
		public function out():void{
			mouseEnabled = false;
			mouseIsDown = false;
			for(var i:int=0; i<stones.length; i++){
				removeEventListenerFromStone( stones[i] )
			}
			for(var j:int=0; j<additionalStones.length; j++){
				removeEventListenerFromAdditionalStone( additionalStones[j] );
			}
game.instance.removeEventListener( MouseEvent.MOUSE_UP, stoneUpHandler );
			var _tc:Number = .1;
			for( var a:int=0; a<animations[0].length; a++ ){
				containerStones[animations[0][a][0]][animations[0][a][1]].scaleX = 1;
				containerStones[animations[0][a][0]][animations[0][a][1]].scaleY = 1;
				_tc+=.04;
				Tweener.addTween(containerStones[animations[0][a][0]][animations[0][a][1]],
						{scaleX:0, scaleY:0, time:.01, delay:a*.04}
				);
			}
			
			outTween = new Tween(this,"alpha",Regular.easeOut,alpha, 1, _tc, true);
			outTween.addEventListener(TweenEvent.MOTION_FINISH,outTweenFinishHandler);
		}
		
		public function outTweenFinishHandler(event:TweenEvent):void{
			disposeStones();
			disposeBackgroundStones();
			disposeAdditionalStones();
			Tweener.removeAllTweens();
			outTween.removeEventListener(TweenEvent.MOTION_FINISH,outTweenFinishHandler);
			outTween = null;
			visible = false;
			switch( status ){
				case COMPLETE:
					dispatchEvent( new Event( COMPLETE ) );
					break;
				case FAILED:
					dispatchEvent( new Event( FAILED ) );
					break;
			}
		}
		
		public function updateTitle( word:String ):void{
			game.instance.top.title.text = word.toUpperCase();
			game.instance.top.subtitle.text = "";
			lastSelectedWord = "";
		}
					
		private function getContainerStone( col:int,row:int ):Sprite
		{
			var stone:Sprite = new Sprite();
			stone.x = 85 * scale * col + 40;
			stone.y = 85 * scale * row + 40;
			return stone;
		}
					
		private function getBackgroundStone( col:int,row:int ):BackgroundStone
		{
			var stone:BackgroundStone = new BackgroundStone();
			stone.col = col;
			stone.row = row;
			stone.gotoAndStop( 1 );
			stone.scaleX = scale;
			stone.scaleY = scale;
			//stone.x = 85 * scale * col;
			//stone.y = 85 * scale * row;
			return stone;
		}
		
		private function getAdditionalStone( col:int,row:int,title:String ):AdditionalStone
		{
			var stone:AdditionalStone = new AdditionalStone();
			stone.col = col;
			stone.row = row;
			stone.addEventListener( MouseEvent.MOUSE_DOWN, stoneDownHandler );
			stone.addEventListener( MouseEvent.MOUSE_UP, stoneUpHandler );
			stone.addEventListener( MouseEvent.MOUSE_OVER, stoneOverHandler );
			stone.addEventListener( MouseEvent.MOUSE_OUT, stoneOutHandler );
			stone.setMultiplers( 1 ); //stone.m = game.instance.data.currentLevel+1;
			stone.setTitle( title.toUpperCase() );
			stone.gotoAndStop( 1 );
			stone.scaleX = scale;
			stone.scaleY = scale;
			//stone.x = 85 * scale * col;
			//stone.y = 85 * scale * row;
			return stone;
		}
		
		private function getNewStone( col:int,row:int,title:String ):Stone
		{
			var stone:Stone = new Stone();
			stone.col = col;
			stone.row = row;
			stone.addEventListener( MouseEvent.MOUSE_DOWN, stoneDownHandler );
			stone.addEventListener( MouseEvent.MOUSE_UP, stoneUpHandler );
			stone.addEventListener( MouseEvent.MOUSE_OVER, stoneOverHandler );
			stone.addEventListener( MouseEvent.MOUSE_OUT, stoneOutHandler );
			stone.setMultiplers( 1 ); //stone.m = game.instance.data.currentLevel+1;
			stone.setTitle( title.toUpperCase() );
			stone.gotoAndStop( 1 );
			stone.scaleX = scale;
			stone.scaleY = scale;
			//stone.x = 85 * scale * col;
			//stone.y = 85 * scale * row;
			return stone;
		}
		
		private function getNewWordArray( word:String ):Array
		{
			var randomCol:int = Math.round(Math.random()*(cols-1));
			var randomRow:int = Math.round(Math.random()*(rows-1));
			var lastCol = randomCol;
			var lastRow = randomRow;
			var letterOK:Boolean;
			var letters:Array = new Array();
			for(var j:int=0; j<word.length; j++){
				letterOK = false;
				while( letterOK == false ){
					var tmp:int = getNewRandomDirection();
					switch( tmp ){
						case 1:
							if( lastCol+1 < cols ){ lastCol++; letterOK = true; }
							break;
						case 2:
							if( lastRow+1 < rows ){ lastRow++; letterOK = true; }
							break;
						case 3:
							if( lastCol-1 >= 0 ){ lastCol--; letterOK = true; }
							break;
						case 4:
							if( lastRow-1 >= 0 ){ lastRow--; letterOK = true; }
							break;
					}
				}
				if( letterOK ){
					for( var i:int=0; i<letters.length-1; i++ ){
						if( lastCol == letters[i].x && lastRow == letters[i].y ){
							return getNewWordArray( word );
						}
					}
					letters.push( {x:lastCol,y:lastRow,title:word.charAt(j)} );
					lastDirection = tmp;
				}
			}
			return letters;
		}
			
		private function getNewRandomDirection():int {
			possibleDirections.shuffle();
			if( 	( possibleDirections[0] == 1 && lastDirection == 3 )
				|| 	( possibleDirections[0] == 2 && lastDirection == 4 )
				|| 	( possibleDirections[0] == 4 && lastDirection == 2 )
				|| 	( possibleDirections[0] == 3 && lastDirection == 1 )
				){
				return getNewRandomDirection();
			}
			return possibleDirections[0];
		}
		
		public function disposeWordStones():void{
			for(var i:int=0; i<wordStones[wordStones.length-1].length; i++){
				disposeStone( wordStones[wordStones.length-1][i] );
			}
			wordStones.pop();			
		}
		
		public function dispose():void{
			outTween.removeEventListener(TweenEvent.MOTION_FINISH,outTweenFinishHandler);
		}
		
		public function removeEventListenerFromStone( stone:Stone ):void{
			if( stone ){
				stone.removeEventListener( MouseEvent.MOUSE_DOWN, stoneDownHandler );
				stone.removeEventListener( MouseEvent.MOUSE_UP, stoneUpHandler );
				stone.removeEventListener( MouseEvent.MOUSE_OVER, stoneOverHandler );
				stone.removeEventListener( MouseEvent.MOUSE_OUT, stoneOutHandler );
			}
		}
		
		public function disposeStones():void{
			for(var i:int=0; i<stones.length; i++){
				removeEventListenerFromStone( stones[i] );
				if( stones[i].parent ){
					stones[i].parent.removeChild( stones[i] );
					stones[i] = null;
				}
			}
		}
		
		public function disposeBackgroundStones():void{
			for(var i:int=0; i<backgroundStones.length; i++){
				//if( contains( backgroundStones[i] ) ){
					backgroundStones[i].parent.removeChild( backgroundStones[i] );
					backgroundStones[i] = null;
				//}
			}
		}
		
		public function removeEventListenerFromAdditionalStone( stone:AdditionalStone ):void{
			if( stone ){
				stone.removeEventListener( MouseEvent.MOUSE_DOWN, stoneDownHandler );
				stone.removeEventListener( MouseEvent.MOUSE_UP, stoneUpHandler );
				stone.removeEventListener( MouseEvent.MOUSE_OVER, stoneOverHandler );
				stone.removeEventListener( MouseEvent.MOUSE_OUT, stoneOutHandler );
			}
		}
		
		public function disposeAdditionalStones():void{
			for(var i:int=0; i<additionalStones.length; i++){
				removeEventListenerFromAdditionalStone( additionalStones[i] );
				//if( contains( additionalStones[i] ) ){
					additionalStones[i].parent.removeChild( additionalStones[i] );
					additionalStones[i] = null;
				//}
			}
		}
		
		public function disposeSelectedStones():void{
			for(var i:int=0; i<selectedStones.length; i++){
				disposeStone( selectedStones[i] );
			}
		}
		
		public function disposeStone( stone:Stone ):void {
			removeEventListenerFromStone( stone );
			stone.parent.removeChild( stone );
			stone = null;
		}
	
		private function stoneDownHandler(event:MouseEvent):void{
//trace( "["+event.target.col+","+event.target.row+"],");
//event.target.alpha = 0.5;
//event.target.enabled = false;
			fxOver.play();
			selectedStones = new Array();
			mouseIsDown = true;
			event.target.stoneSelectionMask.visible = true;
			selectedStones.push( event.target );
			lastSelectedWord += event.target.title.text;
		}

		private function stoneUpHandler(event:MouseEvent):void{
			if( event.currentTarget is game ){
				return
			}

			mouseIsDown = false;
			if( lastSelectedWord.toLowerCase() == game.instance.top.title.text.toLowerCase() ){
				for(var s:int=0; s<selectedStones.length; s++){
					game.instance.data.score += selectedStones[s].points.text*selectedStones[s].m;
					game.instance.data.levels[game.instance.data.currentLevel].score += selectedStones[s].points.text*selectedStones[s].m;
				}
				nextPosition++;
				game.instance.top.setScore();
				game.instance.top.setFound( nextPosition );
				game.instance.top.setRequired( game.instance.data.levels[game.instance.data.currentLevel].maxTargets );
				game.instance.top.setTimer( nextPosition, game.instance.data.levels[game.instance.data.currentLevel].maxTargets);
				disposeWordStones();
				stones.pop();
				
				status = null;

				fxGood.play();
				if( nextPosition >= game.instance.data.levels[game.instance.data.currentLevel].maxTargets ){
					status = COMPLETE;
					game.instance.bottom.timerbar.freeze();
					if( game.instance.bottom.timerbar.timerbarMask.scaleX <= 0.25 ){
						fxYeahLow.play();
					} else if( game.instance.bottom.timerbar.timerbarMask.scaleX <= 0.5 ){
						fxYeahMiddle.play();
					} else {
						fxYeahHigh.play();
						blurTimer = new Timer(20,40);
						blurTimer.addEventListener(TimerEvent.TIMER,blurOn);
						blurTimer.addEventListener(TimerEvent.TIMER_COMPLETE,blurOff);
						blurTimer.start();
						game.instance.data.achievements++;
					}
					out();
					return;
				}
				updateTitle( game.instance.data.words[ nextPosition ] );
			} else {
				fxWrong.play();
			}
			for(var i:int=0; i<selectedStones.length; i++){
				selectedStones[i].stoneSelectionMask.visible = false;
			}
			game.instance.top.subtitle.text = "";
			lastSelectedWord = "";
		}
		
		private function stoneOverHandler(event:MouseEvent):void{
			if(mouseIsDown){
				fxOver.play();
				event.target.stoneSelectionMask.visible = true;
				selectedStones.push( event.target );
				lastSelectedWord += event.target.title.text;
				game.instance.top.subtitle.text = lastSelectedWord.toUpperCase();;
			}
		}
		
		private function stoneOutHandler(event:MouseEvent):void{
		}
		
		public var blurValue:Number = 0;
		public function blurOn(event:TimerEvent):void
		{
//addChild( achievement );
			var blur:BlurFilter = new BlurFilter();
			blur.blurX = blurValue;
			blur.blurY = blurValue;
			blur.quality = BitmapFilterQuality.LOW;
			this.filters = [blur];
			this.filters = [blur];
			blurValue++;
		}        
		 
		public function blurOff(event:TimerEvent):void
		{
//removeChild( achievement );
			blurValue = 0;
			var blur:BlurFilter = new BlurFilter();
			blur.blurX = 0;
			blur.blurY = 0;
			blur.quality = BitmapFilterQuality.HIGH;
			this.filters = [blur];
			this.filters = [blur];
			blurTimer.removeEventListener(TimerEvent.TIMER,blurOn);
			blurTimer.removeEventListener(TimerEvent.TIMER_COMPLETE,blurOff);
			blurTimer.stop();
			blurTimer = null;
		}
	}
}
package {
	
	import flash.display.*;
	import flash.events.*;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.media.*;
	import flash.utils.*;
    import flash.net.*;
	
	import loops.*;
	import fxs.*;
	
	import mochi.as3.*;
	
	public dynamic class game extends MovieClip {
	
		public static var SECRET:String = '90dsfg987dfg987dfg132p351x421bh';
	
		public static var instance:game;
	
		public var language:String;
		public var loader:MovieClip;
		public var volume:Number;
		public var mute:Boolean;
		public var flashvars:Object;
		public var url:URL;
		public var background:Background;
		public var levelBox:LevelBox;
		public var winBox:WinBox;
		public var looseBox:LooseBox;
		public var top:Top;
		public var bottom:Bottom;
		public var board:Board;

		public var data:Object;
	
		function game():void
		{
			//stage.displayState = StageDisplayState.FULL_SCREEN;
			//loader = new MovieClip();
			//addChild(loader);
			//game.instance = this;
			flashvars = LoaderInfo(this.loaderInfo).parameters;
			url = new URL(LoaderInfo(this.loaderInfo).loaderURL);
            //this.loaderInfo.addEventListener(Event.COMPLETE, gameLoaded);
            //this.loaderInfo.addEventListener(ProgressEvent.PROGRESS, showProgress);

			if(url.host != 'games.localhost' 
			   && url.host != 'games.dschini.org'
			   && url.host != 'gamesdev.dschini.org'){
				trace("please visit games.dschini.org to play this game");
				var url:String = "http://games.dschini.org";
	            var request:URLRequest = new URLRequest(url);
	            try {            
	                navigateToURL(request);
	            }
	            catch (e:Error) {
	                // handle error here
	            }
				return;
			}

			addEventListener(Event.ADDED_TO_STAGE,preinit);
		}
	
		
/*
public function showProgress(theProgress:ProgressEvent):void {
	var percent:Number = Math.round((theProgress.bytesLoaded / theProgress.bytesTotal )*760 );
	loader.graphics.clear()
	loader.graphics.lineStyle(1, 0x660000, 0.5)
	loader.graphics.beginFill(0x660000,0.5)
	loader.graphics.drawRect(20,560,percent,20)
}
*/

		public function preinit(event:Event):void
		{
			removeEventListener(Event.ADDED_TO_STAGE,preinit);
			
			mute = false;
			language = "en";
			volume = 0.9;
			game.instance = this;
			volume = 0.9;
			
			var myOptions:Object = {
				id: "b14d3825edd7ca1f",
				res: "800x600",
				clip: this,
				color: 0xffffff,
				background: 0x6fb670,
				outline: 0x000000,
				ad_finished: function (width, height) { gameLoaded(new Event(Event.INIT)) }
			}

			MochiAd.showPreGameAd(myOptions);

			//MochiAd.showPreGameAd({clip:root, id:"b14d3825edd7ca1f", res:"800x600"});

			//gameLoaded(new Event(Event.INIT));
		}
		
		
		public function gameLoaded(event:Event):void
		{
			stage.scaleMode = "exactFit";
			/*if(loader){
				loader.loaderInfo.removeEventListener(Event.COMPLETE, init);
				loader.loaderInfo.removeEventListener(ProgressEvent.PROGRESS, showProgress);
				removeChild(loader);
				loader = null;
			}*/

			background = new Background();
			addChild( background );
			
			top = new Top();
			top.addEventListener( Top.READY, topHandler );
			top.addEventListener( Top.GO, topHandler );
			addChild( top );
			
			bottom = new Bottom();
			bottom.addEventListener( Bottom.READY, bottomHandler );
			bottom.addEventListener( Bottom.GO, bottomHandler );
			bottom.y = 600;
			addChild( bottom );
			
			board = new Board();
			board.addEventListener( Board.READY, boardHandler );
			board.addEventListener( Board.COMPLETE, boardHandler );
			board.addEventListener( Board.FAILED, boardHandler );
			board.x = 20;
			board.y = 120;
			addChild( board );
			
			winBox = new WinBox();
			winBox.addEventListener( WinBox.READY, winBoxHandler );
			winBox.addEventListener( WinBox.GO, winBoxHandler );
			addChild( winBox );
			
			looseBox = new LooseBox();
			looseBox.addEventListener( LooseBox.READY, looseBoxHandler );
			looseBox.addEventListener( LooseBox.GO, looseBoxHandler );
			addChild( looseBox );
			
			levelBox = new LevelBox();
			levelBox.addEventListener( LevelBox.READY, levelBoxHandler );
			levelBox.addEventListener( LevelBox.GO, levelBoxHandler );
			addChild( levelBox );
			
			ready();
		}
		
		public function setDataLanguage():void{
			switch( language ){
				case "en":
					data.lang = "en";
					data.words = new EnglishAnimalsArray();
					break;
				case "de":
					data.lang = "de";
					data.words = new GermanAnimalsArray();
					break;
				case "fr":
					data.lang = "fr";
					data.words = new FrenchAnimalsArray();
					break;
			}
		}
		
		public function ready():void
		{
			data = new Object();
			setDataLanguage();
			data.allowedChars = new ExtendedArray( 	"a","a","a","a","a",
													"e","e","e","e","e",
													"i","i","i","i","i",
													"o","o","o","o","o",
													"u","u","u","u","u",
													"d","d","d",
													"g","g","g",
													"b","b",
													"c","c",
													"m","m",
													"f","h","j","k","l","n",
													"p","q","r","s","t",
													"v","w","x","y","z"
													);
			data.volume = .9;
			data.achievements = 1;
			data.score = 0;
			data.currentLevel = 0;
			data.username = flashvars['player'] ? String(flashvars['player']) : 'Undefined';
			data.currentLevelTarget = 0;
			data.urlSaveHighscore = 'http://games.dschini.org/play/8/savescore/';
			//data.urlSaveHighscore = 'http://games.localhost/play/8/savescore/';
			soundLoops.shuffle();
			data.levels = new Array();
			data.levels[0] = {
				'maxTime':28000,
				'score':0,
				'maxTargets':3,
				'cols':9,
				'rows':5,
				'scale':1,
				'loop': soundLoops[0]
			};
			data.levels[1] = {
				'maxTime':30000,
				'score':0,
				'maxTargets':4,
				'cols':9,
				'rows':5,
				'scale':1,
				'loop': soundLoops[1]
			};
			data.levels[2] = {
				'maxTime':32000,
				'score':0,
				'maxTargets':5,
				'cols':9,
				'rows':5,
				'scale':1,
				'loop': soundLoops[2]
			};
			data.levels[3] = {
				'maxTime':34000,
				'score':0,
				'maxTargets':6,
				'cols':9,
				'rows':5,
				'scale':1,
				'loop': soundLoops[3]
			};
			data.levels[4] = {
				'maxTime':36000,
				'score':0,
				'maxTargets':7,
				'cols':9,
				'rows':5,
				'scale':1,
				'loop': soundLoops[4]
			};
			data.levels[5] = {
				'maxTime':38000,
				'score':0,
				'maxTargets':8,
				'cols':9,
				'rows':5,
				'scale':1,
				'loop': soundLoops[5]
			};
			data.levels[6] = {
				'maxTime':40000,
				'score':0,
				'maxTargets':9,
				'cols':9,
				'rows':5,
				'scale':1,
				'loop': soundLoops[6]
			};
			data.levels[7] = {
				'maxTime':42000,
				'score':0,
				'maxTargets':10,
				'cols':9,
				'rows':5,
				'scale':1,
				'loop': soundLoops[7]
			};
			data.levels[8] = {
				'maxTime':44000,
				'score':0,
				'maxTargets':11,
				'cols':9,
				'rows':5,
				'scale':1,
				'loop': soundLoops[8]
			};
			data.levels[9] = {
				'maxTime':46000,
				'score':0,
				'maxTargets':12,
				'cols':9,
				'rows':5,
				'scale':1,
				'loop': soundLoops[9]
			};

			levelBox.ready();
		}

		public function levelBoxHandler(event:Event):void
		{
			switch( event.type ){
				case LevelBox.READY:
					break;
				case LevelBox.GO:
					data.loopsChannel = new LoopSoundChannel( data.levels[data.currentLevel].loop.instance.play(0,9999));
					data.loopsChannel.fadeIn(0,.6);
					levelBox.dispose();
					board.ready();
					top.ready();
					bottom.ready();
					break;
			}
		}

		public function topHandler(event:Event):void
		{
			switch( event.type ){
				case Top.READY:
					break;
				case Top.GO:
					break;
			}
		}

		public function bottomHandler(event:Event):void
		{
			switch( event.type ){
				case Bottom.READY:
					break;
				case Bottom.GO:
					break;
			}
		}

		public function winBoxHandler(event:Event):void
		{
			switch( event.type ){
				case WinBox.READY:
					break;
				case WinBox.GO:
					ready();
					break;
			}
		}

		public function looseBoxHandler(event:Event):void
		{
			switch( event.type ){
				case LooseBox.READY:
					break;
				case LooseBox.GO:
					ready();
					break;
			}
		}
		
		public function boardHandler(event:Event):void
		{
			switch(event.type){
				case Board.READY:
					data.loopsChannel.volume = .9
					board.go();
					bottom.go();
					break;
				case Board.COMPLETE:
					data.loopsChannel.fadeOut( .9,0 );
					top.out();
					bottom.out();
					data.currentLevel++;
					if( data.currentLevel>=data.levels.length ){
						winBox.ready();
					}else{
						levelBox.ready();
					}
					break;
				case Board.FAILED:
					data.loopsChannel.fadeOut( .9,0 );
					top.out();
					bottom.out();
					looseBox.ready();
					break;
			}
		}
		
		public function timerbarHandler(event:Event):void
		{
		}
		
		var soundLoops:ExtendedArray = new ExtendedArray(
			{'instance':new SoundLoop18(),'title':'-dreamspell-','author':'Icebergslim','link':'http://www.flashkit.com/search.php?term=Icebergslim&cat=loops&per=10&page=1&field=Contact_Name'},
			{'instance':new SoundLoop10(),'title':'Jazzloop','author':'Florian Ennemoser','link':'http://www.flashkit.com/loops/Easy_Listening/Jazz/Jazzloop-Florian_-4699/index.php'},
			{'instance':new SoundLoop11(),'title':'Dreadlock Holida','author':'ZRS','link':'http://www.flashkit.com/loops/Ethnic/Reggae/Dreadloc-ZRS-3845/index.php'},
			{'instance':new SoundLoop15(),'title':'Steinhaug4','author':'Kim Steinhaug','link':'http://www.steinhaug.com/'},
			{'instance':new SoundLoop16(),'title':'Adulterous Empress','author':'Lee I. Garnett','link':'http://www.sentrymarketing.com/'},
			{'instance':new SoundLoop17(),'title':'A Track like this','author':'Beetown Records','link':'http://www.beetown.ch/'},
			{'instance':new SoundLoop18(),'title':'-dreamspell-','author':'Icebergslim','link':'http://www.flashkit.com/search.php?term=Icebergslim&cat=loops&per=10&page=1&field=Contact_Name'},
			{'instance':new SoundLoop19(),'title':'.::SPACE::.','author':'Sudipto Paul','link':'http://www.flashkit.com/search.php?term=Sudipto%20Paul&cat=loops&per=10&page=1&field=Contact_Name'},
			{'instance':new SoundLoop2(),'title':'.:: Mellow Trance ::.','author':'calpomatt','link':'http://http//www.calpoly.edu/~mghanson/flash/spacegrid.html'},
			{'instance':new SoundLoop8(),'title':'.::Unknown::.','author':'unknown','link':'http://flashkit.com/loops/'}
		);
		
	}
}
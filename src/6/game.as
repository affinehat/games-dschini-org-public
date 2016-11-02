package {
	
	import flash.display.*;
	import flash.events.*;
	import fl.transitions.*;
	import fl.transitions.easing.*;
	import flash.media.*;
	import flash.utils.*;
	
	import loops.*;
	
	public class game extends MovieClip {
	
		public static var SECRET:String = '90dsfg987dfg987dfg132p351x421bh';
		
		public static var instance:game;
	
		public var background:MovieClip;
		public var splash:Splash;
		public var board:Board;
		public var end:End;
		public var win:Win;
		public var flashvars:Object;
		public var loader:MovieClip;
		public var url:URL;

		public var volume:Number;

		public var data:Object;
	
		function game():void
		{
			loader = new MovieClip();
			addChild(loader);
			game.instance = this;
			volume = 0.9;
			flashvars = LoaderInfo(this.loaderInfo).parameters;
			url = new URL(LoaderInfo(this.loaderInfo).loaderURL);
            this.loaderInfo.addEventListener(Event.COMPLETE, init);
            this.loaderInfo.addEventListener(ProgressEvent.PROGRESS, showProgress);
		}
		
        public function showProgress(theProgress:ProgressEvent):void {
            var percent:Number = Math.round((theProgress.bytesLoaded / theProgress.bytesTotal )*760 );
            loader.graphics.clear()
            loader.graphics.lineStyle(1, 0x660000, 0.5)
            loader.graphics.beginFill(0x660000,0.5)
            loader.graphics.drawRect(20,560,percent,20)
        }
		
		public function init(event:Event):void
		{
			if(loader){
				loader.loaderInfo.removeEventListener(Event.COMPLETE, init);
				loader.loaderInfo.removeEventListener(ProgressEvent.PROGRESS, showProgress);
				removeChild(loader);
				loader = null;
			}
			if(url.host != 'games.localhost'
			   && url.host != 'games.dschini.org'
			   & url.host != 'gamesdev.dschini.org'){
				trace("please visit games.dschini.org to play this game");
				return;
			}

			soundLoops.shuffle();
			targets.shuffle();
			
			data = new Object();
			data.username = flashvars['player'] ? String(flashvars['player']) : 'Undefined';
			data.score = 0;
			data.volume = volume;
			data.currentLevel = 0;
			data.currentLevelTarget = 0;
			data.urlSaveHighscore = 'http://games.dschini.org/play/6/savescore/';
			data.levels = new Array();
			data.levels[0] = {
				'minScore':350,
				'maxTime':13000,
				'score':0,
				'maxTargets':3,
				'mapBorders':true,
				'mapBackground':true,
				'mapShape':true,
				'mapShapeColor':0xcccccc,
				'mapBordersColor':0x333333,
				'mapBackgroundColor':0x777777,
				'targets': targets,
				'loop': soundLoops[0]
			};
			data.levels[1] = {
				'minScore':550,
				'maxTime':12000,
				'score':0,
				'maxTargets':4,
				'mapBorders':true,
				'mapBackground':true,
				'mapShape':true,
				'mapShapeColor':0x666666,
				'mapBordersColor':0xbbbbbb,
				'mapBackgroundColor':0x333333,
				'targets': targets,
				'loop': soundLoops[1]
			};
			data.levels[2] = {
				'minScore':750,
				'maxTime':11000,
				'score':0,
				'maxTargets':5,
				'mapBorders':true,
				'mapBackground':true,
				'mapShape':true,
				'mapShapeColor':0x333333,
				'mapBordersColor':0x999999,
				'mapBackgroundColor':0x666666,
				'targets': targets,
				'loop': soundLoops[2]
			};
			data.levels[3] = {
				'minScore':1000,
				'maxTime':10000,
				'score':0,
				'maxTargets':6,
				'mapBorders':true,
				'mapBackground':true,
				'mapShape':true,
				'mapShapeColor':0xcccccc,
				'mapBordersColor':0x454545,
				'mapBackgroundColor':0x222222,
				'targets': targets,
				'loop': soundLoops[3]
			};
			data.levels[4] = {
				'minScore':1250,
				'maxTime':9000,
				'score':0,
				'maxTargets':7,
				'mapBorders':true,
				'mapBackground':true,
				'mapShape':true,
				'mapShapeColor':0xa00b00,
				'mapBordersColor':0xeeeeee,
				'mapBackgroundColor':0x300400,
				'targets': targets,
				'loop': soundLoops[4]
			};
			data.levels[5] = {
				'minScore':1450,
				'maxTime':8000,
				'score':0,
				'maxTargets':8,
				'mapBorders':true,
				'mapBackground':true,
				'mapShape':true,
				'mapShapeColor':0x123132,
				'mapBordersColor':0xeeeeee,
				'mapBackgroundColor':0x666666,
				'targets': targets,
				'loop': soundLoops[5]
			};
			data.levels[6] = {
				'minScore':1700,
				'maxTime':7000,
				'score':0,
				'maxTargets':9,
				'mapBorders':false,
				'mapBackground':true,
				'mapShape':true,
				'mapShapeColor':0xcccccc,
				'mapBordersColor':0x454545,
				'mapBackgroundColor':0x222222,
				'targets': targets,
				'loop': soundLoops[6]
			};
			data.levels[7] = {
				'minScore':2000,
				'maxTime':6000,
				'score':0,
				'maxTargets':10,
				'mapBorders':false,
				'mapBackground':true,
				'mapShape':true,
				'mapShapeColor':0x333333,
				'mapBordersColor':0xaaaaaa,
				'mapBackgroundColor':0x999999,
				'targets': targets,
				'loop': soundLoops[7]
			};
			data.levels[8] = {
				'minScore':2300,
				'maxTime':5000,
				'score':0,
				'maxTargets':11,
				'mapBorders':false,
				'mapBackground':true,
				'mapShape':true,
				'mapShapeColor':0xcccccc,
				'mapBordersColor':0x454545,
				'mapBackgroundColor':0x222222,
				'targets': targets,
				'loop': soundLoops[8]
			};
			data.levels[9] = {
				'minScore':2650,
				'maxTime':4000,
				'score':0,
				'maxTargets':12,
				'mapBorders':false,
				'mapBackground':true,
				'mapShape':true,
				'mapShapeColor':0x333333,
				'mapBordersColor':0xaaaaaa,
				'mapBackgroundColor':0x999999,
				'targets': targets,
				'loop': soundLoops[9]
			};
			showSplash();
		}
		
		public function showSplash():void
		{
			splash = new Splash();
			splash.init(data);
			splash.x = 400;
			splash.y = 300;
			addChild(splash);
			splash.addEventListener(Splash.GO,goHandler);
		}

		
		public function goHandler(event:Event):void
		{
			var loopSound:Sound = Sound(data.levels[data.currentLevel].loop.instance);
			data.soundLoopsChannel = new LoopSoundChannel(loopSound.play(0,99));
			data.soundLoopsChannel.soundTransform = new SoundTransform(data.volume,0);
			splash.removeEventListener(Splash.GO,goHandler);
			removeChild(splash);
			splash = null;
			board = new Board();
			board.init(data);
			addChild(board);
			board.addEventListener(Board.WIN,boardHandler);
			board.addEventListener(Board.END,boardHandler);
			board.addEventListener(Board.CONTINUE,boardHandler);
		}
		
		public function boardHandler(event:Event):void
		{
			board.removeEventListener(Board.WIN,boardHandler);
			board.removeEventListener(Board.END,boardHandler);
			board.removeEventListener(Board.CONTINUE,boardHandler);			

			data.soundLoopsChannel.fadeOut(data.volume);
			
			switch(event.type){
				case Board.WIN:
					removeChild(board);
					board = null;
					win = new Win();
					win.init(data);
					win.x = 400;
					win.y = 300;
					addChild(win);
					win.addEventListener(Win.PLAY_AGAIN,playAgainHandler);
					break;
				case Board.END:
					removeChild(board);
					board = null;
					end = new End();
					end.init(data);
					end.x = 400;
					end.y = 300;
					addChild(end);
					end.addEventListener(End.PLAY_AGAIN,playAgainHandler);
					break;
				case Board.CONTINUE:
					board.dispose();
					removeChild(board);
					board = null;
					showSplash();
					break;
			}
		}
		
		public function playAgainHandler(event:Event):void
		{
			switch(event.type){
				case End.PLAY_AGAIN:
					end.removeEventListener(End.PLAY_AGAIN,playAgainHandler);
					removeChild(end);
					end = null;
					break;
				case Win.PLAY_AGAIN:
					win.removeEventListener(Win.PLAY_AGAIN,playAgainHandler);
					removeChild(win);
					win = null;
					break;
			}
			
			init(new Event(Event.INIT));
		}
		
		var soundLoops:ExtendedArray = new ExtendedArray(
			{'instance':new SoundLoop1(),'title':'COOL DISCO GROOVE 2','author':'Ravedeman','link':'http://www.mp3.com/ravedeman'},
			{'instance':new SoundLoop2(),'title':'.:: Mellow Trance ::.','author':'calpomatt','link':'http://http//www.calpoly.edu/~mghanson/flash/spacegrid.html'},
			{'instance':new SoundLoop3(),'title':'.:: Chill ::.','author':'calpomatt','link':'http://http//www.calpoly.edu/~mghanson/flash/spacegrid.html'},
			{'instance':new SoundLoop4(),'title':'..Moonlight Sound..','author':'SLrec.','link':'http://http//www.frychen.de'},
			{'instance':new SoundLoop5(),'title':'.::Searching For Truth::.','author':'calpomatt','link':'http://http//www.calpoly.edu/~mghanson/flash/spacegrid.html'},
			{'instance':new SoundLoop6(),'title':'.::Smooth::.','author':'calpomatt','link':'http://http//www.calpoly.edu/~mghanson/flash/spacegrid.html'},
			{'instance':new SoundLoop10(),'title':'Jazzloop','author':'Florian Ennemoser','link':'http://www.flashkit.com/loops/Easy_Listening/Jazz/Jazzloop-Florian_-4699/index.php'},
			{'instance':new SoundLoop11(),'title':'Dreadlock Holida','author':'ZRS','link':'http://www.flashkit.com/loops/Ethnic/Reggae/Dreadloc-ZRS-3845/index.php'},
			{'instance':new SoundLoop13(),'title':'Arabian dreams','author':'Sarbah','link':'http://www.flashkit.com/search.php?term=Sarbah&cat=loops&per=10&page=1&field=Contact_Name'},
			{'instance':new SoundLoop14(),'title':'AMBIENTAL','author':'DAFILEMAN','link':'http://www.flashkit.com/search.php?term=DAFILEMAN&cat=loops&per=10&page=1&field=Contact_Name'},
			{'instance':new SoundLoop15(),'title':'Steinhaug4','author':'Kim Steinhaug','link':'http://www.steinhaug.com/'},
			{'instance':new SoundLoop16(),'title':'Adulterous Empress','author':'Lee I. Garnett','link':'http://www.sentrymarketing.com/'},
			{'instance':new SoundLoop17(),'title':'A Track like this','author':'Beetown Records','link':'http://www.beetown.ch/'}
		);
		
		var targets:ExtendedArray = new ExtendedArray(
			{title:'Calcutta, India',x:584,y:352},
			{title:'Cairo, Egypt',x:463,y:335},
			{title:'Tianjin, China',x:645.8,y:311.55},
			{title:'Sao Paulo, Brazil',x:298.75,y:453.3},
			{title:'Hyderabad, India',x:564.1,y:363.5},
			{title:'Casablanca, Morocco',x:382.35,y:325.85},
			{title:'Pusan, South Korea',x:670.45,y:320.6},
			{title:'Alexandria, Egypt',x:461.5,y:331.95},
			{title:'Bakersfield, USA',x:144.5,y:320.3},
			{title:'Joinville, Brazil',x:294.25,y:459.3},
			{title:'Ciudad de Mexico, Mexico',x:186.75,y:359.05},
			{title:'Virginia Beach, USA',x:235.75,y:318.05},
			{title:'Memphis, USA',x:207,y:321.05},
			{title:'Columbus, USA',x:221.25,y:307.8},
			{title:'Omaha, USA',x:194.75,y:304.8},
			{title:'Nassau, The Bahamas',x:232.5,y:346.55},
			{title:'Halifax, Canada',x:262,y:294.3},
			{title:'Mt Pearl, Newfoundland',x:284.5,y:287.05},
			{title:'Pearl Harbor, Honolulu',x:62,y:354.55},
			{title:'Lanzhou, China',x:619.8,y:317.8},
			{title:'Wuhan, China',x:637.8,y:333.8},
			{title:'Guiyang, China',x:624.8,y:343.55},
			{title:'Nanning, China',x:648.05,y:329.8},
			{title:'Wulumuqi, China',x:582.3,y:295.8},
			{title:'Sassari, Sardegna Italy',x:415.5,y:306.8},
			{title:'Cagliari, Sardegna Italy',x:417,y:310.8},
			{title:'Charlotte, USA',x:226.25,y:321.3},
			{title:'San Antonio, USA',x:188.1,y:335.7},
			{title:'Tampa, USA',x:222.75,y:339.55},
			{title:'Orlando, USA',x:224.5,y:337.05},
			{title:'Tucson, USA',x:161,y:329.05},
			{title:'Omaha, USA',x:193.75,y:305.55},
			{title:'Indianapolis, USA',x:216,y:309.55},
			{title:'Colorado Spring, USA',x:175.25,y:312.8},
			{title:'Trondheim, Norway',x:419.5,y:226.5},
			{title:'Ar Riyad, Saudi Arabia',x:495.5,y:347},
			{title:'Malatya, Turkey',x:479.25,y:312.95},
			{title:'Leeds, United Kingdom',x:393.95,y:267.35},
			{title:'Aberdeen, United Kingdom',x:392.45,y:252.95},
			{title:'Visby, Sweden',x:434.3,y:250.5},
			{title:'Calgary, Canada',x:154.5,y:274.5},
			{title:'Winnipeg, Canada',x:190.75,y:279},
			{title:'Ottawa, Canada',x:235.75,y:294.05},
			{title:'Fairbanks, Alaska',x:84.25,y:218.75},
			{title:'Magadan, Russia',x:716.8,y:242.5},
			{title:'Sapporo, Japan',x:698.3,y:300.05},
			{title:'Salalah, Oman',x:512.5,y:364.50},
			{title:'Cordoba, Argentinia',x:261.5,y:471.05},
			{title:'Rosario, Argentinia',x:268,y:475.2},
			{title:'Noumea, New Caledonia',x:750,y:449.05},
			{title:'Broken Hill, Australia',x:697.8,y:472.55},
			{title:'Rocky Point, Australia',x:699.05,y:429.55},
			{title:'Alice Springs, Australia',x:680.55,y:452.55},
			{title:'Broome, Australia',x:657.6,y:440.9},
			{title:'Derby, Australia',x:660.4,y:437.45},
			{title:'Karumba, Australia',x:696.5,y:439.95},
			{title:'Linz, Austria',x:428.25,y:283.55},
			{title:'Szeged, Hungary',x:439.8,y:289.35},
			{title:'Split, Croatia',x:431.45,y:298},
			{title:'Cluj, Romania',x:446.75,y:289.1},
			{title:'Constanta, Romania',x:457.4,y:296.6},
			{title:'Homyel, Belarus',x:462.6,y:270.35},
			{title:'Mariupol, Ukraine',x:476.4,y:287.35},
			{title:'Lugansk, Ukraine',x:480.45,y:283.35},
			{title:'Jeddah, Saudi Arabia',x:480.7,y:354.25},
			{title:'Esfahan, Iran',x:506.4,y:327.45},
			{title:'Shiraz, Iran',x:508.4,y:334.35},
			{title:'Bagasra, India',x:548.15,y:354.5},
			{title:'Szczecin, Poland',x:429.1,y:267.65},
			{title:'Lublin, Poland',x:445.4,y:274.7},
			{title:'Gdansk, Poland',x:436.65,y:263.2},
			{title:'Brno, Czech Republic',x:432.15,y:280.65},
			{title:'Venezia, Italy',x:423.3,y:292.3},
			{title:'Parma, Italy',x:419.8,y:294.7},
			{title:'Rennes, France',x:393.5,y:284.8},
			{title:'Montpellier, France',x:405.35,y:298.1},
			{title:'Le Havre, France',x:397.7,y:280.25},
			{title:'Nantes, France',x:394.2,y:287.45},
			{title:'Murcia, Spain',x:394.7,y:314.55},
			{title:'El Ejido, Spain',x:391.85,y:316.75},
			{title:'Cadiz, Spain',x:384.1,y:317.4},
			{title:'Salamanca, Spain',x:385.75,y:306.3},
			{title:'Sintra, Portugal',x:377.8,y:311.6},
			{title:'Bologna, Italy',x:422,y:295.7},
			{title:'Catanzaro, Italy,',x:433,y:312},
			{title:'Rotterdam, Netherlands',x:406.75,y:272},
			{title:'Eindhoven, Netherlands',x:410,y:272.3},
			{title:'Liege, Belgium',x:409.2,y:276.05},
			{title:'Liverpool, United Kingdom',x:390.65,y:267.35},
			{title:'Berlin, Germany',x:425.5,y:271},
			{title:'Kiel, Germany',x:418.9,y:263.8},
			{title:'Stuttgart, Germany',x:416.65,y:283.2},
			{title:'Leipzig, Germany',x:423.65,y:273.55},
			{title:'Frankfurt am Main, Germany',x:415.75,y:278.8},
			{title:'Karlsruhe, Germany',x:415,y:282.55},
			{title:'Hannover, Germany',x:418,y:271.3},
			{title:'Nürnberg, Germany',x:421.25,y:281.2},
			{title:'Regensburg, Germany',x:423.75,y:282.05},
			{title:'Augsburg, Germany',x:420.9,y:283.3},
			{title:'Trier, Germany',x:411.25,y:279.75},
			
			{title:'Philadelphia, USA',x:238,y:309.05},
			{title:'Quebec, Canada',x:246.5,y:287.95},
			{title:'San Jose, Costa Rica',x:219.25,y:380.7},					
			{title:'Al Qatif, Saudi Arabia',x:503.2,y:343.2},
			{title:'Mecca, Saudi Arabia',x:481.2,y:353.95},
			{title:'Al Kuwayt, Kuwait',x:498.7,y:336.55},
			{title:'Al Wakrah, Qatar',x:506.15,y:346.25},
			{title:'Adan, Yemen',x:492.35,y:373.45},
			{title:'Masqat, Oman',x:521.8,y:350.95},
			{title:'Dubai, United Arab Emirates',x:514.55,y:346.8},
			{title:'Mumbai, India',x:552.3,y:359.25},
			{title:'New Delhi, India',x:561.3,y:337.05},
			{title:'Nagpur, India',x:565.75,y:356.3},
			{title:'Colombo, Sri Lanka',x:568.3,y:387.3},
			{title:'Dacca, Bangladesh',x:588.95,y:349.6},
			{title:'Kathmandu, Nepal',x:579,y:340.5},
			{title:'Boston, USA',x:246.85,y:300.65},
			{title:'Chicago, USA',x:210.85,y:302.85},
			{title:'Minneapolis, USA',x:201.3,y:292.05},
			{title:'Portland Oregon, USA',x:137.5,y:293.25},
			{title:'Honolulu, Hawai',x:62.4,y:355.1},
			{title:'Vancover, Canada',x:136.2,y:280.9},
			{title:'Toronto, Canada',x:229.1,y:297.85},
			{title:'Edmonton, Canada',x:157,y:265.7},
			{title:'Anchorage, Alaska',x:79.3,y:236.3},
			{title:'Salt Lake City, USA',x:159.75,y:304.3},
			{title:'Denver, USA',x:173.6,y:309.2},
			{title:'Kansas City, USA',x:197.15,y:312.6},
			{title:'Dayton, USA',x:217.9,y:307.95},
			{title:'Nashville, USA',x:213.25,y:317.8},
			{title:'Atlanta, USA',x:218.85,y:326.55},
			{title:'New Orleans, USA',x:207.25,y:335.35},
			{title:'Dallas, USA',x:191.9,y:327.85},
			{title:'Houston, USA',x:195.35,y:335.65},
			{title:'Albuquerque, USA',x:171.8,y:323.1},
			{title:'Phoenix, USA',x:157.3,y:324.85},
			{title:'Los Angeles, USA',x:145.6,y:324.4},
			{title:'San Francisco, USA',x:137.15,y:314.35},
			{title:'San Diego, USA',x:147.95,y:326.95},
			{title:'Las Vegas, USA',x:153.4,y:320.65},
			{title:'Santo Domingo, Dominican Republic',x:247.5,y:361.8},
			{title:'Carolina, Puerto Rico',x:257.5,y:362.55},
			{title:'Panama, Panama',x:228,y:382.55},
			{title:'Managua, Nicaragua',x:214.15,y:375.3},
			{title:'Tegucigalpa, Honduras',x:212.65,y:371.2},
			{title:'San Miguel, El Salvador',x:210.25,y:373.05},
			{title:'Guatemala, Guatemala',x:203.75,y:369.8},
			{title:'Belmopand, Belize',x:209.75,y:364.2},
			{title:'Benito Juarez, Mexico',x:212.25,y:356.8},
			{title:'Acapulco, Mexico',x:186,y:365.3},
			{title:'Monterrey, Mexico',x:184,y:345.55},
			{title:'Toluca, Mexico',x:187.75,y:360.55},
			{title:'Guadalajara, Mexico',x:178.25,y:358.3},
			{title:'Los Cabos, Mexico',x:164.25,y:350.55},
			{title:'Miami, USA',x:226.75,y:345.05},
			{title:'Jacksonville, USA',x:224,y:333.8},
			{title:'Amsterdam, Netherlands',x:407.9,y:270.45},
			{title:'Den Haag, Netherlands',x:406.95,y:271.7},
			{title:'Brussel, Belgium',x:406,y:275.85},
			{title:'Cologne, Germany',x:413.4,y:277.8},
			{title:'La Habana, Cuba',x:222.65,y:351.15},
			{title:'Kingston, Jamaica',x:234.05,y:362.85},
			{title:'Port-au-Prince, Haiti',x:242.7,y:361.1},
			{title:'Paramaribo, Suriname',x:279.4,y:389.45},
			{title:'Cayenne, French Guiana',x:285.8,y:391.05},
			{title:'Rostock, Germany',x:423,y:264.7},
			{title:'Caracas, Venezuela',x:253.9,y:379.2},
			{title:'Georgetown, Guyana',x:272.75,y:385.95},
			{title:'Stanley, Falkland Islands',x:274,y:530.05},
			{title:'Punta Arenas, Chile',x:246.55,y:534.9},
			{title:'Puerto Mont, Chile',x:242.35,y:499},
			{title:'Santiago, Chile',x:247.45,y:476.6},
			{title:'Calama, Chile',x:250.3,y:448.95},
			{title:'Mendoza, Argentina',x:251.4,y:478.2},
			{title:'Buenos Aires, Argentina',x:274.1,y:480.2},
			{title:'Mar del Plata, Argentina',x:274.7,y:489.3},
			{title:'Trelew, Argentina',x:258.05,y:506.05},
			{title:'Montevideo, Uruguay',x:279.1,y:480},
			{title:'Rio de Janeiro, Brazil',x:303.15,y:451.45},
			{title:'Porto Alegre, Brazil',x:289.8,y:468.55},
			{title:'Rio Grande do Sul, Brazil',x:286.6,y:472.4},
			{title:'Salvador, Brazil',x:315.75,y:427.9},
			{title:'Manaus, Brazil',x:268.7,y:406.85},
			{title:'Fortaleza, Brazil',x:315.2,y:409.65},
			{title:'Santana, Brazil',x:289.65,y:400.3},
			{title:'Asuncion, Paraguay',x:273.45,y:455.05},
			{title:'La Paz, Bolivia',x:252.4,y:436.05},
			{title:'Trinidad, Bolivia',x:258.85,y:432.1},
			{title:'Santa Cruz, Bolivia',x:261.4,y:437.7},
			{title:'Lima, Peru',x:234.8,y:429},
			{title:'Trujillo, Peru',x:229.5,y:418.9},
			{title:'Guayaquil, Ecuador',x:227.45,y:405.8},
			{title:'Buenaventura, Colombia',x:233.75,y:394.6},
			{title:'Bogota, Colombia',x:239.55,y:392.9},
			{title:'Barranquilla, Colombia',x:238.95,y:378.2},
			{title:'Maracaibo, Venezuela',x:245.15,y:379.15},
			{title:'Longyearbyen, Svalbard',x:431.5,y:125.5},
			{title:'Torshavn, Faroe Islands',x:382.75,y:232.5},
			{title:'Reykjavik, Iceland',x:351.75,y:222.25},
			{title:'Akureyri, Iceland',x:359.5,y:214.75},
			{title:'Brisbane, Australia',x:721.8,y:463.3},
			{title:'Adelaide, Australia',x:690.8,y:481.55},
			{title:'Auckland, New Zealand',x:769.3,y:487.05},
			{title:'Wellington, New Zealand',x:767.8,y:498.3},
			{title:'Hobart, Tasmania',x:709.8,y:502.3},
			{title:'Phnum Penh, Cambodia',x:618.45,y:376.75},
			{title:'Bangkok, Thailand',x:611,y:372.3},
			{title:'Singapore, Republic of Singapore',x:617.3,y:397.7},
			{title:'Bantaeng, Indonesia',x:650.5,y:412.95},
			{title:'Manilla, Philippines',x:653.6,y:367.5},
			{title:'Port Moresby, Papua New Guinea',x:708.95,y:421.1},
			{title:'Darwin, Australia',x:676.95,y:427.9},
			{title:'Perth, Australia',x:643.4,y:473.3},
			{title:'Sydney, Australia',x:716.7,y:479.55},
			{title:'Halab, Syria',x:476,y:319.3},
			{title:'Baghdad, Iraq',x:490.7,y:326.3},
			{title:'Mosul, Iraq',x:489.2,y:318.8},
			{title:'Tabriz, Iran',x:494.95,y:313.45},
			{title:'Tehran, Iran',x:506.8,y:321.45},
			{title:'Bakhtaran, Iran',x:497.95,y:324.55},
			{title:'Mashhad, Iran',x:523,y:319.05},
			{title:'Tel Aviv, Israel',x:471.2,y:329.6},
			{title:'Jerusalem',x:471.5,y:330.3},
			{title:'Al Qahirah, Egypt',x:462.85,y:335.1},
			{title:'Tarabulus, Libya',x:425.2,y:328.6},
			{title:'Tunis, Tunisia',x:418.8,y:318.1},
			{title:'El-Jazair, Algeria',x:403.6,y:318.4},
			{title:'Oran, Algeria',x:396.4,y:321.25},
			{title:'Rabat, Morocco',x:383.75,y:324.85},
			{title:'Agadir, Morocco',x:377.1,y:334.95},
			{title:'Las Palmas de Gran Canaria',x:364.5,y:339.8},
			{title:'Laayoune, Western Sahara',x:369.2,y:341.7},
			{title:'Nouakchott, Mauritania',x:364.2,y:362.9},
			{title:'Bamako, Mali',x:379.85,y:374.45},
			{title:'Dakar, Senegal',x:361.2,y:369.75},
			{title:'Conakry, Guinea',x:368.8,y:380.55},
			{title:'Freetown Sierra Leone',x:370.2,y:383.9},
			{title:'Monrovia, Liberia',x:375.4,y:387.95},
			{title:'Tamale, Ghana',x:395.2,y:381.8},
			{title:'Accra, Ghana',x:397.1,y:389},
			{title:'Bobo-Dioulasso, Burkina Faso',x:388.15,y:377.95},
			{title:'Benin City, Nigeria',x:409.55,y:388.45},
			{title:'Niamey, Niger',x:401.95,y:372.45},
			{title:'Sarh, Chad',x:435.55,y:382.5},
			{title:'Douala, Cameroon',x:418.25,y:393.55},
			{title:'Bata, Equatorial Guinea',x:418.4,y:398.5},
			{title:'Libreville, Gabon',x:417.85,y:402.25},
			{title:'Bambari, Central African Republic',x:436.2,y:392.45},
			{title:'Al Khurtum, Sudan',x:467.85,y:367.4},
			{title:'Mitsiwa, Eritrea',x:480.6,y:368.5},
			{title:'Adis Abeba, Ethiopia',x:480.05,y:382.65},
			{title:'Djibouti, Djibouti',x:488.2,y:376.3},
			{title:'Mogadishu, Somalia',x:492.3,y:396.45},
			{title:'Nairobi, Kenya',x:476,y:403.5},
			{title:'Kampala, Uganda',x:466.6,y:401.55},
			{title:'Kisangani, Congo',x:452,y:401.25},
			{title:'Mwanza, Tanzania',x:467.55,y:406.4},
			{title:'Dar es Salaam, Tanzania',x:480.05,y:416.5},
			{title:'Luanda, Angola',x:425.75,y:420.75},
			{title:'Lusaka, Zambia',x:458.2,y:433.9},
			{title:'Lilongwe, Malawi',x:469.65,y:431.1},
			{title:'Maputo, Mozambique',x:466.8,y:457.45},
			{title:'Harare, Zimbabwe',x:464.2,y:438.95},
			{title:'Gaborone, Botswana',x:453.2,y:455.15},
			{title:'Windhoek, Namibia',x:433.95,y:450.4},
			{title:'Mbabane, Swaziland',x:464.75,y:459.05},
			{title:'Pretoria, South Afrika',x:458.35,y:457.5},
			{title:'Maseru, Lesotho',x:457.6,y:466.3},
			{title:'Durban, South Afrika',x:462.4,y:468.2},
			{title:'East London, South Afrika',x:456.7,y:474.7},
			{title:'Antananarivo, Madagascar',x:497.7,y:442.6},
			{title:'Port Louis, Mauritius',x:519.15,y:445.3},
			{title:'Saint-Denis, Reunion',x:515.45,y:447.2},
			{title:'Astana, Kazakhstan',x:549.1,y:273.75},
			{title:'Ashgabat, Turkmenistan',x:520.8,y:313.95},
			{title:'Samarqand, Uzbekistan',x:538.5,y:310.05},
			{title:'Dushanbe, Tajikistan',x:543.45,y:312.65},
			{title:'Bishkek, Kyrgyzstan',x:555.3,y:300.7},
			{title:'Kabul, Afghanistan',x:544.4,y:323.5},
			{title:'Islamabad, Pakistan',x:552.85,y:325.75},
			{title:'Karachi, Pakistan',x:539.7,y:346.15},
			{title:'Pyongyang, North Korea',x:664.1,y:311},
			{title:'Seoul, South Korea',x:666.9,y:315.6},
			{title:'Beijin, China',x:641.85,y:310.2},
			{title:'Gwangju, South Korea',x:666.5,y:322.05},
			{title:'Hiroshima, Japan',x:677.8,y:323.5},
			{title:'Tokyo, Japan',x:692,y:321.05},
			{title:'Taipei, Taiwan',x:655,y:347.5},
			{title:'Hong Kong, China',x:639.15,y:352.3},
			{title:'Shanghai, China',x:654.75,y:331.95},
			{title:'Yangon, Burma',x:600.4,y:365.7},
			{title:'Mandalay, Burma',x:601.4,y:353},
			{title:'Haerbin, China',x:665.45,y:290.4},
			{title:'Ha Noi, Vietnam',x:621.35,y:355.4},
			{title:'Ho Chi Minh, Vietnam',x:623.6,y:378.9},
			{title:'Istanbul, Turkey',x:458.05,y:305.6},
			{title:'Ankara, Turkey',x:466.55,y:308.8},
			{title:'Antalya, Turkey',x:462.45,y:317},
			{title:'Erzurum, Turkey',x:485,y:308.7},
			{title:'Beirut, Lebanon',x:472.55,y:324.9},
			{title:'Dimashq, Syria',x:473.85,y:326.05},
			{title:'Novosibirsk, Russia',x:570.5,y:260},
			{title:'Samara, Russia',x:503.85,y:267.4},
			{title:'Ulaanbaatar, Mongolia',x:625.3,y:284.95},
			{title:'Baky, Azerbaijan',x:502.75,y:308},
			{title:'Gyumri, Armenia',x:490.2,y:306.55},
			{title:'Minsk, Belarus',x:456,y:264.8},
			{title:'Sankt-Peterburg, Russia',x:461.45,y:241.8},
			{title:'Moskva, Russia',x:476.15,y:256.95},
			{title:'Zagreb, Croatia',x:430.65,y:291.6},
			{title:'Sarajevo, Bosnia and Herzegovina',x:435.8,y:297.5},
			{title:'Belgrad, Serbia',x:440.2,y:294.55},
			{title:'Bucuresti, Romania',x:452.6,y:295.65},
			{title:'Sofia, Bulgaria',x:446.6,y:301.15},
			{title:'Kaunu, Lithuania',x:447.95,y:261.45},
			{title:'Vilnius, Lithuania',x:450.75,y:261.9},
			{title:'Vienna, Austria',x:431.7,y:283.65},
			{title:'Luxembourg,Luxembourg',x:410.8,y:279.95},
			{title:'Dortmund, Germany',x:412.95,y:272.95},
			{title:'Dresden, Germany',x:425.85,y:274.6},
			{title:'Strasbourg, France',x:413.45,y:283.4},
			{title:'Praha, Czech Republic',x:427.65,y:278.1},
			{title:'Warszawa, Poland',x:441.7,y:271.75},
			{title:'Krakow, Poland',x:439.65,y:278.55},
			{title:'Budapest, Hungary',x:437,y:285.95},
			{title:'Liechtenstein',x:418.3,y:287.65},
			{title:'Innsbruck, Austria',x:421.3,y:287.1},
			{title:'Salzburg, Austria',x:424.9,y:285.1},
			{title:'Graz, Austria',x:430,y:287.5},
			{title:'Le Mans, France',x:396.25,y:284.4},
			{title:'Zurich, Switzerland',x:415.45,y:287.5},
			{title:'Bern, Switzerland',x:412.7,y:288.45},
			{title:'Bari, Italy',x:433.2,y:305.8},
			{title:'Napoli, Italy',x:427.15,y:306},
			{title:'Palermo, Italy',x:425.4,y:313.45},
			{title:'Malta, Republic of Malta',x:426.6,y:318.15},
			{title:'Milano, Italy',x:415.75,y:292.7},
			{title:'Genova, Italy',x:416.4,y:295.45},
			{title:'Encamp, Andorra',x:400.6,y:301.75},
			{title:'Marseille, France',x:408.2,y:299.05},
			{title:'Toulouse, France',x:400.05,y:298.45},
			{title:'Nimes, France',x:406,y:297.9},
			{title:'Monaco, Monaco',x:411.3,y:299.3},
			{title:'Lyon, France',x:406.45,y:292.1},
			{title:'Bordeaux, France',x:395.15,y:294},
			{title:'Vigo, Spain',x:378.75,y:302.85},
			{title:'Valencia, Spain',x:397.2,y:310.2},
			{title:'Palma, Spain',x:403,y:309.95},
			{title:'Barcelona, Spain',x:401.6,y:304.9},
			{title:'Malaga, Spain',x:387.9,y:317.2},
			{title:'Sevilla, Spain',x:384.25,y:315.5},
			{title:'Valladolid, Spain',x:387.9,y:304.55},
			{title:'Helsinki, Finland',x:450.35,y:240.2},
			{title:'Bergen, Norway',x:408.5,y:239.9},
			{title:'Oslo, Norway',x:418.65,y:242.1},
			{title:'Uppsala, Sweden',x:433.9,y:241.65},
			{title:'Stockholm, Sweden',x:435.2,y:244.2},
			{title:'Goteborg, Sweden',x:422.3,y:250.75},
			{title:'Sundsvall, Sweden',x:433.9,y:231.4},
			{title:'Oulu, Finland',x:451.1,y:217.75},
			{title:'Belfast, North Ireland',x:383.75,y:261.95},
			{title:'Dublin, Ireland',x:383.9,y:267.55},
			{title:'Cork, Ireland',x:378.65,y:272.25},
			{title:'Galway, Ireland',x:377.05,y:267.15},
			{title:'Edinburg, United Kingdom',x:390.75,y:257.2},
			{title:'Glasgow, United Kingdom',x:387.5,y:257.75},
			{title:'Manchester, United Kingdom',x:391.65,y:266.65},
			{title:'Bradford, United Kingdom',x:393.5,y:264.9},
			{title:'Birmingham, United Kingdom',x:392.9,y:270},
			{title:'New York City, USA',x:240.5,y:306.05},
			{title:'Hamburg, Germany',x:417.35,y:266},
			{title:'Munich, Germany',x:422.3,y:283.75},
			{title:'Tbilisi, Georgia',x:492.3,y:304.05},
			{title:'Nordland, Norway',x:428.4,y:206.15},
			{title:'Cape Town, South Afrika',x:436.65,y:478.2},
			{title:'Monrovia, Liberia',x:374.15,y:387.2},
			{title:'Colombo, Sri Lanka',x:566.85,y:386.7},
			{title:'Athina, Greece',x:447.55,y:314},
			{title:'Sofia, Bulgaria',x:447.25,y:300.95},
			{title:'Stockholm, Sweden',x:434.5,y:244.2},
			{title:'Copenhagen, Denmark',x:422.9,y:259.6},
			{title:'London, England',x:396.3,y:272.9},
			{title:'Paris, France',x:401.7,y:282.15},
			{title:'Riga, Latvia',x:448.5,y:253.45},
			{title:'Talinn, Estonia',x:450,y:243.65},
			{title:'Helsinki, Finland',x:450.15,y:240.05},
			{title:'Rome, Italy',x:423.65,y:302.7},
			{title:'Quito, Ecuador',x:229.75,y:401.5},
			{title:'Madrid, Spain',x:389.4,y:307.55},
			{title:'Lisboa, Portugal',x:377.65,y:312.45}
		);
		
	}
}
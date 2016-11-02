package {
	
	import flash.events.Event;
	import flash.media.SoundChannel;
	import flash.media.SoundMixer;
	import flash.media.SoundTransform;
	import flash.utils.Timer;
	import flash.events.TimerEvent;
	
	public class LoopSoundChannel {
		
		public var channel:SoundChannel;
		public var volumeFadeTo:Number;
		private var _volume:Number;
		public var timer:Timer
		public var soundOn:Boolean;

		public function LoopSoundChannel( loopSound ) {
			channel = loopSound;
            channel.soundTransform = new SoundTransform(0,0);
		}
		
		public function set volume( value:Number ):void
		{
			_volume = game.instance.mute ? 0 : value;
			channel.soundTransform = new SoundTransform(_volume,0);
		}
		
		public function get volume():Number
		{
			return _volume;
		}
		
		public function set soundTransform(val:SoundTransform):void
		{
			channel.soundTransform = val;
		}
		
		public function fadeIn( fadeFrom:Number=0, fadeTo:Number=.9 ):void
		{
			this.volume = game.instance.mute ? 0 : fadeFrom;
			this.volumeFadeTo = game.instance.mute ? 0 : fadeTo;
			timer = new Timer(10,30);
			timer.addEventListener(TimerEvent.TIMER, fadeInTimerHandler);
			timer.addEventListener(TimerEvent.TIMER_COMPLETE, fadeInTimerCompleteHandler);
			timer.start();
		}
		
		public function fadeOut( fadeFrom:Number=.9, fadeTo:Number=0 ):void
		{
			this.volume = game.instance.mute ? 0 : fadeFrom;
			this.volumeFadeTo = game.instance.mute ? 0 : fadeTo;
			timer = new Timer(10,100);
			timer.addEventListener(TimerEvent.TIMER, fadeOutTimerHandler);
			timer.addEventListener(TimerEvent.TIMER_COMPLETE, fadeOutTimerCompleteHandler);
			timer.start();
		}
		
		private function fadeInTimerHandler(event:TimerEvent):void
		{
            channel.soundTransform = new SoundTransform(volume,0);
			volume = volume > volumeFadeTo ? volumeFadeTo : volume + 1/30;
		}
		
		private function fadeOutTimerHandler(event:TimerEvent):void
		{
            channel.soundTransform = new SoundTransform(volume,0);
			volume = volume < volumeFadeTo ? volumeFadeTo : volume - 1/100;
		}
		
		private function fadeInTimerCompleteHandler(event:TimerEvent):void
		{
			timer.removeEventListener(TimerEvent.TIMER, fadeInTimerHandler);
			timer.removeEventListener(TimerEvent.TIMER_COMPLETE, fadeInTimerCompleteHandler);
			timer = null;
		}
		
		private function fadeOutTimerCompleteHandler(event:TimerEvent):void
		{
			timer.removeEventListener(TimerEvent.TIMER, fadeOutTimerHandler);
			timer.removeEventListener(TimerEvent.TIMER_COMPLETE, fadeOutTimerCompleteHandler);
			volume = 0;
			channel.stop();
			channel = null;
			timer.stop();
			timer = null;
		}
		
	}
}
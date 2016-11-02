package {
	
	import flash.events.Event;
	import flash.media.SoundChannel;
	import flash.media.SoundMixer;
	import flash.media.SoundTransform;
	import flash.utils.Timer;
	import flash.events.TimerEvent;
	
	public class LoopSoundChannel {
		
		public var channel:SoundChannel;
		public var volume:Number;
		public var timer:Timer

		public function LoopSoundChannel( loopSound, mute=false ) {
			channel = loopSound;
            channel.soundTransform = new SoundTransform((mute?0:1),0);
		}
		
		public function set soundTransform(val:SoundTransform):void
		{
			channel.soundTransform = val;
		}
		
		public function fadeIn(volume:Number=0):void
		{
			this.volume = volume;
			timer = new Timer(10,30);
			timer.addEventListener(TimerEvent.TIMER, fadeInTimerHandler);
			timer.addEventListener(TimerEvent.TIMER_COMPLETE, fadeInTimerCompleteHandler);
			timer.start();
		}
		
		private function fadeInTimerHandler(event:TimerEvent):void
		{
            channel.soundTransform = new SoundTransform(volume,0);
			volume = volume > 0.9 ? 0.9 : volume + 1/30;
		}
		
		private function fadeInTimerCompleteHandler(event:TimerEvent):void
		{
			timer.removeEventListener(TimerEvent.TIMER, fadeInTimerHandler);
			timer.removeEventListener(TimerEvent.TIMER_COMPLETE, fadeInTimerCompleteHandler);
			timer = null;
		}
		
		public function fadeOut(volume:Number=1):void
		{
			this.volume = volume;
			timer = new Timer(10,100);
			timer.addEventListener(TimerEvent.TIMER, fadeOutTimerHandler);
			timer.addEventListener(TimerEvent.TIMER_COMPLETE, fadeOutTimerCompleteHandler);
			timer.start();
		}
		
		private function fadeOutTimerHandler(event:TimerEvent):void
		{
            channel.soundTransform = new SoundTransform(volume,0);
			volume = volume < 0 ? 0 : volume - 1/100;
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